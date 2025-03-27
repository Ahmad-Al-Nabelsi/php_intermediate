<?php
function showPictures($path) {

    // Controleer of de map bestaat.
    if (!is_dir($path)) {
        return "<p> De map bestaat niet </p>";
    }

    // array_diff() is de afkorting van "array difference" en wordt gebruikt om bepaalde elementen uit een array te verwijderen, 
    //in dit geval de "." en ".." mappen.
    // scandir(): retourneert ook de elementen "." en ".." (die de huidige map en de bovenliggende map vertegenwoordigen)
    // scandir($path) retourneert alle bestanden en mappen binnen het opgegeven pad.
    $files = array_diff(scandir($path), array('..', '.'));
    $html = "";


    // Loop door alle bestanden en controleer of de bestandsnaam eindigt op .jpg, .jpeg, .png of .gif.
    //Als dat het geval is, voeg dan een img-tag toe aan de $html variabele.                             
    foreach ($files as $file) {
        $filePath = $path . "/" . $file;

       // preg_match() is een ingebouwde PHP-functie en het is de afkorting van "preg" (Perl Regular Expression) 
       //die wordt gebruikt om te zoeken naar een patroon in een tekenreeks.
       //In dit geval wordt het gebruikt om te controleren of de bestandsnaam eindigt op .jpg, .jpeg, .png of .gif.
       //$ na bestandsextensie: Zorg ervoor dat de extensie aan het einde van de tekst staat en niet in het midden.
       //i: maakt de zoekopdracht hoofdletterongevoelig.
        if (preg_match("/\.(jpg|jpeg|png|gif)$/i", $file)) {
            $html .= '<img class="image-class" src="' . $filePath . '" alt="image ' . htmlspecialchars($file) . '">';
        }
    }

    return $html;
}
//Een andere manier, maar minder flexibel, zonder $html variabele te gebruiken

// function showPictures($path) {
//     if (!is_dir($path)) {
//         echo "<p> De map bestaat niet </p>";
//         return;
//     }

//     $files = array_diff(scandir($path), array('..', '.'));

//     foreach ($files as $file) {
//         $filePath = $path . "/" . $file;
//         if (preg_match("/\.(jpg|jpeg|png|gif)$/i", $file)) {
//             echo '<img class="image-class" src="' . $filePath . '" alt="image ' . htmlspecialchars($file) . '">';
//         }
//     }
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Fotogalerij </title>
    <style>
        .image-class {
            width: 300px;
            height: 200px;
            margin: 10px;
            border: 2px solid #ddd;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <h2> Fotogalerij </h2>
    <div>
        <?php echo showPictures("images"); ?>
    </div>

</body>
</html>
