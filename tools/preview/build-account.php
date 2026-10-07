<?php
/**
 * ساختِ پیش‌نمایشِ حساب کاربری از روی خودِ کدِ PHPِ قالب (page-my-account.php + woocommerce/myaccount/*.php).
 *
 * مشتریِ نمونه با شش سفارش در وضعیت‌های مختلف؛ ترتیبِ هوک‌ها مثلِ خودِ ووکامرس
 * (woocommerce_account_navigation ، woocommerce_account_content ← اعلان‌ها + بخشِ فعلی).
 * فرم‌هایی که قالب بازنویسی نکرده (ورود، ویرایشِ آدرس/حساب، فراموشیِ رمز) از نسخهٔ اصلیِ ووکامرس
 * در tools/preview/wc/myaccount/ خوانده می‌شوند تا استایل روی HTMLِ واقعی‌شان دیده شود.
 *
 * اجرا:   php tools/preview/build-account.php                 > preview/account-preview.html          (پیشخوان)
 *         php tools/preview/build-account.php --orders        > preview/account-orders-preview.html
 *         php tools/preview/build-account.php --orders-empty  (بدونِ سفارش)
 *         php tools/preview/build-account.php --view          > preview/account-view-order-preview.html (سفارشِ در حال انجام)
 *         php tools/preview/build-account.php --view-cancelled
 *         php tools/preview/build-account.php --addresses     > preview/account-addresses-preview.html
 *         php tools/preview/build-account.php --edit-address  > preview/account-edit-address-preview.html
 *         php tools/preview/build-account.php --edit-account  > preview/account-edit-account-preview.html
 *         php tools/preview/build-account.php --login         > preview/account-login-preview.html      (مهمان)
 *         php tools/preview/build-account.php --lost-password
 *         --hostile  ← استایلِ «مزاحمِ» قالب هم اضافه می‌شود
 */

require __DIR__ . '/stubs.php';
require __DIR__ . '/stubs-shop.php';
require __DIR__ . '/stubs-forms.php';

$MODES = array( 'orders', 'orders-empty', 'view', 'view-cancelled', 'addresses', 'edit-address', 'edit-account', 'login', 'lost-password' );
$MODE  = 'dashboard';
foreach ( $MODES as $m ) { if ( in_array( '--' . $m, $argv, true ) ) { $MODE = $m; } }
$GUEST    = in_array( $MODE, array( 'login', 'lost-password' ), true );
$NOORDERS = 'orders-empty' === $MODE;
$ENDPOINT = array( 'dashboard' => '', 'orders' => 'orders', 'orders-empty' => 'orders', 'view' => 'view-order', 'view-cancelled' => 'view-order', 'addresses' => 'edit-address', 'edit-address' => 'edit-address', 'edit-account' => 'edit-account', 'login' => '', 'lost-password' => 'lost-password' )[ $MODE ];
$BODY = 'page-template page-template-page-my-account page woocommerce-account woocommerce-page' . ( $ENDPOINT ? ' woocommerce-' . $ENDPOINT : '' ) . ( $GUEST ? '' : ' logged-in' ) . ' ks-email-optional'; // ks-email-optional: inc/shop-setup.php

