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

                  

                        $sql_check_id = "SELECT id FROM online_players WHERE id = 1";
            $result_check_id = $conn->query($sql_check_id);

                        if ($result_check_id->num_rows > 0) {
                                $next_id = 2;
            } else {
                                $next_id = 1;
            }

                        $sql_insert_player = "INSERT INTO online_players (id, user_id, nickname) VALUES ($next_id, '$user_id', '$nickname')";


            if ($conn->query($sql_insert_player)) {

            } else {
                echo "Błąd przy dodawaniu gracza z user_id = $user_id do online_players: " . $conn->error . "<br>";
            }

                        $sql_delete = "DELETE FROM pending_players WHERE user_id = ? AND nickname = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("ss", $user_id, $nickname);
        }

                $conn->commit();
    } else {
    }
} catch (Exception $e) {
        $conn->rollback();
    echo "Błąd podczas przenoszenia graczy: " . $e->getMessage();
}

$conn->close();
?>
