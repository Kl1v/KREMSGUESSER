<?php
$servername = "medtdb.mysql.database.azure.com";
$username = "kliv";
$password = "Antonginger1"; // Ersetze xxx mit deinem Passwort
$dbname = "opendays";

// Pfad zum SSL-Zertifikat
$ssl_ca = "C:\\xampp\\htdocs\\KREMSGUESSER\\DigiCertGlobalRootG2.crt.pem";

// Initialisiere MySQLi
$conn = mysqli_init();

// SSL-Einstellungen setzen
mysqli_ssl_set($conn, NULL, NULL, $ssl_ca, NULL, NULL);

// Verbinde ohne Zertifikatsprüfung
if (!mysqli_real_connect($conn, $servername, $username, $password, $dbname, 3306, NULL, MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

echo "Verbindung erfolgreich!";
?>