/* ---------------- وردپرس ---------------- */
function is_account_page() { return true; }
function is_user_logged_in() { return ! $GLOBALS['GUEST']; }
function get_current_user_id() { return $GLOBALS['GUEST'] ? 0 : 7; }
$USER = (object) array( 'ID' => 7, 'first_name' => 'مریم', 'last_name' => 'احمدی', 'display_name' => 'مریم احمدی', 'user_email' => 'maryam@example.com', 'user_registered' => '2024-03-02 09:12:00' );
function wp_get_current_user() { return $GLOBALS['GUEST'] ? (object) array( 'ID' => 0 ) : $GLOBALS['USER']; }
function get_user_meta( $id, $k, $single = false ) { return array( 'billing_phone' => '09121234567' )[ $k ] ?? ''; }
function esc_attr__( $t, $d = '' ) { return esc_attr( __( $t ) ); }
function _x( $t, $c, $d = '' ) { return __( $t ); }
function sanitize_html_class( $c ) { return preg_replace( '/[^A-Za-z0-9_-]/', '', $c ); }
function wpautop( $t ) { return '<p>' . $t . '</p>'; }
function wptexturize( $t ) { return $t; }
function wp_lostpassword_url() { return $GLOBALS['U'] . '/my-account/lost-password/'; }
function wc_wp_theme_get_element_class_name( $e ) { return ''; }
function wc_reviews_enabled() { return true; }
function wc_get_post_data_by_key( $k, $d = '' ) { return $d; }
function wc_shipping_enabled() { return true; }
function wc_ship_to_billing_address_only() { return false; }
function wc_placeholder_img( $s = '' ) { return '<img src="" alt="" class="woocommerce-placeholder" />'; }
function wc_print_notice( $m, $t = 'success' ) { echo '<div class="woocommerce-' . ( 'error' === $t ? 'error' : ( 'notice' === $t ? 'info' : 'message' ) ) . '" role="alert">' . $m . '</div>'; }
$I18N += array(
	'Account pages' => 'صفحات حساب کاربری', 'Dashboard' => 'پیشخوان', 'Orders' => 'سفارش‌ها', 'Downloads' => 'دانلودها', 'Addresses' => 'آدرس‌ها', 'Account details' => 'جزئیات حساب', 'Log out' => 'خروج',
	'Order' => 'سفارش', 'Date' => 'تاریخ', 'Status' => 'وضعیت', 'Actions' => 'عملیات', 'View' => 'نمایش', 'Pay' => 'پرداخت', 'Cancel' => 'لغو',
	'View order number %s' => 'نمایش سفارش شماره %s', '%1$s order number %2$s' => '%1$s سفارش شماره %2$s', 'Order #%s' => 'سفارش #%s',
	'Previous' => 'قبلی', 'Next' => 'بعدی', 'No order has been made yet.' => 'هنوز سفارشی ثبت نشده است.', 'Browse products' => 'مشاهده محصولات', 'Order updates' => 'به‌روزرسانی‌های سفارش',
	'Billing address' => 'آدرس صورتحساب', 'Shipping address' => 'آدرس حمل و نقل', 'The following addresses will be used on the checkout page by default.' => 'آدرس‌های زیر به‌طور پیش‌فرض در صفحه پرداخت استفاده می‌شوند.',
	'Edit %s' => 'ویرایش %s', 'Add %s' => 'افزودن %s', 'You have not set up this type of address yet.' => 'شما هنوز این نوع آدرس را وارد نکرده‌اید.', 'Save address' => 'ذخیره آدرس',
	'Login' => 'ورود', 'Username or email address' => 'نام کاربری یا آدرس ایمیل', 'Password' => 'رمز عبور', 'Remember me' => 'مرا به خاطر بسپار', 'Log in' => 'ورود', 'Lost your password?' => 'رمز عبور را فراموش کرده‌اید؟',
	'Register' => 'عضویت', 'Email address' => 'آدرس ایمیل', 'Required' => 'الزامی', 'A link to set a new password will be sent to your email address.' => 'لینک تعیین رمز عبور جدید به ایمیل شما ارسال می‌شود.', 'Username' => 'نام کاربری',
	'First name' => 'نام', 'Last name' => 'نام خانوادگی', 'Display name' => 'نام نمایشی', 'This will be how your name will be displayed in the account section and in reviews' => 'نام شما به این شکل در حساب کاربری و دیدگاه‌ها نمایش داده می‌شود',
	'Password change' => 'تغییر رمز عبور', 'Current password (leave blank to leave unchanged)' => 'رمز عبور فعلی (برای عدم تغییر خالی بگذارید)', 'New password (leave blank to leave unchanged)' => 'رمز عبور جدید (برای عدم تغییر خالی بگذارید)', 'Confirm new password' => 'تکرار رمز عبور جدید', 'Save changes' => 'ذخیره تغییرات',
	'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.' => 'رمز عبور خود را فراموش کرده‌اید؟ نام کاربری یا ایمیل خود را وارد کنید. لینک ساخت رمز عبور جدید به ایمیل شما ارسال می‌شود.', 'Username or email' => 'نام کاربری یا ایمیل', 'Reset password' => 'بازیابی رمز عبور',
);

