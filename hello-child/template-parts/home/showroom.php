<?php
/**
 * سکشن «ویدیوی شوروم» — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/showroom.php
 *
 * بک‌گراند سفید، ارتفاع ۸۰٪ صفحه. فاصلهٔ بالا ۲ برابرِ معمول. کانتینرِ ویدیو کوچک (≈نصف)، لبه‌تیز.
 * ⚡ پرفورمنس: هیچ بایتی از ویدیو هنگام لودِ صفحه گرفته نمی‌شود — کارت یک «پلیسهولدرِ CSS» است؛
 *    ویدیو فقط با کلیک، داخلِ مودال و با `preload="none"` لود و پخش می‌شود (نه autoplay).
 * ورودِ «باابهت» با IntersectionObserver.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_show = array(
	'video' => 'https://khanehsaadat.com/wp-content/uploads/2026/10/showroom-saadat.webm',
	'title' => 'نمایی از شوروم بزرگ خانه سعادت',
	'sub'   => 'تنها و قوی‌ترین شوروم غرب کشور - خانه سعادت',
);
?>
<style id="ks-show-css">
.ks-show{background:#fff;direction:rtl;font-family:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif;min-height:80vh;display:flex;align-items:center}
.ks-show *{box-sizing:border-box}
.ks-show__in{width:100%;max-width:1180px;margin-inline:auto;padding:clamp(80px,12vw,168px) clamp(16px,3vw,32px) clamp(40px,6vw,84px)}
.ks-show__frame{position:relative;max-width:590px;margin-inline:auto;aspect-ratio:16/9;border-radius:6px;overflow:hidden;cursor:pointer;background:linear-gradient(140deg,#063e33 0%,#032a22 55%,#021713 100%);box-shadow:0 44px 84px -42px rgb(0 0 0 / .5);opacity:0;transform:translateY(42px) scale(.96);transition:opacity 1s cubic-bezier(.16,1,.3,1),transform 1.15s cubic-bezier(.16,1,.3,1)}
.ks-show__frame.is-in{opacity:1;transform:none}
.ks-show__frame:focus-visible{outline:3px solid #F2A900;outline-offset:3px}
.ks-show__glow{position:absolute;inset-block-start:-26%;inset-inline:6%;height:72%;background:radial-gradient(ellipse at center,rgb(242 169 0 / .18),transparent 62%);pointer-events:none}
.ks-show__ov{position:absolute;inset:0;pointer-events:none;background:linear-gradient(to top,rgb(0 0 0 / .5) 0%,rgb(0 0 0 / .12) 46%,transparent 100%)}
.ks-show__play{position:absolute;inset:0;margin:auto;width:clamp(58px,7vw,76px);height:clamp(58px,7vw,76px);border:0;border-radius:50%;background:rgb(255 255 255 / .18);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:transform .3s ease,background .3s ease}
.ks-show__frame:hover .ks-show__play{transform:scale(1.09);background:rgb(255 255 255 / .3)}
.ks-show__play svg{width:38%;height:38%;margin-inline-start:6%}
.ks-show__play::after{content:"";position:absolute;inset:-7px;border-radius:50%;border:1px solid rgb(255 255 255 / .35);animation:ks-show-pulse 2.6s ease-out infinite}
@keyframes ks-show-pulse{0%{transform:scale(1);opacity:.75}100%{transform:scale(1.32);opacity:0}}
.ks-show__cap{position:absolute;inset-inline:0;inset-block-end:0;padding:clamp(16px,2.4vw,28px);z-index:2;pointer-events:none}
.ks-show__title{margin:0;color:#fff;font-size:clamp(17px,2.2vw,24px);font-weight:800;line-height:1.5;text-shadow:0 2px 18px rgb(0 0 0 / .4)}
.ks-show__sub{margin:6px 0 0;color:rgb(255 255 255 / .85);font-size:clamp(12px,1.1vw,14px);line-height:1.7}
/* مودال */
.ks-show__modal{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:clamp(14px,3vw,28px);background:rgb(0 0 0 / .86);-webkit-backdrop-filter:blur(5px);backdrop-filter:blur(5px);animation:ks-show-fade .25s ease both}
.ks-show__modal[hidden]{display:none}
@keyframes ks-show-fade{from{opacity:0}to{opacity:1}}
.ks-show__modal-in{width:100%;max-width:1000px;aspect-ratio:16/9}
.ks-show__modal-video{width:100%;height:100%;border-radius:10px;background:#000;display:block}
.ks-show__close{position:absolute;inset-block-start:18px;inset-inline-end:18px;width:46px;height:46px;border:0;border-radius:50%;background:rgb(255 255 255 / .12);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s ease}
.ks-show__close:hover{background:rgb(255 255 255 / .24)}
.ks-show__close svg{width:22px;height:22px}
@media (max-width:640px){.ks-show{min-height:auto}}
@media (prefers-reduced-motion:reduce){.ks-show__frame{opacity:1;transform:none;transition:none}.ks-show__play::after{animation:none}.ks-show__modal{animation:none}}
</style>

<section class="ks-show" id="home-sec-8" aria-label="ویدیوی شوروم خانه سعادت">
  <div class="ks-show__in">
    <div class="ks-show__frame" data-ks-show role="button" tabindex="0" aria-label="پخش ویدیو: <?php echo esc_attr( $ks_show['title'] ); ?>">
      <span class="ks-show__glow" aria-hidden="true"></span>
      <span class="ks-show__ov" aria-hidden="true"></span>
      <button class="ks-show__play" type="button" data-ks-show-play aria-label="پخش ویدیو">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5.14v13.72a1 1 0 0 0 1.52.86l10.5-6.86a1 1 0 0 0 0-1.72L9.52 4.28A1 1 0 0 0 8 5.14z"/></svg>
      </button>
      <div class="ks-show__cap">
        <h2 class="ks-show__title"><?php echo esc_html( $ks_show['title'] ); ?></h2>
        <p class="ks-show__sub"><?php echo esc_html( $ks_show['sub'] ); ?></p>
      </div>
    </div>
  </div>

  <div class="ks-show__modal" data-ks-show-modal hidden>
    <button class="ks-show__close" type="button" data-ks-show-close aria-label="بستن ویدیو">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
    </button>
    <div class="ks-show__modal-in">
      <video class="ks-show__modal-video" data-ks-show-modal-video controls playsinline preload="none">
        <source src="<?php echo esc_url( $ks_show['video'] ); ?>" type="video/webm">
      </video>
    </div>
  </div>
</section>

<script id="ks-show-js">
(function(){
  var frame = document.querySelector('[data-ks-show]');
  if ( ! frame ) { return; }
  var modal = document.querySelector('[data-ks-show-modal]');
  var mv    = document.querySelector('[data-ks-show-modal-video]');

  // ورودِ باابهت
  if ( 'IntersectionObserver' in window && ! window.matchMedia('(prefers-reduced-motion: reduce)').matches ) {
    var io = new IntersectionObserver(function(es){ es.forEach(function(e){ if ( e.isIntersecting ) { frame.classList.add('is-in'); io.disconnect(); } }); }, { threshold:.25 });
    io.observe(frame);
  } else {
    frame.classList.add('is-in');
  }

  function open(){
    if ( ! modal ) { return; }
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    // ویدیو تازه اینجا لود می‌شود (preload=none)
    try { mv.load(); var p = mv.play(); if ( p && p.catch ) { p.catch(function(){}); } } catch(e){}
  }
  function close(){
    if ( ! modal ) { return; }
    modal.hidden = true;
    document.body.style.overflow = '';
    try { mv.pause(); } catch(e){}
  }

  var playBtn = frame.querySelector('[data-ks-show-play]');
  if ( playBtn ) { playBtn.addEventListener('click', function(e){ e.stopPropagation(); open(); }); }
  frame.addEventListener('click', open);
  frame.addEventListener('keydown', function(e){ if ( e.key === 'Enter' || e.key === ' ' ) { e.preventDefault(); open(); } });

  var closeBtn = document.querySelector('[data-ks-show-close]');
  if ( closeBtn ) { closeBtn.addEventListener('click', close); }
  if ( modal ) { modal.addEventListener('click', function(e){ if ( e.target === modal ) { close(); } }); }
  document.addEventListener('keydown', function(e){ if ( e.key === 'Escape' && modal && ! modal.hidden ) { close(); } });
})();
</script>
