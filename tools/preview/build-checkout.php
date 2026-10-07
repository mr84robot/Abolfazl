<?php
/**
 * ساختِ پیش‌نمایشِ تسویه حساب و «سفارش ثبت شد» از روی خودِ کدِ PHPِ قالب
 * (page-checkout.php + woocommerce/checkout/*.php).
 *
 * فیلدها مثلِ یک فروشگاهِ ایرانیِ معمولی (نام، نام خانوادگی، استان، شهر، آدرس، کد پستی، موبایل، ایمیل)،
 * سه درگاهِ نمونه و ترتیبِ هوک‌ها مثلِ خودِ ووکامرس شبیه‌سازی می‌شوند.
 *
 * اجرا:   php tools/preview/build-checkout.php            > preview/checkout-preview.html
 *         php tools/preview/build-checkout.php --errors   > (خطای اعتبارسنجی روی دو فیلد + پیامِ خطا)
 *         php tools/preview/build-checkout.php --thankyou > preview/checkout-thankyou-preview.html
 *         php tools/preview/build-checkout.php --failed   > preview/checkout-failed-preview.html
 *         --hostile  ← استایلِ «مزاحمِ» قالب هم اضافه می‌شود
 */

require __DIR__ . '/stubs.php';
require __DIR__ . '/stubs-shop.php';

$IS_CHECKOUT = true;
$MODE = in_array( '--thankyou', $argv, true ) ? 'thankyou' : ( in_array( '--failed', $argv, true ) ? 'failed' : ( in_array( '--errors', $argv, true ) ? 'errors' : 'form' ) );
$BODY = 'page-template page-template-page-checkout page woocommerce-checkout woocommerce-page' . ( 'form' === $MODE || 'errors' === $MODE ? '' : ' woocommerce-order-received' );

/* ---------------- وردپرس ---------------- */
function checked( $a, $b = true, $echo = true ) { $r = ( (string) $a === (string) $b ) ? ' checked="checked"' : ''; if ( $echo ) { echo $r; } return $r; }
function esc_attr__( $t, $d = '' ) { return esc_attr( __( $t ) ); }
function is_user_logged_in() { return false; }
function get_current_user_id() { return 0; }
function wp_doing_ajax() { return false; }
function wc_ship_to_billing_address_only() { return false; }
function wc_print_notice( $m, $t = 'success' ) { echo '<div class="woocommerce-' . ( 'error' === $t ? 'error' : ( 'notice' === $t ? 'info' : 'message' ) ) . '" role="alert">' . $m . '</div>'; }
function wc_format_datetime( $d ) { return '۱۵ مهر ۱۴۰۵'; }
function wc_get_account_endpoint_url( $e ) { return $GLOBALS['U'] . '/my-account/' . $e . '/'; }
$I18N += array( 'Checkout' => 'پرداخت', 'Coupon:' => 'کد تخفیف:', 'Update totals' => 'بروزرسانی مجموع', 'Order number:' => 'شماره سفارش:', 'Date:' => 'تاریخ:', 'Email:' => 'ایمیل:', 'Total:' => 'مجموع:', 'Payment method:' => 'روش پرداخت:', 'Pay' => 'پرداخت', 'My account' => 'حساب کاربری من',
	'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.' => 'متأسفانه سفارش شما پردازش نشد، چون بانک تراکنش را رد کرد. لطفاً دوباره برای خرید تلاش کنید.',
	'You must be logged in to checkout.' => 'برای تسویه حساب باید وارد شوید.' );

/* ---------------- WC()->cart / customer برای تسویه حساب ---------------- */
class PV_CoCart extends PV_Cart {
	function needs_shipping_address() { return true; }
	function needs_payment() { return true; }
}
class PV_CoCustomer extends PV_Customer { function get_billing_country() { return 'IR'; } }
$WCOBJ = (object) array( 'cart' => new PV_CoCart(), 'customer' => new PV_CoCustomer() );

/* ---------------- فیلدها: stubs-forms.php ---------------- */
require __DIR__ . '/stubs-forms.php';

