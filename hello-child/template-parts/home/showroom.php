<?php
/**
 * سکشن «ویدیوی شوروم» — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/showroom.php
 *
 * بک‌گراند سفید، ارتفاع ۸۰٪ صفحه. فاصلهٔ بالا ۲ برابرِ معمول. کانتینرِ ویدیو کوچک (≈نصف)، لبه‌تیز.
 * ⚡ پرفورمنس: هیچ بایتی از ویدیو هنگام لودِ صفحه گرفته نمی‌شود — کارت یک «پلیسهولدرِ CSS» است؛
 *    ویدیو فقط با کلیک، داخلِ مودال و با `preload="none"` لود و پخش می‌شود (نه autoplay).
 * ورودِ «باابهت» با IntersectionObserver.
 *
 * پس‌زمینه: نمای هواییِ همدان، حالتِ لایت — زیرِ لایهٔ سفیدِ پررنگ، پس بیشترِ سطح سفید می‌ماند.
 *   - مرکز (پشتِ کارتِ ویدیو) سفیدتر است و لبه‌ها کمی از شهر را نشان می‌دهند؛
 *     بالا و پایین به سفید محو می‌شود تا کنارِ سکشن‌های همسایه خطِ تیز نیفتد.
 *   - فایل‌ها در رسانه‌های وردپرس (uploads/2026/10): نسخهٔ ۱۶۰۰px (۷۹KB) و ۹۶۰px برای موبایل (۴۳KB)، webp.
 *     کمی تار شده‌اند؛ زیرِ لایهٔ سفید دیده نمی‌شود و حجم را نصف می‌کند.
 *   - ⚡ مثلِ ویدیو، تصویر هم هنگامِ لودِ صفحه گرفته نمی‌شود: <img loading="lazy"> بومیِ مرورگر
 *     است، پس بدون JS هم کار می‌کند. <picture> روی موبایل (≤۷۶۸px) نسخهٔ ۹۶۰ را اجبار می‌کند؛
 *     srcset به‌تنهایی روی گوشی‌های رتینا باز هم نسخهٔ ۱۶۰۰ را می‌گرفت.
 *
 * تامنیلِ ویدیو: روی کارت (lazy) و به‌عنوانِ poster ویدیوی مودال، تا قبل از پخش صفحهٔ سیاه دیده نشود.
 *   اگر 'poster' خالی بماند، همان پلیسهولدرِ CSS برمی‌گردد.
 *
 * ورودِ کارت: کارت فقط وقتی پنهان می‌شود که JS کلاسِ ks-show--anim را بگذارد (همان الگوی سکشن مزایا)؛
 * بدون JS کارت از اول دیده می‌شود، نه اینکه برای همیشه opacity:0 بماند.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_show = array(
	'video' => 'https://khanehsaadat.com/wp-content/uploads/2026/10/showroom-saadat.webm',
	'title' => 'شوروم خانه سعادت در همدان',
	'sub'   => 'بزرگ‌ترین شوروم لوازم خانگی غرب کشور؛ قبل از خرید، کالا را از نزدیک ببینید.',
	'bg'     => 'https://khanehsaadat.com/wp-content/uploads/2026/10/showroom-bg-hamedan.webp',
	'bg_sm'  => 'https://khanehsaadat.com/wp-content/uploads/2026/10/showroom-bg-hamedan-960.webp',
	'poster' => 'https://khanehsaadat.com/wp-content/uploads/2026/10/تامنیل-ویدیو-.webp',
);
?>

<section class="ks-show" id="home-sec-8" aria-label="ویدیوی شوروم خانه سعادت">
  <picture>
    <source media="(max-width:768px)" srcset="<?php echo esc_url( $ks_show['bg_sm'] ); ?>" width="960" height="410">
    <img class="ks-show__bg" src="<?php echo esc_url( $ks_show['bg'] ); ?>" width="1600" height="682" alt="" loading="lazy" decoding="async">
  </picture>
  <span class="ks-show__veil" aria-hidden="true"></span>
  <div class="ks-show__in">
    <div class="ks-show__frame<?php echo $ks_show['poster'] ? ' has-poster' : ''; ?>" data-ks-show role="button" tabindex="0" aria-label="پخش ویدیو: <?php echo esc_attr( $ks_show['title'] ); ?>">
      <?php if ( $ks_show['poster'] ) : ?>
        <img class="ks-show__poster" src="<?php echo esc_url( $ks_show['poster'] ); ?>" width="1280" height="720" alt="" loading="lazy" decoding="async">
      <?php endif; ?>
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
      <video class="ks-show__modal-video" data-ks-show-modal-video controls playsinline preload="none"<?php if ( $ks_show['poster'] ) : ?> poster="<?php echo esc_url( $ks_show['poster'] ); ?>"<?php endif; ?>>
        <source src="<?php echo esc_url( $ks_show['video'] ); ?>" type="video/webm">
      </video>
    </div>
  </div>
</section>

