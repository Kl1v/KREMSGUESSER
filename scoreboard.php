<?php

include 'connection.php'; 

// Abrufen der Punktestände
function fetchScores($conn) {
    $sql = "SELECT name, score FROM scoreboard ORDER BY score DESC";
    $result = $conn->query($sql);

    $scores = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $scores[] = $row;
        }
    }

    return $scores;
}

// Überprüfen, ob eine POST-Anfrage zum Abrufen der Punktestände gesendet wurde
if (isset($_POST['fetch'])) {
    $scores = fetchScores($conn);
    $conn->close();
    echo json_encode($scores); // Geben Sie die Scores als JSON zurück
    exit; // Stoppen Sie die weitere Verarbeitung
}

// Standardmäßig die Punktestände abrufen, um sie beim Laden der Seite anzuzeigen
$scores = fetchScores($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoreboard - HTL Krems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 20px;
    }

    h1 {
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Scoreboard</h1>
        <table class="table table-striped" id="scoreTable">
            <thead>
                <tr>
                    <th scope="col">Platz</th>
                    <th scope="col">Name</th>
                    <th scope="col">Punkte</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($scores as $index => $player) {
                    echo "<tr>";
                    echo "<th scope='row'>" . ($index + 1) . "</th>";
                    echo "<td>" . htmlspecialchars($player['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($player['score']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function fetchScores() {
        $.ajax({
            url: '', // aktuelle Datei
            type: 'POST',
            data: {
                fetch: true
            },
            success: function(data) {
                const scores = JSON.parse(data);
                updateTable(scores);
            },
            error: function(xhr, status, error) {
                console.error("Fehler beim Abrufen der Punktestände:", error);
            }
        });
    }

    function updateTable(scores) {
        const tableBody = $('#scoreTable tbody');
        tableBody.empty(); // Bestehende Zeilen leeren

        scores.forEach((player, index) => {
            tableBody.append(`
                    <tr>
                        <th scope='row'>${index + 1}</th>
                        <td>${player.name}</td>
                        <td>${player.score}</td>
                    </tr>
                `);
        });
    }

    setInterval(fetchScores, 1000); // Live-Aktualisierung alle 1 Sekunde
    </script>
</body>

</html>