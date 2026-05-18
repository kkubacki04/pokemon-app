<?php
session_start();
include 'db_connection.php'; 
if (!isset($_SESSION['player1_squad'])) {
    $_SESSION['player1_squad'] = [];
    $stmt = $conn->prepare("SELECT id_pokemona FROM player1_squad");
    $stmt->execute();
    $stmt->bind_result($pokemon_id);
    while ($stmt->fetch()) {
        $_SESSION['player1_squad'][] = $pokemon_id;
    }
    $stmt->close();
}

if (!isset($_SESSION['player2_squad'])) {
    $_SESSION['player2_squad'] = [];
    $stmt = $conn->prepare("SELECT id_pokemona FROM player2_squad");
    $stmt->execute();
    $stmt->bind_result($pokemon_id);
    while ($stmt->fetch()) {
        $_SESSION['player2_squad'][] = $pokemon_id;
    }
    $stmt->close();
}


echo "<pre>";
echo "=== DEBUG: player1_squad ===\n";
if (isset($_SESSION['player1_squad'])) {
    foreach ($_SESSION['player1_squad'] as $index => $pokemon_id) {
        echo "[$index] ID Pokémona: $pokemon_id\n";
    }
} else {
    echo "Brak danych w player1_squad\n";
}

echo "\n=== DEBUG: player2_squad ===\n";
if (isset($_SESSION['player2_squad'])) {
    foreach ($_SESSION['player2_squad'] as $index => $pokemon_id) {
        echo "[$index] ID Pokémona: $pokemon_id\n";
    }
} else {
    echo "Brak danych w player2_squad\n";
}
echo "</pre>";

function updatePokemonSquad($playerSquad, $playerTeam) {
    global $conn;

        $first_pokemon_id = $playerSquad[0] ;
    $stmt = $conn->prepare("SELECT hp FROM player{$playerTeam}_squad WHERE id_pokemona = ?");
    $stmt->bind_param("i", $first_pokemon_id);
    $stmt->execute();
    $stmt->bind_result($hp);
    $stmt->fetch();
    $stmt->close();

        if ($hp == 0) {
    array_shift($playerSquad);
}

$_SESSION["player{$playerTeam}_squad"] = $playerSquad;

return count($playerSquad) > 0 ? $playerSquad[0] : null;
}

function checkForWinner($teamSquad, $playerTeam) {
    global $conn;

        $allDead = true;
    foreach ($teamSquad as $pokemonId) {
        $stmt = $conn->prepare("SELECT hp FROM player{$playerTeam}_squad WHERE id_pokemona = ?");
        $stmt->bind_param("i", $pokemonId);
        $stmt->execute();
        $stmt->bind_result($hp);
        $stmt->fetch();
        $stmt->close();
        
        if ($hp > 0) {
            $allDead = false;
            break;
        }
    }

    return $allDead;
}

foreach ([1, 2] as $team) {
    if (checkForWinner($_SESSION["player{$team}_squad"], $team)) {
        $winner = $team === 1 ? 2 : 1;

        try {
            $conn->begin_transaction();
            $conn->query("DELETE FROM player1_squad");
            $conn->query("DELETE FROM player2_squad");
            $conn->query("DELETE FROM squad1status");
            $conn->query("DELETE FROM squad2status");
            $conn->query("DELETE FROM usedpokemons");
            $conn->commit();

            $_SESSION['message'] = "Gra została zresetowana!";
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['message'] = "Wystąpił błąd podczas resetowania gry: " . $e->getMessage();
        }

        header("Location: stronapokemony.php?winner=$winner");
        exit();
    }
}

$first_pokemon_player1 = updatePokemonSquad($_SESSION['player1_squad'], 1);
$stmt = $conn->prepare("SELECT nazwa, hpstart FROM pokemony WHERE Indeks = ?");
$stmt->bind_param("i", $first_pokemon_player1);
$stmt->execute();
$stmt->bind_result($pokemon_name1, $hpstart_player1);
$stmt->fetch();
$stmt->close();

$first_pokemon_player2 = updatePokemonSquad($_SESSION['player2_squad'], 2);
$stmt = $conn->prepare("SELECT nazwa, hpstart FROM pokemony WHERE Indeks = ?");
$stmt->bind_param("i", $first_pokemon_player2);
$stmt->execute();
$stmt->bind_result($pokemon_name2, $hpstart_player2);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT hp FROM player1_squad WHERE id_pokemona = ?");
$stmt->bind_param("i", $first_pokemon_player1);
$stmt->execute();
$stmt->bind_result($hp_player1);
$stmt->fetch();
$stmt->close();

$stmt = $conn->prepare("SELECT hp FROM player2_squad WHERE id_pokemona = ?");
$stmt->bind_param("i", $first_pokemon_player2);
$stmt->execute();
$stmt->bind_result($hp_player2);
$stmt->fetch();
$stmt->close();

