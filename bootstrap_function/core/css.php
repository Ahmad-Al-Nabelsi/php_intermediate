<?php
function cssLinks($path) {
    $fullPath = __DIR__ . '/../' . $path;
    if (!is_dir($fullPath)) {
        return ''; // Retourneer niets als de map niet bestaat
    }
    $files = scandir($fullPath);
    $cssLinks = '';
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'css') {
            $cssLinks .= '<link rel="stylesheet" href="' . $path . '/' . $file . '">' . PHP_EOL;
        }
    }
    return $cssLinks;
}
?>
