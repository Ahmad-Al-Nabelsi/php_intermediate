<?php
session_start();
require 'db.php';

// Zorg ervoor dat de gebruiker een docent is
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: dashboard.php");
    exit();
}

// Studenten- en testgegevens ophalen
$students = $pdo->query("SELECT id, name FROM users WHERE role = 'student'")->fetchAll();
$tests = $pdo->query("SELECT id, title FROM tests")->fetchAll();

// Cijfer toevoegen of wijzigen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["student_id"], $_POST["test_id"], $_POST["score"])) {
    $student_id = $_POST["student_id"];
    $test_id = $_POST["test_id"];
    $score = $_POST["score"];

    // Controleer of het cijfer al bestaat
    $stmt = $pdo->prepare("SELECT * FROM results WHERE student_id = ? AND test_id = ?");
    $stmt->execute([$student_id, $test_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Werk het cijfer bij als het bestaat.
        $stmt = $pdo->prepare("UPDATE results SET score = ? WHERE student_id = ? AND test_id = ?");
        $stmt->execute([$score, $student_id, $test_id]);
        echo "<p style='color:green;'>✅ Het cijfer is succesvol bijgewerkt!</p>";
    } else {
        // Voeg een nieuw cijfer in als dit nog niet bestaat
        $stmt = $pdo->prepare("INSERT INTO results (student_id, test_id, score) VALUES (?, ?, ?)");
        $stmt->execute([$student_id, $test_id, $score]);
        echo "<p style='color:green;'>✅ Het cijfer is succesvol toegevoegd!</p>";
    }
}

// Haal alle huidige cijfers op.
$scores = $pdo->query("
    SELECT users.name AS student_name, tests.title AS test_title, results.score 
    FROM results
    JOIN users ON results.student_id = users.id
    JOIN tests ON results.test_id = tests.id
")->fetchAll();
?>

<h2>🎯 Beheer van studentencijfers</h2>

<!-- Cijferformulier toevoegen of wijzigen -->
<form method="post">
    <label>👨‍🎓 Selecteer de student:</label>
    <select name="student_id" required>
        <?php foreach ($students as $student): ?>
            <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>📌 Kies de test:</label>
    <select name="test_id" required>
        <?php foreach ($tests as $test): ?>
            <option value="<?= $test['id'] ?>"><?= htmlspecialchars($test['title']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>📊 Cijfer:</label>
    <input type="number" name="score" min="0" max="100" step="0.1" required>

    <button type="submit">💾 Bewaar het cijfer </button>
</form>

<hr>

<!-- Bekijk huidige cijfers -->
<h3>📋Lijst met cijfers </h3>
<table border="1">
    <tr>
        <th>👨‍🎓 student </th>
        <th>📌 test </th>
        <th>📊 cijfer </th>
    </tr>
    <?php foreach ($scores as $score): ?>
        <tr>
            <td><?= htmlspecialchars($score["student_name"]) ?></td>
            <td><?= htmlspecialchars($score["test_title"]) ?></td>
            <td><?= htmlspecialchars($score["score"]) ?> / 100</td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<!-- Navigatieknoppen -->
<a href="dashboard.php">🏠 Terug naar Home </a> | <a href="logout.php">🚪 uitloggen </a>
