<?php
/**
 * هدر کدنویسی‌شدهٔ خانه سعادت  (جایگزین تمپلیت هدر المنتور #8843)
 * ---------------------------------------------------------------
 * محل نصب:  wp-content/themes/hello-child/header.php
 *
 * چیدمان:
 *   - موبایل (≤۷۶۷):   نوار [تماس] [لوگو] [جستجو] + اورلی جستجو.
 *   - آی‌پد (۷۶۸–۱۰۲۴): ردیف اصلی (لوگو + جستجو + حساب + سبد) و نوار پایین (مگامنو + لینک‌ها، اسکرول افقی).
 *   - دسکتاپ (≥۱۰۲۵):  ردیف اصلی + نوار پایین کامل، با انیمیشن.
 *
 * مگامنو و جستجو از خود CodeLock ([codelock_mega_menu]/[codelock_search_form]).
 * سبد خرید بازشو = مینی‌کارت ووکامرس (با Ajax fragments خودِ ووکامرس به‌روز می‌شود).
 *
 * برای خروجِ کاملِ هدر از المنتور، تمپلیت هدر #8843 را Draft/حذف کن.
 *
 * استایل و اسکریپت در فایل‌های جدا هستند (هیچ CSS/JSِ درون‌خطی نمانده):
 *   assets/css/site.css  ← در <head> (هدر + فوتر)
 *   assets/js/site.js    ← در فوتر با defer
 * اولویت ۹۹۹: قبلاً CSSِ هدر داخلِ body و بعد از استایلِ قالب/المنتور می‌آمد؛ این ترتیب حفظ می‌شود.
 * روی صفحهٔ اصلی، home.css هدر را بالای هیرو پنهان می‌کند و با اسکرول نشانش می‌دهد.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_logo        = 'https://khanehsaadat.com/wp-content/uploads/2026/05/logo.png'; // 372×80
$ks_phone       = '+989001118364';
$ks_phone_label = '۰۹۰۰۱۱۱۸۳۶۴';
$ks_cart_url    = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$ks_account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
$ks_cart_count  = ( function_exists( 'WC' ) && WC() && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
$ks_nav         = array(
	'فروشگاه'     => home_url( '/shop/' ),
	'وبلاگ سعادت' => home_url( '/blog/' ),
	'خرید اقساطی' => home_url( '/installments/' ),
	'ویدیوها'     => home_url( '/videos/' ),
	'درباره ما'   => home_url( '/about-us/' ),
	'تماس با ما'  => home_url( '/contact-us/' ),
);

if ( ! function_exists( 'hello_elementor_display_header_footer' ) || hello_elementor_display_header_footer() ) {
	// باید قبل از wp_head() صف شود؛ wp_head همین پایین در همین فایل صدا زده می‌شود.
	add_action( 'wp_enqueue_scripts', function () {
		$dir = get_stylesheet_directory();
		$uri = get_stylesheet_directory_uri();
		wp_enqueue_style( 'ks-site', $uri . '/assets/css/site.css', array(), (string) @filemtime( $dir . '/assets/css/site.css' ) );
		wp_enqueue_script( 'ks-site', $uri . '/assets/js/site.js', array(), (string) @filemtime( $dir . '/assets/js/site.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}, 999 );
}
// صفحهٔ سبد خرید: cart.css / cart.js فقط همین‌جا لود می‌شوند (ظاهرِ قالب‌های woocommerce/cart/)
if ( function_exists( 'is_cart' ) && is_cart() ) {
	add_action( 'wp_enqueue_scripts', function () {
		$dir = get_stylesheet_directory();
		$uri = get_stylesheet_directory_uri();
		wp_enqueue_style( 'ks-cart', $uri . '/assets/css/cart.css', array(), (string) @filemtime( $dir . '/assets/css/cart.css' ) );
		wp_enqueue_script( 'ks-cart', $uri . '/assets/js/cart.js', array(), (string) @filemtime( $dir . '/assets/js/cart.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	}, 999 );
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#content">پرش به محتوا</a>

<?php if ( ! function_exists( 'hello_elementor_display_header_footer' ) || hello_elementor_display_header_footer() ) : ?>

<header class="ks-hdr" data-ks-hdr role="banner">

  <svg class="ks-hdr__sprite" width="0" height="0" aria-hidden="true" focusable="false">
    <symbol id="ks-i-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6.5 4.2h3l1.3 3.4-2 1.4a11 11 0 0 0 5.2 5.2l1.4-2 3.4 1.3v3a1.6 1.6 0 0 1-1.8 1.6C10.6 17.6 6.4 13.4 5 6.1a1.6 1.6 0 0 1 1.5-1.9z"/></symbol>
    <symbol id="ks-i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-3.6-3.6"/></symbol>
    <symbol id="ks-i-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m17.5 6.5-11 11"/><path d="m6.5 6.5 11 11"/></symbol>
    <symbol id="ks-i-user" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.6"/><path d="M4.8 20.5v-.8a5.2 5.2 0 0 1 5.2-5.2h4a5.2 5.2 0 0 1 5.2 5.2v.8"/></symbol>
    <symbol id="ks-i-cart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7.5 3.5 4.8 7.2V19a1.8 1.8 0 0 0 1.8 1.8h10.8A1.8 1.8 0 0 0 19.2 19V7.2l-2.7-3.7z"/><path d="M4.8 7.2h14.4"/><path d="M15.4 10.8a3.4 3.4 0 0 1-6.8 0"/></symbol>
  </svg>

  <!-- موبایل -->
  <div class="ks-mh">
    <a class="ks-mh__btn" href="tel:<?php echo esc_attr( $ks_phone ); ?>" aria-label="تماس با فروشگاه">
      <svg aria-hidden="true"><use href="#ks-i-phone"></use></svg>
    </a>
    <a class="ks-mh__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="خانه سعادت">
      <img src="<?php echo esc_url( $ks_logo ); ?>" width="372" height="80" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" decoding="async">
    </a>
    <button type="button" class="ks-mh__btn" data-ks-search-open aria-label="جستجو" aria-controls="ks-search-overlay" aria-expanded="false">
      <svg aria-hidden="true"><use href="#ks-i-search"></use></svg>
    </button>
  </div>

  <!-- دسکتاپ/تبلت: ردیف اصلی -->
  <div class="ks-hdr__bar">
    <div class="ks-hdr__inner">
      <a class="ks-hdr__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="خانه سعادت">
        <img src="<?php echo esc_url( $ks_logo ); ?>" width="372" height="80" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" decoding="async">
      </a>

      <div class="ks-hdr__search"><?php echo do_shortcode( '[codelock_search_form show_button="yes" max_width="720px"]' ); ?></div>

      <div class="ks-hdr__actions">
        <a class="ks-hdr__icon" href="<?php echo esc_url( $ks_account_url ); ?>" aria-label="حساب کاربری">
          <svg aria-hidden="true"><use href="#ks-i-user"></use></svg><span>حساب</span>
        </a>

        <div class="ks-hdr__cart-wrap">
          <a class="ks-hdr__icon ks-hdr__cart" href="<?php echo esc_url( $ks_cart_url ); ?>" aria-label="سبد خرید" aria-haspopup="true">
            <svg aria-hidden="true"><use href="#ks-i-cart"></use></svg><span>سبد خرید</span>
            <span class="ks-hdr__cart-badge<?php echo $ks_cart_count > 0 ? '' : ' is-empty'; ?>"><?php echo esc_html( number_format_i18n( $ks_cart_count ) ); ?></span>
          </a>
          <div class="ks-hdr__cart-dd" role="dialog" aria-label="محتوای سبد خرید">
            <div class="widget_shopping_cart_content">
              <?php if ( function_exists( 'woocommerce_mini_cart' ) ) { woocommerce_mini_cart(); } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- دسکتاپ/تبلت: نوار پایین (مگامنو + لینک‌ها) -->
  <nav class="ks-hdr__nav" aria-label="منوی اصلی">
    <div class="ks-hdr__inner">
      <div class="ks-hdr__mega"><?php echo do_shortcode( '[codelock_mega_menu]' ); ?></div>
      <div class="ks-hdr__links-scroll">
        <ul class="ks-hdr__links">
          <?php foreach ( $ks_nav as $label => $url ) : ?>
            <li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <a class="ks-hdr__phone" href="tel:<?php echo esc_attr( $ks_phone ); ?>">
        <span class="ks-hdr__phone-ic"><svg aria-hidden="true"><use href="#ks-i-phone"></use></svg></span>
        <span class="ks-hdr__phone-txt">پشتیبانی: <bdi><?php echo esc_html( $ks_phone_label ); ?></bdi></span>
      </a>
    </div>
  </nav>
</header>

<!-- اورلی جستجوی موبایل -->
<div class="ks-mh-overlay" id="ks-search-overlay" data-ks-search hidden role="dialog" aria-modal="true" aria-label="جستجو در محصولات">
  <div class="ks-mh-overlay__head">
    <button type="button" data-ks-search-close aria-label="بستن جستجو">
      <svg aria-hidden="true" width="20" height="20"><use href="#ks-i-close"></use></svg>
    </button>
    <span class="ks-mh-overlay__title">جستجو در محصولات</span>
  </div>
  <div class="ks-mh-overlay__body">
    <p class="ks-mh-overlay__lead">نام محصول، برند یا دسته‌بندی را بنویس تا سریع پیدایش کنیم.</p>
    <?php echo do_shortcode( '[codelock_search_form max_width="100%"]' ); ?>
  </div>
</div>

<?php endif; ?>
