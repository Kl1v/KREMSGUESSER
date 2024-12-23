<?php
require 'connection.php'; // Verbindung zur Datenbank
require 'functions.php'; // Gemeinsame Funktionen
session_start(); // Session starten

// Überprüfen, ob der Benutzername in der Session gesetzt ist
if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    die('Benutzername ist nicht gesetzt. Bitte melde dich an.');
}

// Funktion zur Generierung zufälliger Koordinaten in Krems
function getRandomLocationInKrems()
{
    $latMin = 48.392; // Minimaler Breitengrad
    $latMax = 48.428; // Maximaler Breitengrad
    $lngMin = 15.577; // Minimaler Längengrad
    $lngMax = 15.625; // Maximaler Längengrad

    $latitude = rand($latMin * 1000000, $latMax * 1000000) / 1000000;
    $longitude = rand($lngMin * 1000000, $lngMax * 1000000) / 1000000;

    return ['lat' => $latitude, 'lng' => $longitude];
}

// Funktion zur Validierung, ob Street View für eine Position verfügbar ist
function isStreetViewAvailable($lat, $lng)
{
    $apiKey = "AIzaSyCQnFQURsReLCE66o_kF2oNvgFMDkHyO6E";
    $url = "https://maps.googleapis.com/maps/api/streetview/metadata?location=$lat,$lng&key=$apiKey";

    $context = stream_context_create([
        'https' => ['timeout' => 5] // Timeout auf 5 Sekunden setzen
    ]);

    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        return false;
    }

    $data = json_decode($response, true);
    return isset($data['status']) && $data['status'] === "OK";
}

// Funktion zur Erstellung der Lobby
function createLobby($conn, $lobbyCode, $rounds, $timeLimit)
{
    // Überprüfen, ob die Lobby bereits existiert
    $stmt = $conn->prepare("SELECT * FROM lobbies WHERE code = ?");
    $stmt->bind_param("s", $lobbyCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO lobbies (code, rounds, time_limit) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $lobbyCode, $rounds, $timeLimit);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
    }
    $stmt->close();

    // Spieler als Host hinzufügen
    $stmt = $conn->prepare("INSERT INTO players (username, lobby_code, is_host) VALUES (?, ?, 1)");
    $stmt->bind_param("ss", $_SESSION['user_name'], $lobbyCode);
    if (!$stmt->execute()) {
        $stmt->close();
        return false;
    }
    $stmt->close();

    // Zufällige Positionen für jede Runde generieren
    for ($i = 1; $i <= $rounds; $i++) {
        $location = null;

        // Wiederholen, bis eine valide Position gefunden wird
        do {
            $randomLocation = getRandomLocationInKrems();
            $isValid = isStreetViewAvailable($randomLocation['lat'], $randomLocation['lng']);
            if ($isValid) {
                $location = $randomLocation;
            }
        } while ($location === null);

        $stmt = $conn->prepare("INSERT INTO locations (lobby_code, round, latitude, longitude) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sidd", $lobbyCode, $i, $location['lat'], $location['lng']);
        if (!$stmt->execute()) {
            $stmt->close();
            return false;
        }
    }

    return true;
}

// POST-Request verarbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lobbyCode = $_POST['lobbyCode'] ?? null;
    $rounds = isset($_POST['rounds']) ? intval($_POST['rounds']) : null;
    $timeLimit = isset($_POST['timeLimit']) ? intval($_POST['timeLimit']) : null;

    if (!$lobbyCode || !$rounds || !$timeLimit) {
        $errorMessage = 'Alle Felder müssen ausgefüllt sein!';
    } else {
        $result = createLobby($conn, $lobbyCode, $rounds, $timeLimit);
        if ($result) {
            header("Location: start_lobby.php?code=" . $lobbyCode);
            exit;
        } else {
            $errorMessage = 'Fehler beim Erstellen der Lobby.';
        }
    }
}

// Generiere einen eindeutigen Code für die Lobby
$lobbyCode = generateUniqueLobbyCode($conn);
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KremsGuesser - Lobby Erstellen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body style="padding-top: 70px;">
    <?php require 'navbar.php'; ?>

    <div class="container">
        <div class="play-container">
            <h1 class="text-center">Lobby Erstellen</h1>
            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="d-flex flex-column align-items-center gap-3">
                    <div class="code-container w-100">
                        <h1>Code</h1>
                        <input type="text" maxlength="4" value="<?php echo $lobbyCode; ?>" readonly name="lobbyCode">
                    </div>
                    <div class="code-container w-100">
                        <h1>RUNDEN:</h1>
                        <input type="radio" id="round3" name="rounds" value="3" checked><label for="round3">3</label>
                        <input type="radio" id="round5" name="rounds" value="5"><label for="round5">5</label>
                        <input type="radio" id="round10" name="rounds" value="10"><label for="round10">10</label>
                    </div>
                    <div class="code-container w-100">
                        <h1>ZEITLIMIT PRO RUNDE</h1>
                        <input type="number" min="10" max="120" name="timeLimit">
                    </div>
                    <button type="submit" class="btn btn-warning">START</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
