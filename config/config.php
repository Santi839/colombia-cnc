<?php
return [
    'base_url' => rtrim((function () {
        // Protocolo + host
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') == 443);
        $protocol = $https ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        // Ruta del script (siempre estamos en /public/index.php)
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/public/index.php');
        // Directorio del script => /Colombia_CNC/public
        $dir = rtrim(dirname($script), '/');

        // Resultado final => http://localhost/Colombia_CNC/public
        return $protocol . $host . $dir;
    })(), '/'),

    'storage_path' => dirname(__DIR__) . '/storage',
];
