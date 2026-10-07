<?php
/**
 * صفحهٔ اصلی (Front Page) — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/front-page.php
 *
 * چون Front page سایت یک صفحهٔ ثابت است، وردپرس این فایل را به‌جای
 * محتوای المنتورِ صفحهٔ ۶۱ لود می‌کند → باندل المنتور از هوم حذف می‌شود.
 * هر سکشن یک partial در template-parts/home/ است (همین «یک فایلِ» منسجم).
 *
 * فونت: Yekan Bakh FaNum عمداً از همان المنتور پرو می‌آید و در قالب میزبانی نمی‌شود.
 *   چون این صفحه دیگر محتوای المنتور ندارد، بعد از آپلود یک بار چک شود که فونت روی هوم
 *   واقعاً لود شده (DevTools ← Computed ← font-family روی یک تیتر). اگر نشده بود، فونت از
 *   هدر/فوتر یا تنظیمات سراسریِ المنتور می‌آمده و باید آن‌جا روی همهٔ صفحه‌ها فعال شود.
 *   اگر فونت نیاید، متن با Vazirmatn/فونتِ سیستم نمایش داده می‌شود؛ صفحه نمی‌شکند.
 *
 * ⚠️ قبل از زنده‌شدن: روی همین صفحه، هدر سراسری (.ks-hdr) را «قبل از اسکرول مخفی، با اسکرول ظاهر» کن.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/*
 * استایل و اسکریپتِ همهٔ سکشن‌ها در دو فایل است (هیچ CSS/JSِ درون‌خطی نمانده):
 *   assets/css/home.css  ← در <head>
 *   assets/js/home.js    ← در فوتر با defer
 * نسخه = زمانِ تغییرِ فایل؛ پس بعد از هر آپلود، کشِ مرورگر خودکار تازه می‌شود.
 *
 * اولویت ۹۹۹: home.css باید آخرین استایلِ <head> باشد. قبلاً CSSِ سکشن‌ها داخلِ body و بعد از
 * استایلِ قالب/المنتور می‌آمد و در تساویِ specificity برنده بود (مثلاً در برابرِ [type=button]:hover
 * قالبِ Hello)؛ این ترتیب باید حفظ شود وگرنه دکمه‌ها روی سایت عوض می‌شوند.
 */
add_action( 'wp_enqueue_scripts', function () {
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();
	wp_enqueue_style( 'ks-home', $uri . '/assets/css/home.css', array(), (string) @filemtime( $dir . '/assets/css/home.css' ) );
	wp_enqueue_script( 'ks-home', $uri . '/assets/js/home.js', array(), (string) @filemtime( $dir . '/assets/js/home.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
}, 999 );

/*
 * فایل‌هایی که روی صفحهٔ اصلی لود می‌شدند ولی این صفحه هیچ استفاده‌ای از آن‌ها نمی‌کند.
 *
 * علتِ اصلی: محتوای قدیمیِ المنتورِ برگهٔ ۶۱ هنوز در دیتابیس ذخیره است. این فایل جای آن را گرفته و آن را
 * نمایش نمی‌دهد، ولی المنتور (CSSِ برگه + ویجت‌ها + Swiper + اسکریپت‌های فرانت) و افزونهٔ کدلاک
 * (اسلایدر، کاروسل‌ها، بلاگ، فهرست مطالب) با دیدنِ شورت‌کدهای همان محتوای قدیمی، فایل‌هایشان را صف می‌کنند.
 * فیلترِ فروشگاه فقط برای صفحه‌های محصولات است و اسکریپت‌های ورود با موبایل فقط برای صفحهٔ ورود/حساب.
 *
 * اگر بعداً چیزی روی صفحهٔ اصلی به یکی از این‌ها نیاز داشت، فقط همان خط را از فهرست حذف کنید.
 * (CSSِ پایهٔ المنتور و کیتِ سایت — elementor-frontend و elementor-post-6 — عمداً می‌مانند چون فونت از آن‌جا می‌آید.)
 */
$ks_trim = function () {
	$styles = array(
		'elementor-post-61', 'widget-image', 'widget-heading', 'widget-nested-carousel', 'widget-divider', 'widget-icon-list', 'widget-image-box', 'swiper', 'e-swiper', 'base-desktop', 'base-mobile', // محتوای قدیمیِ المنتور
		'codelock-hero-slider', 'codelock-home-carousel', 'codelock-carousel', 'codelock-post-carousel', 'codelock-blog', 'codelock-toc', 'codelock-featured', // ماژول‌های کدلاک که این صفحه ندارد
		'ks-filters', 'codelock-notices', // فیلترِ صفحه‌های فروشگاه (notices به ks-filters وابسته است)
	);
	$scripts = array(
		'elementor-frontend', 'elementor-frontend-modules', 'elementor-webpack-runtime', 'elementor-pro-frontend', 'elementor-pro-webpack-runtime', 'pro-elements-handlers', 'swiper',
		'codelock-hero-slider', 'codelock-home-carousel', 'codelock-carousel', 'codelock-blog', 'codelock-toc', 'ks-filters',
		'woodmart-libphonenumber-child', 'woodmart-api-script-child', 'woodmart-login-script-child', // ورود با موبایل (کتابخانهٔ libphonenumber به‌تنهایی صدها کیلوبایت است)
	);
	foreach ( $styles as $h ) { wp_dequeue_style( $h ); }
	foreach ( $scripts as $h ) { wp_dequeue_script( $h ); }
};
// چند نوبت، چون المنتور اسکریپت‌هایش را دیرتر (در wp_footer) صف می‌کند؛ هر نوبت درست قبل از چاپ اجرا می‌شود
add_action( 'wp_enqueue_scripts', $ks_trim, 1000 );
add_action( 'wp_print_styles', $ks_trim, 1 );
add_action( 'wp_print_scripts', $ks_trim, 1 );
add_action( 'wp_print_footer_scripts', $ks_trim, 1 );

get_header();
?>
<main id="content" class="site-main">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/trust' );
	get_template_part( 'template-parts/home/categories' );
	get_template_part( 'template-parts/home/sale' );
	get_template_part( 'template-parts/home/brands' );
	get_template_part( 'template-parts/home/products' );
	get_template_part( 'template-parts/home/saadat-pay' );
	get_template_part( 'template-parts/home/blog' );
	get_template_part( 'template-parts/home/seo-guide' );
	get_template_part( 'template-parts/home/videos' );
	// سکشن‌های بعدی صفحهٔ اصلی اینجا اضافه می‌شوند…
	?>
</main>
<?php
get_footer();
