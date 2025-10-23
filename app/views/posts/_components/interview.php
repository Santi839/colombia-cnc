<?php
// Usamos $decoded (ya viene de show.php)
$title    = $decoded['title']    ?? 'Entrevista';
$subtitle = $decoded['subtitle'] ?? '';
$person   = $decoded['person']   ?? 'Invitado';
$summary  = $decoded['summary']  ?? 'Reflexiones sobre ciudadanía y desarrollo.';
$accent   = $decoded['accent']   ?? '#06b6d4';
$bg1      = $decoded['bg1']      ?? '#0f172a';
$bg2      = $decoded['bg2']      ?? '#1e293b';
$qa       = $decoded['qa']       ?? [];
$audio    = $decoded['audio']    ?? null;
$footer   = $decoded['footer']   ?? null;
$credit   = $decoded['credit']   ?? 'Colombia_CNC';
?>

<section class="p-4 p-md-5"
         style="background: radial-gradient(circle at 80% 20%, <?= $accent ?>33, transparent 70%), linear-gradient(135deg, <?= $bg1 ?>, <?= $bg2 ?>);
                border-radius:24px; color:#f9fafb;">
  <div class="container py-4">
    <div class="text-center mb-4">
      <h2 class="fw-bold mb-1 text-light"><?= htmlspecialchars($title) ?></h2>
      <?php if ($subtitle): ?>
        <p class="text-info mb-2"><?= htmlspecialchars($subtitle) ?></p>
      <?php endif; ?>
      <p class="fst-italic text-light small mb-3">
        <i class="bi bi-person-circle"></i> Entrevista con <strong><?= htmlspecialchars($person) ?></strong>
      </p>
      <p class="text-light opacity-90"><?= htmlspecialchars($summary) ?></p>
    </div>

    <div class="p-4 rounded-4 shadow-sm" style="background: rgba(255,255,255,0.06); border: 1px solid <?= $accent ?>33;">
      <?php if (!empty($qa)): ?>
        <?php foreach ($qa as $pair): ?>
          <div class="mb-4">
            <p class="fw-semibold text-light mb-1">
              <i class="bi bi-question-circle text-primary"></i> <?= htmlspecialchars($pair['q'] ?? '') ?>
            </p>
            <p class="text-light-50 mb-0" style="color:#e2e8f0;">
              <i class="bi bi-chat-left-text text-success"></i> <?= htmlspecialchars($pair['a'] ?? '') ?>
            </p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-secondary text-center fst-italic">No hay preguntas registradas para esta entrevista.</p>
      <?php endif; ?>
    </div>

    <?php if (!empty($audio)): ?>
      <div class="text-center mt-4">
        <audio controls style="width:100%; max-width:700px;">
          <source src="<?= htmlspecialchars($audio) ?>" type="audio/mp3">
        </audio>
        <small class="text-light d-block mt-2">Reproduzca la entrevista completa</small>
      </div>
    <?php endif; ?>

    <?php if ($footer): ?>
      <p class="text-secondary small text-center mt-4"><?= htmlspecialchars($footer) ?></p>
    <?php endif; ?>
  </div>

  <div class="position-absolute end-0 bottom-0 m-3">
    <span class="badge bg-light text-dark fw-bold"><?= htmlspecialchars($credit) ?></span>
  </div>
</section>
