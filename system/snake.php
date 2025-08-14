<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake and Ladder - Custom Players</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f8ff;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .game-container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 40px;
            margin-top: 20px;
        }
        .board {
            display: none;
            grid-template-columns: repeat(10, 100px);
            gap: 5px;
            margin: 20px auto;
            display: grid;
        }
        .cell {
            width: 100px;
            height: 100px;
            border: 2px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            position: relative;
            transition: background-color 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            color: black;
            font-weight: bold;
        }
        .cell:nth-child(odd) {
            background-color: #d0f0c0;
        }
        .cell:nth-child(even) {
            background-color: #ffffff;
        }
        .cell:hover {
            background-color: #e0e0e0;
        }
        .cell-number {
            font-size: 18px;
            position: absolute;
            top: 5px;
            right: 5px;
        }
        .player {
            position: absolute; /* Ensure the player markers are positioned absolutely */
            top: 20px; /* Adjust the top position */
            left: 20px; /* Adjust the left position */
            width: 50px; /* Set a width for the player marker */
            height: 50px; /* Set a height for the player marker */
            text-align: center; /* Center the text */
            font-size: 30px; /* Increase font size for symbols */
        }
        .special {
            background-color: #ffcc00;
            font-weight: bold;
        }
        .controls {
            display: none;
            text-align: left;
            font-size: 20px;
            width: 300px;
        }
        .dice-button {
            font-size: 24px;
            padding: 15px 25px;
            margin: 20px 0;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }
        .dice-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }
        .dice-result {
            font-size: 30px;
            font-weight: bold;
            margin: 10px 0;
        }
        .setup {
            text-align: center;
            padding: 20px;
            border: 2px solid #333;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            margin: 0 auto;
        }
        input[type="text"] {
            width: 100%; /* Make the input field take the full width */
            padding: 10px; /* Add padding for better spacing */
            font-size: 18px; /* Increase font size */
            border: 2px solid #333; /* Add border for better visibility */
            border-radius: 5px; /* Add border radius for rounded corners */
            margin-bottom: 10px; /* Add margin for spacing */
        }
        .avatar-selection {
            display: flex; /* Use flexbox for better layout */
            flex-wrap: wrap; /* Allow wrapping of avatars */
            justify-content: center; /* Center the avatars */
        }
        .avatar {
            width: 80px; /* Size of card suits */
            height: 80px; /* Size of card suits */
            margin: 15px; /* Increase spacing between avatars */
            cursor: pointer;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: border-color 0.3s;
            font-size: 50px; /* Increase font size for symbols */
        }
        .avatar:hover {
            border-color: #4CAF50;
        }
        .winner-message {
            font-size: 24px;
            font-weight: bold;
            color: green;
            margin-top: 20px;
        }
        .player-name {
            font-size: 14px;
            margin-top: 5px;
            color: #333;
        }
        /* Help Section Styles */
        .help-section {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 2px solid #333;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
        .help-section h2 {
            margin-top: 0;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 999;
        }
    </style>
</head>
<body>

    <h1>Snake and Ladder - Custom Players</h1>

    <div id="setup" class="setup">
        <h2>Select Number of Players</h2>
        <select id="numPlayers">
            <option value="2">2 Players</option>
            <option value="3">3 Players</option>
            <option value="4">4 Players</option>
        </select>

        <div id="playerSetup"></div>

        <button class="dice-button" onclick="startGame()">Start Game</button>
        <button class="dice-button" onclick="showHelp()">Help</button>
    </div>

    <div class="game-container">
        <!-- Board -->
        <div class="board" id="board"></div>

        <!-- Controls -->
        <div class="controls" id="gameControls">
            <button class="dice-button" id="rollButton" onclick="rollDice()">🎲 Roll Dice</button>
            <p class="dice-result">Dice Roll: <span id="diceResult">0</span></p>
            <p>🎯 <strong>Current Player:</strong> <span id="currentPlayer">1</span></p>
            <p>📌 <strong>Player Positions:</strong></p>
            <ul id="playerPositions"></ul>
        </div>
    </div>

    <div id="winnerMessage" class="winner-message" style="display: none;"></div>
    <button class="dice-button" id="restartButton" onclick="restartGame()" style="display: none;">Restart Game</button>

    <!-- Help Section -->
    <div class="overlay" id="overlay"></div>
    <div class="help-section" id="help">
        <h2>How to Play</h2>
        <p>Welcome to Snake and Ladder! The objective of the game is to reach the last square (100) first.</p>
        <p>Players take turns rolling the dice. Move your player forward the number of spaces shown on the dice.</p>
        <p>If you land on a special tile, follow the instructions displayed on the tile.</p>
        <p>The first player to reach square 100 wins!</p>
        <button class="dice-button" onclick="hideHelp()">Close</button>
    </div>

    <script>
        let players = [];
        let currentPlayerIndex = 0;
        let selectedAvatars = [];
        let hasRolled = false; // Flag to track if the current player has rolled the dice
        const cardSuits = [
            '♥️', // Heart
            '♠️', // Spade
            '♦️', // Diamond
            '♣️'  // Club
        ];
        const specialTiles = {
            10: { word: "TRAP", backTo: 3 },
            13: { word: "JUMP TO", goTo: 18 },
            15: { word: "JUMP TO", goTo: 21 },
            17: { word: "TRAP", backTo: 14 },
            25: { word: "TRAP", backTo: 12 },
            27: { word: "JUMP TO", goTo: 35 },
            31: { word: "TRAP", backTo: 26 },
            41: { word: "RESET", backTo: 29 },
            50: { word: "JUMP TO", backTo: 59 },
            53: { word: "TRAP", backTo: 20 },
            55: { word: "JUMP TO", backTo: 60 },
            69: { word: "TRAP", backTo: 36 },
            77: { word: "TRAP", backTo: 65 },
            79: { word: "JUMP TO", goTo: 89 },
            85: { word: "JUMP BACK", backTo: 75 },
            95: { word: "TRAP", backTo: 80 },
            97: { word: "TRAP", backTo: 91 }
        };

        function createPlayerSetup() {
            const numPlayers = parseInt(document.getElementById("numPlayers").value);
            const playerSetupDiv = document.getElementById("playerSetup");
            playerSetupDiv.innerHTML = "";
            selectedAvatars = [];

            for (let i = 1; i <= numPlayers; i++) {
                const div = document.createElement("div");
                div.innerHTML = `
                    <h3>Player ${i}</h3>
                    <label>Name: <input type="text" id="player${i}Name" placeholder="Enter name"></label>
                    <div>
                        <strong>Select Card Suit:</strong>
                        <div id="avatarSelection${i}" class="avatar-selection"></div>
                    </div>
                `;
                playerSetupDiv.appendChild(div);
                createAvatarSelection(i);
            }
        }

        function createAvatarSelection(playerIndex) {
            const avatarSelectionDiv = document.getElementById(`avatarSelection${playerIndex}`);
            cardSuits.forEach((suit, index) => {
                const span = document.createElement("span");
                span.innerHTML = suit;
                span.classList.add("avatar");
                span.onclick = () => selectAvatar(playerIndex, index);
                avatarSelectionDiv.appendChild(span);
            });
        }

        function selectAvatar(playerIndex, avatarIndex) {
            const selectedAvatar = cardSuits[avatarIndex];
            const playerNameInput = document.getElementById(`player${playerIndex}Name`);
            if (playerNameInput.value) {
                selectedAvatars[playerIndex - 1] = selectedAvatar; // Store selected avatar
                playerNameInput.style.borderColor = "#4CAF50"; // Change border color to indicate selection
            } else {
                alert("Please enter a name before selecting a card suit.");
            }
        }

        document.getElementById("numPlayers").addEventListener("change", createPlayerSetup);
        createPlayerSetup();

        function startGame() {
            const numPlayers = parseInt(document.getElementById("numPlayers").value);
            players = [];

            // Collect player names and avatars
            for (let i = 1; i <= numPlayers; i++) {
                const name = document.getElementById(`player${i}Name`).value || `Player ${i}`;
                const avatar = selectedAvatars[i - 1] || cardSuits[0]; // Default to first avatar if none selected

                players.push({
                    id: i,
                    name,
                    position: 1,
                    avatar
                });
            }

            // Check for duplicate avatars
            const avatarSet = new Set(players.map(player => player.avatar));
            if (avatarSet.size !== players.length) {
                alert("Each player must have a unique card suit. Please select different suits.");
                return; // Prevent starting the game
            }

            document.getElementById("setup").style.display = "none";
            document.getElementById("board").style.display = "grid";
            document.getElementById("gameControls").style.display = "block";
            document.getElementById("rollButton").disabled = false; // Enable the roll button for the first player

            createBoard();
        }

        function createBoard() {
            const board = document.getElementById("board");
            board.innerHTML = "";
            const rows = 10; // 10 rows for the board
            const cols = 10; // 10 columns for the board

            for (let row = 0; row < rows; row++) {
                const rowCells = [];
                for (let col = 0; col < cols; col++) {
                    const cellNumber = (rows - row) * cols - col; // 100 at the top, 1 at the bottom
                    const cell = document.createElement("div");
                    cell.classList.add("cell");
                    cell.id = "cell-" + cellNumber;

                    const cellNumberDiv = document.createElement("div");
                    cellNumberDiv.classList.add("cell-number");
                    cellNumberDiv.innerText = cellNumber;

                    cell.appendChild(cellNumberDiv);

                    if (cellNumber === 100) {
                        cell.innerHTML += `<strong>🏆</strong>`;
                    } else if (specialTiles[cellNumber]) {
                        cell.classList.add("special");
                        cell.innerHTML += `<strong>${specialTiles[cellNumber].word}</strong>`;
                    }

                    rowCells.push(cell);
                }
                rowCells.forEach(cell => board.appendChild(cell));
            }
            updatePlayerPositions();
        }

        function rollDice() {
            if (hasRolled) {
                alert("You can only roll the dice once per turn. Wait for your next turn or roll again if you land on a special tile.");
                return; // Prevent rolling again
            }

            const diceRoll = Math.floor(Math.random() * 6) + 1;
            document.getElementById("diceResult").innerText = diceRoll;

            let currentPlayer = players[currentPlayerIndex];
            hasRolled = true; // Set the flag to indicate the player has rolled

            animateMovement(currentPlayer, diceRoll);
        }

        function animateMovement(player, steps) {
            let moveCount = 0;

            function moveStep() {
                if (moveCount < steps && player.position < 100) {
                    player.position++;
                    updatePlayerPositions();
                    moveCount++;
                    setTimeout(moveStep , 500); 
                } else {
                    // Check for victory
                    if (player.position === 100) {
                        celebrateWinner(player);
                    } else {
                        // Check for special tiles
                        if (specialTiles[player.position]) {
                            handleSpecialTile(player);
                        } else {
                            nextPlayerTurn();
                        }
                    }
                }
            }
            moveStep();
        }

        function handleSpecialTile(player) {
            const tile = specialTiles[player.position];
            if (tile.action === "roll") {
                rollDice(); // Automatically roll again
            } else if (tile.goTo) {
                player.position = tile.goTo; // Move to a specific tile
                updatePlayerPositions();
                nextPlayerTurn();
            } else {
                // Handle other special tiles
                player.position = tile.backTo;
                updatePlayerPositions();
                nextPlayerTurn();
            }
        }

        function celebrateWinner(player) {
            document.getElementById("winnerMessage").innerText = `${player.name} has won the game! 🎉`;
            document.getElementById("winnerMessage").style.display = "block";
            document.getElementById("gameControls").style.display = "none"; // Hide controls
            document.getElementById("board").style.display = "none"; // Hide board
            document.getElementById("restartButton").style.display = "block"; // Show restart button
            displayGameSummary();
        }

        function nextPlayerTurn() {
            currentPlayerIndex = (currentPlayerIndex + 1) % players.length;
            document.getElementById("currentPlayer").innerText = players[currentPlayerIndex].name;
            hasRolled = false; // Reset the roll flag for the next player
            document.getElementById("rollButton").disabled = false; // Enable the roll button for the next player
        }

        function updatePlayerPositions() {
            document.querySelectorAll(".player").forEach(p => p.remove());

            players.forEach(player => {
                const marker = document.createElement("div");
                marker.classList.add("player");
                marker.innerHTML = `
                    <div>${player.avatar}</div>
                    <div class="player-name">${player.name}</div>
                `; // Display card suit and player name
                document.getElementById(`cell-${player.position}`).appendChild(marker);
            });
        }

        // Help Section Functions
        function showHelp() {
            document.getElementById("help").style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }

        function hideHelp() {
            document.getElementById("help").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }

        // Game Summary Function
        function displayGameSummary() {
            let summary = "Game Summary:\n";
            players.forEach(player => {
                summary += `${player.name} (Suit: ${player.avatar}) - Final Position: ${player.position}\n`;
            });
            alert(summary);
        }

        // Restart Game Function
        function restartGame() {
            // Reset game variables
            players = [];
            currentPlayerIndex = 0;
            selectedAvatars = [];
            hasRolled = false;

            // Reset the UI
            document.getElementById("winnerMessage").style.display = "none";
            document.getElementById("restartButton").style.display = "none"; // Hide restart button
            document.getElementById("setup").style.display = "block";
            document.getElementById("board").style.display = "none";
            document.getElementById("gameControls").style.display = "none";
            document.getElementById("playerSetup").innerHTML = ""; // Clear player setup
            document.getElementById("numPlayers").value = "2"; // Reset to default number of players
            createPlayerSetup(); // Recreate player setup
        }
    </script>

</body>
</html>