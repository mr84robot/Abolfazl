<?php
/**
 * ابزارهای فارسی — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/inc/fa.php
 *
 * ks_fa_digits()  ارقامِ انگلیسی ← فارسی
 * ks_jdate()      تاریخِ شمسی برای نمایش (مثلاً «۳۰ شهریور ۱۴۰۵»)
 *
 * سایت افزونهٔ تاریخ شمسی ندارد و get_the_date() تاریخِ میلادی می‌دهد (روی کارت‌های وبلاگ «۲۰۲۶-۰۹-۲۱»
 * دیده می‌شد). اگر بعداً افزونهٔ شمسی نصب شود و خودش سال را شمسی بدهد، همان استفاده می‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'ks_fa_digits' ) ) {
	function ks_fa_digits( $str ) {
		return str_replace( array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ), (string) $str );
	}
}

if ( ! function_exists( 'ks_g2j' ) ) {
	/** میلادی ← شمسی (الگوریتمِ jdf). خروجی: array( سال, ماه, روز ). */
	function ks_g2j( $gy, $gm, $gd ) {
		$g_d_m = array( 0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334 );
		$gy2   = ( $gm > 2 ) ? ( $gy + 1 ) : $gy;
		$days  = 355666 + ( 365 * $gy ) + intdiv( $gy2 + 3, 4 ) - intdiv( $gy2 + 99, 100 ) + intdiv( $gy2 + 399, 400 ) + $gd + $g_d_m[ $gm - 1 ];
		$jy    = -1595 + ( 33 * intdiv( $days, 12053 ) );
		$days %= 12053;
		$jy   += 4 * intdiv( $days, 1461 );
		$days %= 1461;
		if ( $days > 365 ) {
			$jy  += intdiv( $days - 1, 365 );
			$days = ( $days - 1 ) % 365;
		}
		if ( $days < 186 ) {
			$jm = 1 + intdiv( $days, 31 );
			$jd = 1 + ( $days % 31 );
		} else {
			$jm = 7 + intdiv( $days - 186, 30 );
			$jd = 1 + ( ( $days - 186 ) % 30 );
		}
		return array( $jy, $jm, $jd );
	}
}

if ( ! function_exists( 'ks_jdate' ) ) {
	/**
	 * تاریخِ شمسیِ خوانا.
	 *
	 * @param string $ymd تاریخِ محلیِ میلادی به شکلِ Y-n-j (مثلاً از get_post_time( 'Y-n-j' )) یا شمسیِ آماده.
	 * @param bool   $with_year سال هم نوشته شود؟
	 */
	function ks_jdate( $ymd, $with_year = true ) {
		$p = array_map( 'intval', explode( '-', (string) $ymd ) );
		if ( count( $p ) !== 3 || ! $p[0] ) { return ks_fa_digits( $ymd ); }
		$months = array( 1 => 'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند' );
		list( $y, $m, $d ) = $p[0] > 1700 ? ks_g2j( $p[0], $p[1], $p[2] ) : $p; // سالِ زیر ۱۷۰۰ یعنی از قبل شمسی است
		if ( empty( $months[ $m ] ) ) { return ks_fa_digits( $ymd ); }
		return ks_fa_digits( $d . ' ' . $months[ $m ] . ( $with_year ? ' ' . $y : '' ) );
	}
}
