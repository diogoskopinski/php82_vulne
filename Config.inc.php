<?php

require_once __DIR__ . '/bootstrap.php';

// CONFIGRAÇÕES DO SITE ####################
//define('HOST', 'localhost');
//define('USER', 'root');
//define('PASS', 'bolacha');
//define('DBSA', 'catalogo');
## Caminhos diretorios
//define('DS', DIRECTORY_SEPARATOR);
//define('ROOT', dirname(__DIR__));

// AUTO LOAD DE CLASSES ####################
spl_autoload_register(function ($classe) {
    $path = __DIR__ . "/Classes/{$classe}.class.php";
    if (is_file($path)) {
        include_once $path;
    }
});
