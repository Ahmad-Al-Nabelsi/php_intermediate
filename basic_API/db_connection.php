<?php
function create_db_connection() {
    $conn = new mysqli('localhost', 'root', '', 'pao_beer');
    if ($conn->connect_error) {
        die("Verbinding mislukt: " . $conn->connect_error);
    }
    return $conn;
}
?>