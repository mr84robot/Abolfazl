<?php
/**
 * سفارش ثبت شد / پرداخت ناموفق — خانه سعادت (بازنویسیِ checkout/thankyou.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/checkout/thankyou.php
 *
 * کلاس‌ها و هوک‌های قالبِ اصلی حفظ شده‌اند: woocommerce_before_thankyou ، woocommerce_thankyou_{درگاه}
 * (مثلاً اطلاعاتِ حسابِ «کارت به کارت») و woocommerce_thankyou (جزئیاتِ سفارش و آدرس، با قالب‌های خودِ ووکامرس).
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 *
 * @var WC_Order $order
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/cart.php';

ks_cart_steps( $order && ! $order->has_status( 'failed' ) ? 3 : 2 );

$ks_shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
?>

<div class="woocommerce-order ks-ty">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<div class="ks-ty__hero is-failed">
				<span class="ks-ty__ic"><?php echo ks_cart_icon( 'x' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h1 class="ks-ty__t">پرداخت انجام نشد</h1>
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed ks-ty__d"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
				<p class="ks-ty__d ks-ty__d--s">اگر مبلغ از حسابتان کم شده، معمولاً تا ۷۲ ساعت به‌صورت خودکار برمی‌گردد.</p>

				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions ks-ty__actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay ks-ty__btn"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay ks-ty__btn ks-ty__btn--ghost"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</p>
			</div>

		<?php else : ?>

			<div class="ks-ty__hero">
				<span class="ks-ty__ic"><?php echo ks_cart_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<h1 class="ks-ty__t">سفارش شما ثبت شد</h1>
				<?php wc_get_template( 'checkout/order-received.php', array( 'order' => $order ) ); ?>

				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details ks-ty__facts">

					<li class="woocommerce-order-overview__order order">
						<span><?php esc_html_e( 'Order number:', 'woocommerce' ); ?></span>
						<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<li class="woocommerce-order-overview__date date">
						<span><?php esc_html_e( 'Date:', 'woocommerce' ); ?></span>
						<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
						<li class="woocommerce-order-overview__email email">
							<span><?php esc_html_e( 'Email:', 'woocommerce' ); ?></span>
							<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						</li>
					<?php endif; ?>

					<li class="woocommerce-order-overview__total total">
						<span><?php esc_html_e( 'Total:', 'woocommerce' ); ?></span>
						<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>

					<?php if ( $order->get_payment_method_title() ) : ?>
						<li class="woocommerce-order-overview__payment-method method">
							<span><?php esc_html_e( 'Payment method:', 'woocommerce' ); ?></span>
							<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</li>
					<?php endif; ?>

				</ul>

				<p class="ks-ty__actions">
					<?php if ( is_user_logged_in() && function_exists( 'wc_get_account_endpoint_url' ) ) : ?>
						<a class="button ks-ty__btn" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">پیگیری سفارش</a>
					<?php endif; ?>
					<a class="button ks-ty__btn ks-ty__btn--ghost" href="<?php echo esc_url( $ks_shop ); ?>">ادامهٔ خرید</a>
				</p>
				<a class="ks-cart-help ks-ty__help" href="tel:+989001110562"><?php echo ks_cart_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>پرسشی دربارهٔ سفارش دارید؟ <strong>۰۹۰۰۱۱۱۰۵۶۲</strong></span></a>
			</div>

		<?php endif; ?>

		<div class="ks-ty__more">
			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
		</div>

	<?php else : ?>

		<div class="ks-ty__hero">
			<span class="ks-ty__ic"><?php echo ks_cart_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<?php wc_get_template( 'checkout/order-received.php', array( 'order' => false ) ); ?>
		</div>

	<?php endif; ?>

</div>
