<?php
/**
 * GradeFlow — Database Connection
 * Auto-switches between Localhost (XAMPP) and Production (InfinityFree)
 */

$http_host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if ($http_host === 'gradeflow.gt.tc') {
    // InfinityFree / Live Credentials
    $host = "sql200.infinityfree.com";
    $db   = "if0_41443406_dh_name";
    $user = "if0_41443406";
    $pass = "Dpatil1677";
} else {
    // Local XAMPP Credentials
    $host = "localhost";
    $db   = "gradeflow";
    $user = "root";
    $pass = "";
}

try {
    $pdo = new PDO(
        "mysql:host=$host;port=3306;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>