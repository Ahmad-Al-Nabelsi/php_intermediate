<?php
session_start();
require 'db.php';

// Zorg ervoor dat de gebruiker de docent is
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "teacher") {
    header("Location: dashboard.php");
    exit();
}
?>

<h2>🎓 Controlepaneel voor docenten </h2>

<ul>
    <li><a href="manage_tests.php">📌 Testbeheer </a></li>
    <li><a href="manage_students.php">👨‍🎓 Studentenadministratie </a></li>
    <li><a href="manage_scores.php">📊 Cijferbeheer </a></li>
    <li><a href="logout.php">🚪Uitloggen </a></li>
</ul>
