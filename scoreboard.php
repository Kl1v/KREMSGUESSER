<?php

include 'connection.php'; 

$sql = "SELECT name, score FROM scoreboard ORDER BY score DESC";
$result = $conn->query($sql);

$scores = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $scores[] = $row;
    }
}

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
        <table class="table table-striped">
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
</body>

</html>