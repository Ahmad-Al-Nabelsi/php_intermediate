<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = $_POST["role"];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "<p style='color:red;'> E-mailadres al geregistreerd! </p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $password, $role])) {
            echo "<p style='color:green;'> Registratie succesvol! U kunt nu inloggen </p>";
        } else {
            echo "<p style='color:red;'> Er is een fout opgetreden bij het registreren! </p>";
        }
    }
}
?>
<form method="post">
    <input type="text" name="name" placeholder="Naam" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="password" placeholder="Wachtwoord" required>
    <select name="role">
        <option value="student">Student</option>
        <option value="teacher">Leraar</option>
    </select>
    <button type="submit">registratie</button>
</form>
<a href="dashboard.php">Vorige</a>
