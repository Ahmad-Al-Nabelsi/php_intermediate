<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: dashboard.php");
    exit();
}

echo "<h2>مرحبًا طالب!</h2>";
echo "<a href='view_results.php'>عرض نتائجي</a>";
echo "<br><br><a href='dashboard.php'>الرئيسية</a> | <a href='logout.php'>تسجيل الخروج</a>";
?>
