<?php
// Vista: Nuevo contenido (formulario)
// Incluye barra de acciones fija con botón "Publicar" y "Cancelar"
?>
<h1 class="h4 fw-semibold mb-4">Nuevo contenido</h1>

<form method="post" action="<?= $baseUrl ?>/posts/create" class="card shadow-sm border-0 overflow-hidden">
  <div class="card-body p-4">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Título</label>
        <input name="title" class="form-control" required placeholder="Ej: ¿Qué es la soberanía?">
      </div>

      <div class="col-md-3">
        <label class="form-label">Categoría</label>
        <select name="category" class="form-select">
          <option>Población</option>
          <option>Territorio</option>
          <option>Poder y autoridad</option>
          <option>Soberanía</option>
          <option>Constitución</option>
          <option>Instituciones</option>
          <option>General</option>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Tipo</label>
        <select name="type" class="form-select" id="typeSelect">
          <option value="article">Artículo</option>
          <option value="infographic">Infografía (SVG)</option>
          <option value="poster">Póster (CSS)</option>
          <option value="canvasVideo">Animación Canvas</option>
          <option value="interview">Entrevista/Q&A</option>
        </select>
      </div>

      <div class="col-12">
        <label class="form-label">Resumen</label>
        <input name="summary" class="form-control" placeholder="Una breve descripción del contenido">
      </div>

      <div class="col-12">
        <label class="form-label">Contenido (JSON o texto)</label>
        <textarea name="content" id="contentField" class="form-control" rows="10" placeholder='{"text":"Tu contenido en texto plano"}'></textarea>
        <div class="form-text">
          Para <strong>Infografía</strong>/<strong>Póster</strong>/<strong>Canvas</strong>/<strong>Entrevista</strong> puedes pegar JSON con claves como:
          <code>title</code>, <code>subtitle</code>, <code>points[]</code>, <code>values[]</code>, <code>quote</code>, <code>qa[{q,a}]</code>.
        </div>
      </div>
    </div>
  </div>

  <!-- Barra de acciones fija -->
  <div class="card-footer bg-transparent p-0">
    <div class="sticky-actions d-flex justify-content-between align-items-center">
      <a class="btn btn-outline-dark" href="<?= $baseUrl ?>/">Cancelar</a>
      <button type="submit" class="btn btn-dark">Publicar</button>
    </div>
  </div>
</form>

<!-- Plantillas rápidas según el tipo -->
<script>
(function(){
  const type = document.getElementById('typeSelect');
  const field = document.getElementById('contentField');

  const TPL = {
    article: JSON.stringify({ text: "Escribe aquí tu artículo en texto plano." }, null, 2),

    infographic: JSON.stringify({
      title: "Elementos del Estado",
      subtitle: "Diagrama de composición",
      points: ["Población","Territorio","Poder/Autoridad"],
      values: [40,35,25],
      color: "#0d6efd"
    }, null, 2),

    poster: JSON.stringify({
      title: "La Soberanía nos une",
      subtitle: "Principio fundamental del Estado colombiano",
      quote: "El poder emana del pueblo y en su nombre se ejerce.",
      accent: "#111827",
      bg1: "#f8fafc",
      bg2: "#e5e7eb"
    }, null, 2),

    canvasVideo: JSON.stringify({
      title: "Territorio, Población y Poder en 30s",
      points: [
        "Territorio: base física",
        "Población: comunidad política",
        "Poder: organización y autoridad"
      ],
      palette: ["#ffd100","#003087","#ce1126"]
    }, null, 2),

    interview: JSON.stringify({
      title: "Entrevista: ¿Qué es la soberanía?",
      subtitle: "Diálogo breve para comprender el concepto",
      qa: [
        { q: "¿Qué es la soberanía?", a: "La autoridad suprema del Estado para decidir de forma autónoma." },
        { q: "¿Cómo se ejerce?", a: "Principalmente a través de la Constitución y las instituciones." },
        { q: "¿Cómo puedo ejercerla directamente?", a: "Votando, participando en plebiscitos, referendos y consultas populares." }
      ]
    }, null, 2)
  };

  // set default
  field.value = TPL.infographic;

  type.addEventListener('change', () => {
    const v = type.value;
    field.value = TPL[v] || TPL.article;
  });
})();
</script>
