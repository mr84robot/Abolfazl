<?php
/**
 * تسویه حساب — خانه سعادت (بازنویسیِ checkout/form-checkout.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/checkout/form-checkout.php
 *
 * همهٔ هوک‌های قالبِ اصلی سرِ جایشان است و شناسه/کلاس‌هایی که checkout.jsِ ووکامرس لازم دارد حفظ شده:
 *   form.checkout.woocommerce-checkout ، #customer_details ، #order_review ، #payment ، #place_order
 * فقط چیدمان عوض شده: ستونِ راست مشخصاتِ گیرنده و ارسال، ستونِ چپ خلاصهٔ سفارش و پرداخت.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/cart.php';

ks_cart_steps( 2 );

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo '<div class="ks-co__login-required">' . esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) ) . '</div>';
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout ks-co__form" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

	<div class="ks-co__grid">

		<div class="ks-co__main">
			<?php if ( $checkout->get_checkout_fields() ) : ?>

				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

				<div class="ks-co__details" id="customer_details">
					<div class="ks-co__billing">
						<?php do_action( 'woocommerce_checkout_billing' ); ?>
					</div>

					<div class="ks-co__shipping">
						<?php do_action( 'woocommerce_checkout_shipping' ); ?>
					</div>
				</div>

				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<?php endif; ?>
		</div>

		<div class="ks-co__side">
			<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

			<div class="ks-co__review-head">
				<h3 id="order_review_heading" class="ks-co__h"><?php echo ks_cart_icon( 'bag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>سفارش شما</span></h3>
				<a class="ks-co__edit" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php echo ks_cart_icon( 'edit' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>ویرایش سبد</span></a>
			</div>

			<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>

			<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

			<?php ks_cart_trust(); ?>
		</div>

	</div>

</form>

<?php // موبایل: نوارِ چسبانِ پایینِ صفحه؛ مبلغ و متنِ دکمه با هر به‌روزرسانیِ ووکامرس از خلاصهٔ سفارش و #place_order خوانده می‌شود (checkout.js) ?>
<div class="ks-co-bar" data-ks-co-bar>
	<div class="ks-co-bar__sum"><span>مبلغ قابل پرداخت</span><strong data-ks-co-bar-total><?php echo wp_kses_post( WC()->cart->get_total() ); ?></strong></div>
	<button type="button" class="ks-co-bar__btn" data-ks-co-bar-go>ثبت سفارش</button>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
