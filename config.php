<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'orion_db');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS);
if (!$conn) {
    die("<div class='alert error'>Connection Failed: " . mysqli_connect_error() . "</div>");
}
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if (!mysqli_query($conn, $sql)) {
    die("<div class='alert error'>Error creating database: " . mysqli_error($conn) . "</div>");
}
mysqli_select_db($conn, DB_NAME);
$sql = "CREATE TABLE IF NOT EXISTS crew_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    stardate DATE NOT NULL,
    status ENUM('ACTIVE', 'OFFLINE') DEFAULT 'ACTIVE',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!mysqli_query($conn, $sql)) {
    die("<div class='alert error'>Error creating table: " . mysqli_error($conn) . "</div>");
}
?>
