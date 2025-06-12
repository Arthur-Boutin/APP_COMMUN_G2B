<?php

include_once 'constants.php';

spl_autoload_register(function ($class) {
    $pathParts = explode('\\', $class);

    $pathPartsCount = count($pathParts);
    for ($i = 0; $i < $pathPartsCount - 1; $i++) {
        $pathParts[$i] = strtolower($pathParts[$i]);
    }

    $filePath = implode('/', $pathParts) . '.php';

    $file = PROJECT_ROOT . '/' . $filePath;

    if (file_exists($file)) {
        require_once $file;
    }
});