/* ---------------- سفارش‌ها ---------------- */
$STATUS = array( 'pending' => 'در انتظار پرداخت', 'processing' => 'در حال انجام', 'on-hold' => 'در انتظار بررسی', 'completed' => 'تکمیل شده', 'cancelled' => 'لغو شده', 'refunded' => 'مسترد شده', 'failed' => 'ناموفق' );
function wc_get_order_status_name( $s ) { return $GLOBALS['STATUS'][ $s ] ?? $s; }
class PV_Date {
	private $t;
	function __construct( $ymd ) { $this->t = strtotime( $ymd . ' 10:30:00' ); }
	function date( $f ) { return 'c' === $f ? date( 'Y-m-d\TH:i:sP', $this->t ) : date( $f, $this->t ); }
}
class PV_Item {
	public $pid, $qty;
	function __construct( $pid, $qty ) { $this->pid = $pid; $this->qty = $qty; }
	function get_product() { return new PV_Product( $this->pid ); }
	function get_name() { return $GLOBALS['PRODS'][ $this->pid ]['name']; }
	function get_quantity() { return $this->qty; }
}
class PV_AccOrder {
	public $d;
	function __construct( $d ) { $this->d = $d; }
	function get_id() { return $this->d['id']; }
	function get_order_number() { return (string) $this->d['id']; }
	function get_status() { return $this->d['status']; }
	function has_status( $s ) { return in_array( $this->d['status'], (array) $s, true ); }
	function is_paid() { return in_array( $this->d['status'], array( 'processing', 'completed' ), true ); }
	function get_date_created() { return new PV_Date( $this->d['date'] ); }
	function get_items( $t = 'line_item' ) { $o = array(); foreach ( $this->d['items'] as $i => $it ) { $o[ $i + 1 ] = new PV_Item( $it[0], $it[1] ); } return $o; }
	function get_item_count() { return array_sum( array_column( $this->d['items'], 1 ) ); }
	function get_item_count_refunded() { return 0; }
	function subtotal() { $t = 0; foreach ( $this->d['items'] as $it ) { $t += $GLOBALS['PRODS'][ $it[0] ]['price'] * $it[1]; } return $t; }
	function total() { return $this->subtotal() + $GLOBALS['SHIP']; }
	function get_formatted_order_total() { return wc_price( $this->total() ); }
	function get_view_order_url() { return $GLOBALS['U'] . '/my-account/view-order/' . $this->d['id'] . '/'; }
	function get_payment_method_title() { return $this->d['pay']; }
	function get_customer_order_notes() {
		return 4521 === $this->d['id'] ? array( (object) array( 'comment_date' => '2026-10-06 14:05:00', 'comment_content' => 'سفارش شما بسته‌بندی شد و فردا تحویل پست می‌شود. کد رهگیری پس از ارسال برایتان پیامک می‌شود.' ) ) : array();
	}
}
$ORDERS = $NOORDERS ? array() : array(
	array( 'id' => 4521, 'status' => 'view-cancelled' === $MODE ? 'cancelled' : 'processing', 'date' => '2026-10-05', 'items' => array( array( 13649, 1 ), array( 13231, 2 ), array( 13225, 1 ) ), 'pay' => 'پرداخت آنلاین (همه کارت‌های شتاب)' ),
	array( 'id' => 4488, 'status' => 'completed', 'date' => '2026-09-12', 'items' => array( array( 13586, 1 ) ), 'pay' => 'پرداخت آنلاین (همه کارت‌های شتاب)' ),
	array( 'id' => 4402, 'status' => 'on-hold', 'date' => '2026-08-28', 'items' => array( array( 13590, 1 ), array( 13588, 1 ) ), 'pay' => 'کارت به کارت' ),
	array( 'id' => 4390, 'status' => 'pending', 'date' => '2026-08-02', 'items' => array( array( 13222, 1 ) ), 'pay' => 'پرداخت آنلاین (همه کارت‌های شتاب)' ),
	array( 'id' => 4311, 'status' => 'completed', 'date' => '2026-06-17', 'items' => array( array( 13587, 1 ), array( 90001, 1 ), array( 90007, 2 ), array( 90013, 1 ) ), 'pay' => 'پرداخت در محل' ),
	array( 'id' => 4250, 'status' => 'cancelled', 'date' => '2026-05-03', 'items' => array( array( 13231, 1 ) ), 'pay' => 'پرداخت آنلاین (همه کارت‌های شتاب)' ),
);
$ORDERS = array_map( function ( $d ) { return new PV_AccOrder( $d ); }, $ORDERS );
function wc_get_order( $o ) { if ( is_object( $o ) ) { return $o; } foreach ( $GLOBALS['ORDERS'] as $x ) { if ( $x->get_id() === (int) $o ) { return $x; } } return false; }
function wc_get_orders( $a ) {
	$list = $GLOBALS['ORDERS'];
	if ( ! empty( $a['status'] ) ) { $st = array_map( function ( $s ) { return preg_replace( '/^wc-/', '', $s ); }, (array) $a['status'] ); $list = array_values( array_filter( $list, function ( $o ) use ( $st ) { return in_array( $o->get_status(), $st, true ); } ) ); }
	$limit = $a['limit'] ?? 5; // صفحهٔ سفارش‌ها: ۵ تا در هر صفحه تا صفحه‌بندی هم دیده شود
	$page  = $a['page'] ?? 1;
	$all   = $list;
	if ( $limit > 0 ) { $list = array_slice( $list, ( $page - 1 ) * $limit, $limit ); }
	if ( ( $a['return'] ?? '' ) === 'ids' ) { return array_map( function ( $o ) { return $o->get_id(); }, $list ); }
	return empty( $a['paginate'] ) ? $list : (object) array( 'orders' => $list, 'total' => count( $all ), 'max_num_pages' => $limit > 0 ? (int) ceil( count( $all ) / $limit ) : 1 );
}
function wc_get_account_orders_columns() { return array( 'order-number' => 'سفارش', 'order-date' => 'تاریخ', 'order-status' => 'وضعیت', 'order-total' => 'مجموع', 'order-actions' => 'عملیات' ); }
function wc_get_account_orders_actions( $o ) {
	$a = array();
	if ( $o->has_status( array( 'pending', 'failed' ) ) ) { $a['pay'] = array( 'url' => $GLOBALS['U'] . '/checkout/order-pay/' . $o->get_id() . '/', 'name' => 'پرداخت' ); }
	if ( $o->has_status( array( 'pending', 'failed' ) ) ) { $a['cancel'] = array( 'url' => $GLOBALS['U'] . '/cart/?cancel_order=true&order_id=' . $o->get_id(), 'name' => 'لغو' ); }
	$a['view'] = array( 'url' => $o->get_view_order_url(), 'name' => 'نمایش' );
	return $a;
}
function wc_get_customer_available_downloads( $id ) { return array(); }
function wc_get_account_formatted_address( $t ) { return 'billing' === $t ? 'مریم احمدی<br/>خیابان ولیعصر، کوچه نسترن، پلاک 12، واحد 3<br/>تهران، تهران<br/>1415683911' : ''; }

