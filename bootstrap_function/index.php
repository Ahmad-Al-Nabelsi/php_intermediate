<?php
// Voeg eerst het bootstrap.php-bestand toe
include_once __DIR__ . '/core/bootstrap.php';

// Voeg ook het css.php-bestand toe als cssLinks hierin staat
include_once __DIR__ . '/core/css.php';

/* Roep de bootstrap-functie aan en geef de mapnaam door
   en voer bootstrap uit om automatisch alle kernbestanden te laden */

//bootstrap('core');
?>

<html>
<head>
    <?php echo cssLinks('css_bestanden'); ?>
</head>
<body>
    <h1> Welkom op onze website! </h1>
<?php 
    /* Roep de bootstrap-functie aan en geef de mapnaam door
       en voer bootstrap uit om automatisch alle kernbestanden te laden */
    bootstrap('core'); ?>

    <!-- Inhoud hier -->
</body>
</html>
