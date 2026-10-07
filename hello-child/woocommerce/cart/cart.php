<?php
/**
 * سبد خرید — خانه سعادت (بازنویسیِ قالبِ کلاسیکِ ووکامرس)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/cart/cart.php
 *
 * ساختار و همهٔ هوک‌ها/فیلترهای قالبِ اصلیِ ووکامرس حفظ شده؛ فقط چیدمان از جدول به کارت تغییر کرده.
 * کلاس‌هایی که cart.jsِ خودِ ووکامرس به آن‌ها وابسته است عمداً سرِ جایشان هستند:
 *   form.woocommerce-cart-form ، .woocommerce-cart-form__contents ، .cart_item ، input.qty ،
 *   .product-remove > a ، #coupon_code ، button[name=apply_coupon] ، button[name=update_cart]
 * پس تغییرِ تعداد، حذف، بازگردانی و کد تخفیف همگی با Ajaxِ خودِ ووکامرس کار می‌کنند.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/cart.php';

// «پیشنهادهای مکمل» از ستونِ کناری به زیرِ لیستِ کالاها می‌رود (ستونِ کناری فقط خلاصهٔ سبد است)
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
if ( ! has_action( 'woocommerce_after_cart', 'ks_cart_cross_sells' ) ) {
	add_action( 'woocommerce_after_cart', 'ks_cart_cross_sells', 5 );
}

do_action( 'woocommerce_before_cart' );

ks_cart_steps( 1 );
?>

<div class="ks-cart__grid">

<form class="woocommerce-cart-form ks-cart__form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="ks-cart__head">
		<h1 class="ks-cart__title">سبد خرید</h1>
		<span class="ks-cart__count"><?php echo esc_html( ks_fa_digits( WC()->cart->get_cart_contents_count() ) ); ?> کالا</span>
	</div>

	<div class="ks-cart__items woocommerce-cart-form__contents">
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			/**
			 * Filter the product name.
			 *
			 * @since 2.1.0
			 * @param string $product_name Name of the product in the cart.
			 * @param array $cart_item The product in the cart.
			 * @param string $cart_item_key Key for the product in the cart.
			 */
			$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				$ks_pct            = ks_cart_item_pct( $_product );
				$ks_qty            = (int) $cart_item['quantity'];
				?>
				<div class="ks-ci woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<div class="ks-ci__media product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s" tabindex="-1" aria-hidden="true">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
						?>
						<?php if ( $ks_pct > 0 ) : ?><span class="ks-ci__badge"><?php echo esc_html( ks_fa_digits( $ks_pct ) ); ?>٪</span><?php endif; ?>
					</div>

					<div class="ks-ci__body product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
						<div class="ks-ci__name">
							<?php
							if ( ! $product_permalink ) {
								echo wp_kses_post( $product_name . '&nbsp;' );
							} else {
								/**
								 * This filter is documented above.
								 *
								 * @since 2.1.0
								 */
								echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
							}
							?>
						</div>
						<?php
						do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data (مثلاً رنگ/مدلِ متغیر).
						echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
						}

						// موجودیِ کم: فقط وقتی انبارداری فعال است و ۳ عدد یا کمتر مانده
						if ( $_product->managing_stock() && ! $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							$ks_left = (int) $_product->get_stock_quantity();
							if ( $ks_left > 0 && $ks_left <= 3 ) {
								echo '<p class="ks-ci__low">' . esc_html( 'فقط ' . ks_fa_digits( $ks_left ) . ' عدد در انبار باقی مانده' ) . '</p>';
							}
						}
						?>
						<div class="ks-ci__unit product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php if ( $ks_pct > 0 && $_product->get_regular_price() ) : ?>
								<del class="ks-ci__was"><?php echo wp_kses_post( wc_price( wc_get_price_to_display( $_product, array( 'price' => (float) $_product->get_regular_price() ) ) ) ); ?></del>
							<?php endif; ?>
							<span class="ks-ci__each<?php echo $ks_qty > 1 ? '' : ' is-one'; ?>">هر عدد <?php // تعدادِ ۱ = همان مبلغِ ردیف؛ با CSS پنهان می‌شود ?><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok. ?></span>
						</div>
					</div>

					<div class="ks-ci__ctrl">
					<div class="ks-ci__qty product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$min_quantity = 1;
							$max_quantity = 1;
						} else {
							$min_quantity = 0;
							$max_quantity = $_product->get_max_purchase_quantity();
						}

						$product_quantity = woocommerce_quantity_input(
							array(
								'input_name'   => "cart[{$cart_item_key}][qty]",
								'input_value'  => $cart_item['quantity'],
								'max_value'    => $max_quantity,
								'min_value'    => $min_quantity,
								'product_name' => $product_name,
							),
							$_product,
							false
						);

						$ks_fixed = ( $min_quantity === $max_quantity ); // تک‌فروشی: تعداد قابلِ تغییر نیست
						?>
						<div class="ks-qty<?php echo $ks_fixed ? ' is-fixed' : ''; ?>">
							<?php if ( ! $ks_fixed ) : ?>
								<button type="button" class="ks-qty__btn ks-qty__btn--inc" data-ks-qty="1" aria-label="<?php echo esc_attr( 'یکی بیشتر: ' . wp_strip_all_tags( $product_name ) ); ?>"<?php echo ( $max_quantity > 0 && $ks_qty >= $max_quantity ) ? ' disabled' : ''; ?>><?php echo ks_cart_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
							<?php endif; ?>
							<?php echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok. ?>
							<?php if ( ! $ks_fixed ) : ?>
								<button type="button" class="ks-qty__btn ks-qty__btn--dec" data-ks-qty="-1" aria-label="<?php echo esc_attr( 'یکی کمتر: ' . wp_strip_all_tags( $product_name ) ); ?>"<?php echo $ks_qty <= 1 ? ' disabled' : ''; ?>><?php echo ks_cart_icon( 'minus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
							<?php else : ?>
								<span class="ks-qty__one"><?php echo esc_html( ks_fa_digits( $ks_qty ) ); ?> عدد</span>
							<?php endif; ?>
						</div>
					</div>

					<div class="ks-ci__sub product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
						<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok. ?>
					</div>
					</div>

					<div class="ks-ci__remove product-remove">
						<?php
						echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'woocommerce_cart_item_remove_link',
							sprintf(
								'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								/* translators: %s is the product name */
								esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() ),
								ks_cart_icon( 'trash' )
							),
							$cart_item_key
						);
						?>
					</div>
				</div>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_contents' ); ?>

		<div class="ks-cart__actions actions">
			<?php if ( wc_coupons_enabled() ) { ?>
				<div class="ks-coupon coupon">
					<label for="coupon_code" class="ks-coupon__l"><?php echo ks_cart_icon( 'tag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>کد تخفیف دارید؟</label>
					<div class="ks-coupon__f">
						<input type="text" name="coupon_code" class="input-text ks-coupon__in" id="coupon_code" value="" placeholder="کد تخفیف را وارد کنید" autocomplete="off" />
						<button type="submit" class="button ks-coupon__btn" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">ثبت کد</button>
					</div>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			<?php } ?>

			<button type="submit" class="button ks-cart__update" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">به‌روزرسانی سبد</button>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<aside class="cart-collaterals ks-cart__aside" aria-label="خلاصهٔ سبد خرید">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display — removed above, rendered after the cart instead
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );

		ks_cart_trust();
	?>
</aside>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
