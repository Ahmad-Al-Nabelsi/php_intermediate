<?php
/* Deze functie bevat een invoerreiniger die ongewenste HTML- of PHP-tags 
verwijdert om de site te beschermen tegen XSS-aanvallen.

strip_tags($data): Wordt gebruikt om alle HTML- en XML-tags uit de tekst te verwijderen. */
function sanitize_input($data) {
    return htmlspecialchars(strip_tags($data));
}
?>
