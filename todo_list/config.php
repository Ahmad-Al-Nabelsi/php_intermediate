<?php
//Instellingen voor databaseverbinding
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "task_manager";

// Verbinding maken
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer verbinding
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
