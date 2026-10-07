<?php
/**
 * صفحهٔ اصلی (Front Page) — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/front-page.php
 *
 * چون Front page سایت یک صفحهٔ ثابت است، وردپرس این فایل را به‌جای
 * محتوای المنتورِ صفحهٔ ۶۱ لود می‌کند → باندل المنتور از هوم حذف می‌شود.
 * هر سکشن یک partial در template-parts/home/ است (همین «یک فایلِ» منسجم).
 *
 * ⚠️ دو کار قبل از زنده‌شدن:
 *   ۱) فونت Yekan Bakh FaNum را با @font-face در قالب فرزند مستقل کن
 *      (الان از المنتور پرو می‌آید و با حذف المنتور از هوم ممکن است بیفتد).
 *   ۲) روی همین صفحه، هدر سراسری (.ks-hdr) را «قبل از اسکرول مخفی، با اسکرول ظاهر» کن.
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
	get_template_part( 'template-parts/home/showroom' );
	get_template_part( 'template-parts/home/blog' );
	get_template_part( 'template-parts/home/seo-guide' );
	get_template_part( 'template-parts/home/videos' );
	// سکشن‌های بعدی صفحهٔ اصلی اینجا اضافه می‌شوند…
	?>
</main>
<?php
get_footer();
