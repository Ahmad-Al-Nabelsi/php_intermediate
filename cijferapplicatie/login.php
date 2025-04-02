<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        /* password_verify is een ingebouwde functie in PHP waarmee een ingevoerd wachtwoord 
        (zoals het wachtwoord dat een gebruiker typt in een inlogformulier) wordt vergeleken 
        met een gecodeerd wachtwoord dat is opgeslagen in de database.*/
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            header("Location: dashboard.php");
        } else {
            echo "<p style='color:red;'>Onjuist wachtwoord!</p>";
        }
    } else {
        echo "<p style='color:red;'>E-mailadres niet geregistreerd!</p>";
    }
}
?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Wachtwoord" required>
    <button type="submit">Login</button>
</form>
<a href="dashboard.php">vorige</a>
