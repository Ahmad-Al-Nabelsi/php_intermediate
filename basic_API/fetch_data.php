<?php
########## 1- Functiedefinitie ##########
/* Definieer een functie met de naam fetch_data die vier parameters accepteert:
$conn: Databaseverbinding.
$find: De parameter die aangeeft of een specifieke record of alle records moeten worden opgehaald.
$page: Het paginanummer dat wordt gebruikt bij het browsen (standaard is 1).
$sort: Een array met sorteerinformatie (standaard is null) */
function fetch_data($conn, $find, $page = 1, $sort = null) {

    ########## 2- Geef het aantal records per pagina op en bereken de offset ##########
    $limit = 10; // Aantal records per pagina
    /*Bereken de offset op basis van het paginanummer. De offset geeft aan waar moet worden begonnen 
    met het ophalen van records uit de database. */
    $offset = ($page - 1) * $limit;
    
    ########## 3- Bouw een SQL-query op basis van de gevonden waarde ##########
    //Als de waarde van $find een getal is, wordt er een specifieke record opgehaald.
    if (is_numeric($find)) {
        $sql = 'SELECT * FROM `beers` WHERE `id` ="' . $find . '"';
        // Als de waarde van $find 'all' is, worden alle records opgehaald.
    } elseif ($find == 'all') {
        $sql = "SELECT * FROM `beers`";
        /*Als er een sorteerarray bestaat, wordt het een ORDER BY-component toegevoegd aan de query 
        om het sorteerveld en de sorteerrichting op te geven */
        if ($sort) {
            $sql .= " ORDER BY " . $sort['field'] . " " . $sort['direction'];
        }
        // Voeg de instructies LIMIT en OFFSET toe aan de query om het aantal op te halen records en de offset op te geven.
        $sql .= " LIMIT $limit OFFSET $offset";
    } else {
        // Als de waarde van $find geen getal is en ook niet 'all', wordt er een foutmelding geretourneerd.
        // Als de waarde van $find ongeldig is, wordt er een array geretourneerd met het adres van MY API-server.
        return ['title' => 'My API-server'];
    }

    ########## 4- Voer de query uit en haal de resultaten op ##########
    $result = $conn->query($sql);
    $collection = []; // Maak een lege array aan om de resultaten op te slaan
    if ($result->num_rows > 0) {  // Als er resultaten zijn
        while ($row = $result->fetch_assoc()) { // Loop door de resultaten
            $collection[] = (object)$row; // Voeg elke rij toe aan de collectie als een object
        }
    }
    return $collection;
}
?>