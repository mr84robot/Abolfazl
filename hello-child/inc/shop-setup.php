<?php
/**
 * تنظیماتِ سراسریِ فروشگاه — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/inc/shop-setup.php
 *
 * ⚠️ این فایل باید از functions.phpِ قالبِ فرزند لود شود (یک خط، انتهای functions.php):
 *
 *     require_once get_stylesheet_directory() . '/inc/shop-setup.php';
 *
 * چرا functions.php؟ ووکامرس فیلدها را دو بار می‌خواند: یک بار موقعِ نمایشِ فرم، یک بار موقعِ ثبتِ سفارش
 * (درخواستِ Ajax که هیچ قالبی در آن اجرا نمی‌شود). اگر این تغییرها فقط در قالبِ صفحه بود، فرم «ایمیل اختیاری»
 * نشان می‌داد ولی موقعِ ثبت باز هم ایمیل را اجباری حساب می‌کرد.
 *
 * ۱) کشور: فقط ایران. فیلدِ کشور از فرم برداشته می‌شود (پنهان، با مقدارِ IR)؛ ووکامرس برای استان‌ها،
 *    مناطقِ ارسال و مالیات به کشور نیاز دارد، پس خودِ مقدار باید بماند.
 * ۲) ایمیل: اختیاری (اگر وارد شود، درستیِ قالبش همچنان بررسی می‌شود).
 * ۳) شمارهٔ تلفن/موبایل: اجباری (اگر در تنظیماتِ ووکامرس «پنهان» شده باشد، دوباره اضافه می‌شود).
 * ۴) حساب کاربری ← جزئیات حساب: ایمیل اختیاری (کاربرانی که با موبایل وارد شده‌اند مجبور به واردکردنِ ایمیل نباشند).
 *
 * همین تغییرها روی فرم‌های «ویرایش آدرس» در حساب کاربری هم اعمال می‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ۱) فقط ایران — فهرستِ کشورهای فروش و ارسال، و پیش‌فرضِ فرم */
if ( ! function_exists( 'ks_only_iran' ) ) {
	function ks_only_iran( $countries ) {
		return ( is_array( $countries ) && isset( $countries['IR'] ) ) ? array( 'IR' => $countries['IR'] ) : $countries;
	}
}
add_filter( 'woocommerce_countries_allowed_countries', 'ks_only_iran' );
add_filter( 'woocommerce_countries_shipping_countries', 'ks_only_iran' );
add_filter( 'default_checkout_billing_country', function () { return 'IR'; } );
add_filter( 'default_checkout_shipping_country', function () { return 'IR'; } );

/* ۲ و ۳) فیلدهای صورتحساب */
add_filter( 'woocommerce_billing_fields', function ( $fields ) {
	if ( isset( $fields['billing_country'] ) ) {
		$fields['billing_country']['class'][] = 'ks-field-hidden'; // با فقط-ایران، ووکامرس آن را input مخفی + نامِ کشور چاپ می‌کند؛ ردیفش پنهان می‌شود
	}
	if ( isset( $fields['billing_email'] ) ) {
		$fields['billing_email']['required'] = false;
	}
	if ( ! isset( $fields['billing_phone'] ) ) {
		$fields['billing_phone'] = array(
			'label'        => 'شماره موبایل',
			'type'         => 'tel',
			'class'        => array( 'form-row-wide' ),
			'validate'     => array( 'phone' ),
			'autocomplete' => 'tel',
			'priority'     => 100,
		);
	}
	$fields['billing_phone']['required'] = true;
	return $fields;
}, 20 );

/* کشورِ آدرسِ ارسال هم پنهان */
add_filter( 'woocommerce_shipping_fields', function ( $fields ) {
	if ( isset( $fields['shipping_country'] ) ) {
		$fields['shipping_country']['class'][] = 'ks-field-hidden';
	}
	return $fields;
}, 20 );

/* ۴) جزئیاتِ حساب: ایمیل اختیاری. اگر خالی بماند ووکامرس ایمیلِ فعلی را دست نمی‌زند؛ اگر پر شود درستی‌اش بررسی می‌شود.
   برچسبِ «*» فرم با کلاسِ ks-email-optional روی body به «(اختیاری)» تبدیل می‌شود (account.css و account.js). */
add_filter( 'woocommerce_save_account_details_required_fields', function ( $fields ) {
	unset( $fields['account_email'] );
	return $fields;
} );
add_filter( 'body_class', function ( $classes ) {
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$classes[] = 'ks-email-optional';
	}
	return $classes;
} );
