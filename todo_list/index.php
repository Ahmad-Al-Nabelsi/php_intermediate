<?php
include 'config.php';

// Alle taken worden uit de database opgehaald en gesorteerd op oplopende vervaldatum.
// ASC:Ascending betekent oplopend, DESC:Descending betekent aflopend.
$sql = "SELECT * FROM tasks ORDER BY due_date ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Manager</title>
</head>
<body>

<h1>Task Manager</h1>

<!-- Taken bekijken -->
<h2>Task List:</h2>
<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        // Bepaal of de taak te laat is
        $isOverdue = (strtotime($row['due_date']) < time()) ? 'style="color: red;"' : '';
        echo '<p ' . $isOverdue . '>';
        echo 'Task: ' . $row['task'] . ' - Due Date: ' . date('d-m-Y H:i', strtotime($row['due_date']));
        echo ' <a href="edit_task.php?id=' . $row['id'] . '">Edit</a> ';
        echo ' <a href="delete_task.php?id=' . $row['id'] . '">Delete</a></p>';
    }
} else {
    echo "<p>Geen taken gevonden</p>";
}
?>

<!-- Nieuw taakformulier toevoegen -->
<h2>Add New Task:</h2>
<form method="POST" action="">
    <label for="task">Task:</label>
    <input type="text" name="task" required>

    <label for="due_date">Due Date:</label>
    <input type="datetime-local" name="due_date" required>

    <button type="submit">Add Task</button>
</form>

<?php
// Voeg een nieuwe taak toe
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST['task'];
    $due_date = $_POST['due_date'];

    $sql = "INSERT INTO tasks (task, due_date) VALUES ('$task', '$due_date')";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

</body>
</html>

<?php
$conn->close();
?>
