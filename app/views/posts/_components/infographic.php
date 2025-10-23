<?php
// Usamos $decoded, ya viene de show.php
$title    = $decoded['title']    ?? 'Infografía';
$subtitle = $decoded['subtitle'] ?? '';
$points   = $decoded['points']   ?? [];
$values   = $decoded['values']   ?? [];
$colors   = $decoded['colors']   ?? ['#38bdf8', '#0ea5e9', '#0284c7', '#0369a1', '#075985'];
$accent   = $decoded['accent']   ?? '#38bdf8';
$bg1      = $decoded['bg1']      ?? '#0f172a';
$bg2      = $decoded['bg2']      ?? '#1e293b';
?>

<section class="p-5"
         style="background: radial-gradient(circle at 80% 20%, <?= $accent ?>33, transparent 70%), linear-gradient(135deg, <?= $bg1 ?>, <?= $bg2 ?>);
                border-radius:24px; color:#f8fafc;">
  <div class="container text-center">
    <h2 class="fw-bold text-light mb-1"><?= htmlspecialchars($title) ?></h2>
    <?php if ($subtitle): ?>
      <p class="text-info mb-4"><?= htmlspecialchars($subtitle) ?></p>
    <?php endif; ?>

    <div class="d-flex justify-content-center flex-wrap gap-4 mt-4">
      <?php
      if (!empty($points) && !empty($values)) {
          $total = array_sum($values);
          foreach ($points as $i => $p):
              $percent = round(($values[$i] / $total) * 100, 1);
              $color = $colors[$i % count($colors)];
      ?>
          <div class="p-3 rounded-4 shadow-lg text-start"
               style="background: rgba(255,255,255,0.05); border: 1px solid <?= $color ?>33; min-width:220px; flex:1; transition:transform 0.3s;">
            <div class="d-flex align-items-center mb-2">
              <div class="rounded-circle me-2" style="width:14px; height:14px; background: <?= $color ?>;"></div>
              <h6 class="text-light mb-0"><?= htmlspecialchars($p) ?></h6>
            </div>
            <div class="progress" style="height:10px; background: rgba(255,255,255,0.1); border-radius:8px;">
              <div class="progress-bar" role="progressbar"
                   style="width: <?= $percent ?>%; background: <?= $color ?>; border-radius:8px;"></div>
            </div>
            <small class="text-light opacity-75"><?= $percent ?>%</small>
          </div>
      <?php endforeach; } else { ?>
          <p class="text-secondary">No hay datos disponibles para esta infografía.</p>
      <?php } ?>
    </div>

    <p class="text-secondary small mt-5">Colombia_CNC · Datos educativos sobre el Estado Colombiano</p>
  </div>
</section>
