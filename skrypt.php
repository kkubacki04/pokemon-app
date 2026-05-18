<?php
include 'db_connection.php';
$sql = "SELECT Indeks,nazwa FROM pokemony";  $result = $conn->query($sql);

if ($result->num_rows > 0) {
        $pokemons = [];
    while ($row = $result->fetch_assoc()) {
        $pokemons[] = $row;
    }
} else {
    echo "0 results";
}
$conn->close();
?>