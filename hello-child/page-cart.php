<?php
/**
 * Template Name: سبد خرید (خانه سعادت)
 * Template Post Type: page
 *
 * صفحهٔ سبد خرید — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/page-cart.php
 *
 * کدام برگه از این فایل استفاده می‌کند؟
 *   - اگر نامکِ برگهٔ سبد خرید «cart» باشد (آدرس /cart/)، وردپرس خودش همین فایل را برمی‌دارد.
 *   - در غیر این صورت (یا اگر برگه با المنتور «تمام‌عرض» ساخته شده)، در ویرایشِ برگهٔ سبد خرید
 *     از کادرِ «قالب» گزینهٔ «سبد خرید (خانه سعادت)» را انتخاب و به‌روزرسانی کنید.
 *
 * محتوای خودِ برگه (المنتور یا بلوکِ سبد خرید) نادیده گرفته می‌شود و سبدِ کلاسیکِ ووکامرس
 * ([woocommerce_cart]) رندر می‌شود؛ پس همهٔ کارکردهای خودِ ووکامرس سرِ جایش است: تغییرِ تعداد
 * و حذف با Ajax، کد تخفیف، روش ارسال، هوک‌های افزونه‌ها. ظاهر از قالب‌های بازنویسی‌شده در
 * woocommerce/cart/ می‌آید و استایل/اسکریپت از assets/css/cart.css و assets/js/cart.js
 * (در header.php فقط روی صفحهٔ سبد خرید لود می‌شوند؛ هیچ CSS/JSِ درون‌خطی نیست).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>
<main id="content" class="site-main ks-cart">
	<div class="ks-cart__in">
		<?php
		require_once get_stylesheet_directory() . '/inc/cart.php';
		ks_setup_warning();
		if ( function_exists( 'WC' ) && shortcode_exists( 'woocommerce_cart' ) ) {
			echo do_shortcode( '[woocommerce_cart]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — خروجیِ خودِ ووکامرس
		} else {
			echo '<p class="ks-cart__nowc">فروشگاه در حال حاضر در دسترس نیست.</p>';
		}
		?>
	</div>
</main>
<?php
get_footer();
