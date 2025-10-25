<h1 class="h3 fw-semibold mb-3">Publicaciones</h1>
<div class="list-group shadow-sm">
  <?php foreach ($posts as $p): ?>
    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
       href="<?= $baseUrl ?>posts/show?id=<?= htmlspecialchars($p['id']) ?>">
      <span>
        <span class="badge bg-dark-subtle text-dark me-2"><?= htmlspecialchars($p['category']) ?></span>
        <?= htmlspecialchars($p['title']) ?>
      </span>
      <small class="text-muted"><?= date('d M Y', strtotime($p['created_at'])) ?></small>
    </a>
  <?php endforeach; ?>
</div>
