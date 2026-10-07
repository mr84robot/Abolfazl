<?php
/**
 * دکمهٔ «ادامهٔ فرایند خرید» — خانه سعادت (بازنویسیِ cart/proceed-to-checkout-button.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/cart/proceed-to-checkout-button.php
 *
 * همان لینک و کلاس‌های اصلیِ ووکامرس (checkout-button button alt wc-forward)؛ فقط متن و آیکون.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once get_stylesheet_directory() . '/inc/cart.php';
?>

<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward ks-sum__go">
	<span>ادامهٔ فرایند خرید</span><?php echo ks_cart_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</a>
