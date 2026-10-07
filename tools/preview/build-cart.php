<?php
/**
 * ساختِ پیش‌نمایشِ صفحهٔ سبد خرید از روی خودِ کدِ PHPِ قالب (page-cart.php + woocommerce/cart/*.php).
 *
 * WC()->cart و توابعِ ووکامرسی که قالب‌ها صدا می‌زنند با داده‌ی نمونه شبیه‌سازی می‌شوند؛ ترتیبِ هوک‌ها
 * مثلِ خودِ ووکامرس است (اعلان‌ها، پیشنهادهای مکمل، خلاصهٔ سبد، دکمهٔ ادامه).
 *
 * اجرا:   php tools/preview/build-cart.php > preview/cart-preview.html
 *         php tools/preview/build-cart.php --empty > preview/cart-empty-preview.html
 *         --hostile  ← استایلِ «مزاحمِ» قالب هم اضافه می‌شود
 */

require __DIR__ . '/stubs.php';

$EMPTY = in_array( '--empty', $argv, true );
$BODY  = 'page-template page-template-page-cart page woocommerce-cart woocommerce-page';

/* ---------------- داده‌ی سبد ---------------- */
// یک کالای حراجیِ متغیر (رنگ) با موجودیِ کم، یک کالای بدونِ تخفیف ×۲، یک کالای تک‌فروشی
$PRODS[13649] = array_merge( $PRODS[13649], array( 'type' => 'variation', 'stock_qty' => 2, 'attrs' => array( 'رنگ' => 'نقره‌ای' ) ) );
$PRODS[13225]['solo'] = true;
$CART_ITEMS = $EMPTY ? array() : array(
	'k1' => array( 'product_id' => 13649, 'quantity' => 1 ),
	'k2' => array( 'product_id' => 13231, 'quantity' => 2 ),
	'k3' => array( 'product_id' => 13225, 'quantity' => 1 ),
);
$COUPONS   = $EMPTY ? array() : array( 'saadat5' => 0.05 );
$SHIP      = 1200000;
$CROSS     = array( 13586, 13590, 13588, 13222, 13587, 90001, 90007, 90013 ); // 13586 ناموجود است و نباید بیاید

class PV_Cart {
	function get_cart() {
		$out = array();
		foreach ( $GLOBALS['CART_ITEMS'] as $k => $it ) {
			$p = new PV_Product( $it['product_id'] );
			$out[ $k ] = array( 'key' => $k, 'product_id' => $it['product_id'], 'variation_id' => 0, 'quantity' => $it['quantity'], 'data' => $p, 'variation' => $GLOBALS['PRODS'][ $it['product_id'] ]['attrs'] ?? array() );
		}
		return $out;
	}
	function get_cart_contents_count() { return array_sum( array_column( $GLOBALS['CART_ITEMS'], 'quantity' ) ); }
	function get_product_price( $p ) { return wc_price( $p->get_price() ); }
	function get_product_subtotal( $p, $q ) { return wc_price( $p->get_price() * $q ); }
	function get_subtotal() { $t = 0; foreach ( $this->get_cart() as $i ) { $t += $i['data']->get_price() * $i['quantity']; } return $t; }
	function get_subtotal_tax() { return 0; }
	function get_discount_total() { $r = 0; foreach ( $GLOBALS['COUPONS'] as $pct ) { $r += round( $this->get_subtotal() * $pct, -4 ); } return $r; }
	function get_discount_tax() { return 0; }
	function get_coupons() { $o = array(); foreach ( $GLOBALS['COUPONS'] as $c => $pct ) { $o[ $c ] = (object) array( 'code' => $c, 'pct' => $pct ); } return $o; }
	function needs_shipping() { return true; }
	function show_shipping() { return true; }
	function get_fees() { return array(); }
	function display_prices_including_tax() { return false; }
	function get_cross_sells() { return $GLOBALS['CROSS']; }
	function total() { return $this->get_subtotal() - $this->get_discount_total() + $GLOBALS['SHIP']; }
}
class PV_Customer { function has_calculated_shipping() { return false; } }
$WCOBJ = (object) array( 'cart' => new PV_Cart(), 'customer' => new PV_Customer() );

