<?php
// Usa $decoded que te pasa show.php (¡no $post!)
$title    = $decoded['title']    ?? 'Animación educativa';
$subtitle = $decoded['subtitle'] ?? '';
$accent   = $decoded['accent']   ?? '#06b6d4';
$bg1      = $decoded['bg1']      ?? '#0f172a';
$bg2      = $decoded['bg2']      ?? '#1e293b';
$videoSrc = $decoded['video']    ?? null;            // ruta MP4 opcional
$elements = $decoded['elements'] ?? [];              // [{shape,color,motion}]
$quote    = $decoded['quote']    ?? null;
$footer   = $decoded['footer']   ?? null;
$credit   = $decoded['credit']   ?? 'Colombia_CNC';

// id único para el canvas si hay varios en la página
$canvasId = 'cv_' . bin2hex(random_bytes(4));
?>
<section class="p-4 p-md-5"
         style="background: linear-gradient(135deg, <?= $bg1 ?>, <?= $bg2 ?>);
                border-radius:24px; position:relative; overflow:hidden; color:#e5e7eb;">
  <div class="container text-center">
    <h2 class="fw-bold mb-1"><?= htmlspecialchars($title) ?></h2>
    <?php if ($subtitle): ?>
      <p class="text-secondary mb-4"><?= htmlspecialchars($subtitle) ?></p>
    <?php endif; ?>

    <div class="position-relative d-inline-block rounded-4 overflow-hidden shadow-lg"
         style="max-width:960px; border:1px solid <?= $accent ?>55;">
      <?php if (!empty($videoSrc)): ?>
        <video autoplay muted loop controls playsinline style="width:100%; display:block;">
          <source src="<?= htmlspecialchars($videoSrc) ?>" type="video/mp4">
        </video>
      <?php else: ?>
        <canvas id="<?= $canvasId ?>" width="960" height="480" class="d-block"></canvas>
        <script>
          (() => {
            const canvas = document.getElementById('<?= $canvasId ?>');
            const ctx = canvas.getContext('2d');
            const W = canvas.width, H = canvas.height;

            // Elementos desde el JSON (con defaults)
            const elems = <?= json_encode($elements ?: [
              ['shape'=>'circle','color'=>'#3b82f6','motion'=>'float'],
              ['shape'=>'triangle','color'=>'#60a5fa','motion'=>'pulse'],
              ['shape'=>'square','color'=>'#93c5fd','motion'=>'rotate']
            ]) ?>;

            // Crea partículas a partir de "elements"
            const parts = elems.map((e, i) => ({
              shape: e.shape || 'circle',
              color: e.color || '#3b82f6',
              motion: e.motion || 'float',
              x: (i+1)/(elems.length+1) * W,
              y: H*0.55,
              r: 26 + i*6,
              a: Math.random()*Math.PI*2,
              s: 0.6 + Math.random()*0.8
            }));

            let t = 0;
            function drawShape(p){
              ctx.save();
              ctx.translate(p.x, p.y);
              ctx.rotate(p.a);
              ctx.fillStyle = p.color;
              ctx.globalAlpha = 0.22;

              if (p.shape === 'square') {
                const s = p.r*2;
                ctx.fillRect(-s/2, -s/2, s, s);
              } else if (p.shape === 'triangle') {
                const s = p.r*2.2;
                ctx.beginPath();
                ctx.moveTo(0, -s/2);
                ctx.lineTo(-s/2, s/2);
                ctx.lineTo(s/2, s/2);
                ctx.closePath();
                ctx.fill();
              } else {
                ctx.beginPath();
                ctx.arc(0, 0, p.r*1.2, 0, Math.PI*2);
                ctx.fill();
              }
              ctx.restore();
            }

            function step(){
              // fondo con suave gradiente animado
              const grd = ctx.createLinearGradient(0,0,W,H);
              grd.addColorStop(0, '<?= $bg1 ?>');
              grd.addColorStop(1, '<?= $bg2 ?>');
              ctx.fillStyle = grd;
              ctx.fillRect(0,0,W,H);

              // halo/acento
              ctx.fillStyle = '<?= $accent ?>22';
              ctx.beginPath();
              ctx.ellipse(W*0.65, H*0.35, 220, 140, 0, 0, Math.PI*2);
              ctx.fill();

              parts.forEach((p, i)=>{
                // movimiento según "motion"
                if (p.motion === 'rotate') p.a += 0.01*p.s;
                if (p.motion === 'pulse')  p.r = 24 + 10*Math.sin(t*0.04 + i);
                if (p.motion === 'float') {
                  p.x += Math.sin(t*0.01 + i)*0.3;
                  p.y += Math.cos(t*0.008 + i)*0.25;
                }
                drawShape(p);
              });

              // título fijo en el centro
              ctx.globalAlpha = 1;
              ctx.fillStyle = '#e5e7eb';
              ctx.textAlign = 'center';
              ctx.font = '700 22px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto';
              ctx.fillText('Territorio · Población · Poder · Instituciones', W/2, H*0.55);

              t++;
              requestAnimationFrame(step);
            }
            step();
          })();
        </script>
      <?php endif; ?>
    </div>

    <?php if ($quote): ?>
      <p class="mt-3 fst-italic">“<?= htmlspecialchars($quote) ?>”</p>
    <?php endif; ?>
    <?php if ($footer): ?>
      <div class="small text-secondary"><?= htmlspecialchars($footer) ?></div>
    <?php endif; ?>
  </div>

  <div class="position-absolute end-0 bottom-0 m-3">
    <span class="badge bg-light text-dark fw-bold"><?= htmlspecialchars($credit) ?></span>
  </div>
</section>
