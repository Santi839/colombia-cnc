<?php
/**
 * Configuración de conexión a PostgreSQL (Render + local).
 * - En Render: usa DATABASE_URL (postgres://user:pass@host:port/db?sslmode=require)
 * - En local: configura los valores por defecto de abajo.
 */

$databaseUrl = getenv('DATABASE_URL');

if ($databaseUrl) {
    // Parsear DATABASE_URL
    // Ej: postgres://usuario:contraseña@host:5432/dbname?sslmode=require
    $parts = parse_url($databaseUrl);

    $host   = $parts['host'] ?? 'localhost';
    $port   = $parts['port'] ?? 5432;
    $user   = $parts['user'] ?? '';
    $pass   = $parts['pass'] ?? '';
    $dbname = ltrim($parts['path'] ?? '', '/');

    // sslmode=require es estándar en Render
    $sslmode = 'require';

    return [
        'driver'  => 'pgsql',
        'host'    => $host,
        'port'    => (int)$port,
        'dbname'  => $dbname,
        'user'    => $user,
        'pass'    => $pass,
        'charset' => 'UTF8',    // Se aplicará vía "SET NAMES" tras conectar
        'sslmode' => $sslmode,  // require en Render
    ];
}

// Fallback local (ajusta usuario/clave según tu entorno)
return [
    'driver'  => 'pgsql',
    'host'    => 'localhost',
    'port'    => 5432,
    'dbname'  => 'colombia_cnc',
    'user'    => 'postgres',
    'pass'    => 'tu_password_local',
    'charset' => 'UTF8',
    'sslmode' => 'prefer',     // prefer en local
];
