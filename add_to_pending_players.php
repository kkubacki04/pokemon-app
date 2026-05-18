<?php
if (isset($_POST['user_id']) && isset($_POST['nickname'])) {
    $user_id = $_POST['user_id'];
    $nickname = $_POST['nickname'];

    include 'db_connection.php';

   
    $stmt = $conn->prepare("INSERT IGNORE INTO Pending_players (user_id, nickname) VALUES (?, ?)");
    $stmt->bind_param("ss", $user_id, $nickname);
    $stmt->execute();
    $stmt->close();

    echo "Gracz został dodany do Pending_players.";
} else {
    echo "Brak wymaganych danych.";
}
?>