class PV_Checkout {
	function is_registration_enabled() { return true; }
	function is_registration_required() { return false; }
	function get_checkout_fields( $s = '' ) {
		$all = array(
			'billing'  => pv_fields( 'billing' ),
			'shipping' => pv_fields( 'shipping' ),
			'account'  => array( 'account_password' => array( 'type' => 'password', 'label' => 'رمز عبور حساب', 'required' => true, 'class' => array( 'form-row-wide' ), 'placeholder' => 'حداقل ۸ حرف' ) ),
			'order'    => array( 'order_comments' => array( 'type' => 'textarea', 'label' => 'یادداشت سفارش', 'required' => false, 'class' => array( 'notes', 'form-row-wide' ), 'placeholder' => 'مثلاً زمانِ مناسب برای تحویل یا نشانیِ دقیق‌تر' ) ),
		);
		return '' === $s ? $all : $all[ $s ];
	}
	function get_value( $k ) {
		$v = array( 'billing_first_name' => 'مریم', 'billing_last_name' => 'احمدی', 'billing_state' => 'THR', 'billing_city' => 'تهران', 'billing_address_1' => 'خیابان ولیعصر، کوچه نسترن، پلاک ۱۲، واحد ۳', 'billing_postcode' => 'errors' === $GLOBALS['MODE'] ? '' : '1415683911', 'billing_phone' => 'errors' === $GLOBALS['MODE'] ? '' : '09121234567' );
		return $v[ $k ] ?? '';
	}
}
$CHECKOUT = new PV_Checkout();

/* ---------------- درگاه‌ها ---------------- */
class PV_Gateway {
	public $id, $chosen, $order_button_text = '', $title, $desc, $icon;
	function __construct( $id, $title, $desc, $chosen = false, $icon = '' ) { $this->id = $id; $this->title = $title; $this->desc = $desc; $this->chosen = $chosen; $this->icon = $icon; }
	function get_title() { return $this->title; }
	function get_icon() { return $this->icon ? '<img src="' . $this->icon . '" alt="' . esc_attr( $this->title ) . '" />' : ''; }
	function has_fields() { return false; }
	function get_description() { return $this->desc; }
	function payment_fields() { echo '<p>' . $this->desc . '</p>'; }
}
$GATEWAYS = array(
	new PV_Gateway( 'zarinpal', 'پرداخت آنلاین (همه کارت‌های شتاب)', 'پس از ثبت سفارش به درگاه امن بانکی منتقل می‌شوید و با هر کارتِ عضوِ شتاب پرداخت می‌کنید.', true, $U . '/wp-content/uploads/zarinpal.png' ),
	new PV_Gateway( 'bacs', 'کارت به کارت', 'مبلغ را به شماره کارتی که بعد از ثبت سفارش نمایش داده می‌شود واریز کنید و شماره پیگیری را برای ما بفرستید.' ),
	new PV_Gateway( 'cod', 'پرداخت در محل (فقط تهران)', 'پرداخت با کارتخوان هنگام تحویل کالا.' ),
);

/* ---------------- سفارش (برای صفحهٔ «سفارش ثبت شد») ---------------- */
class PV_Order {
	function get_id() { return 4521; }
	function get_order_number() { return '4521'; }
	function has_status( $s ) { return 'failed' === $GLOBALS['MODE'] && 'failed' === $s; }
	function get_date_created() { return new stdClass(); }
	function get_formatted_order_total() { return wc_price( WC()->cart->total() ); }
	function get_payment_method_title() { return 'پرداخت آنلاین (همه کارت‌های شتاب)'; }
	function get_payment_method() { return 'zarinpal'; }
	function get_checkout_payment_url() { return $GLOBALS['U'] . '/checkout/order-pay/4521/?pay_for_order=true&key=wc_order_x'; }
	function get_billing_email() { return 'maryam@example.com'; }
	function get_user_id() { return 0; }
}

/* ---------------- قالب‌های پیش‌فرضِ ووکامرس که بازنویسی نشده‌اند ---------------- */
$PV_TPL['checkout/terms.php'] = function () {
	echo '<div class="woocommerce-terms-and-conditions-wrapper"><div class="woocommerce-privacy-policy-text"><p>اطلاعات شخصی شما برای پردازش سفارش و پشتیبانی استفاده می‌شود؛ جزئیات در <a href="#" class="woocommerce-privacy-policy-link" target="_blank">حریم خصوصی</a>.</p></div>'
		. '<p class="form-row validate-required"><label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox"><input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" id="terms" /><span class="woocommerce-terms-and-conditions-checkbox-text"><a href="#" class="woocommerce-terms-and-conditions-link" target="_blank">شرایط و قوانین</a> سایت را خوانده‌ام و می‌پذیرم</span>&nbsp;<span class="required" aria-hidden="true">*</span></label><input type="hidden" name="terms-field" value="1" /></p></div>';
};
$PV_TPL['checkout/order-received.php'] = function ( $a ) {
	echo '<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">متشکریم، سفارش شما دریافت شد.</p>';
};

