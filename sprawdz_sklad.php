<?php
header('Content-Type: application/json');

$response = ['status' => 'success', 'message' => 'Pokemony są w drużynie!'];

$user_id = $_GET['user_id']; 
$query = "SELECT COUNT(*) FROM player1_squad WHERE user_id = '$user_id'";
$result = mysqli_query($db, $query);
$data = mysqli_fetch_assoc($result);

if ($data['COUNT(*)'] < 3) {
    $response = ['status' => 'error', 'message' => 'Musisz mieć co najmniej 3 pokemony!'];
}

echo json_encode($response); ?>
