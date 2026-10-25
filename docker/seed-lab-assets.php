<?php

/**
 * Cria ícones PNG mínimos e a pasta fotos/ quando o clone não traz binários.
 */

$root = dirname(__DIR__);
$imagesDir = $root . '/images';
$fotosDir = $root . '/fotos';

$iconPng = base64_decode(
    'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='
);

if (!is_dir($imagesDir)) {
    mkdir($imagesDir, 0755, true);
}

if (!is_dir($fotosDir)) {
    mkdir($fotosDir, 0755, true);
}

$iconFiles = [
    'delete.png',
    'foto.png',
    'cesta.png',
    'excluir.png',
    'editar.png',
    'mais.png',
    'menos.png',
    'home.png',
    'logo_nota.png',
];

foreach ($iconFiles as $file) {
    $path = $imagesDir . '/' . $file;
    if (!is_file($path) || filesize($path) === 0) {
        file_put_contents($path, $iconPng);
    }
}

$svgIcons = ['delete.svg', 'foto.svg', 'cesta.svg', 'no-foto.svg'];
foreach ($svgIcons as $svgFile) {
    $src = $root . '/images/' . $svgFile;
    if (!is_file($src)) {
        continue;
    }
    $dest = $imagesDir . '/' . $svgFile;
    if (!is_file($dest)) {
        copy($src, $dest);
    }
}
