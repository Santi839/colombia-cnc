/* ========= Colombia_CNC – App JS ========= */

/** --- THEMING --- */
(function ThemeBoot() {
  const STORAGE_KEY = 'cnc-theme';

  function applyTheme(name) {
    // nombre normalizado
    const theme = (name || '').toLowerCase();
    // aplica en <html data-theme="...">
    document.documentElement.setAttribute('data-theme', theme);
    try { localStorage.setItem(STORAGE_KEY, theme); } catch (_) {}
  }

  // Exponer global
  window.ThemePick = function(name) {
    applyTheme(name);
  };

  // Cargar guardado o establecer por defecto (violeta)
  document.addEventListener('DOMContentLoaded', () => {
    let saved = null;
    try { saved = localStorage.getItem(STORAGE_KEY); } catch (_) {}
    applyTheme(saved || 'violeta');
  });
})();

/** --- BUSCADOR EN HOME (filtro por texto en cards) --- */
const SiteSearch = {
  run(e){
    e.preventDefault();
    const q = (document.getElementById('q')?.value || '').toLowerCase().trim();
    const cards = document.querySelectorAll('.hover-card');
    if (!cards.length) return;
    cards.forEach(card=>{
      const txt = card.innerText.toLowerCase();
      card.style.display = txt.includes(q) ? '' : 'none';
    });
  }
};

/** --- Canvas Explainer (si lo usas) --- */
window.CanvasExplainer = {
  ctx:null, raf:null, t:0, colors:['#ffd100','#003087','#ce1126'],
  setup(id, palette){ this.colors = Array.isArray(palette)&&palette.length?palette:this.colors; const c=document.getElementById(id); if(!c) return; this.ctx=c.getContext('2d'); this.reset(); },
  start(){ if(!this.ctx) return; const loop=()=>{ this.t+=0.016; this.draw(); this.raf=requestAnimationFrame(loop); }; cancelAnimationFrame(this.raf); this.raf=requestAnimationFrame(loop); },
  pause(){ cancelAnimationFrame(this.raf); },
  reset(){ if(!this.ctx) return; const c=this.ctx.canvas; this.ctx.clearRect(0,0,c.width,c.height); this.t=0; this.draw(); },
  draw(){ const ctx=this.ctx; if(!ctx) return; const {width:w, height:h}=ctx.canvas; ctx.clearRect(0,0,w,h);
    const cx=w/2, cy=h/2, r=Math.min(w,h)/3; for(let i=0;i<3;i++){ ctx.beginPath(); ctx.arc(cx+Math.sin(this.t+i)*20, cy+Math.cos(this.t*0.7+i)*18, r-(i*22), 0, Math.PI*2); ctx.fillStyle=this.colors[i%this.colors.length]; ctx.globalAlpha=0.18+(0.12*i); ctx.fill(); }
    ctx.globalAlpha=1; ctx.fillStyle='#e5e7eb'; ctx.font='600 18px ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto'; ctx.textAlign='center';
    ctx.fillText('Territorio · Población · Poder', cx, cy); }
};

/** --- Audio grabación simple (entrevistas) --- */
window.AudioRecorder = {
  media: null, chunks: [], rec: null,
  init(btnStartId, btnStopId, audioId, dlId){
    const bs=document.getElementById(btnStartId), bt=document.getElementById(btnStopId),
          audio=document.getElementById(audioId), link=document.getElementById(dlId);
    if(!bs||!bt||!audio||!link) return;
    bs.onclick = async ()=>{
      const s = await navigator.mediaDevices.getUserMedia({audio:true});
      this.media=s; this.rec = new MediaRecorder(s);
      this.chunks=[]; this.rec.ondataavailable=e=>this.chunks.push(e.data);
      this.rec.onstop=()=>{
        const blob=new Blob(this.chunks,{type:'audio/webm'}); const url=URL.createObjectURL(blob);
        audio.src=url; audio.style.display='block'; link.href=url; link.classList.remove('d-none'); bt.disabled=true; bs.disabled=false;
      };
      this.rec.start(); bs.disabled=true; bt.disabled=false;
    };
    bt.onclick = ()=>{ if(this.rec && this.rec.state!=='inactive'){ this.rec.stop(); } if(this.media){ this.media.getTracks().forEach(t=>t.stop()); } };
  }
};
