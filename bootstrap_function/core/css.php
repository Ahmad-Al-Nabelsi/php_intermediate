<?php
function cssLinks($path) {

    /* __DIR__ is een ingebouwde PHP-constante die het volledige pad bevat naar 
    de huidige directory met het bestand dat de code uitvoert.
    Hier voegen we '/../' toe om naar de bovenliggende map te gaan. */
    $fullPath = __DIR__ . '/../' . $path;
    if (!is_dir($fullPath)) {
        return ''; // Retourneer niets als de map niet bestaat
    }
    $files = scandir($fullPath);
   
    /* Als de map niet bestaat, retourneert de functie een lege tekenreeks ('') en gebeurt er niets. 
    Dit betekent dat als de map niet bestaat, er geen CSS-links worden gegenereerd en er niets op de pagina verschijnt. */
    $cssLinks = '';
    foreach ($files as $file) {

        /* De pathinfo()-functie wordt gebruikt om de bestandsextensie te extraheren. */
        if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {

        /* PHP_EOL is een ingebouwde constante in PHP die het einde van een regel aangeeft in scripts die in PHP worden verwerkt.
           -Het is een afkorting voor "End of Line".
           -Het wordt gebruikt om een ​​nieuwe regel toe te voegen na elke <link>-tag die wordt gemaakt.
           -Het gebruik van PHP_EOL is flexibeler en duurzamer omdat het ervoor zorgt dat het regeleinde 
           consistent is met het besturingssysteem waarop u draait.*/
            $cssLinks .= '<link rel="stylesheet" href="' . $path . '/' . $file . '">' . PHP_EOL;
        }
    }
    return $cssLinks;
}
?>
