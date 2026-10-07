<?php
/**
 * سکشن هیرو صفحهٔ اصلی — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/hero.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/hero' );
 *
 * ⚠️ یادداشت یکپارچه‌سازی هدر:
 *   این سکشن هدرِ ناوبریِ خودش را ندارد (تا با هدر سراسریِ کدشده تداخل نکند).
 *   روی صفحهٔ اصلی، هدر سراسری (.ks-hdr) باید «شفاف روی هیرو» شود و با اسکرول توپُر گردد.
 *   وقتی front-page.php را ساختیم این حالت شفاف را هم اضافه می‌کنیم.
 *
 * ویدیو هنوز آماده نیست: مسیرها را پایین پر کن؛ فعلاً فقط poster نمایش داده می‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$hero = array(
	'video_mp4'   => '', // اختیاری: نسخهٔ mp4 برای سازگاری بیشتر (سافاری قدیمی). مثال: get_stylesheet_directory_uri() . '/assets/video/hero.mp4'
	'video_webm'  => 'https://khanehsaadat.com/wp-content/uploads/2026/10/hiro-video-saadat.webm',
	'poster'      => 'https://khanehsaadat.com/wp-content/uploads/2026/01/بنر-سعادت-پی-scaled-1.webp', // موقت — با پوسترِ ویدیو عوض شود
	'title_pre'   => 'خانه سعادت؛ فراتر از',
	'title_em'    => 'یک خرید…',
	'subtitle'    => 'از سال ۱۳۵۷ در کنار شما؛ لوازم خانگی اصل با ضمانت واقعی، مشاوره تخصصی و پشتیبانی بعد از فروش.',
	'cta1_text'   => 'مشاهده محصولات',
	'cta1_url'    => home_url( '/shop/' ),
	'cta2_text'   => 'شرایط اقساطی',
	'cta2_url'    => home_url( '/installments/' ),
	'next_anchor' => '#home-sec-2',
);
?>
<style id="ks-hero-css">
.ks-hero{--brand:#005949;--accent:#F2A900;--hero-font:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif;min-height:100dvh;background:#05080a;padding:10px;display:flex;font-family:var(--hero-font)}
.ks-hero *{box-sizing:border-box}
.ks-hero__frame{flex:1;position:relative;border-radius:24px;overflow:hidden;min-height:calc(100dvh - 20px);display:flex;align-items:center;justify-content:center;isolation:isolate}
.ks-hero__video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:1}
.ks-hero__ov{position:absolute;inset:0;pointer-events:none;z-index:2}
.ks-hero__ov--solid{background:rgb(0 0 0 / .70)}
.ks-hero__ov--vignette{background:radial-gradient(120% 90% at 50% 42%, transparent 0%, rgb(0 0 0 / .35) 70%, rgb(0 0 0 / .72) 100%)}
.ks-hero__ov--bottom{background:linear-gradient(to top, rgb(0 0 0 / .92) 0%, rgb(0 0 0 / .35) 26%, transparent 55%)}
.ks-hero__side{position:absolute;inset-inline-start:18px;top:50%;transform:translateY(-50%);z-index:5;display:flex;flex-direction:column;align-items:center;gap:14px;pointer-events:none}
.ks-hero__side svg{width:16px;height:16px;color:rgb(255 255 255 / .55)}
.ks-hero__side-name{writing-mode:vertical-rl;transform:rotate(180deg);font-size:10px;letter-spacing:.42em;color:rgb(255 255 255 / .6);text-transform:uppercase;font-weight:600}
.ks-hero__core{position:relative;z-index:4;display:flex;flex-direction:column;align-items:center;text-align:center;padding:40px clamp(16px,4vw,24px) 48px;max-width:900px}
.ks-hero__glow{position:absolute;top:2%;inset-inline:18%;height:180px;background:var(--brand);filter:blur(70px);opacity:.28;border-radius:50%;z-index:-1;pointer-events:none}
.ks-hero__wordmark{position:relative;direction:ltr;display:inline-flex;gap:.06em;font-weight:800;line-height:1;font-size:clamp(3.25rem,12vw,9rem);user-select:none;cursor:default}
.ks-hero__ltr{display:inline-block;color:transparent;-webkit-text-stroke:1.6px rgb(255 255 255 / .5);transition:color .35s cubic-bezier(.16,1,.3,1),-webkit-text-stroke-color .35s ease,transform .35s cubic-bezier(.16,1,.3,1),text-shadow .35s ease}
/* هاور نسبت به هر حرف: موس روی هر حرف، همان حرف سفید پر می‌شود */
@media (hover:hover) and (pointer:fine){.ks-hero__wordmark:hover .ks-hero__ltr{-webkit-text-stroke-color:rgb(255 255 255 / .28)}.ks-hero__ltr:hover{color:#fff;-webkit-text-stroke-color:transparent;transform:translateY(-6px);text-shadow:0 14px 46px rgb(255 255 255 / .3)}}
/* لمسی: شیمرِ حرف‌به‌حرف چون هاوری نیست */
@media (hover:none){.ks-hero__ltr{animation:ks-hero-ltr 4.8s cubic-bezier(.16,1,.3,1) infinite;animation-delay:calc(var(--i,0) * .16s)}}
@keyframes ks-hero-ltr{0%,66%,100%{color:transparent;-webkit-text-stroke-color:rgb(255 255 255 / .5)}10%,26%{color:#fff;-webkit-text-stroke-color:transparent}}
.ks-hero__title{margin:18px 0 0;font-weight:700;color:#fff;font-size:clamp(1.25rem,2.5vw,2rem);line-height:1.5}
.ks-hero__title-em{color:var(--accent)}
.ks-hero__sub{margin:14px auto 0;max-width:34rem;color:rgb(255 255 255 / .72);font-size:.95rem;line-height:1.9}
.ks-hero__cta{display:flex;flex-wrap:nowrap;gap:12px;justify-content:center;margin-top:26px}
.ks-hero__btn{display:inline-flex;align-items:center;gap:8px;min-height:46px;padding:.9rem 1.75rem;border-radius:10px;font-weight:600;font-size:15px;text-decoration:none;cursor:pointer;transition:transform .2s ease,box-shadow .2s ease,background .2s ease}
.ks-hero__btn svg{width:18px;height:18px}
.ks-hero__btn--primary{background:var(--accent);color:#2a1d00;box-shadow:0 0 45px -8px rgb(242 169 0 / .7);animation:ks-hero-breath 3s ease-in-out infinite}
.ks-hero__btn--primary:hover{transform:translateY(-2px);box-shadow:0 0 60px -6px rgb(242 169 0 / .95)}
.ks-hero__btn--primary:active{transform:translateY(0)}
@keyframes ks-hero-breath{0%,100%{box-shadow:0 0 40px -10px rgb(242 169 0 / .6)}50%{box-shadow:0 0 54px -6px rgb(242 169 0 / 1)}}
.ks-hero__btn--ghost{background:rgb(255 255 255 / .06);color:#fff;border:1px solid rgb(255 255 255 / .4)}
.ks-hero__btn--ghost:hover{background:rgb(255 255 255 / .14);transform:translateY(-2px)}
.ks-hero__btn:focus-visible{outline:3px solid #fff;outline-offset:3px}
.ks-hero__scroll{position:absolute;bottom:20px;left:50%;transform:translateX(-50%);z-index:5;width:28px;height:46px;border:2px solid rgb(255 255 255 / .35);border-radius:16px;display:flex;justify-content:center;padding-top:8px}
.ks-hero__scroll-dot{width:4px;height:8px;border-radius:2px;background:rgb(255 255 255 / .8);animation:ks-hero-scroll 1.8s ease-in-out infinite}
@keyframes ks-hero-scroll{0%{transform:translateY(0);opacity:.2}50%{opacity:1}100%{transform:translateY(12px);opacity:.2}}
.ks-hero .ks-anim{opacity:0;animation:ks-hero-in .6s cubic-bezier(.22,1,.36,1) both;animation-delay:var(--d,0ms)}
.ks-hero__wordmark.ks-anim{animation-name:ks-hero-in-wm}
@keyframes ks-hero-in{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
@keyframes ks-hero-in-wm{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:none}}
@media (max-width:1023px){.ks-hero__side{display:none}}
@media (max-width:600px){.ks-hero__core{padding-top:52px}.ks-hero__cta{gap:10px}.ks-hero__btn{padding:.75rem 1rem;font-size:13px}.ks-hero__btn svg{width:15px;height:15px}}
@media (prefers-reduced-motion:reduce){.ks-hero .ks-anim{opacity:1;transform:none;animation:none}.ks-hero__ltr{animation:none;transition:none;transform:none}.ks-hero__btn--primary,.ks-hero__scroll-dot{animation:none}.ks-hero__btn--primary{box-shadow:0 0 40px -10px rgb(242 169 0 / .7)}}
</style>

<section class="ks-hero" aria-label="معرفی خانه سعادت">
  <div class="ks-hero__frame">

    <video class="ks-hero__video" autoplay muted loop playsinline preload="metadata" aria-hidden="true"
           poster="<?php echo esc_url( $hero['poster'] ); ?>">
      <?php if ( $hero['video_webm'] ) : ?><source src="<?php echo esc_url( $hero['video_webm'] ); ?>" type="video/webm"><?php endif; ?>
      <?php if ( $hero['video_mp4'] ) : ?><source src="<?php echo esc_url( $hero['video_mp4'] ); ?>" type="video/mp4"><?php endif; ?>
    </video>

    <div class="ks-hero__ov ks-hero__ov--solid"></div>
    <div class="ks-hero__ov ks-hero__ov--vignette"></div>
    <div class="ks-hero__ov ks-hero__ov--bottom"></div>

    <div class="ks-hero__side" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1.1" fill="currentColor" stroke="none"/></svg>
      <span class="ks-hero__side-name">KHANEH SAADAT</span>
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 4.2h3l1.3 3.4-2 1.4a11 11 0 0 0 5.2 5.2l1.4-2 3.4 1.3v3a1.6 1.6 0 0 1-1.8 1.6C10.6 17.6 6.4 13.4 5 6.1a1.6 1.6 0 0 1 1.5-1.9z"/></svg>
    </div>

    <div class="ks-hero__core">
      <div class="ks-hero__glow" aria-hidden="true"></div>

      <div class="ks-hero__wordmark ks-anim" style="--d:0ms" aria-hidden="true">
        <span class="ks-hero__ltr" style="--i:0">S</span><span class="ks-hero__ltr" style="--i:1">A</span><span class="ks-hero__ltr" style="--i:2">A</span><span class="ks-hero__ltr" style="--i:3">D</span><span class="ks-hero__ltr" style="--i:4">A</span><span class="ks-hero__ltr" style="--i:5">T</span>
      </div>

      <h1 class="ks-hero__title ks-anim" style="--d:120ms">
        <?php echo esc_html( $hero['title_pre'] ); ?> <span class="ks-hero__title-em"><?php echo esc_html( $hero['title_em'] ); ?></span>
      </h1>

      <p class="ks-hero__sub ks-anim" style="--d:240ms"><?php echo esc_html( $hero['subtitle'] ); ?></p>

      <div class="ks-hero__cta ks-anim" style="--d:360ms">
        <a class="ks-hero__btn ks-hero__btn--primary" href="<?php echo esc_url( $hero['cta1_url'] ); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.6"/></svg>
          <?php echo esc_html( $hero['cta1_text'] ); ?>
        </a>
        <a class="ks-hero__btn ks-hero__btn--ghost" href="<?php echo esc_url( $hero['cta2_url'] ); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="6" width="18" height="13" rx="2.4"/><path d="M3 10h18"/></svg>
          <?php echo esc_html( $hero['cta2_text'] ); ?>
        </a>
      </div>
    </div>

    <a class="ks-hero__scroll" href="<?php echo esc_attr( $hero['next_anchor'] ); ?>" aria-label="رفتن به بخش بعدی">
      <span class="ks-hero__scroll-dot"></span>
    </a>

  </div>
</section>

<script id="ks-hero-js">
(function(){
  // ویدیو: با کاهش‌حرکت پخش نشود
  var v=document.querySelector('.ks-hero__video');
  if(v && window.matchMedia('(prefers-reduced-motion: reduce)').matches){ v.removeAttribute('autoplay'); try{v.pause();}catch(e){} }
})();
</script>
