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
 * ویدیو: WebM برای کروم/اندروید، mp4 (H.264) برای آیفون/آیپد/سافاری.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$hero = array(
	// mp4 (H.264) برای آیفون/آیپد/سافاری؛ کروم و اندروید همان WebM را می‌گیرند (home.js انتخاب می‌کند)
	'video_mp4'   => 'https://khanehsaadat.com/wp-content/uploads/2026/10/hiro-video-saadat.mp4',
	'video_mp4_m' => '', // اختیاری: نسخهٔ سبکِ mp4 برای صفحه‌های زیر 768px
	'video_webm'  => 'https://khanehsaadat.com/wp-content/uploads/2026/10/hiro-video-saadat.webm',
	// اختیاری ولی برای سرعتِ موبایل توصیه می‌شود: نسخهٔ سبکِ عمودی/کم‌حجم (مثلاً 720px، زیر ۱ مگابایت) برای صفحه‌های زیر 768px.
	'video_webm_m' => '',
	// پوستر عمداً خالی است: بنرِ قبلی (سعادت‌پی) تا رسیدنِ ویدیو روی هیرو دیده می‌شد. اگر از فریمِ اولِ
	// خودِ ویدیو یک webp ساختید، آدرسش را اینجا بگذارید؛ تا آن موقع هیرو تیره می‌ماند تا ویدیو محو شود.
	'poster'      => '',
	'title_pre'   => 'فروشگاه اینترنتی لوازم خانگی خانه سعادت',
	'title_em'    => 'برای خانه‌ای بهتر',
	'subtitle'    => 'از سال ۱۳۵۷ کنار شما هستیم؛ کالای اصل با گارانتی رسمی، مشاوره قبل از خرید، فروش اقساطی و پشتیبانی بعد از فروش.',
	'cta1_text'   => 'خرید لوازم خانگی',
	'cta1_url'    => home_url( '/shop/' ),
	'cta2_text'   => 'شرایط خرید اقساطی',
	'cta2_url'    => home_url( '/installments/' ),
	'next_anchor' => '#home-sec-2',
);
?>

<section class="ks-hero" aria-label="معرفی خانه سعادت">
  <div class="ks-hero__frame">

    <?php // پوستر یک <img> واقعی با اولویت بالاست: مرورگر زود پیدایش می‌کند و همین LCP صفحه است. ?>
    <?php if ( $hero['poster'] ) : ?><img class="ks-hero__poster" src="<?php echo esc_url( $hero['poster'] ); ?>" alt="" fetchpriority="high" decoding="async"><?php endif; ?>
    <?php // ویدیو در HTML آدرس ندارد (data-src)؛ home.js بعد از لودِ کاملِ صفحه آن را وصل و پخش می‌کند. ?>
    <video class="ks-hero__video" muted loop playsinline preload="none" aria-hidden="true" data-ks-hero-video>
      <?php if ( $hero['video_webm'] ) : ?><source data-src="<?php echo esc_url( $hero['video_webm'] ); ?>"<?php echo $hero['video_webm_m'] ? ' data-src-m="' . esc_url( $hero['video_webm_m'] ) . '"' : ''; ?> type="video/webm"><?php endif; ?>
      <?php if ( $hero['video_mp4'] ) : ?><source data-src="<?php echo esc_url( $hero['video_mp4'] ); ?>"<?php echo $hero['video_mp4_m'] ? ' data-src-m="' . esc_url( $hero['video_mp4_m'] ) . '"' : ''; ?> type="video/mp4"><?php endif; ?>
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

      <div class="ks-hero__wordmark ks-anim" aria-hidden="true">
        <span class="ks-hero__ltr" data-l="S">S</span><span class="ks-hero__ltr" data-l="A">A</span><span class="ks-hero__ltr" data-l="A">A</span><span class="ks-hero__ltr" data-l="D">D</span><span class="ks-hero__ltr" data-l="A">A</span><span class="ks-hero__ltr" data-l="T">T</span>
      </div>

      <h1 class="ks-hero__title ks-anim">
        <?php echo esc_html( $hero['title_pre'] ); ?> <span class="ks-hero__title-tail"><span class="ks-hero__title-sep" aria-hidden="true">|</span> <span class="ks-hero__title-em"><?php echo esc_html( $hero['title_em'] ); ?></span></span>
      </h1>

      <p class="ks-hero__sub ks-anim"><?php echo esc_html( $hero['subtitle'] ); ?></p>

      <div class="ks-hero__cta ks-anim">
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

