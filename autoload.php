<?php
spl_autoload_register(function($class) {
    $namespace = 'WPMinecraftJP';

    if (strpos($class, $namespace) !== 0) {
        return;
    }

    $file = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($file)) {
        require($file);
    }
});