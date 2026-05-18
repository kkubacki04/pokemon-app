<?php
$user_id = $_POST['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Gra Pokémon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="client.js" defer></script>

    <?php if (!$user_id): ?>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            const userId = sessionStorage.getItem("user_id");

            if (userId) {
                const form = document.createElement("form");
                form.method = "POST";
                form.action = "logowanie.php";

                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "user_id";
                input.value = userId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            } else {
                console.warn("❗ Brak user_id w sessionStorage.");
            }
        });
        </script>
    <?php endif; ?>
</head>
<body>

<?php
if (!$user_id) {
    echo "<p>Brak user_id — zamykam połączenie.</p>";
    exit;
}

echo "<p>Otrzymano user_id: <code>" . htmlspecialchars($user_id) . "</code></p>";

echo '
<form id="nicknameForm">
    <label for="nickname">Podaj swój nick:</label>
    <input type="text" id="nickname" name="nickname" required placeholder="Twój nick">

    <label for="password">Podaj swoje hasło:</label>
    <input type="password" id="password" name="password" required placeholder="Twoje hasło">

    <p><strong>Debug: user_id = ' . htmlspecialchars($user_id) . '</strong></p>

    <input type="hidden" name="user_id" id="user_id" value="' . htmlspecialchars($user_id) . '">

    <button type="submit">Zaloguj</button>
</form>';
?>



<script>

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("nicknameForm");

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const nickname = document.getElementById("nickname").value;
            const password = document.getElementById("password").value;
            const userId = document.getElementById("user_id").value;

            console.log("📤 Wysyłane dane:", { userId, nickname, password });

            fetch("save_nickname.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: "user_id=" + encodeURIComponent(userId) +
                      "&nickname=" + encodeURIComponent(nickname) +
                      "&password=" + encodeURIComponent(password)
            })
            .then(response => response.text())
            .then(data => {
                console.log("📥 Odpowiedź z serwera:", data);

                const messageBox = document.getElementById("loginMessage");

                if (messageBox) {
                    messageBox.classList.remove("hidden", "success", "error");

                    if (data.includes("Zalogowano") || data.includes("dodany")) {
                        messageBox.textContent = `✅ Zalogowano: ${nickname}`;
                        messageBox.classList.add("success");

                        
                        fetch("add_to_pending_players.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: "user_id=" + encodeURIComponent(userId) +
                                  "&nickname=" + encodeURIComponent(nickname)
                        })
                        .then(response => response.text())
                        .then(pendingData => {
                            console.log("📥 Odpowiedź z add_to_pending_players:", pendingData);
                        })
                        .catch(error => {
                            console.error("❗ Błąd w add_to_pending_players fetch:", error);
                        });

                        setTimeout(() => {
                            window.location.href = "STRONAPOKEMONY.php";
                        }, 4000);
                    } else if (data.includes("Niepoprawne hasło")) {
                        messageBox.textContent = "❌ Niepoprawne hasło. Spróbuj ponownie.";
                        messageBox.classList.add("error");
                    } 
                    else if (data.includes("jest już zalogowany")) {
                        messageBox.textContent = "❌ Użytkownik o tym nicku jest już zalogowany";
                        messageBox.classList.add("error");
                    }else {
                        messageBox.textContent = `⚠️ Błąd: ${data}`;
                        messageBox.classList.add("error");
                    }

                    setTimeout(() => {
                        messageBox.classList.add("hidden");
                    }, 3000);
                }
            })
            .catch(error => {
                console.error("❗ Błąd w fetch:", error);
            });
        });
    }
});
</script>

<div id="loginMessage" class="hidden message-box">...</div>

</body>
</html>
