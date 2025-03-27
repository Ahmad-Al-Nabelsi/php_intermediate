<?php 

include_once 'core/functions.php';

// Witte lijst met toegestane bestanden
$allowed_pages = ['home', 'bla'];

// Stel de standaardpagina in als er geen "controller" in de link staat
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';

// Controleer of de pagina op de witte lijst staat
if (!in_array($controller, $allowed_pages)) {
    $controller = 'home'; // Terug naar de standaardpagina
}

// Upload het vereiste inhoudsbestand
$content_file = "content/$controller.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic PHP Website</title>
    <link rel="stylesheet" href="css/layout.css">
</head>
<body>
    <!-- Lijst -->
    <nav>
        <a href="index.php?controller=home">Home</a> | 
        <a href="index.php?controller=bla">Bla</a>
    </nav>

    <!-- Inhoud weergeven -->
    <article>
        <?php
        if (file_exists($content_file)) {
            include_once $content_file;
            if (function_exists($controller)) {
                $controller(); // Roep de functie aan met dezelfde bestandsnaam
            }
        } else {
            echo "<h1>404 - Page Not Found</h1>";
        }
        ?>
    </article>
</body>
</html>
