<?php

// Als de waarde van "controller" in de query leeg is, stellen we deze in als "home"
if (empty($_GET['controller'])) {
    $_GET['controller'] = 'home';
}

// Haal de controllerwaarde uit de URL
$controller = $_GET['controller'];

// Witte lijst met alleen toegestane namen
$validControllers = ['home', 'bla', 'gallery'];  

// Controleer of de controllerwaarde in de whitelist staat
if (in_array($controller, $validControllers)) {

    // Maak het bestandspad op basis van de controllerwaarde
    $contentFile = 'content/' . $controller . '.php';
    
    // Controleer of het bestand bestaat voordat u het opneemt
    if (file_exists($contentFile)) {
        include($contentFile);
        
        // Roep de functie aan voor het meegeleverde bestand
        $render = $controller();
    } else {

        // Als het bestand niet bestaat, wordt er een foutmelding weergegeven.
        echo "The requested content file does not exist.";
    }
} else {

    // Als de controllerwaarde niet in de witte lijst staat, wordt er een foutmelding weergegeven.
    echo "The requested page does not exist.";
}

// Als de variabele $render succesvol is gedefinieerd, geven we deze weer.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Site</title>
    <link rel="stylesheet" href="css/layout.css">
</head>
<body>
    <!-- Links naar diverse inhoud -->
    <nav>
        <a href="index.php?controller=home">Home</a> |
        <a href="index.php?controller=bla">Bla</a> |
        <a href="index.php?controller=gallery">Gallery</a>
    </nav>

    <article>
        <?php
       // Zorg ervoor dat de variabele $render succesvol is ingesteld
        if (isset($render)) {
            echo $render; // Inhoud weergeven
        }
        ?>
    </article>
</body>
</html>
