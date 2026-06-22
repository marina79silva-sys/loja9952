<?php
namespace App;

/**
 * Gera URL dentro do subfolde /loja9952/
 */
function url(string $path = ''): string {
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $base = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
    if (str_ends_with($base, '/public')) {
        $base = substr($base, 0, -7);
    }
    if ($base === '/' || $base === '.') {
        $base = '';
    }
    return $base . '/' . ltrim($path, '/');
}
