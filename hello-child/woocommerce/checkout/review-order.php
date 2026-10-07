<?php
/**
 * خلاصهٔ سفارش در تسویه حساب — خانه سعادت (بازنویسیِ checkout/review-order.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/checkout/review-order.php
 *
 * ووکامرس این جدول را بعد از هر تغییر (آدرس، روش ارسال، کد تخفیف) با Ajax و از روی کلاسِ
 * woocommerce-checkout-review-order-table عوض می‌کند؛ پس کلاس و همهٔ هوک‌ها/ردیف‌ها حفظ شده‌اند.
 * اضافه‌ها: عکسِ کوچکِ هر کالا و ردیفِ «سود شما از این خرید».
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/cart.php';
$ks_a = ks_cart_amounts();
?>
<table class="shop_table woocommerce-checkout-review-order-table ks-sum__table ks-co__review">
	<thead class="ks-co__sr">
		<tr>
			<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
			<th class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
		</tr>
	</thead>
	<tbody class="ks-co__items">
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>
				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> ks-co__item">
					<td class="product-name">
						<span class="ks-co__thumb"><?php echo $_product->get_image( 'woocommerce_gallery_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="ks-co__iname">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?>
							<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', ks_fa_digits( $cart_item['quantity'] ) ) . '</strong>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</span>
					</td>
					<td class="product-total">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</tbody>
	<tfoot>

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
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
					<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<th><?php echo esc_html( $tax->label ); ?></th>
						<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
					<td><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<tr class="order-total">
			<th>مبلغ قابل پرداخت</th>
			<td><?php wc_cart_totals_order_total_html(); ?></td>
		</tr>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		<?php if ( $ks_a['total_save'] > 0 ) : ?>
			<tr class="ks-co__profit">
				<th><?php echo ks_cart_icon( 'percent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>سود شما از این خرید</th>
				<td><?php echo wp_kses_post( wc_price( $ks_a['total_save'] ) ); ?><?php echo $ks_a['save_pct'] > 0 ? ' <em>(' . esc_html( ks_fa_digits( $ks_a['save_pct'] ) ) . '٪)</em>' : ''; ?></td>
			</tr>
		<?php endif; ?>

	</tfoot>
</table>
