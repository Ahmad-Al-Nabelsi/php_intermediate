<?php
// bootstrapfunctie die alle PHP-bestanden in de map leest
function bootstrap($path) {

   // Haal het volledige pad correct op
    $fullPath = realpath(__DIR__ . '/../' . $path);

    // Controleer of de map bestaat
    if (!$fullPath || !is_dir($fullPath)) {
        die("Error: The directory '$path' does not exist.");
    }

    // Lees bestanden in de map
    $files = scandir($fullPath);
    if ($files === false) {
        die("Error: Failed to scan directory '$path'.");
    }

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            include_once $fullPath . '/' . $file;
        }
    }

    return true;
}
?>