/* ---------------- منو و نقطه‌های پایانی ---------------- */
function wc_get_account_menu_items() { return array( 'dashboard' => 'پیشخوان', 'orders' => 'سفارش‌ها', 'downloads' => 'دانلودها', 'edit-address' => 'آدرس‌ها', 'edit-account' => 'جزئیات حساب', 'customer-logout' => 'خروج' ); }
function pv_current_item( $ep ) { $cur = $GLOBALS['ENDPOINT']; return ( 'dashboard' === $ep && '' === $cur ) || $ep === $cur || ( 'orders' === $ep && 'view-order' === $cur ); }
function wc_is_current_account_menu_item( $ep ) { return pv_current_item( $ep ); }
function wc_get_account_menu_item_classes( $ep ) { return 'woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--' . $ep . ( pv_current_item( $ep ) ? ' is-active' : '' ); }
function wc_get_account_endpoint_url( $ep ) { return 'dashboard' === $ep ? $GLOBALS['U'] . '/my-account/' : ( 'customer-logout' === $ep ? wc_logout_url() : $GLOBALS['U'] . '/my-account/' . $ep . '/' ); }
function wc_get_endpoint_url( $ep, $v = '' ) { return $GLOBALS['U'] . '/my-account/' . $ep . '/' . ( '' !== $v ? $v . '/' : '' ); }
function wc_logout_url() { return $GLOBALS['U'] . '/my-account/customer-logout/?_wpnonce=abc123'; }
class PV_Query {
	function get_current_endpoint() { return $GLOBALS['ENDPOINT']; }
	function get_endpoint_title( $ep ) { return array( 'orders' => 'سفارش‌ها', 'view-order' => 'سفارش #4521', 'edit-address' => 'آدرس‌ها', 'edit-account' => 'جزئیات حساب', 'lost-password' => 'بازیابی رمز عبور' )[ $ep ] ?? ''; }
}
$WCOBJ->query = new PV_Query();

