<?php
/**
 * کد تخفیف در تسویه حساب — خانه سعادت (بازنویسیِ checkout/form-coupon.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/checkout/form-coupon.php
 *
 * لینکِ a.showcoupon و form.checkout_coupon همان‌هایی هستند که checkout.jsِ ووکامرس باز می‌کند و با Ajax
 * ثبت می‌کند. فرم به‌جای style="display:none" با CSS پنهان است (CSS درون‌خطی نداریم).
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

require_once get_stylesheet_directory() . '/inc/cart.php';
?>
<div class="woocommerce-form-coupon-toggle ks-co__coupon-toggle">
	<?php echo ks_cart_icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<span><?php echo wp_kses_post( apply_filters( 'woocommerce_checkout_coupon_message', 'کد تخفیف دارید؟ <a href="#" role="button" aria-label="وارد کردن کد تخفیف" aria-controls="woocommerce-checkout-form-coupon" aria-expanded="false" class="showcoupon">برای وارد کردن کد اینجا بزنید</a>' ) ); ?></span>
</div>

<form class="checkout_coupon woocommerce-form-coupon ks-co__coupon" method="post" id="woocommerce-checkout-form-coupon">
	<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
	<div class="ks-coupon__f">
		<input type="text" name="coupon_code" class="input-text ks-coupon__in" placeholder="کد تخفیف را وارد کنید" id="coupon_code" value="" autocomplete="off" />
		<button type="submit" class="button ks-coupon__btn" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">ثبت کد</button>
	</div>
</form>
