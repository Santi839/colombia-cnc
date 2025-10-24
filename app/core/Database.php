<?php
class Database {
    private static ?PDO $pdo = null;

    public static function pdo(): PDO {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        // Ajusta la ruta si tu config vive en otra carpeta
        $cfg = require dirname(__DIR__, 2) . '/config/database.php';

        // Construir DSN para PostgreSQL con SSL
        // Nota: en pgsql no se usa "charset" en el DSN; lo aplicamos con "SET NAMES"
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;sslmode=%s',
            $cfg['host'],
            (int)$cfg['port'],
            $cfg['dbname'],
            $cfg['sslmode'] ?? 'require'
        );

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], $opt);

        // Aplicar charset si está definido (equivalente a SET client_encoding)
        if (!empty($cfg['charset'])) {
            // Ej.: 'UTF8'
            $charset = strtoupper($cfg['charset']);
            self::$pdo->exec("SET NAMES '{$charset}'");
        }

        return self::$pdo;
    }
}
