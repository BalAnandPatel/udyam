<?php
date_default_timezone_set("Asia/Kolkata"); // ✅ India timezone
// $servername = "localhost";  // usually localhost
// $username   = "u564757906_giplmlm";
// $password   = "12qw!@QW99"; 
// $database   = "u564757906_giplmlm";


$servername = "localhost";  // usually localhost
$username   = "root";
$password   = ""; 
$database   = "u564757906_giplmlm";


// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("DB connection failed: " . mysqli_connect_error());
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
