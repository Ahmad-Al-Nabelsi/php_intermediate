<?php
/* Deze functie bevat een invoerreiniger die ongewenste HTML- of PHP-tags 
verwijdert om de site te beschermen tegen XSS-aanvallen. */
function sanitize_input($data) {
    return htmlspecialchars(strip_tags($data));
}
?>
