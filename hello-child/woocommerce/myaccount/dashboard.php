<?php
/**
 * پیشخوانِ حساب کاربری — خانه سعادت (بازنویسیِ myaccount/dashboard.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/myaccount/dashboard.php
 *
 * خوشامد، آمارِ سفارش‌ها، سه سفارشِ آخر، دسترسیِ سریع به بخش‌ها و پشتیبانی.
 * سفارش‌ها با همان کوئریِ صفحهٔ «سفارش‌ها» خوانده می‌شوند (فیلترِ woocommerce_my_account_my_orders_query)،
 * پس تعداد و فهرست با آن صفحه یکی است. هوک‌های woocommerce_account_dashboard و قدیمی‌ها سرِ جایشان است.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once get_stylesheet_directory() . '/inc/account.php';

$ks_uid  = get_current_user_id();
$ks_name = $current_user->first_name ? $current_user->first_name : ks_acc_name( $current_user );
if ( preg_match( '/^[\d+\s۰-۹]+$/u', $ks_name ) ) { $ks_name = ''; } // نامِ کاربری = موبایل

$ks_q             = apply_filters( 'woocommerce_my_account_my_orders_query', array( 'customer' => $ks_uid, 'page' => 1, 'paginate' => true ) ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment
$ks_q['limit']    = 3;
$ks_q['page']     = 1;
$ks_q['paginate'] = true;
$ks_recent        = wc_get_orders( $ks_q );
$ks_total         = (int) $ks_recent->total;

$ks_open = 0; // سفارش‌های در جریان (در انتظار بررسی / در حال انجام)
if ( $ks_total ) {
	$ks_open = count( wc_get_orders( array( 'customer' => $ks_uid, 'status' => array( 'wc-on-hold', 'wc-processing' ), 'limit' => 50, 'return' => 'ids' ) ) );
}
$ks_last = $ks_recent->orders ? ks_acc_date( $ks_recent->orders[0]->get_date_created(), false ) : '';

$ks_links = array(
	'orders'          => 'پیگیری وضعیت و جزئیات سفارش‌ها',
	'edit-address'    => 'آدرس ارسال و اطلاعات تماس',
	'edit-account'    => 'نام، ایمیل و رمز عبور',
	'payment-methods' => 'کارت‌های ذخیره‌شده',
	'downloads'       => 'فایل‌های خریداری‌شده',
);
$ks_menu = ks_acc_menu_items();
?>

<section class="ks-dash">

	<div class="ks-dash__hi">
		<p class="ks-dash__t"><?php echo $ks_name ? 'سلام ' . esc_html( $ks_name ) . '، خوش آمدید' : 'سلام، خوش آمدید'; ?></p>
		<p class="ks-dash__d">از اینجا سفارش‌هایتان را پیگیری کنید، آدرس ارسال را تغییر دهید یا اطلاعات حساب را ویرایش کنید.</p>
		<p class="ks-dash__out">
			<?php
			printf(
				'%1$s نیستید؟ <a href="%2$s">خروج از حساب</a>',
				'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
				esc_url( wc_logout_url() )
			);
			?>
		</p>
	</div>

	<ul class="ks-dash__stats">
		<li><?php echo ks_cart_icon( 'box' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>همه سفارش‌ها</span><strong><?php echo esc_html( ks_fa_digits( $ks_total ) ); ?></strong></li>
		<li><?php echo ks_cart_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>در جریان</span><strong><?php echo esc_html( ks_fa_digits( $ks_open ) ); ?></strong></li>
		<li><?php echo ks_cart_icon( 'calendar' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>آخرین سفارش</span><strong><?php echo $ks_last ? esc_html( $ks_last ) : '—'; ?></strong></li>
	</ul>

	<div class="ks-dash__sec">
		<div class="ks-dash__head">
			<h2 class="ks-dash__h">آخرین سفارش‌ها</h2>
			<?php if ( $ks_total > 3 && isset( $ks_menu['orders'] ) ) : ?>
				<a class="ks-dash__all" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"><span>همه سفارش‌ها</span><?php echo ks_cart_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			<?php endif; ?>
		</div>

		<?php if ( $ks_recent->orders ) : ?>
			<ul class="ks-dash__orders">
				<?php foreach ( $ks_recent->orders as $ks_order ) : ?>
					<li>
						<a class="ks-dash__o" href="<?php echo esc_url( $ks_order->get_view_order_url() ); ?>">
							<?php echo ks_acc_thumbs( $ks_order, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="ks-dash__o-main">
								<strong>سفارش <?php echo esc_html( ks_fa_digits( '#' . $ks_order->get_order_number() ) ); ?></strong>
								<small><?php echo esc_html( ks_acc_date( $ks_order->get_date_created() ) ); ?></small>
							</span>
							<?php echo ks_acc_status( $ks_order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="ks-dash__o-sum"><?php echo wp_kses_post( $ks_order->get_formatted_order_total() ); ?></span>
							<?php echo ks_cart_icon( 'arrow', 'ks-dash__o-go' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<div class="ks-dash__none">
				<p>هنوز سفارشی ثبت نکرده‌اید.</p>
				<a class="ks-acc-btn" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment ?>">مشاهده محصولات</a>
			</div>
		<?php endif; ?>
	</div>

	<ul class="ks-dash__links">
		<?php foreach ( $ks_links as $ks_ep => $ks_desc ) : ?>
			<?php if ( empty( $ks_menu[ $ks_ep ] ) ) { continue; } ?>
			<li>
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $ks_ep ) ); ?>">
					<?php echo ks_acc_endpoint_icon( $ks_ep ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><strong><?php echo esc_html( $ks_menu[ $ks_ep ] ); ?></strong><small><?php echo esc_html( $ks_desc ); ?></small></span>
					<?php echo ks_cart_icon( 'arrow', 'ks-dash__go' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php ks_acc_help(); ?>

</section>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
