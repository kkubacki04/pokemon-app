let userId = sessionStorage.getItem("user_id");

if (!userId) {
        userId = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });

    sessionStorage.setItem("user_id", userId); }

console.log("User ID: " + userId);  

const socket = new WebSocket("ws://192.168.215.212:9000?user_id=" + userId);
socket.onopen = function () {
    console.log("Połączono z serwerem WebSocket jako: " + userId);
};

socket.onmessage = function (event) {
    const data = JSON.parse(event.data);

    if (data.role) {
        sessionStorage.setItem("playerRole", data.role);
        console.log("Twoja rola:", data.role);
    }

    if (data.error) {
        console.log("Błąd:", data.error);
        alert("Gra jest pełna! Przekierowanie na stronę błędu...");
        window.location.href = "error.html";
    }

    if (data.type === "countdown") {
        console.log("Odliczanie: " + data.countdown);
        document.getElementById("countdown").innerText = `Start za: ${data.countdown}s`;
    }

    if (data.type === "redirect") {
        console.log("Przekierowanie na:", data.url);
        window.location.href = data.url;
    }


    if (data.type === 'pokemon_update') {
        const localUserId = sessionStorage.getItem("user_id");
        if (String(data.from_user) !== String(localUserId)) {
            setTimeout(() => {

                window.location.reload();
            }, 2000);
        }

        else {
            console.log(String(data.from_user));
            console.log("🟢 Otrzymałem własną wiadomość o aktualizacji — nic nie robię.");
        }
    }
};
socket.onclose = function () {
    console.log("Rozłączono z serwerem WebSocket.");
};
