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

get_header();
?>
<main id="content" class="site-main">
	<?php // صفحهٔ اصلی: اسکرول‌کیوی تزئینیِ هیرو حذف می‌شود؛ نوارِ «مزایا» نقشِ پلِ هیرو→محتوا را دارد (مطابق گایدلاین دیزاین). ?>
	<style>.ks-hero__scroll{display:none}</style>
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/trust' );
	get_template_part( 'template-parts/home/categories' );
	get_template_part( 'template-parts/home/sale' );
	get_template_part( 'template-parts/home/brands' );
	get_template_part( 'template-parts/home/products' );
	get_template_part( 'template-parts/home/saadat-pay' );
	get_template_part( 'template-parts/home/showroom' );
	// سکشن‌های بعدی صفحهٔ اصلی اینجا اضافه می‌شوند…
	?>
</main>
<?php
get_footer();
