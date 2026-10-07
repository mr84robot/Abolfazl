<?php
/**
 * Template Name: تسویه حساب (خانه سعادت)
 * Template Post Type: page
 *
 * صفحهٔ تسویه حساب — خانه سعادت (همچنین «سفارش دریافت شد» بعد از پرداخت)
 * محل نصب:  wp-content/themes/hello-child/page-checkout.php
 *
 * کدام برگه از این فایل استفاده می‌کند؟
 *   - اگر نامکِ برگهٔ تسویه حساب «checkout» باشد (آدرس /checkout/)، وردپرس خودش همین فایل را برمی‌دارد.
 *   - در غیر این صورت (یا اگر برگه با المنتور «تمام‌عرض» ساخته شده)، در ویرایشِ برگهٔ تسویه حساب
 *     از کادرِ «قالب» گزینهٔ «تسویه حساب (خانه سعادت)» را انتخاب و به‌روزرسانی کنید.
 *
 * محتوای خودِ برگه (المنتور یا بلوکِ تسویه حساب) نادیده گرفته می‌شود و تسویه حسابِ کلاسیکِ ووکامرس
 * ([woocommerce_checkout]) رندر می‌شود؛ پس فیلدها (و فیلدهایی که افزونه‌ها اضافه می‌کنند)، درگاه‌های
 * پرداخت، کد تخفیف، روش ارسال و اعتبارسنجی همگی همان کارکردِ خودِ ووکامرس را دارند.
 * ظاهر از قالب‌های woocommerce/checkout/ و استایل از cart.css (پایهٔ مشترک) + checkout.css.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>
<main id="content" class="site-main ks-cart ks-co">
	<div class="ks-cart__in">
		<?php
		require_once get_stylesheet_directory() . '/inc/cart.php';
		ks_setup_warning();
		if ( function_exists( 'WC' ) && shortcode_exists( 'woocommerce_checkout' ) ) {
			echo do_shortcode( '[woocommerce_checkout]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — خروجیِ خودِ ووکامرس
		} else {
			echo '<p class="ks-cart__nowc">فروشگاه در حال حاضر در دسترس نیست.</p>';
		}
		?>
	</div>
</main>
<?php
get_footer();
