<?php
/**
 * Poster avanzado – Colombia en Construcción
 * Lee $decoded (array) desde show.php
 * Admite claves:
 *  - title, subtitle, quote, footer, credit
 *  - accent (color), bg1, bg2, overlay
 *  - decoration: { pattern: 'dots'|'diagonal-lines'|'grid', opacity, color }
 *  - layout: { textAlign, shadow, maxWidth, borderRadius }
 *  - chart (opcional): { regions[], percentages[], colors[] }
 */

$headline = $decoded['title'] ?? 'Título del póster';
$sub      = $decoded['subtitle'] ?? 'Subtítulo o bajada';
$quote    = $decoded['quote'] ?? 'Una idea fuerza corta que resuma el mensaje del póster.';
$footer   = $decoded['footer'] ?? 'Proyecto Estado Colombiano · 2025';
$credit   = $decoded['credit'] ?? 'Colombia_CNC';

$accent   = $decoded['accent'] ?? '#7c3aed';
$bg1      = $decoded['bg1'] ?? 'linear-gradient(135deg, #111827, #0b1223)';
$bg2      = $decoded['bg2'] ?? 'radial-gradient(120% 100% at 100% 0%, rgba(6,182,212,.18), rgba(124,58,237,.12))';
$overlay  = $decoded['overlay'] ?? 'rgba(255,255,255,.04)';

$dec      = $decoded['decoration'] ?? [];
$pattern  = $dec['pattern'] ?? 'dots'; // 'dots' | 'diagonal-lines' | 'grid'
$patColor = $dec['color']   ?? '#ffffff';
$patOpac  = (float)($dec['opacity'] ?? 0.15);

$lay      = $decoded['layout'] ?? [];
$textAlign   = $lay['textAlign']   ?? 'left';
$shadow      = $lay['shadow']      ?? '0 10px 40px rgba(0,0,0,.35)';
$maxWidth    = $lay['maxWidth']    ?? '960px';
$borderRadius= $lay['borderRadius']?? '28px';

$chart       = $decoded['chart'] ?? null;

