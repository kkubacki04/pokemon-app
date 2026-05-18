<?php
session_start();
include 'db_connection.php';
include 'functions.php'; if (!isset($_SESSION['player1_squad'])) {
    $_SESSION['player1_squad'] = [];
}
if (!isset($_SESSION['player2_squad'])) {
    $_SESSION['player2_squad'] = [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pokemon_id']) && !isset($_POST['clear_squad'])) {
    $pokemon_id = intval($_POST['pokemon_id']);
    $squad = 'player1_squad';     $message = '';

        $stmt = $conn->prepare("SELECT COUNT(*) FROM USEDPOKEMONS WHERE pokemon_id = ?");
    $stmt->bind_param("i", $pokemon_id);
    $stmt->execute();
    $stmt->bind_result($usedCount);
    $stmt->fetch();
    $stmt->close();

    if ($usedCount > 0) {
        $message = "Ten Pokémon został już wybrany przez inną drużynę!";
    } elseif (count($_SESSION[$squad]) >= 3) {
        $message = "Drużyna 1 ma już 3 Pokémony!";
    } elseif (in_array($pokemon_id, $_SESSION[$squad])) {
        $message = "Ten Pokémon jest już w drużynie!";
    } else {
                $stmt = $conn->prepare("SELECT lvlstart, hpstart FROM pokemony WHERE Indeks = ?");
        $stmt->bind_param("i", $pokemon_id);
        $stmt->execute();
        $stmt->bind_result($lvlstart, $hpstart);
        $stmt->fetch();
        $stmt->close();

        if ($lvlstart === null || $hpstart === null) {
            $message = "Nie można dodać Pokémona - brak wymaganych danych.";
        } else {
                        $statusID = count($_SESSION[$squad]) + 1;

                        ensure_status_exists($conn, $squad, $statusID);

                        $_SESSION[$squad][] = $pokemon_id;

                        $stmt = $conn->prepare("INSERT INTO player1_squad (id_pokemona, lvl, hp, statusID) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $pokemon_id, $lvlstart, $hpstart, $statusID);
            if ($stmt->execute()) {
                                $stmt2 = $conn->prepare("INSERT INTO USEDPOKEMONS (pokemon_id) VALUES (?)");
                $stmt2->bind_param("i", $pokemon_id);
                $stmt2->execute();
                $stmt2->close();

                $message = "Pokémon został dodany do drużyny 1!";
            } else {
                $message = "Wystąpił błąd podczas dodawania Pokémona do bazy danych.";
            }
            $stmt->close();
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pokemon_id2']) && !isset($_POST['clear_squad'])) {
    $pokemon_id2 = intval($_POST['pokemon_id2']);
    $squad = 'player2_squad';     $message = '';

        $stmt = $conn->prepare("SELECT COUNT(*) FROM USEDPOKEMONS WHERE pokemon_id = ?");
    $stmt->bind_param("i", $pokemon_id2);     $stmt->execute();
    $stmt->bind_result($usedCount);
    $stmt->fetch();
    $stmt->close();

    if ($usedCount > 0) {
        $message = "Ten Pokémon został już wybrany przez inną drużynę!";
    } elseif (count($_SESSION[$squad]) >= 3) {
        $message = "Drużyna 2 ma już 3 Pokémony!";
    } elseif (in_array($pokemon_id2, $_SESSION[$squad])) {
        $message = "Ten Pokémon jest już w drużynie!";
    } else {
                $stmt = $conn->prepare("SELECT lvlstart, hpstart FROM pokemony WHERE Indeks = ?");
        $stmt->bind_param("i", $pokemon_id2);         $stmt->execute();
        $stmt->bind_result($lvlstart, $hpstart);
        $stmt->fetch();
        $stmt->close();

        if ($lvlstart === null || $hpstart === null) {
            $message = "Nie można dodać Pokémona - brak wymaganych danych.";
        } else {
                        $statusID = count($_SESSION[$squad]) + 1;

                        ensure_status_exists($conn, $squad, $statusID);

                        $_SESSION[$squad][] = $pokemon_id2; 
                        $stmt = $conn->prepare("INSERT INTO player2_squad (id_pokemona, lvl, hp, statusID) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $pokemon_id2, $lvlstart, $hpstart, $statusID);             if ($stmt->execute()) {
                                $stmt2 = $conn->prepare("INSERT INTO USEDPOKEMONS (pokemon_id) VALUES (?)");
                $stmt2->bind_param("i", $pokemon_id2);                 $stmt2->execute();
                $stmt2->close();

                $message = "Pokémon został dodany do drużyny 2!";

            } else {
                $message = "Wystąpił błąd podczas dodawania Pokémona do bazy danych.";
            }
            $stmt->close();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_squad'])) {
        $squad_to_clear = $_POST['clear_squad'] == '1' ? 'player1_squad' : 'player2_squad';

        if ($squad_to_clear == 'player1_squad') {
                foreach ($_SESSION['player1_squad'] as $pokemon_id) {
                        $stmt = $conn->prepare("DELETE FROM player1_squad WHERE id_pokemona = ?");
            $stmt->bind_param("i", $pokemon_id);
            $stmt->execute();

                        $stmt_used = $conn->prepare("DELETE FROM USEDPOKEMONS WHERE pokemon_id = ?");
            $stmt_used->bind_param("i", $pokemon_id);
            $stmt_used->execute();

                        $stmt->close();
            $stmt_used->close();
        }

                $stmt_status = $conn->prepare("DELETE FROM squad1status");
        $stmt_status->execute();
        $stmt_status->close();

                $stmt = $conn->prepare("DELETE FROM player1_squad");
        $stmt->execute();
        $stmt->close();
    } elseif ($squad_to_clear == 'player2_squad') {
                foreach ($_SESSION['player2_squad'] as $pokemon_id) {
                        $stmt = $conn->prepare("DELETE FROM player2_squad WHERE id_pokemona = ?");
            $stmt->bind_param("i", $pokemon_id);
            $stmt->execute();

                        $stmt_used = $conn->prepare("DELETE FROM USEDPOKEMONS WHERE pokemon_id = ?");
            $stmt_used->bind_param("i", $pokemon_id);
            $stmt_used->execute();

                        $stmt->close();
            $stmt_used->close();
        }

                $stmt_status = $conn->prepare("DELETE FROM squad2status");
        $stmt_status->execute();
        $stmt_status->close();

                $stmt = $conn->prepare("DELETE FROM player2_squad");
        $stmt->execute();
        $stmt->close();
    }

        $_SESSION[$squad_to_clear] = [];

    $message = "Drużyna została wyczyszczona, a Pokémony zostały usunięte z bazy danych!";
}

?>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const playerRole = sessionStorage.getItem("playerRole");

    if (playerRole === "Player 1") {
                const team2Header = document.getElementById("team2-header");
        const team2Form = document.getElementById("team2-form");
        const team2List = document.getElementById("team2-list");
        const team2HeaderTeam = document.getElementById("team2-headerTeam");
        const team2button = document.getElementById("clear-button2");
        const team2startbutton = document.getElementById("startButtonTeam2");
        
            team2Header.classList.add("hidden");
            team2HeaderTeam.classList.add("hidden");             team2Form.classList.add("hidden");
            team2List.classList.add("hidden");               team2button.classList.add("hidden");             team2startbutton.classList.add("hidden");
        
    } else if (playerRole === "Player 2") {
                const team1Header = document.getElementById("team1-header");
        const team1Form = document.getElementById("team1-form");
        const team1List = document.getElementById("team1-list");
        const team1HeaderTeam = document.getElementById("team1-headerTeam");
        const team1button = document.getElementById("clear-button1");
        const team1startbutton = document.getElementById("startButtonTeam1");
        
            team1Header.classList.add("hidden");
            team1HeaderTeam.classList.add("hidden");             team1Form.classList.add("hidden");
            team1List.classList.add("hidden");               team1button.classList.add("hidden");             team1startbutton.classList.add("hidden");
    }
  });
</script>
<?php
$team1_ready = count($_SESSION['player1_squad']) == 3;
$team2_ready = count($_SESSION['player2_squad']) == 3;
?>
<?php
$user_id = $_POST['user_id'] ?? null;
?>
<!DOCTYPE html>

<html lang="pl">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gra Pokémon</title>
    <script src="client.js" defer></script>
    <link rel="stylesheet" href="style.css">
        <?php if (!$user_id): ?>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const userId = sessionStorage.getItem("user_id");

            if (userId) {
                const form = document.createElement("form");
                form.method = "POST";
                form.action = "stronapokemony.php";

                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "user_id";
                input.value = userId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            } else {
                console.warn("❗ Brak user_id w sessionStorage.");
            }
        });
        </script>
    <?php endif; ?>
</head>
<body>
<?php
if (!$user_id) {
    echo "<h1>Brak user_id. Nie można kontynuować.</h1>";
    exit;
}

$sql = "SELECT nickname FROM online_players WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $nickname = $row['nickname'];
    echo "✅ Gracz z user_id $user_id już jest w online_players.<br>";
} else {
        echo "❌ Gracza z user_id $user_id NIE MA w online_players. Przenoszę...<br>";
    include 'move_player.php';
    include 'db_connection.php';
        $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $nickname = $row['nickname'] ?? "Nieznany";
}

$stmt->close();
$conn->close();
    include 'db_connection.php';
echo "<h1>Witaj $nickname w grze Pokémon!</h1>";
?>
    <header>
        <p>Wybierz swoje drużyny i walcz z przeciwnikami!</p>
    </header>

    <?php if (isset($message)) echo "<p style='color: red;'>$message</p>"; ?>
<h2 id="team1-header">Drużyna 1</h2>

<form method="POST" action="" id="team1-form">
    <select id="pokemon-select1" name="pokemon_id">
        <option value="">Wybierz...</option>
        <?php
                $player1_level_query = "SELECT lvl FROM player1lvl WHERE id = 1"; 
        $player1_level_result = $conn->query($player1_level_query);
        $player1_level_row = $player1_level_result->fetch_assoc();
        $player1_level = $player1_level_row['lvl'];

        $result = $conn->query("SELECT Indeks, nazwa, lvlstart FROM pokemony");

        while ($row = $result->fetch_assoc()) {
            $pokemon_id = $row['Indeks'];
            $lvlstart = $row['lvlstart'];

            if ($lvlstart <= $player1_level) {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM USEDPOKEMONS WHERE pokemon_id = ?");
                $stmt->bind_param("i", $pokemon_id);
                $stmt->execute();
                $stmt->bind_result($usedCount);
                $stmt->fetch();
                $stmt->close();

                if ($usedCount == 0) {
                    echo "<option value='" . $row['Indeks'] . "'>" . $row['nazwa'] . " (Lvl " . $row['lvlstart'] . ")</option>";
                }
            }
        }
        ?>
    </select>
    <br><br>
    <div id="submitbutton1"><input type="submit" value="Zatwierdź"></div>
</form>


    <h2 id="team2-header">Drużyna 2</h2>
    <form method="POST" action="" id="team2-form">
    <select id="pokemon-select2" name="pokemon_id2">
        <option value="">Wybierz...</option>
        <?php
                $player2_level_query = "SELECT lvl FROM player2lvl WHERE id = 1"; 
        $player2_level_result = $conn->query($player2_level_query);
        $player2_level_row = $player2_level_result->fetch_assoc();
        $player2_level = $player2_level_row['lvl'];

        $result = $conn->query("SELECT Indeks, nazwa, lvlstart FROM pokemony");

        while ($row = $result->fetch_assoc()) {
            $pokemon_id = $row['Indeks'];
            $lvlstart = $row['lvlstart'];

            if ($lvlstart <= $player2_level) {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM USEDPOKEMONS WHERE pokemon_id = ?");
                $stmt->bind_param("i", $pokemon_id);
                $stmt->execute();
                $stmt->bind_result($usedCount);
                $stmt->fetch();
                $stmt->close();

                if ($usedCount == 0) {
                    echo "<option value='" . $row['Indeks'] . "'>" . $row['nazwa'] . " (Lvl " . $row['lvlstart'] . ")</option>";
                }
            }
        }
        ?>
    </select>
    <br><br>
    <div id="submitbutton2"><input type="submit" value="Zatwierdź"></div>
</form>

<h3 id="team1-headerTeam">Drużyna 1:</h3>
<ul id="team1-list">
    <?php
    foreach ($_SESSION['player1_squad'] as $pokemon_id) {
        $stmt = $conn->prepare("SELECT nazwa FROM pokemony WHERE Indeks = ?");
        $stmt->bind_param("i", $pokemon_id);
        $stmt->execute();
        $stmt->bind_result($pokemon_name);
        $stmt->fetch();
        $stmt->close();
        echo "<li>" . htmlspecialchars($pokemon_name) . "</li>";
    }
    ?>
</ul>

<h3 id="team2-headerTeam">Drużyna 2:</h3>
<ul id="team2-list">
    <?php
    foreach ($_SESSION['player2_squad'] as $pokemon_id2) {
        $stmt = $conn->prepare("SELECT nazwa FROM pokemony WHERE Indeks = ?");
        $stmt->bind_param("i", $pokemon_id2);
        $stmt->execute();
        $stmt->bind_result($pokemon_name);
        $stmt->fetch();
        $stmt->close();
        echo "<li>" . htmlspecialchars($pokemon_name) . "</li>";
    }
    ?>
</ul>
<form method="POST" action="" id="clear-button1">
    <div id="submitbuttonclear1"><button type="submit" name="clear_squad" value="1">Wyczyść drużynę 1</button></div>
</form>
<form method="POST" action="" id="clear-button2">
    <div id="submitbuttonclear2"><button type="submit" name="clear_squad" value="2">Wyczyść drużynę 2</button></div>
</form>
<?php if ($team1_ready ): ?>
    <button id="startButtonTeam1">Start dla drużyny 1</button>
<?php endif; ?>

<?php if ($team2_ready): ?>
    <button id="startButtonTeam2">Start dla drużyny 2</button>
<?php endif; ?>
      
<script>
const playerRole = sessionStorage.getItem("playerRole");
<?php if ($team1_ready ): ?>
if(playerRole === "Player 1")
{
document.getElementById("startButtonTeam1").addEventListener("click", function() {
    const userId = sessionStorage.getItem("user_id");

    if (userId) {
                const message = JSON.stringify({
            type: "player_ready",
            user_id: userId,
            team: 1
        });

        socket.send(message);  
                document.getElementById("startButtonTeam1").disabled = true;

        console.log("Wysłano do serwera:", message);
    } else {
        console.warn("❗ Brak user_id w sessionStorage.");
    }
});
}
<?php endif; ?>
<?php if ($team2_ready): ?>
if(playerRole === "Player 2")
{
document.getElementById("startButtonTeam2").addEventListener("click", function() {
    const userId = sessionStorage.getItem("user_id");

    if (userId) {
                const message = JSON.stringify({
            type: "player_ready",
            user_id: userId,
            team: 2
        });

        socket.send(message);  
                document.getElementById("startButtonTeam2").disabled = true;

        console.log("Wysłano do serwera:", message);
    } else {
        console.warn("❗ Brak user_id w sessionStorage.");
    }
});
}
<?php endif; ?>
</script>
<script>
document.getElementById("submitbutton1").addEventListener("click", function() {
    const userId = sessionStorage.getItem("user_id");

    if (userId) {
                const message = JSON.stringify({
            type: "pokemon_update",
            user_id: userId,
        });

        socket.send(message);          console.log("Wysłano do serwera:", message);
    } else {
        console.warn("❗ Brak user_id w sessionStorage.");
    }
});

document.getElementById("submitbutton2").addEventListener("click", function() {
    const userId = sessionStorage.getItem("user_id");

    if (userId) {
                const message = JSON.stringify({
            type: "pokemon_update",
            user_id: userId,
        });

        socket.send(message);          console.log("Wysłano do serwera:", message);
    } else {
        console.warn("❗ Brak user_id w sessionStorage.");
    }
});

document.getElementById("submitbuttonclear1").addEventListener("click", function() {
    const userId = sessionStorage.getItem("user_id");

    if (userId) {
                const message = JSON.stringify({
            type: "pokemon_update",
            user_id: userId,
        });

        socket.send(message);          console.log("Wysłano do serwera:", message);
    } else {
        console.warn("❗ Brak user_id w sessionStorage.");
    }
});

document.getElementById("submitbuttonclear2").addEventListener("click", function() {
    const userId = sessionStorage.getItem("user_id");

    if (userId) {
                const message = JSON.stringify({
            type: "pokemon_update",
            user_id: userId,
        });

        socket.send(message);          console.log("Wysłano do serwera:", message);
    } else {
        console.warn("❗ Brak user_id w sessionStorage.");
    }
});
</script>
<div id="countdown-container">
    <div id="countdown">Oczekiwanie na graczy...</div>
</div>
</body>
</html>
    

