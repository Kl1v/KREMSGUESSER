<?php
$servername = "medtdb.mysql.database.azure.com";
$username = "kliv@medtdb";
$password = "Antonginger1"; 
$dbname = "opendays";

$ssl_ca = "\DigiCertGlobalRootG2.crt.pem";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
    ]);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Verbindung erfolgreich!";
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}
?>