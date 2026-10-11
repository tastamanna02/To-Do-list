<?php
/**
 * Database Connection for To-Do List Application
 * Database Name: web_class
 */

$servername = "localhost";
$username = "root";     // XAMPP default is "root" (change if you have a custom username)
$password = "";         // XAMPP default password is empty "" (change if you have a custom password)
$dbname = "web_class";  // Database name: web_class

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// When accessed directly in browser (e.g., http://localhost/To-Do-list/db_connect.php)
if (basename($_SERVER['PHP_SELF']) === 'db_connect.php') {
  echo "Connected successfully";
}
?>
