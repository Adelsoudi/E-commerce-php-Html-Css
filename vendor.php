<?php
// Path to the autoload.php file
$autoloadPath = __DIR__ . '/vendor/autoload.php';

// Check if the file exists
if (file_exists($autoloadPath)) {
    echo "The autoload.php file was found.";
} else {
    echo "The autoload.php file does not exist.";
}
?>
