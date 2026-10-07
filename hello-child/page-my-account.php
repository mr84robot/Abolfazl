<?php
/**
 * Template Name: حساب کاربری (خانه سعادت)
 * Template Post Type: page
 *
 * صفحهٔ حساب کاربری — خانه سعادت (پیشخوان، سفارش‌ها، آدرس‌ها، جزئیات حساب و ورود)
 * محل نصب:  wp-content/themes/hello-child/page-my-account.php
 *
 * کدام برگه از این فایل استفاده می‌کند؟
 *   - اگر نامکِ برگهٔ حساب کاربری «my-account» باشد (آدرس /my-account/)، وردپرس خودش همین فایل را برمی‌دارد.
 *   - در غیر این صورت (یا اگر برگه با المنتور «تمام‌عرض» ساخته شده)، در ویرایشِ برگه
 *     از کادرِ «قالب» گزینهٔ «حساب کاربری (خانه سعادت)» را انتخاب و به‌روزرسانی کنید.
 *
 * محتوای خودِ برگه نادیده گرفته می‌شود و حساب کاربریِ ووکامرس ([woocommerce_my_account]) رندر می‌شود؛
 * پس همهٔ بخش‌ها، بخش‌هایی که افزونه‌ها اضافه می‌کنند، ذخیرهٔ فرم‌ها و ورود/ثبت‌نام همان کارکردِ ووکامرس را دارند.
 * ظاهر از قالب‌های woocommerce/myaccount/ و استایل از cart.css (پایهٔ مشترک) + account.css.
 * فرمِ ورود (form-login.php) بازنویسی نشده تا ورودِ پیامکی/افزونه‌ها دست نخورد؛ فقط استایل گرفته.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>
<main id="content" class="site-main ks-cart ks-acc<?php echo is_user_logged_in() ? '' : ' ks-acc--guest'; ?>">
	<div class="ks-cart__in">
		<?php
		if ( function_exists( 'WC' ) && shortcode_exists( 'woocommerce_my_account' ) ) {
			echo do_shortcode( '[woocommerce_my_account]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — خروجیِ خودِ ووکامرس
		} else {
			echo '<p class="ks-cart__nowc">فروشگاه در حال حاضر در دسترس نیست.</p>';
		}
		?>
	</div>
</main>
<?php
get_footer();
