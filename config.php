<?php
$host = 'localhost';
$dbname = 'db9cwwq02mheia';
$username = 'u2eqm4kmqggeb';
$password = 'atsjwdv1fqz2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

session_start();
?>
