<?php
session_start();
include 'db_connection.php'; 

if (!isset($_SESSION['walka_trwa'])) {
    $_SESSION['player1_squad'] = [];
    $stmt = $conn->prepare("SELECT id_pokemona FROM player1_squad ORDER BY statusID ASC");
    $stmt->execute();
    $stmt->bind_result($pokemon_id);
    while ($stmt->fetch()) {
        $_SESSION['player1_squad'][] = $pokemon_id;
    }
    $stmt->close();

    $_SESSION['player2_squad'] = [];
    $stmt = $conn->prepare("SELECT id_pokemona FROM player2_squad ORDER BY statusID ASC");
    $stmt->execute();
    $stmt->bind_result($pokemon_id);
    while ($stmt->fetch()) {
        $_SESSION['player2_squad'][] = $pokemon_id;
    }
    $stmt->close();

    $conn->query("DELETE FROM player_round");
    $conn->query("INSERT INTO player_round (player_round) VALUES (1)");

    $_SESSION['walka_trwa'] = true;
}

if (empty($_SESSION['player1_squad']) || empty($_SESSION['player2_squad'])) {
    unset($_SESSION['walka_trwa']);
    header("Location: stronapokemony.php");
    exit();
}


$res1 = $conn->query("SELECT id_pokemona, statusID FROM player1_squad WHERE hp > 0 ORDER BY statusID ASC LIMIT 1");
$row1 = $res1->fetch_assoc();
$first_pokemon_player1 = $row1 ? $row1['id_pokemona'] : null;
$status_id_player1 = $row1 ? intval($row1['statusID']) : null;

$res2 = $conn->query("SELECT id_pokemona, statusID FROM player2_squad WHERE hp > 0 ORDER BY statusID ASC LIMIT 1");
$row2 = $res2->fetch_assoc();
$first_pokemon_player2 = $row2 ? $row2['id_pokemona'] : null;
$status_id_player2 = $row2 ? intval($row2['statusID']) : null;


