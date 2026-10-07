/*!
 * خانه سعادت — اسکریپتِ هدرِ سراسری (چسبیدن با اسکرول، اورلیِ جستجوی موبایل)
 * محل:  wp-content/themes/hello-child/assets/js/header.js  — header.php صفش می‌کند (defer، فوتر).
 */
(function(){
  var h = document.querySelector('[data-ks-hdr]');
  if(h){
    var onScroll = function(){ h.classList.toggle('is-stuck', window.scrollY > 40); };
    onScroll(); window.addEventListener('scroll', onScroll, {passive:true});
  }
  var ov = document.getElementById('ks-search-overlay');
  var openBtn = document.querySelector('[data-ks-search-open]');
  var closeBtn = ov ? ov.querySelector('[data-ks-search-close]') : null;
  function openOv(){ if(!ov) return; ov.hidden=false; requestAnimationFrame(function(){ ov.classList.add('is-open'); }); document.body.style.overflow='hidden'; openBtn && openBtn.setAttribute('aria-expanded','true'); var f=ov.querySelector('input'); if(f) setTimeout(function(){ f.focus(); },80); }
  function closeOv(){ if(!ov) return; ov.classList.remove('is-open'); document.body.style.overflow=''; openBtn && openBtn.setAttribute('aria-expanded','false'); setTimeout(function(){ ov.hidden=true; }, 230); }
  openBtn && openBtn.addEventListener('click', openOv);
  closeBtn && closeBtn.addEventListener('click', closeOv);
  document.addEventListener('keydown', function(e){ if(e.key==='Escape' && ov && !ov.hidden) closeOv(); });
})();
