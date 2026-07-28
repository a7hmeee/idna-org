<?php

$dir = __DIR__ . '/storage/framework/views';
$files = glob($dir . '/*');

foreach ($files as $file) {
    if (basename($file) === '.gitignore') continue;
    if (is_file($file)) {
        unlink($file);
        echo "Deleted: " . basename($file) . "\n";
    }
}

echo "\nDone! All cached views cleared.\n";
