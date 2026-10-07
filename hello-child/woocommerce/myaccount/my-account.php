<?php
/**
 * حساب کاربری — خانه سعادت (بازنویسیِ myaccount/my-account.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/myaccount/my-account.php
 *
 * فقط چیدمان: ستونِ کناری (کارتِ کاربر + منو) و ستونِ اصلی با عنوانِ بخشِ فعلی.
 * هر دو هوکِ اصلی سرِ جایشان است:
 *   woocommerce_account_navigation  ← منو (navigation.php)
 *   woocommerce_account_content     ← اعلان‌ها + محتوای هر بخش (پیشخوان، سفارش‌ها، آدرس‌ها، بخش‌های افزونه‌ها…)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/account.php';

$ks_ep = ( function_exists( 'WC' ) && WC()->query ) ? WC()->query->get_current_endpoint() : '';
?>
<div class="ks-acc__grid ks-acc--<?php echo esc_attr( $ks_ep ? $ks_ep : 'dashboard' ); ?>">

	<aside class="ks-acc__side">
		<?php ks_acc_user_card(); ?>
		<?php
		/**
		 * My Account navigation.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_navigation' );
		?>
	</aside>

	<div class="woocommerce-MyAccount-content ks-acc__main">
		<h1 class="ks-acc__title"><?php echo esc_html( ks_acc_title() ); ?></h1>
		<?php
		/**
		 * My Account content.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_content' );
		?>
	</div>

</div>
