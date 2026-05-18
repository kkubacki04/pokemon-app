#  Pokémon Real-Time Multiplayer Arena

A real-time, browser-based turn-based RPG Pokémon battle game. This project demonstrates backend-driven state machine management, low-level WebSocket synchronization, complex side-effect engines (RPG status effects), and strong web security practices using vanilla PHP and MySQL—without relying on high-level frameworks or third-party real-time services (like Firebase or Pusher).


<img width="823" height="806" alt="image" src="https://github.com/user-attachments/assets/48868bfb-91ee-42f8-b420-d9cd1f789c36" />


##  Core Technical Architecture & Features

* **Database-Driven Turn-Based State Machine:** Game turns (`player_round`) are managed entirely on the backend to prevent client-side tampering. Even if a user alters the DOM to unlock action buttons, the PHP backend rejects unauthorized requests by validating the current turn state directly against the database.
* **Low-Level WebSocket Synchronization:** Built using **Ratchet** and **ReactPHP** as an asynchronous event loop. It maintains active player roles, handles sudden browser refreshes cleanly without losing connection slot reservations, and broadcasts structural view updates across clients in real-time.
* **Advanced RPG Status Effects Engine:** Implements a complex side-effects loop triggered on turn initialization. The backend dynamically evaluates active temporary conditions (Poison, Burn, Sleep, Stun):
  * *Sleep/Stun:* Skips the player's attack phase, clears the state, and auto-forwards the turn to the opponent.
  * *Poison/Burn:* Inflicts passive damage at turn start, handling edge-case faint resolutions before any action executes.
* **Post-Redirect-Get (PRG) Pattern Compliance:** Form submissions for combat abilities are routed through the PRG design pattern. After mutations are written to the database, the backend forces an immediate `GET` redirection, entirely neutralizing the common "Form Resubmission" flaw on client-side page refreshes.
* **Strict Relational Database Integrity:** Fully protected against SQL Injection via strict Prepared Statements. Leverages clean indexing, database transactions for session resets, and structured relationships between pokemon squads and condition modifiers.

##  Tech Stack

* **Backend:** PHP 8.x (Object-Oriented, Custom Event Loops)
* **Real-time Networking:** Ratchet (WebSockets), ReactPHP
* **Frontend:** Vanilla JavaScript (ES6+), HTML5, CSS3 Custom Properties
* **Database:** MySQL / MariaDB (Prepared Statements, Transactions)
* **Dependency Manager:** Composer

##  Database Design Highlights

The relational schema is optimized to act as the absolute *Single Source of Truth* across distributed sessions:
* `player_round` - Drives the global turn state machine.
* `player1_squad` / `player2_squad` - Live tracking of team status, current HP, and battle progression.
* `squad1status` / `squad2status` - Persistent matrices monitoring passive and active crowd control status conditions.
* `type_chart` - Matrix mapping elemental effectiveness indicators (dynamic structural lookups).

##  Local Installation & Deployment

1. **Clone the Repo:**
   ```bash
   git clone [https://github.com/kkubacki04/pokemon-app.git](https://github.com/kkubacki04/pokemon-app.git)
   cd pokemon-app
   #  Pokémon Real-Time Multiplayer Arena

A real-time, browser-based turn-based RPG Pokémon battle game. This project demonstrates backend-driven state machine management, low-level WebSocket synchronization, complex side-effect engines (RPG status effects), and strong web security practices using vanilla PHP and MySQL—without relying on high-level frameworks or third-party real-time services (like Firebase or Pusher).

##  Core Technical Architecture & Features

* **Database-Driven Turn-Based State Machine:** Game turns (`player_round`) are managed entirely on the backend to prevent client-side tampering. Even if a user alters the DOM to unlock action buttons, the PHP backend rejects unauthorized requests by validating the current turn state directly against the database.
* **Low-Level WebSocket Synchronization:** Built using **Ratchet** and **ReactPHP** as an asynchronous event loop. It maintains active player roles, handles sudden browser refreshes cleanly without losing connection slot reservations, and broadcasts structural view updates across clients in real-time.
* **Advanced RPG Status Effects Engine:** Implements a complex side-effects loop triggered on turn initialization. The backend dynamically evaluates active temporary conditions (Poison, Burn, Sleep, Stun):
  * *Sleep/Stun:* Skips the player's attack phase, clears the state, and auto-forwards the turn to the opponent.
  * *Poison/Burn:* Inflicts passive damage at turn start, handling edge-case faint resolutions before any action executes.
* **Post-Redirect-Get (PRG) Pattern Compliance:** Form submissions for combat abilities are routed through the PRG design pattern. After mutations are written to the database, the backend forces an immediate `GET` redirection, entirely neutralizing the common "Form Resubmission" flaw on client-side page refreshes.
* **Strict Relational Database Integrity:** Fully protected against SQL Injection via strict Prepared Statements. Leverages clean indexing, database transactions for session resets, and structured relationships between pokemon squads and condition modifiers.

##  Tech Stack

* **Backend:** PHP 8.x (Object-Oriented, Custom Event Loops)
* **Real-time Networking:** Ratchet (WebSockets), ReactPHP
* **Frontend:** Vanilla JavaScript (ES6+), HTML5, CSS3 Custom Properties
* **Database:** MySQL / MariaDB (Prepared Statements, Transactions)
* **Dependency Manager:** Composer

##  Database Design Highlights

The relational schema is optimized to act as the absolute *Single Source of Truth* across distributed sessions:
* `player_round` - Drives the global turn state machine.
* `player1_squad` / `player2_squad` - Live tracking of team status, current HP, and battle progression.
* `squad1status` / `squad2status` - Persistent matrices monitoring passive and active crowd control status conditions.
* `type_chart` - Matrix mapping elemental effectiveness indicators (dynamic structural lookups).

##  Local Installation & Deployment

1. **Clone the Repo:**
   ```bash
   git clone [https://github.com/kkubacki04/pokemon-app.git](https://github.com/kkubacki04/pokemon-app.git)
   cd pokemon-app
   ```
   
2. **Environment Setup (XAMPP / Local Server):**

Move the project directory into your local server root (e.g., C:/xampp/htdocs/Pokemon/).

Open the local server control panel and activate both Apache and MySQL modules.

3. **Database Migration:**

Access phpMyAdmin (typically at http://localhost:8080/phpmyadmin/ or http://localhost/phpmyadmin/).

Construct a new database titled pokemondb.

Import the structured pokemondb.sql file provided within this repository.
4. **Install Dependencies:**

```Bash
composer install
```
5. **Fire up the WebSocket Daemon:**
Open your terminal inside the project directory and execute the background daemon:
```
Bash
php server.php
```
(Keep this terminal terminal window running to handle asynchronous communication).
6. **Initialize Combat:**

Open a standard browser tab at: http://localhost:8080/Pokemon/logowanie.php (Player 1)

Open a secondary Incognito / Private window at the same link (Player 2) to prevent local session storage overrides.

Lock in your rosters, hit "Start", and enter the arena.
<img width="1919" height="1029" alt="image" src="https://github.com/user-attachments/assets/3ba78af6-c82d-48d6-8c3d-5631383b6ff4" />
<img width="1914" height="905" alt="image" src="https://github.com/user-attachments/assets/ee514818-a3f2-4622-832a-d734985f311d" />
<img width="1895" height="932" alt="image" src="https://github.com/user-attachments/assets/6a797ac0-4b3d-4dd5-9a25-3e412c172f7a" />

