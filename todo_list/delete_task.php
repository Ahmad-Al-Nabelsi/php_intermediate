<?php
include 'config.php';

// Controleer of de ID-parameter bestaat voor verwijdering
$taskId = isset($_GET['id']) ? $_GET['id'] : null;

if ($taskId) {

    // Verwijder de taak uit de database
    $sql = "DELETE FROM tasks WHERE id = $taskId";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting task: " . $conn->error;
    }
} else {
    echo "No task ID provided.";
}
?>

<?php
$conn->close();
?>
