<?php
session_start();
require 'db.php';

echo "<h2>Welkom bij de Test Management App</h2>";

if (!isset($_SESSION["user_id"])) {
    echo "<p>Meld u aan of registreer u om de app te gebruiken.</p>";
    echo "<a href='login.php'>inloggen</a> | <a href='register.php'>registreren</a>";
} else {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION["user_id"]]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user["role"] == "teacher") {
            header("Location: teacher_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }
    } else {
        echo "<p style='color:red;'>U bent nog niet geregistreerd!</p>";
        session_destroy();
    }
}

echo "<br><br><a href='logout.php'>uitloggen/a>";
?>
