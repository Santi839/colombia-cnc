<?php
class Database {
    private static ?PDO $pdo = null;

    public static function pdo(): PDO {
        if (self::$pdo instanceof PDO) return self::$pdo;

        $cfg = require dirname(__DIR__, 2) . '/config/database.php';
        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $cfg['driver'], $cfg['host'], $cfg['port'], $cfg['dbname'], $cfg['charset']
        );

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], $opt);
        return self::$pdo;
    }
}
