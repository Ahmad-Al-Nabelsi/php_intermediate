<?php
session_start();
require 'db.php';

// Bevestig dat de gebruiker een docent is
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "teacher") {
    header("Location: dashboard.php");
    exit();
}

// Voeg een nieuwe student toe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_student"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_ARGON2ID);

    // Controleer of het e-mailadres niet dubbel is
    $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    
    if ($checkStmt->rowCount() > 0) {
        echo "<p style='color:red;'>⚠️ E-mailadres al geregistreerd!</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
        if ($stmt->execute([$name, $email, $password])) {
            echo "<p style='color:green;'>✅ Student succesvol toegevoegd!</p>";
        } else {
            echo "<p style='color:red;'>❌ Er is een fout opgetreden bij het toevoegen!</p>";
        }
    }
}

// Studentgegevens bijwerken
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_student"])) {
    $id = $_POST["id"];
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'student'");
    if ($stmt->execute([$name, $email, $id])) {
        echo "<p style='color:green;'>✅ Studentgegevens succesvol bijgewerkt!</p>";
    } else {
        echo "<p style='color:red;'>❌ Er is een fout opgetreden tijdens het updaten!</p>";
    }
}

// Student verwijderen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_student"])) {
    $id = $_POST["id"];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'student'");
    if ($stmt->execute([$id])) {
        echo "<p style='color:green;'>✅ De student is succesvol verwijderd!</p>";
    } else {
        echo "<p style='color:red;'>❌ Er is een fout opgetreden bij het verwijderen!</p>";
    }
}

// Haal alle studenten uit de database
$students = $pdo->query("SELECT * FROM users WHERE role = 'student'")->fetchAll();
?>

<h2>إدارة الطلاب</h2>

<!-- Formulier voor toevoeging van nieuwe studenten -->
<form method="post">
    <input type="text" name="name" placeholder="اسم الطالب" required>
    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
    <input type="password" name="password" placeholder="كلمة المرور" required>
    <button type="submit" name="add_student">➕ إضافة طالب</button>
</form>

<hr>

<!-- Studentenlijst -->
<h3>📋  Studentenlijst </h3>
<table border="1">
    <tr>
        <th>👤 Naam </th>
        <th>📧 Email </th>
        <th>✏️ Update </th>
        <th>🗑️ Verwijderen </th>
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
<a href="dashboard.php">🏠 Home </a> | <a href="logout.php">🚪 Uitloggen </a>
