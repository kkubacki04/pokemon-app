<?php
require 'vendor/autoload.php';
include 'db_connection.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;
use React\EventLoop\Factory;

class WebSocketServer implements MessageComponentInterface {
    protected $clients;
    protected $players = [];
    protected $readyPlayers = [];
    protected $db;
    protected $loop;
    protected $countdownTimer;
    protected $countdownActive = false;
    private $assigning = false;

    public function __construct($db, $loop) {
        $this->clients = new \SplObjectStorage;
        $this->db = $db;
        $this->loop = $loop;
    }

public function onOpen(ConnectionInterface $conn) {
        $queryString = $conn->httpRequest->getUri()->getQuery();
    parse_str($queryString, $params);
    $userId = $params['user_id'] ?? null;

    if (!$userId) {
        $conn->send(json_encode(["error" => "Brak user_id w połączeniu."]));
        $conn->close();
        return;
    }

    $conn->userId = $userId;
    echo "Nowe połączenie: {$conn->resourceId}, user_id: $userId\n";
    $this->clients->attach($conn);

        while ($this->assigning) {
        usleep(100);
    }
    $this->assigning = true;

        if (isset($this->players[1]) && isset($this->players[1]->userId) && $this->players[1]->userId === $userId) {
        $this->players[1] = $conn;
        $this->assigning = false;
        $conn->send(json_encode(["role" => "Player 1", "message" => "Wróciłeś jako Player 1"]));
        return;
    }

    if (isset($this->players[2]) && isset($this->players[2]->userId) && $this->players[2]->userId === $userId) {
        $this->players[2] = $conn;
        $this->assigning = false;
        $conn->send(json_encode(["role" => "Player 2", "message" => "Wróciłeś jako Player 2"]));
        return;
    }

        if (!isset($this->players[1])) {
        $this->players[1] = $conn;
        $this->assignPlayer(1, $conn);
    } elseif (!isset($this->players[2])) {
        $this->players[2] = $conn;
        $this->assignPlayer(2, $conn);
    } else {
        $conn->send(json_encode(["error" => "Gra pełna"]));
        $conn->close();
    }

    $this->assigning = false;
}


    private function assignPlayer($playerNumber, $conn) {
        $this->clearPlayerData($playerNumber);

        if ($playerNumber == 1) {
            $this->assignToPlayer1($conn);
        } elseif ($playerNumber == 2) {
            $this->assignToPlayer2($conn);
        }
    }

    private function clearPlayerData($playerNumber) {
        if ($playerNumber == 1) {
            $this->db->query("DELETE FROM player1lvl");
        } elseif ($playerNumber == 2) {
            $this->db->query("DELETE FROM player2lvl");
        }
    }

    private function assignToPlayer1($conn) {
        $this->db->query("INSERT INTO player1lvl (id, lvl) VALUES (1, 9)");
        $player1UserId = $this->players[1]->userId ?? null;

        if ($player1UserId) {
            $stmt = $this->db->prepare("DELETE FROM online_players WHERE user_id != ? AND id = 1");
            $stmt->bind_param("s", $player1UserId);
            $stmt->execute();
        }

        $conn->send(json_encode([
            "role" => "Player 1",
            "message" => "Jesteś graczem 1, Twoje dane zostały przypisane.",
        ]));
    }

    private function assignToPlayer2($conn) {
        $this->db->query("INSERT INTO player2lvl (id, lvl) VALUES (1, 9)");
        $player2UserId = $this->players[2]->userId ?? null;

        if ($player2UserId) {
            $stmt = $this->db->prepare("DELETE FROM online_players WHERE user_id != ? AND id = 2");
            $stmt->bind_param("s", $player2UserId);
            $stmt->execute();
        }

        $conn->send(json_encode([
            "role" => "Player 2",
            "message" => "Jesteś graczem 2, Twoje dane zostały przypisane.",
        ]));
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Otrzymano wiadomość: $msg\n";
        $data = json_decode($msg, true);

        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $client->send($msg);
            }
        }

        if (!isset($data['type']) || !isset($data['user_id'])) return;

        if ($data['type'] === 'player_ready') {
            $this->readyPlayers[$data['user_id']] = true;
            echo "Gracz {$data['user_id']} kliknął Start\n";

            if (count($this->readyPlayers) === 2) {
                echo "Obaj gracze gotowi – start odliczania!\n";
                $this->startCountdown();
            }
        }
        if ($data['type'] === 'pokemon_update') {
    echo "Gracz {$data['user_id']} zaktualizował pokemony\n";

    foreach ($this->clients as $client) {
    $client->send(json_encode([
        'type' => 'pokemon_update',
        'from_user' => $data['user_id']
  ]));
          }
    }
}
 

    

    private function startCountdown() {
    if ($this->countdownActive) {
        echo "Odliczanie już trwa – przerwano próbę ponownego uruchomienia.\n";
        return;
    }

    $countdown = 5;
    $this->countdownActive = true;

    $this->countdownTimer = $this->loop->addPeriodicTimer(1, function () use (&$countdown) {
        foreach ($this->players as $player) {
            if ($player instanceof ConnectionInterface) {
                $player->send(json_encode([
                    'type' => 'countdown',
                    'countdown' => $countdown
                ]));
            }
        }

        if ($countdown === 0) {
            foreach ($this->players as $player) {
                if ($player instanceof ConnectionInterface) {
                    $player->send(json_encode([
                        'type' => 'redirect',
                        'url' => 'walkapokemon.php'
                    ]));
                }
            }

            echo "Przekierowano graczy na walkapokemon.php\n";
            $this->readyPlayers = [];
            $this->stopCountdown();         }

        $countdown--;
    });
}
private function stopCountdown() {
    if ($this->countdownTimer) {
        $this->loop->cancelTimer($this->countdownTimer);
        $this->countdownTimer = null;
        $this->countdownActive = false;
        echo "Countdown zatrzymany.\n";
    }
}

    public function onClose(ConnectionInterface $conn) {
        echo "Połączenie zamknięte: {$conn->resourceId} (user_id: {$conn->userId})\n";

        if ($this->players[1] === $conn) {
            unset($this->players[1]);
        } elseif ($this->players[2] === $conn) {
            unset($this->players[2]);
        }

        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Błąd: {$e->getMessage()}\n";
        $conn->close();
    }
}

$loop = Factory::create();
$webSocket = new WebSocketServer($conn, $loop);

$socket = new React\Socket\SocketServer('0.0.0.0:9000', [], $loop);

$server = new IoServer(
    new HttpServer(
        new WsServer($webSocket)
    ),
    $socket,
    $loop
);

echo "Serwer WebSocket uruchomiony na porcie 9000...\n";
$server->run();