/** Utilidad: genera data-uri SVG de patrón según $pattern */
function poster_pattern_data_uri(string $pattern, string $color, float $opacity=0.15): string {
  $color = htmlspecialchars($color, ENT_QUOTES, 'UTF-8');
  $op = max(0, min(1, $opacity));
  if ($pattern === 'diagonal-lines') {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"><defs><pattern id="p" width="16" height="16" patternUnits="userSpaceOnUse" patternTransform="rotate(45)"><rect width="16" height="16" fill="none"/><rect x="0" y="0" width="2" height="16" fill="'.$color.'" opacity="'.$op.'"/></pattern></defs><rect width="100%" height="100%" fill="url(#p)"/></svg>';
  } elseif ($pattern === 'grid') {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><defs><pattern id="p" width="24" height="24" patternUnits="userSpaceOnUse"><path d="M 24 0 L 0 0 0 24" fill="none" stroke="'.$color.'" stroke-width="1" opacity="'.$op.'"/></pattern></defs><rect width="100%" height="100%" fill="url(#p)"/></svg>';
  } else {
    // por defecto "dots"
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"><defs><pattern id="p" width="14" height="14" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.6" fill="'.$color.'" opacity="'.$op.'"/></pattern></defs><rect width="100%" height="100%" fill="url(#p)"/></svg>';
  }
  $base64 = base64_encode($svg);
  return "url('data:image/svg+xml;base64,{$base64}')";
}
$patternURI = poster_pattern_data_uri($pattern, $patColor, $patOpac);
?>
<style>
/* Estilos aislados del poster avanzado */
.poster-adv{
  position:relative; overflow:hidden;
  border:1px solid rgba(255,255,255,.12);
  border-radius: <?= $borderRadius ?>;
  box-shadow: <?= $shadow ?>;
  color:#e9eefb;
  background: <?= $bg1 ?>;
}
.poster-adv .layer-bg2{ position:absolute; inset:-10% -20% auto auto; width:70%; height:70%; pointer-events:none; background: <?= $bg2 ?>; filter:blur(0px); opacity:.90; }
.poster-adv .layer-overlay{ position:absolute; inset:0; background: <?= $overlay ?>; }
.poster-adv .layer-shine{
  position:absolute; inset:-20% -10% auto auto; width:60%; height:70%;
  background: conic-gradient(from 120deg, rgba(255,255,255,.06), transparent 60%);
  filter: blur(50px); opacity:.85; animation: shineMove 8s ease-in-out infinite alternate;
}
.poster-adv .layer-pattern{ position:absolute; inset:0; background-image: <?= $patternURI ?>; background-size:auto; opacity:1; mix-blend-mode: overlay; }
@keyframes shineMove{
  0%{ transform: translate(0,0) rotate(-8deg); }
  100%{ transform: translate(-8%, 2%) rotate(-2deg); }
}
.poster-adv .content{
  position:relative; z-index:2;
  display:grid; gap:1rem;
  padding: clamp(20px, 4vw, 48px);
  max-width: <?= $maxWidth ?>;
}
.poster-adv .title{
  font-weight:900; letter-spacing:.3px;
  font-size: clamp(1.6rem, 3.8vw, 3.2rem);
  line-height:1.1;
}
.poster-adv .subtitle{
  color:#cbd5e1; font-size: clamp(.95rem, 1.6vw, 1.05rem);
}
.poster-adv .bar{
  height: 6px; width: clamp(120px, 30%, 280px);
  border-radius: 999px;
  background: linear-gradient(90deg, <?= $accent ?>, #06b6d4);
  box-shadow: 0 0 18px rgba(0,0,0,.25);
}
.poster-adv .quote{
  font-size: clamp(1.05rem, 2vw, 1.35rem);
  color:#e5e7eb;
}
.poster-adv .badge{
  position:absolute; right:0; bottom:0; margin:1rem;
  padding:.45rem .75rem; font-weight:800; font-size:.8rem;
  color:#111827; background:#e5e7eb; border-radius:999px; border:1px solid rgba(255,255,255,.15);
}
.poster-adv .footer{
  color:#cbd5e1; font-size:.85rem; margin-top: .25rem;
}
.poster-adv .chart{
  display:flex; gap:.5rem; align-items:flex-end; margin-top:.35rem;
}
.poster-adv .bar-v{
  width: 26px; border-radius: 6px 6px 2px 2px;
  background: linear-gradient(180deg, rgba(255,255,255,.18), rgba(0,0,0,.18));
  position:relative; overflow:hidden;
  border:1px solid rgba(255,255,255,.15);
}
.poster-adv .bar-v > span{
  position:absolute; left:0; right:0; bottom:0;
  height:0; border-radius: 6px 6px 2px 2px;
  transition:height .8s ease;
}
.poster-adv .chip{
  font-size:.8rem; color:#e2e8f0; margin-top:.35rem;
}
@media (min-width: 768px){
  .poster-adv .grid{
    display:grid; grid-template-columns: 1.1fr .9fr; gap: min(3vw, 28px);
    align-items: center;
  }
}
</style>

<div class="poster-adv">
  <div class="layer-bg2"></div>
  <div class="layer-shine"></div>
  <div class="layer-pattern"></div>
  <div class="layer-overlay"></div>

  <div class="content" style="text-align: <?= htmlspecialchars($textAlign) ?>;">
    <div class="grid">
      <div>
        <div class="title"><?= htmlspecialchars($headline) ?></div>
        <div class="subtitle"><?= htmlspecialchars($sub) ?></div>
        <div class="bar my-3"></div>
        <div class="quote">“<?= htmlspecialchars($quote) ?>”</div>

        <?php if (!empty($footer)): ?>
          <div class="footer"><?= htmlspecialchars($footer) ?></div>
        <?php endif; ?>
      </div>

      <div>
        <!-- Silueta abstracta de Colombia (SVG estilizado) -->
        <svg viewBox="0 0 230 300" class="w-100" style="filter: drop-shadow(0 12px 36px rgba(0,0,0,.35));">
          <defs>
            <linearGradient id="gCol" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0%" stop-color="<?= htmlspecialchars($accent) ?>" />
              <stop offset="100%" stop-color="#06b6d4" />
            </linearGradient>
          </defs>
          <!-- Silueta simplificada/abstracta (no geográfica exacta) -->
          <path d="M140 10c-12 8-17 20-20 28-6 14-14 12-23 20-9 8-12 21-5 30 7 9 5 16-2 24-7 8-11 19-13 29-2 10-8 18-16 24-12 9-21 21-19 36 3 20 21 32 41 39 22 8 46 12 69 10 27-2 49-14 60-33 8-15 9-34 1-49-6-12-10-25-7-38 3-12-2-27-9-38-10-15-14-33-16-51-1-10-16-37-41-31z" fill="url(#gCol)" opacity=".85"/>
          <circle cx="160" cy="70" r="6" fill="rgba(255,255,255,.9)"><animate attributeName="r" values="6;9;6" dur="3.2s" repeatCount="indefinite"/></circle>
          <circle cx="120" cy="160" r="4" fill="rgba(255,255,255,.9)"><animate attributeName="opacity" values="0.6;1;0.6" dur="2.6s" repeatCount="indefinite"/></circle>
        </svg>

        <?php if (is_array($chart) && !empty($chart['regions']) && !empty($chart['percentages'])): ?>
          <?php
            $regions = $chart['regions'];
            $vals = $chart['percentages'];
            $cols = $chart['colors'] ?? array_fill(0, count($regions), $accent);
            $max = max(1, max($vals));
          ?>
          <div class="chart">
            <?php foreach($regions as $i=>$r): 
              $h = max(8, round(($vals[$i] ?? 0) / $max * 120)); // altura 8-120px
              $c = htmlspecialchars($cols[$i] ?? $accent);
            ?>
              <div class="text-center">
                <div class="bar-v" style="height: 130px;">
                  <span style="background: <?= $c ?>; height: <?= $h ?>px;"></span>
                </div>
                <div class="chip"><?= htmlspecialchars($r) ?> · <?= (int)($vals[$i] ?? 0) ?>%</div>
              </div>
            <?php endforeach; ?>
          </div>
          <script>
            // “animar” la subida de barras
            (function(){
              const bars = document.querySelectorAll('.poster-adv .bar-v > span');
              requestAnimationFrame(()=>bars.forEach(b=>{
                const h = b.style.height; b.style.height='0'; setTimeout(()=>{ b.style.height=h; }, 30);
              }));
            })();
          </script>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="badge"><?= htmlspecialchars($credit) ?></div>
</div>
