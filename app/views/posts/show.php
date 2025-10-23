<?php
// Lee el JSON desde la BD (posts.content_json). Si está vacío, usa arreglo.
$data = (string)($post['content_json'] ?? '');
$decoded = [];
if ($data !== '') {
  $tmp = json_decode($data, true);
  $decoded = (json_last_error() === JSON_ERROR_NONE) ? $tmp : ['text' => $data];
} elseif (!empty($post['content'])) {
  // compat: si existe 'content'
  $tmp = json_decode((string)$post['content'], true);
  $decoded = (json_last_error() === JSON_ERROR_NONE) ? $tmp : ['text' => (string)$post['content']];
}

$type = $post['type'] ?? 'article';

// Helper para incluir componente de forma segura (Windows/Linux)
function include_component(string $type, array $decoded, string $baseDir) {
  $map = [
    'infographic'  => 'infographic.php',
    'poster'       => 'poster.php',
    'canvasVideo'  => 'canvasVideo.php',
    'interview'    => 'interview.php',
  ];
  if (!isset($map[$type])) return false;

  $file = $baseDir . DIRECTORY_SEPARATOR . $map[$type];
  if (is_file($file)) {
    // Hace visible $decoded dentro del componente
    /** @var array $decoded */
    include $file;
    return true;
  }
  // Fallback amigable si el archivo no existe
  echo '<div class="alert alert-warning">No se encontró el componente <code>' . htmlspecialchars($map[$type]) .
       '</code>. Ruta buscada: <code>' . htmlspecialchars($file) . '</code></div>';
  return false;
}

$componentsDir = __DIR__ . DIRECTORY_SEPARATOR . '_components';
?>
<article class="post">
  <header class="mb-4">
    <span class="badge rounded-pill bg-dark-subtle text-dark mb-2">
      <?= htmlspecialchars($post['category'] ?? 'General') ?>
    </span>
    <h1 class="display-6 fw-semibold"><?= htmlspecialchars($post['title'] ?? 'Sin título') ?></h1>
    <p class="text-secondary"><?= htmlspecialchars($post['summary'] ?? '') ?></p>
    <div class="small text-muted">
      <?= date('d M Y, H:i', strtotime($post['created_at'] ?? 'now')) ?>
      · Tipo: <?= htmlspecialchars($type) ?>
    </div>
  </header>

  <section class="mb-4">
    <?php if ($type === 'article'): ?>
      <div class="prose"><?= nl2br(htmlspecialchars($decoded['text'] ?? 'Contenido no estructurado')) ?></div>

    <?php elseif (in_array($type, ['infographic','poster','canvasVideo','interview'], true)): ?>
      <?php
        // intenta incluir el componente; si falla, renderiza un fallback mínimo
        if (!include_component($type, $decoded, $componentsDir)) {
          if ($type === 'infographic') {
            echo '<pre class="prose">'.htmlspecialchars(json_encode($decoded, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)).'</pre>';
          } elseif ($type === 'poster') {
            echo '<blockquote class="prose">“'.htmlspecialchars($decoded['quote'] ?? 'Póster'). '”</blockquote>';
          } elseif ($type === 'canvasVideo') {
            echo '<div class="alert alert-info">Animación Canvas: configura <code>palette</code> y <code>points</code> en el JSON.</div>';
          } elseif ($type === 'interview') {
            $qa = $decoded['qa'] ?? [];
            echo '<ul class="prose">';
            foreach ($qa as $item) echo '<li><strong>'.htmlspecialchars($item['q'] ?? '').':</strong> '.htmlspecialchars($item['a'] ?? '').'</li>';
            echo '</ul>';
          }
        }
      ?>

    <?php else: ?>
      <p>Tipo no soportado.</p>
    <?php endif; ?>
  </section>

  <a class="btn btn-outline-dark btn-sm" href="<?= $baseUrl ?>/">Volver</a>
</article>
