<?php
// Voeg eerst het bootstrap.php-bestand toe
include_once __DIR__ . '/../core/bootstrap.php';

// Roep de bootstrap-functie aan en geef de mapnaam door
// Voer bootstrap uit om automatisch alle kernbestanden te laden
bootstrap('core');
?>

<html>
<head>
    <?php echo cssLinks('css'); ?>
</head>
<body>
    <h1> Welkom op onze website! </h1>
    <!-- Inhoud hier -->
</body>
</html>
