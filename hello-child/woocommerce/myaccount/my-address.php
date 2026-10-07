<?php
/**
 * آدرس‌ها — خانه سعادت (بازنویسیِ myaccount/my-address.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/myaccount/my-address.php
 *
 * همان آدرس‌ها، فیلترها و هوک‌های ووکامرس (woocommerce_my_account_get_addresses،
 * woocommerce_my_account_my_address_description، woocommerce_my_account_after_my_address)؛ فقط به شکلِ کارت.
 * فرمِ ویرایشِ آدرس (form-edit-address.php) بازنویسی نشده و فقط استایل گرفته.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/account.php';

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}

$oldcol = 1;
$col    = 1;
?>

<p class="ks-acc__lead">
	<?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</p>

<div class="u-columns woocommerce-Addresses col2-set addresses ks-addr">

<?php foreach ( $get_addresses as $name => $address_title ) : ?>
	<?php
		$address = wc_get_account_formatted_address( $name );
		$col     = $col * -1;
		$oldcol  = $oldcol * -1;
	?>

	<div class="u-column<?php echo $col < 0 ? 1 : 2; ?> col-<?php echo $oldcol < 0 ? 1 : 2; ?> woocommerce-Address ks-addr__card<?php echo $address ? '' : ' is-empty'; ?>">
		<header class="woocommerce-Address-title title ks-addr__head">
			<?php echo ks_cart_icon( 'shipping' === $name ? 'truck' : 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<h2><?php echo esc_html( $address_title ); ?></h2>
		</header>
		<address>
			<?php
				echo $address ? wp_kses_post( ks_fa_digits( $address ) ) : esc_html__( 'You have not set up this type of address yet.', 'woocommerce' );

				/**
				 * Used to output content after core address fields.
				 *
				 * @param string $name Address type.
				 * @since 8.7.0
				 */
				do_action( 'woocommerce_my_account_after_my_address', $name );
			?>
		</address>
		<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', $name ) ); ?>" class="edit ks-addr__edit">
			<?php echo ks_cart_icon( $address ? 'edit' : 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<span>
			<?php
				printf(
					/* translators: %s: Address title */
					$address ? esc_html__( 'Edit %s', 'woocommerce' ) : esc_html__( 'Add %s', 'woocommerce' ),
					esc_html( $address_title )
				);
			?>
			</span>
		</a>
	</div>

<?php endforeach; ?>

</div>
