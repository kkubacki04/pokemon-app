<?php
include 'db_connection.php';

if ($conn->connect_error) {
    die("Połączenie z bazą danych nie powiodło się: " . $conn->connect_error);
}

$conn->begin_transaction();

try {
        $sql_select = "SELECT user_id, nickname FROM pending_players";
    $result = $conn->query($sql_select);

        if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user_id = $row['user_id'];
            $nickname = $row['nickname'];

                        echo "Debug: Przenoszę gracza z user_id = $user_id i nickname = $nickname.<br>";

                        $sql_check_id = "SELECT id FROM online_players WHERE id = 1";
            $result_check_id = $conn->query($sql_check_id);

                        if ($result_check_id->num_rows > 0) {
                                $next_id = 2;
            } else {
                                $next_id = 1;
            }

                        $sql_insert_player = "INSERT INTO online_players (id, user_id, nickname) VALUES ($next_id, '$user_id', '$nickname')";

                        echo "Debug: Wykonuję zapytanie SQL: $sql_insert_player<br>";

            if ($conn->query($sql_insert_player)) {
                echo "Gracz z user_id = $user_id został dodany do online_players.<br>";
            } else {
                echo "Błąd przy dodawaniu gracza z user_id = $user_id do online_players: " . $conn->error . "<br>";
            }

                        $sql_delete = "DELETE FROM pending_players WHERE user_id = ? AND nickname = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("ss", $user_id, $nickname);
            if ($stmt_delete->execute()) {
                echo "Gracz z user_id = $user_id został usunięty z pending_players.<br>";
            } else {
                echo "Błąd przy usuwaniu gracza z user_id = $user_id z pending_players.<br>";
            }
        }

                $conn->commit();
        echo "Gracze zostali przeniesieni do online_players i usunięci z pending_players.";
    } else {
        echo "Brak graczy do przeniesienia.";
    }
} catch (Exception $e) {
        $conn->rollback();
    echo "Błąd podczas przenoszenia graczy: " . $e->getMessage();
}

$conn->close();
?>
