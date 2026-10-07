<?php
/**
 * منوی حساب کاربری — خانه سعادت (بازنویسیِ myaccount/navigation.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/myaccount/navigation.php
 *
 * همان آیتم‌ها و کلاس‌های ووکامرس (wc_get_account_menu_items، is-active، aria-current)، به‌علاوهٔ آیکون.
 * آیتم‌هایی که افزونه‌ها اضافه می‌کنند هم نمایش داده می‌شوند. «دانلودها» فقط اگر مشتری فایلی دارد.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/account.php';

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation ks-acc-nav" aria-label="<?php esc_attr_e( 'Account pages', 'woocommerce' ); ?>">
	<ul>
		<?php foreach ( ks_acc_menu_items() as $endpoint => $label ) : ?>
			<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — خودِ ووکامرس sanitize می‌کند ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>>
					<?php echo ks_acc_endpoint_icon( $endpoint ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php echo esc_html( $label ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
