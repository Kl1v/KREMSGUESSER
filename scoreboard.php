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


header('Content-Type: application/json');
echo json_encode($scores);
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scoreboard - HTL Krems</title>
    <link href=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
            <tbody id="scoreboard">

            </tbody>
        </table>
    </div>

    <script>
    function updateScoreboard() {
        $.ajax({
            url: 'scoreboard.php',
            method: 'GET',
            success: function(data) {
                var scoreboard = $('#scoreboard');
                scoreboard.empty();


                data.forEach(function(player, index) {
                    scoreboard.append('<tr><th scope="row">' + (index + 1) + '</th><td>' + player
                        .name + '</td><td>' + player.score + '</td></tr>');
                });
            },
            error: function() {
                alert("Error fetching scoreboard.");
            }
        });
    }

    $(document).ready(function() {
        updateScoreboard();

        setInterval(updateScoreboard, 2000);
    });
    </script>
</body>

</html>