/* ---------------- هوک‌ها مثلِ ووکامرس ---------------- */
function woocommerce_checkout_login_form() {
	echo '<div class="woocommerce-form-login-toggle"><div class="woocommerce-info">قبلاً خرید کرده‌اید؟ <a href="#" class="showlogin">برای ورود اینجا بزنید</a></div></div>';
}
function woocommerce_checkout_coupon_form() { wc_get_template( 'checkout/form-coupon.php', array( 'checkout' => $GLOBALS['CHECKOUT'] ) ); }
function pv_checkout_billing() { wc_get_template( 'checkout/form-billing.php', array( 'checkout' => $GLOBALS['CHECKOUT'] ) ); }
function pv_checkout_shipping() { wc_get_template( 'checkout/form-shipping.php', array( 'checkout' => $GLOBALS['CHECKOUT'] ) ); }
function woocommerce_order_review() { wc_get_template( 'checkout/review-order.php', array( 'checkout' => $GLOBALS['CHECKOUT'] ) ); }
function woocommerce_checkout_payment() { wc_get_template( 'checkout/payment.php', array( 'checkout' => $GLOBALS['CHECKOUT'], 'available_gateways' => $GLOBALS['GATEWAYS'], 'order_button_text' => 'ثبت سفارش' ) ); }
function pv_checkout_errors() {
	if ( 'errors' !== $GLOBALS['MODE'] ) { return; }
	echo '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout"><ul class="woocommerce-error" role="alert"><li data-id="billing_postcode"><strong>کد پستی</strong> یک فیلد ضروری است.</li><li data-id="billing_phone"><strong>شماره موبایل</strong> یک فیلد ضروری است.</li></ul></div>';
}
function woocommerce_order_details_table( $id ) { // خروجیِ order/order-details.php و order-details-customer.phpِ ووکامرس
	$rows = '';
	foreach ( WC()->cart->get_cart() as $it ) {
		$p = $it['data'];
		$rows .= '<tr class="woocommerce-table__line-item order_item"><td class="woocommerce-table__product-name product-name"><a href="' . esc_url( $p->get_permalink() ) . '">' . esc_html( $p->get_name() ) . '</a> <strong class="product-quantity">&times;&nbsp;' . $it['quantity'] . '</strong>'
			. ( $it['variation'] ? '<ul class="wc-item-meta"><li><strong class="wc-item-meta-label">رنگ:</strong> <p>نقره‌ای</p></li></ul>' : '' ) . '</td><td class="woocommerce-table__product-total product-total">' . wc_price( $p->get_price() * $it['quantity'] ) . '</td></tr>';
	}
	echo '<section class="woocommerce-order-details"><h2 class="woocommerce-order-details__title">جزئیات سفارش</h2><table class="woocommerce-table woocommerce-table--order-details shop_table order_details"><thead><tr><th class="woocommerce-table__product-name product-name">محصول</th><th class="woocommerce-table__product-table product-total">مجموع</th></tr></thead><tbody>' . $rows . '</tbody><tfoot>'
		. '<tr><th scope="row">جمع جزء:</th><td>' . wc_price( WC()->cart->get_subtotal() ) . '</td></tr><tr><th scope="row">تخفیف:</th><td>-' . wc_price( WC()->cart->get_discount_total() ) . '</td></tr><tr><th scope="row">حمل و نقل:</th><td>' . wc_price( $GLOBALS['SHIP'] ) . ' <small class="shipped_via">از طریق پست پیشتاز</small></td></tr><tr><th scope="row">روش پرداخت:</th><td>پرداخت آنلاین</td></tr><tr><th scope="row">مجموع:</th><td>' . wc_price( WC()->cart->total() ) . '</td></tr></tfoot></table></section>'
		. '<section class="woocommerce-customer-details"><h2 class="woocommerce-column__title">آدرس صورتحساب</h2><address>مریم احمدی<br>خیابان ولیعصر، کوچه نسترن، پلاک ۱۲، واحد ۳<br>تهران، تهران<br>1415683911<p class="woocommerce-customer-details--phone">09121234567</p><p class="woocommerce-customer-details--email">maryam@example.com</p></address></section>';
}
add_action( 'woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
add_action( 'woocommerce_checkout_before_customer_details', 'pv_checkout_errors', 1 ); // ووکامرس خطاها را با JS اولِ فرم می‌گذارد
add_action( 'woocommerce_checkout_billing', 'pv_checkout_billing' );
add_action( 'woocommerce_checkout_shipping', 'pv_checkout_shipping' );
add_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
add_action( 'woocommerce_thankyou', 'woocommerce_order_details_table', 10 );

// WC_Shortcode_Checkout::output()
$SC['woocommerce_checkout'] = function () {
	ob_start();
	echo '<div class="woocommerce">';
	if ( 'thankyou' === $GLOBALS['MODE'] || 'failed' === $GLOBALS['MODE'] ) {
		wc_get_template( 'checkout/thankyou.php', array( 'order' => new PV_Order() ) );
	} else {
		wc_get_template( 'checkout/form-checkout.php', array( 'checkout' => $GLOBALS['CHECKOUT'] ) );
	}
	echo '</div>';
	return ob_get_clean();
};

require $THEME . '/page-checkout.php';
