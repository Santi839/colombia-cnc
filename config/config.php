<?php
// config/app.php (o donde tengas esta config)
return [
    'base_url' => (function () {
        // 1) Esquema correcto detrás de proxy (Render)
        $httpsByServer = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') == 443);
        $protoHdr      = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null; // 'https' en Render
        $scheme        = $protoHdr ? $protoHdr : ($httpsByServer ? 'https' : 'http');

        // 2) Host correcto detrás de proxy
        $hostHdr = $_SERVER['HTTP_X_FORWARDED_HOST'] ?? null;
        $host    = $hostHdr ?: ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost');

        // 3) Base path sin duplicar /public
        //    Si index.php vive en /public, dirname(SCRIPT_NAME) devuelve '/public' (o '/algo/public')
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/public/index.php'));
        $scriptDir = rtrim($scriptDir, '/');

        // Quita el sufijo '/public' si está presente para que las URLs base queden en la raíz del sitio
        $basePath = preg_replace('#/public$#', '', $scriptDir);
        // Normaliza: cadena vacía => '/'
        $basePath = $basePath === '' ? '/' : $basePath . '/';

        return $scheme . '://' . $host . $basePath; // SIEMPRE termina con '/'
    })(),

    'storage_path' => dirname(__DIR__) . '/storage',
];
