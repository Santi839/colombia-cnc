<div class="mb-4">
  <h1 class="display-5 fw-semibold">Elementos básicos del Estado colombiano</h1>
  <p class="lead text-secondary">
    Blog educativo con contenidos propios: infografías, pósters, entrevistas y animaciones interactivas
    para explicar población, territorio, poder/autoridad, soberanía, Constitución e instituciones.
  </p>
</div>

<div class="row g-4">
  <?php if (!$posts): ?>
    <div class="col-12">
      <div class="card shadow-sm border-0">
        <div class="card-body p-5 text-center text-secondary">
          <p class="mb-2">Aún no hay publicaciones.</p>
          <a class="btn btn-dark" href="<?= $baseUrl ?>/posts/create">Crear la primera</a>
        </div>
      </div>
    </div>
  <?php else: foreach ($posts as $p): ?>
    <div class="col-12 col-md-6 col-lg-4">
      <a class="card h-100 shadow-sm border-0 hover-card text-reset text-decoration-none" href="<?= $baseUrl ?>/posts/show?id=<?= htmlspecialchars($p['id']) ?>">
        <div class="card-body">
          <span class="badge rounded-pill bg-dark-subtle text-dark mb-2"><?= htmlspecialchars($p['category']) ?></span>
          <h3 class="h5 fw-semibold mb-2"><?= htmlspecialchars($p['title']) ?></h3>
          <p class="text-secondary small mb-3"><?= htmlspecialchars($p['summary'] ?? '') ?></p>
          <span class="small text-muted"><?= date('d M Y', strtotime($p['created_at'])) ?> · <?= htmlspecialchars($p['type']) ?></span>
        </div>
      </a>
    </div>
  <?php endforeach; endif; ?>
</div>