$res_round = $conn->query("SELECT player_round FROM player_round LIMIT 1");
$current_turn = ($row_round = $res_round->fetch_assoc()) ? intval($row_round['player_round']) : 1;



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['attack_id'])) {
    $attackerId = intval($_POST['attacker_id']);
    $defenderId = intval($_POST['defender_id']);
    $abilityType = $_POST['ability_type'];
    $abilityDamage = intval($_POST['ability_damage']);
    $abilityEffect = $_POST['ability_effect'] ?? '';
    $abilityEffectChance = intval($_POST['ability_effect_chance'] ?? 0);
    $abilityName = $_POST['ability_name'] ?? 'Atak';

    $attacker_team = in_array($attackerId, $_SESSION['player1_squad']) ? 1 : 2;
    $defender_team = ($attacker_team === 1) ? 2 : 1;
    $attacker_status_id = ($attacker_team === 1) ? $status_id_player1 : $status_id_player2;
    $defender_status_id = ($attacker_team === 1) ? $status_id_player2 : $status_id_player1;

   
    if ($attacker_team !== $current_turn) {
        $_SESSION['damage_message'] = "To nie jest Twoja tura!";
        header("Location: WalkaPokemon.php");
        exit();
    }

   
    $stmt_status = $conn->prepare("SELECT Sleep, Stun, Poison, Burn FROM squad{$attacker_team}status WHERE StatusID = ?");
    $stmt_status->bind_param("i", $attacker_status_id);
    $stmt_status->execute();
    $stmt_status->bind_result($s_sleep, $s_stun, $s_poison, $s_burn);
    $stmt_status->fetch();
    $stmt_status->close();

   
    if (($s_sleep !== null && $s_sleep > 0) || ($s_stun !== null && $s_stun > 0)) {
       
        $conn->query("UPDATE squad{$attacker_team}status SET Sleep = NULL, Stun = NULL WHERE StatusID = $attacker_status_id");

        $_SESSION['damage_message'] = "❌ Twój Pokémon śpi lub jest ogłuszony! Runda stracona.";
        
        $next_turn = ($current_turn === 1) ? 2 : 1;
        $conn->query("UPDATE player_round SET player_round = $next_turn");
        header("Location: WalkaPokemon.php");
        exit();
    }

   
    $status_dmg_msg = "";
    if (($s_poison !== null && $s_poison > 0) || ($s_burn !== null && $s_burn > 0)) {
        $stmt_hp = $conn->prepare("SELECT hp FROM player{$attacker_team}_squad WHERE id_pokemona = ?");
        $stmt_hp->bind_param("i", $attackerId);
        $stmt_hp->execute();
        $stmt_hp->bind_result($attacker_hp);
        $stmt_hp->fetch();
        $stmt_hp->close();

        $new_attacker_hp = max(0, $attacker_hp - 10);
        $stmt_update_hp = $conn->prepare("UPDATE player{$attacker_team}_squad SET hp = ? WHERE id_pokemona = ?");
        $stmt_update_hp->bind_param("ii", $new_attacker_hp, $attackerId);
        $stmt_update_hp->execute();
        $stmt_update_hp->close();

        $status_label = ($s_poison > 0) ? "trucizny" : "oparzenia";
        $status_dmg_msg = "⚠️ Twój Pokémon otrzymał 10 obrażeń od {$status_label}! ";

        if ($new_attacker_hp <= 0) {
            $conn->query("UPDATE squad{$attacker_team}status SET Poison = NULL, Burn = NULL WHERE StatusID = $attacker_status_id");
            $_SESSION['damage_message'] = $status_dmg_msg . "i niespodziewanie zemglał!";
            
            $next_turn = ($current_turn === 1) ? 2 : 1;
            $conn->query("UPDATE player_round SET player_round = $next_turn");
            header("Location: WalkaPokemon.php");
            exit();
        }
    }

   
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
    $result_type = $stmt->get_result();
    $typeRow = $result_type->fetch_assoc();
    $stmt->close();

    $multiplier = 1; 
    if ($typeRow) {
        foreach ($typeRow as $columnName => $value) {
            if ($columnName === 'type' || empty($value)) continue;
            if ($value === $defenderType1 || $value === $defenderType2) {
                $loweredCol = strtolower($columnName);
                if (strpos($loweredCol, 'strong') !== false) {
                    $multiplier = 2;
                    break;
                } elseif (strpos($loweredCol, 'weak') !== false) {
                    $multiplier = 0.5;
                } elseif (strpos($loweredCol, 'immune') !== false) {
                    $multiplier = 0;
                    break;
                }
            }
        }
    }

    $finalDamage = $abilityDamage * $multiplier;

   
    $stmt = $conn->prepare("SELECT hp FROM player{$defender_team}_squad WHERE id_pokemona = ?");
    $stmt->bind_param("i", $defenderId);
    $stmt->execute();
    $stmt->bind_result($defenderHp);
    $stmt->fetch();
    $stmt->close();

    $newHp = max(0, $defenderHp - $finalDamage);
    $stmt = $conn->prepare("UPDATE player{$defender_team}_squad SET hp = ? WHERE id_pokemona = ?");
    $stmt->bind_param("ii", $newHp, $defenderId);
    $stmt->execute();
    $stmt->close();

   
    $effect_applied_msg = "";
    if (!empty($abilityEffect) && $abilityEffectChance > 0 && $newHp > 0) {
        if (rand(1, 100) <= $abilityEffectChance) {
            $col = "";
            $eff = strtolower($abilityEffect);
            if ($eff === 'poison') $col = "Poison";
            elseif ($eff === 'burn') $col = "Burn";
            elseif ($eff === 'sleep') $col = "Sleep";
            elseif ($eff === 'stun') $col = "Stun";

            if (!empty($col)) {
                $conn->query("UPDATE squad{$defender_team}status SET {$col} = 1 WHERE StatusID = {$defender_status_id}");
                $translations = ['Poison' => 'zatruty', 'Burn' => 'oparzony', 'Sleep' => 'uśpiony', 'Stun' => 'ogłuszony'];
                $effect_applied_msg = " Przeciwnik został " . $translations[$col] . "!";
            }
        }
    }

    $_SESSION['damage_message'] = $status_dmg_msg . "Pokémon użył {$abilityName} i zadał {$finalDamage} obrażeń!" . $effect_applied_msg;

   
    $next_turn = ($current_turn === 1) ? 2 : 1;
    $conn->query("UPDATE player_round SET player_round = $next_turn");

    header("Location: WalkaPokemon.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset_game'])) {
    try {
        $conn->begin_transaction();
        $conn->query("DELETE FROM player1_squad");
        $conn->query("DELETE FROM player2_squad");
        $conn->query("DELETE FROM squad1status");
        $conn->query("DELETE FROM squad2status");
        $conn->query("DELETE FROM usedpokemons");
        $conn->query("DELETE FROM player_round");
        $conn->query("UPDATE player1lvl SET lvl = 9 WHERE id = 1");
        $conn->query("UPDATE player2lvl SET lvl = 9 WHERE id = 1");
        $conn->commit();

        unset($_SESSION['walka_trwa']);
        $_SESSION['player1_squad'] = [];
        $_SESSION['player2_squad'] = [];
    } catch (Exception $e) {
        $conn->rollback();
    }
    header("Location: stronapokemony.php");
    exit();
}

$res1 = $conn->query("SELECT id_pokemona FROM player1_squad WHERE hp > 0 ORDER BY statusID ASC LIMIT 1");
$first_pokemon_player1 = ($row = $res1->fetch_assoc()) ? $row['id_pokemona'] : null;

$res2 = $conn->query("SELECT id_pokemona FROM player2_squad WHERE hp > 0 ORDER BY statusID ASC LIMIT 1");
$first_pokemon_player2 = ($row = $res2->fetch_assoc()) ? $row['id_pokemona'] : null;

if ($first_pokemon_player1 === null || $first_pokemon_player2 === null) {
    $winner = ($first_pokemon_player1 === null) ? 2 : 1;
    $conn->query("DELETE FROM player1_squad");
    $conn->query("DELETE FROM player2_squad");
    $conn->query("DELETE FROM squad1status");
    $conn->query("DELETE FROM squad2status");
    $conn->query("DELETE FROM usedpokemons");
    $conn->query("DELETE FROM player_round");
    
    unset($_SESSION['walka_trwa']);
    $_SESSION['player1_squad'] = [];
    $_SESSION['player2_squad'] = [];
    
    header("Location: stronapokemony.php?winner=$winner");
    exit();
}


$stmt = $conn->prepare("SELECT nazwa, hpstart FROM pokemony WHERE Indeks = ?");
$stmt->bind_param("i", $first_pokemon_player1);
$stmt->execute();
$stmt->bind_result($pokemon_name1, $hpstart_player1);
$stmt->fetch();
$stmt->close();

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
                $stmt->bind_result($p_name, $lvlstart, $hpstart);
                $stmt->fetch();
                $stmt->close();

                $stmt_hp = $conn->prepare("SELECT hp, statusID FROM player1_squad WHERE id_pokemona = ?");
                $stmt_hp->bind_param("i", $pokemon_id);
                $stmt_hp->execute();
                $stmt_hp->bind_result($current_hp, $loop_status_id);
                $stmt_hp->fetch();
                $stmt_hp->close();

               
                $status_badge = "";
                if ($current_hp > 0 && $loop_status_id) {
                    $stmt_st = $conn->prepare("SELECT Sleep, Stun, Poison, Burn FROM squad1status WHERE StatusID = ?");
                    $stmt_st->bind_param("i", $loop_status_id);
                    $stmt_st->execute();
                    $stmt_st->bind_result($st_sleep, $st_stun, $st_poison, $st_burn);
                    $stmt_st->fetch();
                    $stmt_st->close();

                    if ($st_sleep > 0) $status_badge .= " <span style='color:#3498db;font-weight:bold;'>[ŚPI]</span>";
                    if ($st_stun > 0) $status_badge .= " <span style='color:#e67e22;font-weight:bold;'>[OGŁ]</span>";
                    if ($st_poison > 0) $status_badge .= " <span style='color:#9b59b6;font-weight:bold;'>[ZAT]</span>";
                    if ($st_burn > 0) $status_badge .= " <span style='color:#e74c3c;font-weight:bold;'>[OPARZ]</span>";
                }

                $image_path = "images/" . sprintf("%03d", $pokemon_id) . ".png";

                if ($pokemon_id == $first_pokemon_player1) {
                    $stmt = $conn->prepare("SELECT name, type, damage, effect, effect_chance FROM abilities WHERE pokemon_indeks = ?");
                    $stmt->bind_param("i", $pokemon_id);
                    $stmt->execute();
                    $stmt->bind_result($ability_name, $ability_type, $ability_damage, $ability_effect, $ability_effect_chance);
                    $abilities_player1 = [];
                    while ($stmt->fetch()) {
                        $abilities_player1[] = [
                            'name' => $ability_name,
                            'type' => $ability_type,
                            'damage' => $ability_damage,
                            'effect' => $ability_effect,
                            'effect_chance' => $ability_effect_chance
                        ];
                    }
                    $stmt->close();
                }

                echo "<li>";
                echo "<img src='$image_path' alt='" . htmlspecialchars($p_name) . "'>";
                echo htmlspecialchars($p_name) . " (Lvl: $lvlstart, HP: $current_hp/$hpstart)" . $status_badge;
                if($current_hp == 0) echo " <b style='color:red;'>[POKONANY]</b>";
                echo "</li>";
            }
            ?>
        </ul>
        <img class="pokemon-image" src="<?php echo $image_path_player1; ?>" alt="<?php echo htmlspecialchars($pokemon_name1); ?>">

        <div class="hp-bar-container">
            <?php $hp_percentage_player1 = ($hp_player1 / $hpstart_player1) * 100; ?>
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
                        <input type="hidden" name="ability_name" value="<?php echo htmlspecialchars($ability['name']); ?>">
                        <input type="hidden" name="ability_effect" value="<?php echo htmlspecialchars($ability['effect'] ?? ''); ?>">
                        <input type="hidden" name="ability_effect_chance" value="<?php echo intval($ability['effect_chance'] ?? 0); ?>">
                        <button type="submit" class="ability-button" name="attack_id" value="1">
                            <?php echo htmlspecialchars($ability['name']); ?> (<?php echo $ability['type']; ?>)
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="team">
        <h2>Drużyna 2</h2>
        <ul>
            <?php
            foreach ($_SESSION['player2_squad'] as $pokemon_id) {
                $stmt = $conn->prepare("SELECT nazwa, lvlstart, hpstart FROM pokemony WHERE Indeks = ?");
                $stmt->bind_param("i", $pokemon_id);
                $stmt->execute();
                $stmt->bind_result($p_name, $lvlstart, $hpstart);
                $stmt->fetch();
                $stmt->close();

                $stmt_hp = $conn->prepare("SELECT hp, statusID FROM player2_squad WHERE id_pokemona = ?");
                $stmt_hp->bind_param("i", $pokemon_id);
                $stmt_hp->execute();
                $stmt_hp->bind_result($current_hp, $loop_status_id);
                $stmt_hp->fetch();
                $stmt_hp->close();

                $status_badge = "";
                if ($current_hp > 0 && $loop_status_id) {
                    $stmt_st = $conn->prepare("SELECT Sleep, Stun, Poison, Burn FROM squad2status WHERE StatusID = ?");
                    $stmt_st->bind_param("i", $loop_status_id);
                    $stmt_st->execute();
                    $stmt_st->bind_result($st_sleep, $st_stun, $st_poison, $st_burn);
                    $stmt_st->fetch();
                    $stmt_st->close();

                    if ($st_sleep > 0) $status_badge .= " <span style='color:#3498db;font-weight:bold;'>[ŚPI]</span>";
                    if ($st_stun > 0) $status_badge .= " <span style='color:#e67e22;font-weight:bold;'>[OGŁ]</span>";
                    if ($st_poison > 0) $status_badge .= " <span style='color:#9b59b6;font-weight:bold;'>[ZAT]</span>";
                    if ($st_burn > 0) $status_badge .= " <span style='color:#e74c3c;font-weight:bold;'>[OPARZ]</span>";
                }

                $image_path = "images/" . sprintf("%03d", $pokemon_id) . ".png";

                if ($pokemon_id == $first_pokemon_player2) {
                    $stmt = $conn->prepare("SELECT name, type, damage, effect, effect_chance FROM abilities WHERE pokemon_indeks = ?");
                    $stmt->bind_param("i", $pokemon_id);
                    $stmt->execute();
                    $stmt->bind_result($ability_name, $ability_type, $ability_damage, $ability_effect, $ability_effect_chance);
                    $abilities_player2 = [];
                    while ($stmt->fetch()) {
                        $abilities_player2[] = [
                            'name' => $ability_name,
                            'type' => $ability_type,
                            'damage' => $ability_damage,
                            'effect' => $ability_effect,
                            'effect_chance' => $ability_effect_chance
                        ];
                    }
                    $stmt->close();
                }

                echo "<li>";
                echo "<img src='$image_path' alt='" . htmlspecialchars($p_name) . "'>";
                echo htmlspecialchars($p_name) . " (Lvl: $lvlstart, HP: $current_hp/$hpstart)" . $status_badge;
                if($current_hp == 0) echo " <b style='color:red;'>[POKONANY]</b>";
                echo "</li>";
            }
            ?>
        </ul>
        <img class="pokemon-image" src="<?php echo $image_path_player2; ?>" alt="<?php echo htmlspecialchars($pokemon_name2); ?>">

        <div class="hp-bar-container">
            <?php $hp_percentage_player2 = ($hp_player2 / $hpstart_player2) * 100; ?>
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
                        <input type="hidden" name="ability_name" value="<?php echo htmlspecialchars($ability['name']); ?>">
                        <input type="hidden" name="ability_effect" value="<?php echo htmlspecialchars($ability['effect'] ?? ''); ?>">
                        <input type="hidden" name="ability_effect_chance" value="<?php echo intval($ability['effect_chance'] ?? 0); ?>">
                        <button type="submit" class="ability-button" name="attack_id" value="1">
                            <?php echo htmlspecialchars($ability['name']); ?> (<?php echo $ability['type']; ?>)
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div id="footer">
    <div id="console">
        <?php
        if (isset($_SESSION['damage_message'])) {
            echo $_SESSION['damage_message'];
            unset($_SESSION['damage_message']);
        }
        ?>
    </div>
