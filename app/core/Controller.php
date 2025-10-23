<?php
class Controller {
    protected array $config;

    public function __construct() {
        $this->config = require dirname(__DIR__, 2) . '/config/config.php';
    }

    protected function view(string $view, array $params = []) {
        extract($params);
        $baseUrl = $this->config['base_url'];
        $viewFile = dirname(__DIR__) . "/views/{$view}.php";
        $layout = dirname(__DIR__) . "/views/layouts/main.php";
        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "View not found: {$viewFile}";
        }
        $content = ob_get_clean();
        include $layout;
    }

    protected function redirect(string $path) {
        $url = rtrim($this->config['base_url'], '/') . $path;
        header("Location: {$url}");
        exit;
    }

    protected function json($data, int $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