$image_path_player1 = "images/" . sprintf("%03d", $first_pokemon_player1) . ".png";
$image_path_player2 = "images/" . sprintf("%03d", $first_pokemon_player2) . ".png";

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
                $stmt = $conn->prepare("SELECT hp FROM player2_squad WHERE id_pokemona = ?");
        $stmt->bind_param("i", $defenderId);
        $stmt->execute();
        $stmt->bind_result($defenderHp);
        $stmt->fetch();
        $stmt->close();

                $newHp = max(0, $defenderHp - $finalDamage);

                $stmt = $conn->prepare("UPDATE player2_squad SET hp = ? WHERE id_pokemona = ?");
        $stmt->bind_param("ii", $newHp, $defenderId);
        $stmt->execute();
        $stmt->close();
    }

        if (in_array($attackerId, $_SESSION['player2_squad'])) {
                $stmt = $conn->prepare("SELECT hp FROM player1_squad WHERE id_pokemona = ?");
        $stmt->bind_param("i", $defenderId);
        $stmt->execute();
        $stmt->bind_result($defenderHp);
        $stmt->fetch();
        $stmt->close();

                $newHp = max(0, $defenderHp - $finalDamage);

                $stmt = $conn->prepare("UPDATE player1_squad SET hp = ? WHERE id_pokemona = ?");
        $stmt->bind_param("ii", $newHp, $defenderId);
        $stmt->execute();
        $stmt->close();
    }


        $damage_message = "Pokémon zadał {$finalDamage} obrażeń!";
    $_SESSION['damage_message'] = $damage_message; 
    
}
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_game'])) {
    

    try {
                $conn->begin_transaction();

                $conn->query("DELETE FROM player1_squad");
        $conn->query("DELETE FROM player2_squad");
        $conn->query("DELETE FROM squad1status");
        $conn->query("DELETE FROM squad2status");
        $conn->query("DELETE FROM usedpokemons");
        $conn->query("UPDATE player1lvl SET lvl = 9 WHERE id = 1");
        $conn->query("UPDATE player2lvl SET lvl = 9 WHERE id = 1");

                $conn->commit();

                $_SESSION['message'] = "Gra została zresetowana!";
    } catch (Exception $e) {
                $conn->rollback();
        $_SESSION['message'] = "Wystąpił błąd podczas resetowania gry: " . $e->getMessage();
    }

    
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walka Pokémon</title>
    <link rel="stylesheet" href="style2.css">
    <script src="client.js" defer></script>

</head>

<body>

<div class="container">
        <div class="team">
        <h2>Drużyna 1</h2>
        <ul>
            <?php
                        foreach ($_SESSION['player1_squad'] as $pokemon_id) {
                $stmt = $conn->prepare("SELECT nazwa, lvlstart, hpstart FROM pokemony WHERE Indeks = ?");
                $stmt->bind_param("i", $pokemon_id);
                $stmt->execute();
                $stmt->bind_result($pokemon_name, $lvlstart, $hpstart);
                $stmt->fetch();
                $stmt->close();

                                $image_path = "images/" . sprintf("%03d", $pokemon_id) . ".png";

                                if ($pokemon_id == $first_pokemon_player1) {
                    $stmt = $conn->prepare("SELECT name, type, damage, effect FROM abilities WHERE pokemon_indeks = ?");
                    $stmt->bind_param("i", $pokemon_id);
                    $stmt->execute();
                    $stmt->bind_result($ability_name, $ability_type, $ability_damage, $ability_effect);
                    $abilities_player1 = [];
                    while ($stmt->fetch()) {
                        $abilities_player1[] = [
                            'name' => $ability_name,
                            'type' => $ability_type,
                            'damage' => $ability_damage,
                            'effect' => $ability_effect
                        ];
                    }
                    $stmt->close();
                }

                                echo "<li>";
                echo "<img src='$image_path' alt='" . htmlspecialchars($pokemon_name) . "'>";
                echo htmlspecialchars($pokemon_name) . " (Lvl: $lvlstart, HP: $hpstart)";
                echo "</li>";
            }
            ?>
        </ul>
                <img class="pokemon-image" src="<?php echo $image_path_player1; ?>" alt="<?php echo htmlspecialchars($pokemon_name1); ?>">

                <div class="hp-bar-container">
            <?php
                        $hp_percentage_player1 = ($hp_player1 / $hpstart_player1) * 100;
            ?>
            <div class="hp-bar" data-hp="<?php echo $hp_percentage_player1; ?>"></div>
        </div>
       
                <?php if (isset($abilities_player1)): ?>
             <div class="abilities" id="player1-abilities">
                <?php foreach ($abilities_player1 as $ability): ?>
                    <form method="POST">
                        <input type="hidden" name="attacker_id" value="<?php echo $first_pokemon_player1; ?>">
                        <input type="hidden" name="defender_id" value="<?php echo $first_pokemon_player2; ?>">
                        <input type="hidden" name="ability_type" value="<?php echo $ability['type']; ?>">
                        <input type="hidden" name="ability_damage" value="<?php echo $ability['damage']; ?>">
 <button id="submitbutton1" type="submit" class="ability-button" name="attack">
    <?php echo htmlspecialchars($ability['name']); ?> (<?php echo $ability['type']; ?>)
