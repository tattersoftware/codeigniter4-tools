<?php

declare(strict_types=1);

require __DIR__ . '/tests/_support/bootstrap.php';

$helperDirs = [
    'vendor/codeigniter4/framework/system/Helpers',
];

foreach ($helperDirs as $dir) {
    $dir = __DIR__ . '/' . $dir;
    chdir($dir);

    foreach (glob('*_helper.php') as $filename) {
        $filePath = realpath($dir . '/' . $filename);

        require_once $filePath;
    }
}
