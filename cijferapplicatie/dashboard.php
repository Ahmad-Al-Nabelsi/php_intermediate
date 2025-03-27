<?php
session_start();
require 'db.php';

echo "<h2>مرحبًا بك في تطبيق إدارة الاختبارات</h2>";

if (!isset($_SESSION["user_id"])) {
    echo "<p>يرجى تسجيل الدخول أو التسجيل لاستخدام التطبيق.</p>";
    echo "<a href='login.php'>تسجيل الدخول</a> | <a href='register.php'>التسجيل</a>";
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
        echo "<p style='color:red;'>أنت غير مسجل بعد!</p>";
        session_destroy();
    }
}

echo "<br><br><a href='logout.php'>تسجيل الخروج</a>";
?>
