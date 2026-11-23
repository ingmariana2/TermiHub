<?php
/**
 * Configuration for the database connection
 */

$host = 'localhost';
$db   = 'termihub_db';
$user = 'root';
$pass = ''; // Default for local MySQL installations like XAMPP
$port = '3306';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     // Connection successful
} catch (\PDOException $e) {
     // Connection failed
     // In a real app, you might want to log this instead of throwing/echoing
     // throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
