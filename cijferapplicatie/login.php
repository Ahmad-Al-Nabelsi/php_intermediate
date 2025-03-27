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
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            header("Location: dashboard.php");
        } else {
            echo "<p style='color:red;'>كلمة المرور غير صحيحة!</p>";
        }
    } else {
        echo "<p style='color:red;'>البريد الإلكتروني غير مسجل!</p>";
    }
}
?>
<form method="post">
    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
    <input type="password" name="password" placeholder="كلمة المرور" required>
    <button type="submit">تسجيل الدخول</button>
</form>
<a href="dashboard.php">رجوع</a>
