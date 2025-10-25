<?php
spl_autoload_register(function($class) {
    $paths = [
        dirname(__DIR__)."/app/core/$class.php",
        dirname(__DIR__)."/app/controllers/$class.php",
        dirname(__DIR__)."/app/models/$class.php",
    ];
    foreach ($paths as $p) if (file_exists($p)) { require $p; return; }
});

$config = require dirname(__DIR__) . '/config/config.php';

$router = new Router();
$router->get('', [HomeController::class, 'index']);
$router->get('posts', [PostController::class, 'index']);
$router->get('posts/create', [PostController::class, 'create']);
$router->post('posts/create', [PostController::class, 'create']);
$router->get('posts/show', [PostController::class, 'show']);

$router->dispatch($config['base_url']);
