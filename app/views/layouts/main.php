<?php /** @var string $content */ ?>
<!doctype html>
<html lang="es" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Colombia en Construcción: Nuestros Cimientos</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Tus estilos (OJO: ya sin /public delante) -->
  <link href="<?= $baseUrl ?>/assets/css/styles.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg border-0 sticky-top">
  <div class="container">
    <!-- SOLO CAMBIO DE COLOR EN EL NOMBRE DEL BLOG -->
    <a class="navbar-brand fw-bold" href="<?= $baseUrl ?>/" style="color:#f8fafc;">
      Colombia en Construcción: <span style="color:#e2e8f0;">Nuestros Cimientos</span>
    </a>

    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-2">

        <!-- Inicio -->
        <li class="nav-item">
          <a class="nav-link" href="<?= $baseUrl ?>/">Inicio</a>
        </li>

        <!-- Secciones (filtro por categoría) -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Secciones</a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= $baseUrl ?>/posts?cat=Población">Población</a></li>
            <li><a class="dropdown-item" href="<?= $baseUrl ?>/posts?cat=Territorio">Territorio</a></li>
            <li><a class="dropdown-item" href="<?= $baseUrl ?>/posts?cat=Poder%20y%20autoridad">Poder y autoridad</a></li>
            <li><a class="dropdown-item" href="<?= $baseUrl ?>/posts?cat=Soberanía">Soberanía</a></li>
            <li><a class="dropdown-item" href="<?= $baseUrl ?>/posts?cat=Constitución">Constitución</a></li>
            <li><a class="dropdown-item" href="<?= $baseUrl ?>/posts?cat=Instituciones">Instituciones</a></li>
          </ul>
        </li>

        <!-- Buscador local -->
        <li class="nav-item">
          <form class="d-flex" role="search" onsubmit="SiteSearch.run(event)">
            <input class="form-control form-control-sm me-2" id="q" type="search" placeholder="Buscar…">
            <button class="btn btn-sm btn-outline-dark" type="submit">Buscar</button>
          </form>
        </li>

        <!-- Selector de tema -->
        <li class="nav-item d-none d-lg-flex align-items-center gap-1 ms-2">
          <button class="btn btn-sm btn-outline-dark" onclick="ThemePick('violeta')" title="Violeta">V</button>
          <button class="btn btn-sm btn-outline-dark" onclick="ThemePick('esmeralda')" title="Esmeralda">E</button>
          <button class="btn btn-sm btn-outline-dark" onclick="ThemePick('coral')" title="Coral">C</button>
          <button class="btn btn-sm btn-outline-dark" onclick="ThemePick('azul')" title="Azul">A</button>
        </li>

        <!-- CTA -->
        <li class="nav-item ms-lg-2">
          <a class="btn btn-dark btn-sm" href="<?= $baseUrl ?>/posts/create">Nuevo contenido</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="py-4">
  <div class="container">
    <?= $content ?>
  </div>
</main>

<footer class="border-top py-4 mt-5">
  <div class="container small text-secondary">
    <div class="d-flex justify-content-between">
      <span>© <?= date('Y') ?> Colombia en Construcción: Nuestros Cimientos · Minimal MVC</span>
      <span>Hecho con PHP · Bootstrap 5 · Canvas · SVG</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.BASE_URL = "<?= $baseUrl ?>";</script>

<!-- Tus scripts (OJO: ya sin /public delante) -->
<script src="<?= $baseUrl ?>/assets/js/app.js"></script>
</body>
</html>