</button>

                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div id="footer">
            <div id="console">
                <?php
                echo "<pre>";
                echo "</pre>";

                if (isset($_SESSION['damage_message'])) {
                    echo $_SESSION['damage_message'];
                    unset($_SESSION['damage_message']);                 }
                ?>
            </div>
        </div>
    </div>

        <div class="team">
        <h2>Drużyna 2</h2>
        <ul>
            <?php
                        foreach ($_SESSION['player2_squad'] as $pokemon_id) {
                $stmt = $conn->prepare("SELECT nazwa, lvlstart, hpstart FROM pokemony WHERE Indeks = ?");
                $stmt->bind_param("i", $pokemon_id);
                $stmt->execute();
                $stmt->bind_result($pokemon_name, $lvlstart, $hpstart);
                $stmt->fetch();
                $stmt->close();

                                $image_path = "images/" . sprintf("%03d", $pokemon_id) . ".png";

                                if ($pokemon_id == $first_pokemon_player2) {
                    $stmt = $conn->prepare("SELECT name, type, damage, effect FROM abilities WHERE pokemon_indeks = ?");
                    $stmt->bind_param("i", $pokemon_id);
                    $stmt->execute();
                    $stmt->bind_result($ability_name, $ability_type, $ability_damage, $ability_effect);
                    $abilities_player2 = [];
                    while ($stmt->fetch()) {
                        $abilities_player2[] = [
                            'name' => $ability_name,
                            'type' => $ability_type,
                            'damage' => $ability_damage,
                            'effect' => $ability_effect
                        ];
                    }
                    $stmt->close();
                }

                                echo "<li>";
                echo "<img src='$image_path' alt='" . htmlspecialchars($pokemon_name) . "'>";
                echo htmlspecialchars($pokemon_name) . " (Lvl: $lvlstart, HP: $hpstart)";
                echo "</li>";
            }
            ?>
        </ul>
                <img class="pokemon-image" src="<?php echo $image_path_player2; ?>" alt="<?php echo htmlspecialchars($pokemon_name2); ?>">

                <div class="hp-bar-container">
            <?php
                        $hp_percentage_player2 = ($hp_player2 / $hpstart_player2) * 100;
            ?>
            <div class="hp-bar" data-hp="<?php echo $hp_percentage_player2; ?>"></div>

        </div>

             
        <?php if (isset($abilities_player2)): ?>
             <div class="abilities" id="player2-abilities">
                <?php foreach ($abilities_player2 as $ability): ?>
                    <form method="POST">
                        <input type="hidden" name="attacker_id" value="<?php echo $first_pokemon_player2; ?>">
                        <input type="hidden" name="defender_id" value="<?php echo $first_pokemon_player1; ?>">
                        <input type="hidden" name="ability_type" value="<?php echo $ability['type']; ?>">
                        <input type="hidden" name="ability_damage" value="<?php echo $ability['damage']; ?>">
    <button id="submitbutton2" type="submit" class="ability-button" name="attack">
    <?php echo htmlspecialchars($ability['name']); ?> (<?php echo $ability['type']; ?>)
</button>

                    </form>
                <?php endforeach; ?>
            </div>
            <div id="footer">
            <div id="console">
                <?php
                echo "<pre>";
                echo "</pre>";

                if (isset($_SESSION['damage_message'])) {
                    echo $_SESSION['damage_message'];
                    unset($_SESSION['damage_message']);                 }
                ?>
            </div>
        </div>
    </div>
        <?php endif; ?>
    </div>
</div>
<form method="POST" action="">
    <button type="submit" name="reset_game" onclick="return confirm('Czy na pewno chcesz zresetować grę?')">Resetuj grę</button>
</form>
<script>
function handleReset(event) {
    if (confirm('Czy na pewno chcesz zresetować grę?')) {
        window.location.href = "http://192.168.215.212:8080/pokemon/stronapokemony.php";
    event.preventDefault();     return false; }
</script>
<script>
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const userId = sessionStorage.getItem("user_id");

        if (userId && typeof socket !== 'undefined') {
            const message = JSON.stringify({
                type: "pokemon_update",
                user_id: userId,
            });

            socket.send(message);
            console.log("Wysłano do serwera:", message);

                        setTimeout(() => {
                    
                form.submit();
                
                              
                
            }, 1000);
            
        } else {
            console.warn("❗ Nie znaleziono socket lub user_id.");
            form.submit();         }
    });
});
</script>
<script>

  document.addEventListener("DOMContentLoaded", function() {
    const playerRole = sessionStorage.getItem("playerRole");

    if (playerRole === "Player 1") {
                const gr2um = document.getElementById("player2-abilities");
 
            gr2um.classList.add("hidden");

    } else if (playerRole === "Player 2") {
                const gr1um = document.getElementById("player1-abilities");
 
            gr1um.classList.add("hidden");
    }
  });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.hp-bar').forEach(el => {
            const hp = el.getAttribute('data-hp');
            el.style.setProperty('--hp-width', hp + '%');
        });
    });
</script>

</body>
</html>