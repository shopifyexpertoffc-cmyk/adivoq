<?php

// Source (original Laravel storage folder)
$source = __DIR__ . '/../storage/app/public';

// Destination (public folder)
$destination = __DIR__ . '/storage';

function recurse_copy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst, 0755, true);

    while(false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }

    closedir($dir);
}

// Run copy
if (!file_exists($destination)) {
    recurse_copy($source, $destination);
    echo "Storage folder copied successfully!";
} else {
    echo "Storage folder already exists!";
}
