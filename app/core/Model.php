<?php
class Model {
    protected array $config;
    protected PDO $db;

    public function __construct() {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
        $this->db = Database::pdo();
    }
}
