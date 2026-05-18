<?php
// Funkcja sprawdzająca i dodająca brakujące wpisy do tabeli squad1status lub squad2status
function ensure_status_exists($conn, $squad, $statusID) {
    $statusTable = ($squad == 'player1_squad') ? 'squad1status' : 'squad2status';
    
    $stmt = $conn->prepare("SELECT COUNT(*) FROM $statusTable WHERE StatusID = ?");
    $stmt->bind_param("i", $statusID);
    $stmt->execute();
    $stmt->bind_result($exists);
    $stmt->fetch();
    $stmt->close();

    if ($exists == 0) {
        $stmt = $conn->prepare("INSERT INTO $statusTable (StatusID) VALUES (?)");
        $stmt->bind_param("i", $statusID);
        $stmt->execute();
        $stmt->close();
    }
}

// Funkcja czyszcząca drużynę
function clear_squad($conn, $squad) {
    if ($squad === 'player1_squad') {
        // Usuń wszystkie rekordy w player1_squad
        $conn->query("DELETE FROM player1_squad");

        // Teraz usuń rekordy z tabeli squad1status, które nie są powiązane z żadną drużyną
        $conn->query("DELETE FROM squad1status WHERE StatusID NOT IN (SELECT statusID FROM player1_squad) AND StatusID NOT IN (SELECT statusID FROM player2_squad)");

    } elseif ($squad === 'player2_squad') {
        // Usuń wszystkie rekordy w player2_squad
        $conn->query("DELETE FROM player2_squad");

        // Teraz usuń rekordy z tabeli squad2status, które nie są powiązane z żadną drużyną
        $conn->query("DELETE FROM squad2status WHERE StatusID NOT IN (SELECT statusID FROM player2_squad) AND StatusID NOT IN (SELECT statusID FROM player1_squad)");
    }
}

?>