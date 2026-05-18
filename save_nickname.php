<?php
session_start();

if (isset($_POST['user_id']) && isset($_POST['nickname']) && isset($_POST['password'])) {
    $user_id = $_POST['user_id'];
    $nickname = $_POST['nickname'];
    $password123 = $_POST['password'];

        include 'db_connection.php';

        $stmt = $conn->prepare("SELECT nickname FROM online_players WHERE nickname = ?");
    $stmt->bind_param("s", $nickname);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
                echo "Gracz z tym nickiem jest już zalogowany. Proszę spróbować ponownie później.";
        $stmt->close();
        $conn->close();
        exit();      }

        $stmt = $conn->prepare("SELECT id, password, user_id FROM users WHERE nickname = ?");
    $stmt->bind_param("s", $nickname);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id_in_db, $hashed_password, $existing_user_id);
        $stmt->fetch();

                if (password_verify($password123, $hashed_password)) {
                        if ($existing_user_id !== $user_id) {
                                $update_stmt = $conn->prepare("UPDATE users SET user_id = ? WHERE nickname = ?");
                $update_stmt->bind_param("ss", $user_id, $nickname);
                $update_stmt->execute();
                echo "Zalogowano. Zmieniono user_id.";
            } else {
                                echo "Zalogowano. Hasło jest poprawne, nic nie zostało zmienione.";
            }

        } else {
                        echo "Niepoprawne hasło. Spróbuj ponownie.";
        }
    } else {
                $hashed_password = password_hash($password123, PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO users (user_id, nickname, password) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("sss", $user_id, $nickname, $hashed_password);
        $insert_stmt->execute();

    }

    $stmt->close();
    $conn->close();
} else {
    echo "Brak wymaganych danych.";
}
?>
