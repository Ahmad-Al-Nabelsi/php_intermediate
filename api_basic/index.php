<?php
// Maak een databaseverbinding
$conn = new mysqli('localhost', 'root', '', 'pao_beer');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Functie om gegevens op te halen op basis van URL-parameters
function get_data($conn, $find, $page = 1, $sort_field = 'id', $sort_order = 'ASC') {

    // Specificeer de query op basis van de parameter 'find'
    if ($find === 'all') {
        $sql = "SELECT * FROM `beers` ORDER BY `$sort_field` $sort_order";
    } elseif (is_numeric($find)) {
        $sql = "SELECT * FROM `beers` WHERE `id` = '$find'";
    } else {
        return ['title' => 'Invalid request'];
    }

    // Paginering toevoegen
    $limit = 10;  // Aantal records per pagina
    $offset = ($page - 1) * $limit;
    $sql .= " LIMIT $limit OFFSET $offset";

    $result = $conn->query($sql);
    $collection = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $collection[] = (object)$row;
        }
    }

    return $collection;
}

//URL-parameters lezen
$find = isset($_GET['find']) ? $_GET['find'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Gegevens ophalen
$collection = get_data($conn, $find, $page, $sort, $sort_order);

// Maak de resultaatarray
$out = array_merge(
    ['meta' => ['count' => count($collection), 'page' => $page, 'sort' => "$sort $sort_order"]],
    ['data' => $collection]
);

// Gegevensuitvoer in JSON-formaat
header('Content-Type: application/json; charset=utf-8');
echo json_encode($out, JSON_PRETTY_PRINT);
die;
?>
