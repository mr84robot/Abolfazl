<?php
/**
 * مشاهدهٔ سفارش — خانه سعادت (بازنویسیِ myaccount/view-order.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/myaccount/view-order.php
 *
 * بالای صفحه: خلاصهٔ سفارش (تاریخِ شمسی، وضعیت، مبلغ، روش پرداخت) و مراحلِ سفارش
 * (ثبت ← تأیید پرداخت ← آماده‌سازی و ارسال ← تحویل). بعد یادداشت‌های فروشگاه برای مشتری و
 * هوکِ woocommerce_view_order (جدولِ جزئیاتِ سفارش و آدرس‌ها از قالب‌های خودِ ووکامرس، با دکمه‌های پرداخت/لغو).
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.6.0
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/account.php';

$notes = $order->get_customer_order_notes();

$ks_st   = $order->get_status();
$ks_bad  = in_array( $ks_st, array( 'cancelled', 'refunded', 'failed' ), true );
$ks_paid = $order->is_paid();
$ks_done = 'completed' === $ks_st;
$ks_steps = array(
	array( 'ثبت سفارش', 'done' ),
	array( 'تأیید پرداخت', $ks_paid ? 'done' : 'current' ),
	array( 'آماده‌سازی و ارسال', $ks_done ? 'done' : ( $ks_paid ? 'current' : '' ) ),
	array( 'تحویل', $ks_done ? 'done' : '' ),
);
?>

<div class="ks-vo">

	<a class="ks-vo__back" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"><?php echo ks_cart_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>همه سفارش‌ها</span></a>

	<ul class="ks-vo__facts">
		<li><span>تاریخ ثبت</span><strong><?php echo esc_html( ks_acc_date( $order->get_date_created() ) ); ?></strong></li>
		<li><span>وضعیت</span><strong><?php echo ks_acc_status( $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong></li>
		<li><span>مبلغ کل</span><strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong></li>
		<?php if ( $order->get_payment_method_title() ) : ?>
			<li><span>روش پرداخت</span><strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong></li>
		<?php endif; ?>
	</ul>

	<?php if ( $ks_bad ) : ?>
		<p class="ks-vo__bad"><?php echo ks_cart_icon( 'x' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>این سفارش <strong><?php echo esc_html( wc_get_order_status_name( $ks_st ) ); ?></strong> است. اگر سؤالی دارید با پشتیبانی تماس بگیرید.</span></p>
	<?php else : ?>
		<ol class="ks-track" aria-label="مراحل سفارش">
			<?php foreach ( $ks_steps as $ks_i => $ks_s ) : ?>
				<li class="ks-track__i<?php echo $ks_s[1] ? ' is-' . esc_attr( $ks_s[1] ) : ''; ?>"<?php echo 'current' === $ks_s[1] ? ' aria-current="step"' : ''; ?>>
					<span class="ks-track__n"><?php echo 'done' === $ks_s[1] ? ks_cart_icon( 'check' ) : esc_html( ks_fa_digits( $ks_i + 1 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="ks-track__t"><?php echo esc_html( $ks_s[0] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	<?php endif; ?>

	<?php if ( $notes ) : ?>
		<section class="ks-vo__notes">
			<h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
			<ol class="woocommerce-OrderUpdates commentlist notes">
				<?php foreach ( $notes as $note ) : ?>
				<li class="woocommerce-OrderUpdate comment note">
					<div class="woocommerce-OrderUpdate-inner comment_container">
						<div class="woocommerce-OrderUpdate-text comment-text">
							<p class="woocommerce-OrderUpdate-meta meta"><?php echo esc_html( ks_jdate( gmdate( 'Y-n-j', strtotime( $note->comment_date ) ) ) . '، ساعت ' . ks_fa_digits( gmdate( 'H:i', strtotime( $note->comment_date ) ) ) ); ?></p>
							<div class="woocommerce-OrderUpdate-description description">
								<?php echo wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ); ?>
							</div>
						</div>
					</div>
				</li>
				<?php endforeach; ?>
			</ol>
		</section>
	<?php endif; ?>

	<?php do_action( 'woocommerce_view_order', $order_id ); ?>

	<?php ks_acc_help(); ?>

</div>
