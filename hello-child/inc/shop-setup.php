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
 * ۵) پیگیری سفارش با شمارهٔ موبایل (inc/order-track.php): پاپ‌آپ + دکمهٔ ثابتِ صفحهٔ اصلی.
 *
 * اولویتِ فیلترها خیلی بالاست (۹۹۹۹) تا اگر افزونه‌ای (ووکامرس فارسی، ویرایشگرِ فیلدها، ورودِ پیامکی…)
 * فیلدها را دوباره تنظیم کرد، باز هم موبایل اجباری و ایمیل اختیاری بماند.
 *
 * همین تغییرها روی فرم‌های «ویرایش آدرس» در حساب کاربری هم اعمال می‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// قالب‌ها با این ثابت می‌فهمند این فایل از functions.php لود شده (وگرنه به مدیر هشدار می‌دهند)
if ( ! defined( 'KS_SHOP_SETUP' ) ) { define( 'KS_SHOP_SETUP', true ); }

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
if ( ! function_exists( 'ks_billing_fields' ) ) {
function ks_billing_fields( $fields ) {
	if ( isset( $fields['billing_country'] ) && ! in_array( 'ks-field-hidden', (array) ( $fields['billing_country']['class'] ?? array() ), true ) ) {
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
}
}
add_filter( 'woocommerce_billing_fields', 'ks_billing_fields', 9999 ); // فرمِ ویرایشِ آدرس + پایهٔ تسویه حساب
// تسویه حساب و اعتبارسنجیِ ثبتِ سفارش از این فهرست می‌خوانند؛ افزونه‌های ویرایشِ فیلد معمولاً همین‌جا تغییر می‌دهند
add_filter( 'woocommerce_checkout_fields', function ( $fields ) {
	if ( isset( $fields['billing'] ) && is_array( $fields['billing'] ) ) {
		$fields['billing'] = ks_billing_fields( $fields['billing'] );
	}
	if ( isset( $fields['shipping']['shipping_country'] ) && ! in_array( 'ks-field-hidden', (array) ( $fields['shipping']['shipping_country']['class'] ?? array() ), true ) ) {
		$fields['shipping']['shipping_country']['class'][] = 'ks-field-hidden';
	}
	return $fields;
}, 9999 );

/* کشورِ آدرسِ ارسال هم پنهان */
add_filter( 'woocommerce_shipping_fields', function ( $fields ) {
	if ( isset( $fields['shipping_country'] ) && ! in_array( 'ks-field-hidden', (array) ( $fields['shipping_country']['class'] ?? array() ), true ) ) {
		$fields['shipping_country']['class'][] = 'ks-field-hidden';
	}
	return $fields;
}, 9999 );

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

/* ۵) پیگیری سفارش با شمارهٔ موبایل */
require_once __DIR__ . '/order-track.php';
