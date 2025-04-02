<?php
/*Een eenvoudig programma dat verbinding maakt met een MySQL-database om gegevens uit de tabel 'beers' 
op te halen, met ondersteuning voor filteren, sorteren en paginering. De gegevens worden in JSON-formaat 
verzonden, zodat ze kunnen worden gebruikt in applicaties of websites die JSON ondersteunen.*/

// Maak een databaseverbinding
$conn = new mysqli('localhost', 'root', '', 'pao_beer');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Functie om gegevens op te halen op basis van URL-parameters
/*Met deze functie kunt u gegevens uit een database ophalen op basis van bepaalde criteria. Het ontvangt de volgende parameters:

$conn: Maak verbinding met de database.

$find: Een parameter om het type query te specificeren (zoals all om alle gegevens weer te geven of id om een ​​specifiek record weer te geven).

$page: Paginanummer (standaard is 1).

$sort_field: Het veld waarop de resultaten worden gesorteerd (standaard is id).

$sort_order: Sorteer de resultaten in oplopende (ASC) of aflopende (DESC) volgorde (standaard is ASC). */
function get_data($conn, $find, $page = 1, $sort_field = 'id', $sort_order = 'ASC') {

    // Specificeer de query op basis van de parameter 'find'
    if ($find === 'all') {
        // Query voor het ophalen van alle records
        $sql = "SELECT * FROM `beers` ORDER BY `$sort_field` $sort_order";
    } elseif (is_numeric($find)) {
        // Query voor het ophalen van één record op basis van 'id'
        $sql = "SELECT * FROM `beers` WHERE `id` = '$find'";
    } else {
        // Ongeldige waarde voor 'find'
        return ['title' => 'Invalid request', 'message' => 'Ongeldige zoekparameter: ' . $find];
    }

    // Paginering toevoegen
    /*Filtering toevoegen op basis van pagina
    Paginering wordt aan de query toegevoegd.

$limit: Geeft het aantal records aan dat per pagina moet worden opgehaald (hier 10 records).

$offset: Geeft het begin van de op te halen records aan op basis van het huidige paginanummer. 
Als het bijvoorbeeld de tweede pagina is, worden de eerste 10 records overgeslagen. */

    $limit = 10;  // Aantal records per pagina
    /*Bereken de offset op basis van het paginanummer
    $page: Huidig ​​paginanummer. Bijvoorbeeld, als de pagina 1, 2, 3, etc. is.
    $limit: Aantal records dat per pagina wordt weergegeven. Als limiet bijvoorbeeld 10 is, 
    betekent dit dat er op elke pagina 10 records worden weergegeven.*/
    $offset = ($page - 1) * $limit;
    $sql .= " LIMIT $limit OFFSET $offset";

    /*Voer de query uit en haal de resultaten op*/
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
