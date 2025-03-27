<?php
session_start();
require 'db.php';

// تأكد من أن المستخدم معلم
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: dashboard.php");
    exit();
}

// جلب الطلاب والاختبارات
$students = $pdo->query("SELECT id, name FROM users WHERE role = 'student'")->fetchAll();
$tests = $pdo->query("SELECT id, title FROM tests")->fetchAll();

// إضافة أو تعديل الدرجة
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["student_id"], $_POST["test_id"], $_POST["score"])) {
    $student_id = $_POST["student_id"];
    $test_id = $_POST["test_id"];
    $score = $_POST["score"];

    // التحقق مما إذا كانت الدرجة موجودة بالفعل
    $stmt = $pdo->prepare("SELECT * FROM results WHERE student_id = ? AND test_id = ?");
    $stmt->execute([$student_id, $test_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        // تحديث الدرجة إذا كانت موجودة
        $stmt = $pdo->prepare("UPDATE results SET score = ? WHERE student_id = ? AND test_id = ?");
        $stmt->execute([$score, $student_id, $test_id]);
        echo "<p style='color:green;'>✅ تم تحديث الدرجة بنجاح!</p>";
    } else {
        // إدراج درجة جديدة إذا لم تكن موجودة
        $stmt = $pdo->prepare("INSERT INTO results (student_id, test_id, score) VALUES (?, ?, ?)");
        $stmt->execute([$student_id, $test_id, $score]);
        echo "<p style='color:green;'>✅ تم إضافة الدرجة بنجاح!</p>";
    }
}

// جلب جميع الدرجات الحالية
$scores = $pdo->query("
    SELECT users.name AS student_name, tests.title AS test_title, results.score 
    FROM results
    JOIN users ON results.student_id = users.id
    JOIN tests ON results.test_id = tests.id
")->fetchAll();
?>

<h2>🎯 إدارة درجات الطلاب</h2>

<!-- نموذج إضافة أو تعديل درجة -->
<form method="post">
    <label>👨‍🎓 اختر الطالب:</label>
    <select name="student_id" required>
        <?php foreach ($students as $student): ?>
            <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>📌 اختر الاختبار:</label>
    <select name="test_id" required>
        <?php foreach ($tests as $test): ?>
            <option value="<?= $test['id'] ?>"><?= htmlspecialchars($test['title']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>📊 الدرجة:</label>
    <input type="number" name="score" min="0" max="100" step="0.1" required>

    <button type="submit">💾 حفظ الدرجة</button>
</form>

<hr>

<!-- عرض الدرجات الحالية -->
<h3>📋 قائمة الدرجات</h3>
<table border="1">
    <tr>
        <th>👨‍🎓 الطالب</th>
        <th>📌 الاختبار</th>
        <th>📊 الدرجة</th>
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
<!-- أزرار التنقل -->
<a href="dashboard.php">🏠 العودة إلى الرئيسية</a> | <a href="logout.php">🚪 تسجيل الخروج</a>
