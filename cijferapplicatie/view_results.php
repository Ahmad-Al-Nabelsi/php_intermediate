<?php
session_start();
require 'db.php';

if ($_SESSION["role"] != "student") {
    die("U hebt geen toestemming om deze pagina te bezoeken");
}

$stmt = $pdo->prepare("SELECT tests.title, results.score FROM results JOIN tests ON results.test_id = tests.id WHERE results.student_id = ?");
$stmt->execute([$_SESSION["user_id"]]);
$results = $stmt->fetchAll();
?>

<ul>
    <?php foreach ($results as $result) : ?>
        <li><?= htmlspecialchars($result["title"]) ?> - Het resultaat: <?= $result["score"] ?></li>
    <?php endforeach; ?>
</ul>

<?php echo "<br><br><a href='javascript:history.back()'> Vorige </a> | <a href='dashboard.php'> Thuis </a>"; ?>
