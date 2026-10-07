<?php
/**
 * یک روشِ پرداخت — خانه سعادت (بازنویسیِ checkout/payment-method.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/checkout/payment-method.php
 *
 * فقط یک تفاوت با قالبِ اصلی: توضیحِ روش‌های انتخاب‌نشده به‌جای style="display:none" با کلاسِ
 * is-closed پنهان است (CSS درون‌خطی نداریم)؛ checkout.jsِ ووکامرس همچنان با slideDown/slideUp بازش می‌کند.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?>">
	<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

	<label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>">
		<span class="ks-pay__title"><?php echo $gateway->get_title(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?></span> <?php echo $gateway->get_icon(); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
	</label>
	<?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?><?php echo $gateway->chosen ? '' : ' is-closed'; ?>">
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
</li>
