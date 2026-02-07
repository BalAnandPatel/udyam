<?php
// Database configuration
$servername = "localhost";  // usually localhost
$username   = "u564757906_giplmlm";
$password   = "12qw!@QW99"; 
$database   = "u564757906_giplmlm";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Start session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default timezone to avoid date() warnings
if (!ini_get('date.timezone')) {
    date_default_timezone_set("Asia/Kolkata"); // Change if needed
}
?>
