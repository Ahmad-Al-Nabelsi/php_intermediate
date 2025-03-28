<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: dashboard.php");
    exit();
}

echo "<h2>Hallo student!</h2>";
echo "<a href='view_results.php'>Bekijk mijn resultaten</a>";
echo "<br><br><a href='dashboard.php'>Home</a> | <a href='logout.php'>Uitloggen</a>";
?>
