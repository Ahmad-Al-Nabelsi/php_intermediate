<?php
// Functie om alle CSS-bestanden uit de vereiste map op te halen en <link>-tags te maken
function cssLinks($path) {
    // Controleer of de map bestaat
    if (!is_dir($path)) {
        return "<!-- De map bestaat niet -->";
    }

    // Zoek alle bestanden die eindigen op .css in de map
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
    <title>تحميل ملفات CSS تلقائيًا</title>
    
    <!-- Roep de functie aan om links naar CSS-bestanden af ​​te drukken -->
    <?php echo cssLinks("css");?>

</head>
<body>

    <h1> Welkom op mijn website </h1>
    <p> CSS-bestanden worden automatisch geladen /p>

</body>
</html>
