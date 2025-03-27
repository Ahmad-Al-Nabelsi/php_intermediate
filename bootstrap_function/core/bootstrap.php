<?php
function bootstrap($path) {
    // Maak een relatief pad naar de gewenste map.
    $path = __DIR__ . '/../' . $path;

    // Controleer of de map bestaat
    if (!is_dir($path)) {
        die("Error: The directory '$path' does not exist.");
    }

    // Bestanden in de map lezen
    /* scandir($path) is een PHP-functie die de inhoud van de directory die is opgegeven in $path leest en een array 
    retourneert met de namen van bestanden en directory's in die directory */
    $files = scandir($path);

    /* Als de functie scandir() de inhoud van een map niet kan lezen (bijvoorbeeld omdat er een probleem is met de map 
    of de machtigingen), wordt het script gestopt met die() en wordt er een bericht weergegeven waarin staat dat de map 
    niet kon worden gescand. */
    if ($files === false) {
        die("Error: Failed to scan directory '$path'.");
    }

    // Voeg elk PHP-bestand in de map toe
    foreach ($files as $file) {

        /* pathinfo($file, PATHINFO_EXTENSION) is een PHP-functie die de bestandsextensie retourneert.
           Hier controleren we of het bestand de extensie .php heeft.
           Als het bestand een PHP-bestand is (met de extensie .php) gaan we naar de volgende regel. */
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            
            /* include_once is een PHP-opdracht waarmee u een bestand slechts één keer in uw code opneemt 
            (als het bestand al eerder in hetzelfde script is opgenomen, wordt het niet nogmaals opgenomen). */
            include_once $path . '/' . $file;
        }
    }

    /* Nadat alle bestanden succesvol zijn geüpload, wordt de waarde true geretourneerd om aan te geven dat de 
    bewerking succesvol was. */
    return true;
}

?>