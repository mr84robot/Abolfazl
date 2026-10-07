<?php
/**
 * خلاصهٔ سبد خرید — خانه سعادت (بازنویسیِ cart/cart-totals.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/cart/cart-totals.php
 *
 * همهٔ ردیف‌ها و هوک‌های قالبِ اصلی (کوپن، ارسال، هزینه‌ها، مالیات، جمعِ کل، دکمهٔ ادامه) حفظ شده‌اند؛
 * cart.jsِ ووکامرس بعد از هر تغییر همین div.cart_totals را با نسخهٔ تازه جایگزین می‌کند.
 * اضافه‌ها: «قیمت کالاها» پیش از تخفیف، «تخفیف کالاها» و «سود شما از این خرید».
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/cart.php';
$ks_a = ks_cart_amounts();
?>
<div class="cart_totals ks-sum <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2 class="ks-sum__t">خلاصهٔ سفارش</h2>

	<table cellspacing="0" class="shop_table ks-sum__table">

		<?php if ( $ks_a['items_save'] > 0 ) : ?>
			<tr class="ks-sum__regular">
				<th>قیمت کالاها (<?php echo esc_html( ks_fa_digits( WC()->cart->get_cart_contents_count() ) ); ?>)</th>
				<td><?php echo wp_kses_post( wc_price( $ks_a['regular'] ) ); ?></td>
			</tr>
			<tr class="ks-sum__save">
				<th>تخفیف کالاها</th>
				<td><?php echo wp_kses_post( wc_price( $ks_a['items_save'] ) ); ?></td>
			</tr>
		<?php endif; ?>

		<tr class="cart-subtotal">
			<th><?php echo $ks_a['items_save'] > 0 ? 'جمع کالاها' : esc_html( 'قیمت کالاها (' . ks_fa_digits( WC()->cart->get_cart_contents_count() ) . ')' ); ?></th>
			<td data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<th><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></th>
				<td data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php elseif ( WC()->cart->needs_shipping() ) : ?>

			<tr class="shipping ks-sum__ship-later">
				<th>هزینهٔ ارسال</th>
				<td>در مرحلهٔ بعد محاسبه می‌شود</td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
						<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
					<?php
				}
			} else {
				?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></th>
					<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

		<tr class="order-total">
			<th>مبلغ قابل پرداخت</th>
			<td data-title="<?php esc_attr_e( 'Total', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	</table>

	<?php if ( $ks_a['total_save'] > 0 ) : ?>
		<p class="ks-sum__profit"><?php echo ks_cart_icon( 'percent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>سود شما از این خرید</span><strong><?php echo wp_kses_post( wc_price( $ks_a['total_save'] ) ); ?><?php echo $ks_a['save_pct'] > 0 ? ' <em>(' . esc_html( ks_fa_digits( $ks_a['save_pct'] ) ) . '٪)</em>' : ''; ?></strong></p>
	<?php endif; ?>

	<div class="wc-proceed-to-checkout">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>

	<p class="ks-sum__note">کالاهای سبد هنوز برای شما رزرو نشده‌اند؛ برای ثبت سفارش، مراحلِ بعد را کامل کنید.</p>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
