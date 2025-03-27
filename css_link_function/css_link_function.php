<?php
// Functie om alle CSS-bestanden uit de vereiste map op te halen en <link>-tags te maken
/* Voordelen van deze code
- Automatisch: U hoeft niet handmatig een <link> aan elk CSS-bestand toe te voegen.
- Flexibel: werkt met elk aantal CSS-bestanden in de map.
- Gemakkelijk te onderhouden: Wanneer u een nieuw CSS-bestand toevoegt, wordt dit automatisch 
geladen zonder dat u de code hoeft te wijzigen.

- Op deze manier kunnen CSS-bestanden eenvoudig worden georganiseerd en worden ze efficiënter geladen!*/

function cssLinks($path) {
    // Controleer of de map bestaat
    if (!is_dir($path)) {
        return "<!-- De map bestaat niet -->";
    }

    // [De glob()-functie in PHP wordt gebruikt om te zoeken naar bestanden of mappen die voldoen aan een specifiek 
    //patroon (Pattern Matching) binnen een specifiek pad.
    //In dit gevaal: Zoek alle bestanden die eindigen op .css in de map]
    $files = glob($path . "/*.css");

   // Variabele om de resulterende HTML-code op te slaan
    $html = "";

    // Herhaal alle bestanden en maak <link>-tags
    foreach ($files as $file) {
        $html .= '<link rel="stylesheet" href="' . $file . '">' . "\n";
    }
    
    // Geef de HTML-code terug die bestaat uit links naar CSS-bestanden
    return $html;

}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS-bestanden automatisch laden</title>
    
    <!-- Roep de functie aan om links naar CSS-bestanden af ​​te drukken -->
    <?php echo cssLinks("css_bestanden"); ?>


</head>
<body>

    <h1> Welkom op mijn website </h1>
    <p> CSS-bestanden worden automatisch geladen </p>

</body>
</html>

