<?php
// Externe bestanden opnemen
include 'db_connection.php'; // Voeg een bestand toe dat een functie bevat om een ​​databaseverbinding te maken.
include 'fetch_data.php'; // Voeg een bestand toe met een functie om gegevens uit de database op te halen.

// Een databaseverbinding maken
$conn = create_db_connection(); // Roep de functie aan vanuit het bestand db_connection.php om een ​​databaseverbinding te maken

//Parameters uit URL lezen
$find = $_GET['find'] ?? null; // Lees de find-parameter uit de URL. Als deze niet bestaat, wordt het op null gezet.
$page = $_GET['page'] ?? 1; // Lees de paginaparameter uit de URL. Als deze niet bestaat, wordt het op 1 gezet.
$sort_field = $_GET['sort_field'] ?? null; //Lees de sort_field-parameter uit de URL. Als deze niet bestaat, wordt het op null gezet.
//Lees de sort_direction-parameter uit de URL en als deze niet bestaat, wordt het op ASC gezet.
$sort_direction = $_GET['sort_direction'] ?? 'ASC'; 

// Als de parameter sort_field bestaat is, wordt het een array gemakt met het sorteerveld en de sorteerrichting, wordt het op null gezet.
$sort = $sort_field ? ['field' => $sort_field, 'direction' => $sort_direction] : null; 

/* Controleer of de parameter find bestaat, 
als de parameter find niet bestaat is, wordt de uitvoering beëindigd en wordt er een foutmelding in JSON-formaat geretourneerd. */
if ($find === null) {
    die(json_encode(['error' => 'Parameter "find" is missing']));
}

// Gegevens uit database ophalen
// Roep de functie uit het bestand fetch_data.php aan om gegevens uit de database op te halen op basis van de doorgegeven parameters.
$collection = fetch_data($conn, $find, $page, $sort); 

// Gegevens voorbereiden voor uitvoer in JSON-formaat
// Metagegevens (aantal records, huidige pagina, sorteerinformatie) samenvoegen met actuele gegevens
$out = array_merge(['meta' => ['count' => count($collection), 'page' => $page, 'sort' => $sort]], ['data' => $collection]);
header('Content-Type: application/json; charset=utf-8'); // Stel de header in op JSON-indeling

// Geef de gegevens weer in JSON-indeling met een nette opmaak (JSON_PRETTY_PRINT maakt de uitvoer leesbaarder voor mensen).
echo json_encode($out, JSON_PRETTY_PRINT); 
die; //Stop de uitvoering van het script. Dit is een goede praktijk om ervoor te zorgen dat er geen extra uitvoer na de JSON-gegevens komt.
// Sluit de databaseverbinding
?>