/* ---------------- محتوای هر بخش (مثلِ wc-template-functions.php) ---------------- */
function pv_wc_tpl( $name, $args = array() ) { extract( $args ); include __DIR__ . '/wc/' . $name; }
function woocommerce_account_navigation() { wc_get_template( 'myaccount/navigation.php' ); }
function woocommerce_account_content() {
	switch ( $GLOBALS['MODE'] ) {
		case 'orders':
		case 'orders-empty':
			$q = wc_get_orders( array( 'customer' => 7, 'page' => 1, 'paginate' => true ) );
			wc_get_template( 'myaccount/orders.php', array( 'current_page' => 1, 'customer_orders' => $q, 'has_orders' => 0 < $q->total, 'wp_button_class' => '' ) );
			break;
		case 'view':
		case 'view-cancelled':
			wc_get_template( 'myaccount/view-order.php', array( 'status' => null, 'order' => wc_get_order( 4521 ), 'order_id' => 4521 ) );
			break;
		case 'addresses':
			wc_get_template( 'myaccount/my-address.php' );
			break;
		case 'edit-address':
			$address = array();
			foreach ( pv_fields( 'billing' ) as $k => $f ) { $f['value'] = array( 'billing_first_name' => 'مریم', 'billing_last_name' => 'احمدی', 'billing_state' => 'THR', 'billing_city' => 'تهران', 'billing_address_1' => 'خیابان ولیعصر، کوچه نسترن، پلاک ۱۲، واحد ۳', 'billing_postcode' => '1415683911', 'billing_phone' => '09121234567' )[ $k ] ?? ''; $address[ $k ] = $f; }
			pv_wc_tpl( 'myaccount/form-edit-address.php', array( 'load_address' => 'billing', 'address' => $address ) );
			break;
		case 'edit-account':
			pv_wc_tpl( 'myaccount/form-edit-account.php', array( 'user' => $GLOBALS['USER'] ) );
			break;
		default:
			wc_get_template( 'myaccount/dashboard.php', array( 'current_user' => $GLOBALS['USER'] ) );
	}
}
function woocommerce_order_details_table( $id ) { // خروجیِ order/order-details.php و order-details-customer.phpِ ووکامرس
	$o    = wc_get_order( $id );
	$rows = '';
	foreach ( $o->get_items() as $it ) {
		$p = $it->get_product();
		$rows .= '<tr class="woocommerce-table__line-item order_item"><td class="woocommerce-table__product-name product-name"><a href="' . esc_url( $p->get_permalink() ) . '">' . esc_html( $p->get_name() ) . '</a> <strong class="product-quantity">&times;&nbsp;' . $it->qty . '</strong></td><td class="woocommerce-table__product-total product-total">' . wc_price( $p->get_price() * $it->qty ) . '</td></tr>';
	}
	echo '<section class="woocommerce-order-details"><h2 class="woocommerce-order-details__title">جزئیات سفارش</h2><table class="woocommerce-table woocommerce-table--order-details shop_table order_details"><thead><tr><th class="woocommerce-table__product-name product-name">محصول</th><th class="woocommerce-table__product-table product-total">مجموع</th></tr></thead><tbody>' . $rows . '</tbody><tfoot>'
		. '<tr><th scope="row">جمع جزء:</th><td>' . wc_price( $o->subtotal() ) . '</td></tr><tr><th scope="row">حمل و نقل:</th><td>' . wc_price( $GLOBALS['SHIP'] ) . ' <small class="shipped_via">از طریق پست پیشتاز</small></td></tr><tr><th scope="row">روش پرداخت:</th><td>' . esc_html( $o->get_payment_method_title() ) . '</td></tr><tr><th scope="row">مجموع:</th><td>' . wc_price( $o->total() ) . '</td></tr></tfoot></table></section>'
		. '<section class="woocommerce-customer-details"><section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses"><div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1"><h2 class="woocommerce-column__title">آدرس صورتحساب</h2><address>مریم احمدی<br>خیابان ولیعصر، کوچه نسترن، پلاک ۱۲، واحد ۳<br>تهران، تهران<br>1415683911<p class="woocommerce-customer-details--phone">09121234567</p><p class="woocommerce-customer-details--email">maryam@example.com</p></address></div>'
		. '<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2"><h2 class="woocommerce-column__title">آدرس حمل و نقل</h2><address>مریم احمدی<br>خیابان ولیعصر، کوچه نسترن، پلاک ۱۲، واحد ۳<br>تهران، تهران<br>1415683911</address></div></section></section>';
}
add_action( 'woocommerce_account_navigation', 'woocommerce_account_navigation' );
add_action( 'woocommerce_account_content', 'woocommerce_output_all_notices', 5 );
add_action( 'woocommerce_account_content', 'woocommerce_account_content' );
add_action( 'woocommerce_view_order', 'woocommerce_order_details_table' );
add_action( 'woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10 );

// WC_Shortcode_My_Account::output()
$SC['woocommerce_my_account'] = function () {
	ob_start();
	echo '<div class="woocommerce">';
	if ( 'lost-password' === $GLOBALS['MODE'] ) {
		woocommerce_output_all_notices();
		pv_wc_tpl( 'myaccount/form-lost-password.php' );
	} elseif ( $GLOBALS['GUEST'] ) {
		$GLOBALS['OPTS']['woocommerce_enable_myaccount_registration'] = 'yes';
		pv_wc_tpl( 'myaccount/form-login.php' );
	} else {
		wc_get_template( 'myaccount/my-account.php' );
	}
	echo '</div>';
	return ob_get_clean();
};

require $THEME . '/page-my-account.php';
