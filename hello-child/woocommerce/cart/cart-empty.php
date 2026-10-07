<?php
/**
 * سبد خرید خالی — خانه سعادت (بازنویسیِ cart/cart-empty.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/cart/cart-empty.php
 *
 * پیامِ پیش‌فرضِ ووکامرس (wc_empty_cart_message) با همین طرح جایگزین شده؛ کلاسِ wc-empty-cart-message
 * حفظ شده چون cart.jsِ ووکامرس وقتی آخرین کالا با Ajax حذف می‌شود دنبالِ همین می‌گردد.
 * اعلان‌ها (مثلاً «… حذف شد. بازگردانی؟») همچنان با هوکِ woocommerce_cart_is_empty (اولویت ۵) چاپ می‌شوند.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/cart.php';

remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );

/*
 * @hooked woocommerce_output_all_notices - 5
 */
do_action( 'woocommerce_cart_is_empty' );

$ks_shop = apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) );
$ks_cats = array();
foreach ( array( 'chaeisaz', 'sorkhkon', 'espersosaz', 'flask', 'otobokhar' ) as $ks_slug ) {
	$ks_t = get_term_by( 'slug', $ks_slug, 'product_cat' );
	if ( $ks_t && ! is_wp_error( $ks_t ) ) {
		$ks_l = get_term_link( $ks_t );
		if ( ! is_wp_error( $ks_l ) ) { $ks_cats[] = array( $ks_t->name, $ks_l ); }
	}
}
?>
<div class="wc-empty-cart-message ks-empty">
	<div class="cart-empty ks-empty__box">
		<span class="ks-empty__ic"><?php echo ks_cart_icon( 'bag' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<h1 class="ks-empty__t">سبد خرید شما خالی است</h1>
		<p class="ks-empty__d">هنوز کالایی انتخاب نکرده‌اید. از دسته‌های پرطرفدار شروع کنید یا سری به محصولات تخفیف‌دار بزنید.</p>

		<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
			<p class="return-to-shop ks-empty__cta">
				<a class="button wc-backward ks-empty__btn" href="<?php echo esc_url( $ks_shop ); ?>">
					<span><?php echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', 'رفتن به فروشگاه' ) ); ?></span><?php echo ks_cart_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</p>
		<?php endif; ?>

		<?php if ( $ks_cats ) : ?>
			<ul class="ks-empty__cats" aria-label="دسته‌های پرطرفدار">
				<?php foreach ( $ks_cats as $ks_c ) : ?>
					<li><a href="<?php echo esc_url( $ks_c[1] ); ?>"><?php echo esc_html( $ks_c[0] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>
