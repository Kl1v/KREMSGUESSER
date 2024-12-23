<?php
session_start();
require 'connection.php'; // Verbindung zur Datenbank
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krems Guesser - Bestenliste</title>
    <link rel="stylesheet" href="stylemain.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
    body {
        margin: 0;
        background: linear-gradient(to bottom, #3D0059, #ffffff);
        color: #fff;
    }

    nav.navbar {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    .wave-container {
        position: relative;
        height: 50vh;
        background: #3D0059;
    }

    .wave {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 100%;
    }

    main {
        margin-top: 11px;
    }

    .scoreboard {
        margin: auto;
        width: 80%;
        max-width: 800px;
        background-color: #fff;
        color: #333;
        border-radius: 20px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        text-align: center;
        padding-bottom: 20px;
    }

    .scoreboard h2 {
        background: linear-gradient(90deg, #A972D8, #C0A5E5);
        color: #333;
        margin: 0;
        padding: 15px 0;
        font-size: 1.8em;
        font-weight: bold;
    }

    .user-score {
        background: #F0E4FF;
        color: #333;
        padding: 10px;
        font-weight: bold;
        border-bottom: 1px solid #ddd;
    }

    .scrollable-table {
        max-height: 300px;
        overflow-y: auto;
        margin-top: 20px;
    }

    .scores-table {
        width: 100%;
        border-collapse: collapse;
    }

    .scores-table th,
    .scores-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .scores-table th {
        background: #E6D7F1;
        color: #333;
    }

    .scores-table td {
        color: #333;
    }

    .start-button {
        margin: 30px 0;
        text-align: center;
    }

    .start-button a {
        text-decoration: none;
        font-size: 1.2em;
        color: #fff;
        background-color: #FFC800;
        padding: 10px 20px;
        border-radius: 25px;
        transition: background-color 0.3s ease;
    }

    .start-button a:hover {
        background-color: #E6A600;
    }

    footer {
        text-align: center;
        padding: 10px;
        font-size: 0.8em;
        background-color: #22003D;
        color: #fff;
    }

    .boardstyle {
        position: relative;
        text-align: center;
    }

    .overlay-title {
        position: absolute;
        top: 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 3rem;
        font-weight: bold;
        z-index: 1;
        pointer-events: none;
    }

    svg {
        display: block;
        width: 100%;
        height: auto;
    }

    .wave-container {
        position: relative;
        width: 100%;
        overflow: hidden;
        background: #3D0059;
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php require 'navbar.php'; ?>

    <div class="boardstyle">
        <h2 class="overlay-title">BESTENLISTE</h2>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#1e0028" fill-opacity="1"
                d="M0,96L80,122.7C160,149,320,203,480,213.3C640,224,800,192,960,186.7C1120,181,1280,203,1360,213.3L1440,224L1440,0L1360,0C1280,0,1120,0,960,0C800,0,640,0,480,0C320,0,160,0,80,0L0,0Z">
            </path>
        </svg>
    </div>

    <!-- Hauptinhalt -->
    <main style="margin-bottom: 50px;">
        <section class="scoreboard">
            <?php
            if (isset($_SESSION['user_id'])) {
                // Benutzer ist angemeldet, hole seinen Score
                $user_id = $_SESSION['user_id'];
                $user_query = $conn->prepare("SELECT username, score FROM login WHERE id = ?");
                $user_query->bind_param("i", $user_id);
                $user_query->execute();
                $user_result = $user_query->get_result();

                if ($user_result->num_rows > 0) {
                    $user_row = $user_result->fetch_assoc();
                    // Berechne den Rang des Benutzers
                    $rank_query = $conn->prepare("SELECT COUNT(*) + 1 AS rank FROM login WHERE score > ?");
                    $rank_query->bind_param("i", $user_row['score']);
                    $rank_query->execute();
                    $rank_result = $rank_query->get_result();
                    $rank = $rank_result->fetch_assoc()['rank'];

                    echo "<div class='user-score'>Dein Rang: $rank | Dein Score: " . htmlspecialchars($user_row['score']) . " | Name: " . htmlspecialchars($user_row['username']) . "</div>";
                }
            }
            ?>

            <table class="scores-table">
                <thead>
                    <tr>
                        <th>Rang</th>
                        <th>Score</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_top10 = "SELECT username, score FROM login ORDER BY score DESC LIMIT 10";
                    $result_top10 = $conn->query($sql_top10);

                    if ($result_top10->num_rows > 0) {
                        $rank = 1;
                        while ($row = $result_top10->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $rank . "</td>
                                    <td>" . htmlspecialchars($row['score']) . "</td>
                                    <td>" . htmlspecialchars($row['username']) . "</td>
                                  </tr>";
                            $rank++;
                        }
                    } else {
                        echo "<tr><td colspan='3'>Keine Spieler gefunden</td></tr>";
                    }
                    ?>
                </tbody>
            </table>


            <div class="start-button">
                <a href="play.php">START</a>
            </div>
        </section>
    </main>

    <footer>
        © 2024 KREMSGUESSER
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>