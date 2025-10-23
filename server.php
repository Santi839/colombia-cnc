<?php
// server.php (en la raíz del repo)
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$public = __DIR__ . '/public';
$docroot = is_dir($public) ? $public : __DIR__;

// Si el archivo existe (CSS/JS/imagenes), entrégalo tal cual
if ($uri !== '/' && file_exists($docroot . $uri)) {
  return false;
}

// Para todo lo demás, carga el front controller
require $docroot . '/index.php';
