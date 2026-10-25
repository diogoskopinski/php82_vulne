<?php

function imagem_placeholder_svg(): string
{
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="150" viewBox="0 0 200 150">'
        . '<rect width="200" height="150" fill="#e0e0e0"/>'
        . '<text x="100" y="80" text-anchor="middle" font-family="Arial,sans-serif" font-size="14" fill="#555">Sem foto</text>'
        . '</svg>';

    return 'data:image/svg+xml,' . rawurlencode($svg);
}

function foto_produto_url(?string $filename): string
{
    $filename = trim((string) $filename);
    if ($filename === '') {
        return imagem_placeholder_svg();
    }

    $basename = basename($filename);
    $fullPath = dirname(__DIR__) . '/fotos/' . $basename;

    if (!is_readable($fullPath) || filesize($fullPath) === 0) {
        return imagem_placeholder_svg();
    }

    return 'fotos/' . rawurlencode($basename);
}
