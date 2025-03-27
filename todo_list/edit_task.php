<?php
include 'config.php';

// Controleer of er een taak is om te wijzigen
$taskId = isset($_GET['id']) ? $_GET['id'] : null;

if ($taskId) {
    // Taakgegevens ophalen uit database
    $sql = "SELECT * FROM tasks WHERE id = $taskId";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $task = $row['task'];
        $due_date = $row['due_date'];
    } else {
        echo "Task not found.";
        exit();
    }
} else {
    echo "No task ID provided.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>

<h1>Edit Task</h1>

<form method="POST" action="">
    <label for="task">Task:</label>
    <input type="text" name="task" value="<?php echo $task; ?>" required>

    <label for="due_date">Due Date:</label>
    <input type="datetime-local" name="due_date" value="<?php echo date('Y-m-d\TH:i', strtotime($due_date)); ?>" required>

    <button type="submit">Update Task</button>
</form>

<?php
// Werk de taak in de database bij
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST['task'];
    $due_date = $_POST['due_date'];

    $sql = "UPDATE tasks SET task = '$task', due_date = '$due_date' WHERE id = $taskId";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating task: " . $conn->error;
    }
}
?>

</body>
</html>

<?php
$conn->close();
?>