</div>

<form method="POST" action="">
    <button type="submit" name="reset_game" onclick="return confirm('Czy na pewno chcesz zresetować grę?')">Resetuj grę</button>
</form>

<script>
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const userId = sessionStorage.getItem("user_id");
        if (userId && typeof socket !== 'undefined' && socket.readyState === WebSocket.OPEN) {
            const message = JSON.stringify({
                type: "pokemon_update",
                user_id: userId,
            });
            socket.send(message);
        }
    });
});

if (typeof socket !== 'undefined') {
    socket.onmessage = function(event) {
        const data = JSON.parse(event.data);
        const currentUserId = sessionStorage.getItem("user_id");

        if (data.type === 'pokemon_update' && data.from_user !== currentUserId) {
            console.log("Przeciwnik wykonał ruch! Odświeżam arenę...");
            window.location.reload();
        }
    };
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const playerRole = sessionStorage.getItem("playerRole");
    const currentTurn = <?php echo $current_turn; ?>;

    if (playerRole === "Player 1") {
        const gr2um = document.getElementById("player2-abilities");
        if(gr2um) gr2um.classList.add("hidden");
    } else if (playerRole === "Player 2") {
        const gr1um = document.getElementById("player1-abilities");
        if(gr1um) gr1um.classList.add("hidden");
    }

    const isMyTurn = (playerRole === "Player 1" && currentTurn === 1) || (playerRole === "Player 2" && currentTurn === 2);
    
    if (!isMyTurn) {
        document.querySelectorAll('.ability-button').forEach(button => {
            button.disabled = true;
            button.style.opacity = "0.4";
            button.style.cursor = "not-allowed";
        });
        
        const consoleDiv = document.getElementById("console");
        if (consoleDiv && !consoleDiv.innerHTML.includes("TURA PRZECIWNIKA")) {
            consoleDiv.innerHTML = "<b style='color:orange;'>⏳ TURA PRZECIWNIKA. Czekaj na ruch...</b><br>" + consoleDiv.innerHTML;
        }
    } else {
        const consoleDiv = document.getElementById("console");
        if (consoleDiv && !consoleDiv.innerHTML.includes("TWOJA TURA")) {
            consoleDiv.innerHTML = "<b style='color:green;'>🟢 TWOJA TURA! Wybierz atak.</b><br>" + consoleDiv.innerHTML;
        }
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