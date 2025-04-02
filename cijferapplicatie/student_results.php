<?php
// session_start();
// require 'db.php';

// // Zorg ervoor dat de gebruiker is ingelogd als student
// if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
//     header("Location: dashboard.php");
//     exit();
// }

// $student_id = $_SESSION["user_id"];

// // Haal studentenresultaten uit de database
// $stmt = $pdo->prepare("
//     SELECT tests.title, results.score 
//     FROM results
//     JOIN tests ON results.test_id = tests.id
//     WHERE results.student_id = ?
// ");
// $stmt->execute([$student_id]);
// $results = $stmt->fetchAll();

?>

<!-- <h2>📊 Mijn resultaten </h2> -->

<!-- <?php if (count($results) > 0): ?>
    <table border="1">
        <tr>
            <th>📌 Testtitel </th>
            <th>📈 De cijfer </th>
        </tr>
        <?php foreach ($results as $result): ?>
            <tr>
                <td><?= htmlspecialchars($result["title"]) ?></td>
                <td><?= htmlspecialchars($result["score"]) ?> / 100</td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p style="color: red;">⚠️ Er zijn nog geen resultaten beschikbaar </p>
<?php endif; ?>

<br>
<a href="dashboard.php">🏠 Tuis </a> | <a href="logout.php">🚪Uitloggen </a> -->
