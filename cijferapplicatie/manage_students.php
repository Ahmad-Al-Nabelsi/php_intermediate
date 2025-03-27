<?php
session_start();
require 'db.php';

// تأكيد أن المستخدم هو معلم
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: dashboard.php");
    exit();
}

// إضافة طالب جديد
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_student"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_ARGON2ID);

    // التحقق من عدم تكرار البريد الإلكتروني
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    
    if ($checkStmt->rowCount() > 0) {
        echo "<p style='color:red;'>⚠️ البريد الإلكتروني مسجل بالفعل!</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
        if ($stmt->execute([$name, $email, $password])) {
            echo "<p style='color:green;'>✅ تم إضافة الطالب بنجاح!</p>";
        } else {
            echo "<p style='color:red;'>❌ حدث خطأ أثناء الإضافة!</p>";
        }
    }
}

// تحديث بيانات الطالب
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_student"])) {
    $id = $_POST["id"];
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'student'");
    if ($stmt->execute([$name, $email, $id])) {
        echo "<p style='color:green;'>✅ تم تحديث بيانات الطالب بنجاح!</p>";
    } else {
        echo "<p style='color:red;'>❌ حدث خطأ أثناء التحديث!</p>";
    }
}

// حذف طالب بدون JavaScript
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_student"])) {
    $id = $_POST["id"];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
    if ($stmt->execute([$id])) {
        echo "<p style='color:green;'>✅ تم حذف الطالب بنجاح!</p>";
    } else {
        echo "<p style='color:red;'>❌ حدث خطأ أثناء الحذف!</p>";
    }
}

// جلب جميع الطلاب من قاعدة البيانات
$students = $pdo->query("SELECT * FROM users WHERE role = 'student'")->fetchAll();
?>

<h2>إدارة الطلاب</h2>

<!-- نموذج إضافة طالب جديد -->
<form method="post">
    <input type="text" name="name" placeholder="اسم الطالب" required>
    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
    <input type="password" name="password" placeholder="كلمة المرور" required>
    <button type="submit" name="add_student">➕ إضافة طالب</button>
</form>

<hr>

<!-- قائمة الطلاب -->
<h3>📋 قائمة الطلاب</h3>
<table border="1">
    <tr>
        <th>👤 الاسم</th>
        <th>📧 البريد الإلكتروني</th>
        <th>✏️ تعديل</th>
        <th>🗑️ حذف</th>
    </tr>
    <?php foreach ($students as $student): ?>
        <tr>
            <td><?= htmlspecialchars($student["name"]) ?></td>
            <td><?= htmlspecialchars($student["email"]) ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $student["id"] ?>">
                    <input type="text" name="name" value="<?= htmlspecialchars($student["name"]) ?>" required>
                    <input type="email" name="email" value="<?= htmlspecialchars($student["email"]) ?>" required>
                    <button type="submit" name="edit_student">💾 حفظ</button>
                </form>
            </td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $student["id"] ?>">
                    <button type="submit" name="delete_student" onclick="return confirmDelete();">🗑️ حذف</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<!-- أزرار الرجوع -->
<a href="dashboard.php">🏠 الرئيسية</a> | <a href="logout.php">🚪 تسجيل الخروج</a>
