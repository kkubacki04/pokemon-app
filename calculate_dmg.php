<?php
session_start();
include 'db_connection.php'; $attackResult = ''; 
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attack'])) {
    $attackerId = $_POST['attacker_id'];
    $defenderId = $_POST['defender_id'];
    $abilityType = $_POST['ability_type'];
    $abilityDamage = $_POST['ability_damage'];

        $stmt = $conn->prepare("SELECT typ1, typ2 FROM pokemony WHERE Indeks = ?");
    $stmt->bind_param("i", $attackerId);
    $stmt->execute();
    $stmt->bind_result($attackerType1, $attackerType2);
    $stmt->fetch();
    $stmt->close();

        $stmt = $conn->prepare("SELECT typ1, typ2 FROM pokemony WHERE Indeks = ?");
    $stmt->bind_param("i", $defenderId);
    $stmt->execute();
    $stmt->bind_result($defenderType1, $defenderType2);
    $stmt->fetch();
    $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM type_chart WHERE type = ?");
    $stmt->bind_param("s", $abilityType);
    $stmt->execute();
    $stmt->bind_result($type, $strongAgainst1, $strongAgainst2, $strongAgainst3, $strongAgainst4, $strongAgainst5, $weakAgainst1, $weakAgainst2, $weakAgainst3, $weakAgainst4, $weakAgainst5, $weakAgainst6, $immuneTo);
    $stmt->fetch();
    $stmt->close();

        $multiplier = 1; 
        $defenderTypes = [$defenderType1, $defenderType2];
    if (in_array($defenderType1, [$strongAgainst1, $strongAgainst2, $strongAgainst3, $strongAgainst4, $strongAgainst5]) || in_array($defenderType2, [$strongAgainst1, $strongAgainst2, $strongAgainst3, $strongAgainst4, $strongAgainst5])) {
        $multiplier = 2;
    }
        elseif (in_array($defenderType1, [$weakAgainst1, $weakAgainst2, $weakAgainst3, $weakAgainst4, $weakAgainst5, $weakAgainst6]) || in_array($defenderType2, [$weakAgainst1, $weakAgainst2, $weakAgainst3, $weakAgainst4, $weakAgainst5, $weakAgainst6])) {
        $multiplier = 0.5;
    }
        elseif (in_array($defenderType1, [$immuneTo]) || in_array($defenderType2, [$immuneTo])) {
        $multiplier = 0;
    }

        $finalDamage = $abilityDamage * $multiplier;

        if (in_array($attackerId, $_SESSION['player1_squad'])) {
                $stmt = $conn->prepare("SELECT hp FROM player2_squad WHERE pokemon_indeks = ?");
        $stmt->bind_param("i", $defenderId);
        $stmt->execute();
        $stmt->bind_result($defenderHp);
        $stmt->fetch();
        $stmt->close();

                $newHp = max(0, $defenderHp - $finalDamage);

                $stmt = $conn->prepare("UPDATE player2_squad SET hp = ? WHERE pokemon_indeks = ?");
        $stmt->bind_param("ii", $newHp, $defenderId);
        $stmt->execute();
        $stmt->close();
    }
        else {
                $stmt = $conn->prepare("SELECT hp FROM player1_squad WHERE pokemon_indeks = ?");
        $stmt->bind_param("i", $defenderId);
        $stmt->execute();
        $stmt->bind_result($defenderHp);
        $stmt->fetch();
        $stmt->close();

                $newHp = max(0, $defenderHp - $finalDamage);

                $stmt = $conn->prepare("UPDATE player1_squad SET hp = ? WHERE pokemon_indeks = ?");
        $stmt->bind_param("ii", $newHp, $defenderId);
        $stmt->execute();
        $stmt->close();
    }

        $attackResult = "Pokémon o ID $attackerId zadał $finalDamage obrażeń Pokémonowi o ID $defenderId!";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walka Pokémon</title>
    <style>
    </style>
</head>
<body>
    <h1>Walka Pokémon</h1>

    <?php
        if ($attackResult != '') {
        echo "<div>$attackResult</div>";
    }
    ?>

        <form method="POST">
        <input type="hidden" name="attacker_id" value="1">         <input type="hidden" name="defender_id" value="2">         <input type="hidden" name="ability_type" value="fire">         <input type="hidden" name="ability_damage" value="50">         <button type="submit" name="attack">Zaatakuj</button>
    </form>

    <p><strong>Obrażeń: </strong> <?php echo $finalDamage ?? 0; ?></p>
</body>
</html>
