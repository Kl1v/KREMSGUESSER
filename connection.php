<?php
$servername = "medtdb.mysql.database.azure.com";
$username = "kliv@medtdb";
$password = "Antonginger1";
$dbname = "opendays";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}
?>