/* ---------------- ووکامرس ---------------- */
function is_cart() { return true; }
function absint( $n ) { return abs( (int) $n ); }
function shortcode_exists( $t ) { return isset( $GLOBALS['SC'][ $t ] ); }
function wc_get_checkout_url() { return $GLOBALS['U'] . '/checkout/'; }
function wc_get_cart_remove_url( $k ) { return $GLOBALS['U'] . '/cart/?remove_item=' . $k . '&_wpnonce=x'; }
function wc_get_page_id( $p ) { return 5; }
function wc_coupons_enabled() { return true; }
function wc_tax_enabled() { return false; }
function wc_price( $v ) { return '<span class="woocommerce-Price-amount amount"><bdi>' . pv_money( $v ) . '</bdi></span>'; }
function wc_get_price_to_display( $p, $a = array() ) { return ( $a['price'] ?? $p->get_price() ) * ( $a['qty'] ?? 1 ); }
function wc_get_formatted_cart_item_data( $item ) {
	if ( empty( $item['variation'] ) ) { return ''; }
	$h = '<dl class="variation">';
	foreach ( $item['variation'] as $k => $v ) { $h .= '<dt class="variation-' . esc_attr( $k ) . '">' . esc_html( $k ) . ':</dt><dd class="variation-' . esc_attr( $k ) . '"><p>' . esc_html( $v ) . '</p></dd>'; }
	return $h . '</dl>';
}
function woocommerce_quantity_input( $a, $p, $echo = true ) {
	static $n = 0; $n++;
	$type = ( $a['min_value'] > 0 && $a['min_value'] === $a['max_value'] ) ? 'hidden' : 'number';
	$h = '<div class="quantity"><label class="screen-reader-text" for="quantity_' . $n . '">' . esc_html( $a['product_name'] ) . ' تعداد</label><input type="' . $type . '" id="quantity_' . $n . '" class="input-text qty text" name="' . esc_attr( $a['input_name'] ) . '" value="' . esc_attr( $a['input_value'] ) . '" aria-label="تعداد محصول" min="' . esc_attr( $a['min_value'] ) . '" max="' . esc_attr( $a['max_value'] > 0 ? $a['max_value'] : '' ) . '" step="1" placeholder="" inputmode="numeric" autocomplete="off" /></div>';
	if ( $echo ) { echo $h; } return $h;
}
function wp_nonce_field( $a, $n ) { echo '<input type="hidden" id="' . $n . '" name="' . $n . '" value="abc123" /><input type="hidden" name="_wp_http_referer" value="/cart/" />'; }
function wp_kses_post( $h ) { return $h; }
function wp_strip_all_tags( $h ) { return trim( strip_tags( $h ) ); }
function sanitize_title( $t ) { return strtolower( preg_replace( '/[^a-z0-9]+/i', '-', $t ) ); }
$I18N = array( 'Product' => 'محصول', 'Price' => 'قیمت', 'Quantity' => 'تعداد', 'Subtotal' => 'جمع جزء', 'Remove %s from cart' => 'حذف %s از سبد خرید', 'Apply coupon' => 'اعمال کد تخفیف', 'Update cart' => 'به‌روزرسانی سبد خرید', 'Shipping' => 'حمل و نقل', 'Total' => 'مجموع', 'Available on backorder' => 'قابل پیش‌خرید' );
function __( $t, $d = '' ) { return $GLOBALS['I18N'][ $t ] ?? $t; }
function esc_html__( $t, $d = '' ) { return esc_html( __( $t ) ); }
function esc_attr_e( $t, $d = '' ) { echo esc_attr( __( $t ) ); }
function esc_html_e( $t, $d = '' ) { echo esc_html( __( $t ) ); }
function wc_cart_totals_subtotal_html() { echo wc_price( WC()->cart->get_subtotal() ); }
function wc_cart_totals_order_total_html() { echo '<strong>' . wc_price( WC()->cart->total() ) . '</strong> '; }
function wc_cart_totals_coupon_label( $c, $echo = true ) { $l = 'کد تخفیف: ' . $c->code; if ( $echo ) { echo esc_html( $l ); } return $l; }
function wc_cart_totals_coupon_html( $c ) { echo '-' . wc_price( round( WC()->cart->get_subtotal() * $c->pct, -4 ) ) . ' <a href="' . $GLOBALS['U'] . '/cart/?remove_coupon=' . $c->code . '" class="woocommerce-remove-coupon" data-coupon="' . $c->code . '">[حذف]</a>'; }
function wc_cart_totals_shipping_html() { // خروجیِ cart/cart-shipping.phpِ ووکامرس
	echo '<tr class="woocommerce-shipping-totals shipping"><th>حمل و نقل</th><td data-title="حمل و نقل"><ul id="shipping_method" class="woocommerce-shipping-methods">'
		. '<li><input type="radio" name="shipping_method[0]" data-index="0" id="shipping_method_0_flat_rate1" value="flat_rate:1" class="shipping_method" checked="checked" /><label for="shipping_method_0_flat_rate1">پست پیشتاز: ' . wc_price( $GLOBALS['SHIP'] ) . '</label></li>'
		. '<li><input type="radio" name="shipping_method[0]" data-index="0" id="shipping_method_0_flat_rate2" value="flat_rate:2" class="shipping_method" /><label for="shipping_method_0_flat_rate2">تیپاکس (پس‌کرایه)</label></li>'
		. '</ul><p class="woocommerce-shipping-destination">ارسال به <strong>تهران</strong>.</p></td></tr>';
}
function wc_cart_totals_fee_html( $f ) {}
function woocommerce_shipping_calculator() {}
function wc_get_template( $name, $args = array() ) { extract( $args ); include $GLOBALS['THEME'] . '/woocommerce/' . $name; }
function woocommerce_output_all_notices() { echo '<div class="woocommerce-notices-wrapper"></div>'; }
function wc_empty_cart_message() { echo '<div class="wc-empty-cart-message"><div class="cart-empty woocommerce-info">DEFAULT EMPTY MESSAGE</div></div>'; }
function woocommerce_cross_sell_display() { echo '<div class="cross-sells">DEFAULT CROSS SELLS</div>'; }
function woocommerce_cart_totals() { wc_get_template( 'cart/cart-totals.php' ); }
function woocommerce_button_proceed_to_checkout() { wc_get_template( 'cart/proceed-to-checkout-button.php' ); }
add_action( 'woocommerce_before_cart', 'woocommerce_output_all_notices', 10 );
add_action( 'woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5 );
add_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
add_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
// WC_Shortcode_Cart::output()
$SC['woocommerce_cart'] = function () {
	ob_start();
	echo '<div class="woocommerce">';
	wc_get_template( WC()->cart->get_cart() ? 'cart/cart.php' : 'cart/cart-empty.php' );
	echo '</div>';
	return ob_get_clean();
};

require $THEME . '/page-cart.php';
