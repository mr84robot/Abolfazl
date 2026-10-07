<?php
/**
 * مشخصاتِ گیرنده — خانه سعادت (بازنویسیِ checkout/form-billing.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/checkout/form-billing.php
 *
 * فیلدها همان‌هایی هستند که ووکامرس (و افزونه‌ها با فیلترِ woocommerce_checkout_fields) تعریف کرده‌اند؛
 * با همان woocommerce_form_field چاپ می‌شوند، فقط داخلِ کارتی با تیتر و آیکون.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 * @global WC_Checkout $checkout
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/cart.php';
?>
<div class="woocommerce-billing-fields ks-co__card">
	<h3 class="ks-co__h"><?php echo ks_cart_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo ( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() ) ? 'مشخصات و آدرس گیرنده' : 'مشخصات خریدار'; ?></span></h3>

	<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>

	<div class="woocommerce-billing-fields__field-wrapper ks-co__fields">
		<?php
		$fields = $checkout->get_checkout_fields( 'billing' );

		foreach ( $fields as $key => $field ) {
			woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
		}
		?>
	</div>

	<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
</div>

<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
	<div class="woocommerce-account-fields ks-co__card ks-co__card--account">
		<?php if ( ! $checkout->is_registration_required() ) : ?>

			<p class="form-row form-row-wide create-account">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span>ساخت حساب کاربری (برای پیگیری سفارش و خریدهای بعدی)</span>
				</label>
			</p>

		<?php endif; ?>

		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

		<?php if ( $checkout->get_checkout_fields( 'account' ) ) : ?>

			<div class="create-account ks-co__fields">
				<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : ?>
					<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
				<div class="clear"></div>
			</div>

		<?php endif; ?>

		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
	</div>
<?php endif; ?>
