<?php
session_start();
require 'db.php';

// Bevestig dat de gebruiker een docent is
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: dashboard.php");
    exit();
}

// Voeg een nieuwe test toe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_test"])) {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);

    $stmt = $pdo->prepare("INSERT INTO tests (title, description) VALUES (?, ?)");
    if ($stmt->execute([$title, $description])) {
        echo "<p style='color:green;'>✅ Test succesvol toegevoegd!</p>";
    } else {
        echo "<p style='color:red;'>❌ Er is een fout opgetreden bij het toevoegen!</p>";
    }
}

// Test verwijderen
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $stmt = $pdo->prepare("DELETE FROM tests WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "<p style='color:green;'>✅ De test is succesvol verwijderd!</p>";
    } else {
        echo "<p style='color:red;'>❌Er is een fout opgetreden bij het verwijderen!</p>";
    }
}

// Haal alle tests op
$tests = $pdo->query("SELECT * FROM tests")->fetchAll();
?>

<h2>📚 Testbeheer </h2>

<!-- Nieuw testformulier toevoegen -->
<form method="post">
    <input type="text" name="title" placeholder="Testtitel" required>
    <textarea name="description" placeholder="Testbeschrijving" required></textarea>
    <button type="submit" name="add_test">➕ Test toevoegen </button>
</form>

<hr>

<!-- Testlijst -->
<h3>📝 Testlijst </h3>
<table border="1">
    <tr>
        <th>📌 Testtitel </th>
        <th>📄 Beschrijving </th>
        <th>🗑️ verwijderen </th>
    </tr>
    <?php foreach ($tests as $test): ?>
        <tr>
            <td><strong><?= htmlspecialchars($test["title"]) ?></strong></td>
            <td><?= nl2br(htmlspecialchars($test["description"])) ?></td>
            <td>
                <a href="?delete=<?= $test["id"] ?>" onclick="return confirm('Weet u zeker dat u deze test wilt verwijderen?');">❌ verwijderen </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<!-- Terug knoppen -->
<a href="dashboard.php">🏠 Home </a> | <a href="logout.php">🚪Uitloggen </a>
