<?php
/**
 * توابعِ کمکیِ حساب کاربری — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/inc/account.php
 *
 * از قالب‌های woocommerce/myaccount/*.php با require_once لود می‌شود (به functions.php دست نمی‌زنیم).
 * آیکون‌ها، ارقامِ فارسی و تاریخِ شمسی از inc/cart.php و inc/fa.php می‌آیند.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once get_stylesheet_directory() . '/inc/fa.php';
require_once get_stylesheet_directory() . '/inc/cart.php';

if ( ! function_exists( 'ks_acc_endpoint_icon' ) ) {
	/** آیکونِ هر بخشِ منو؛ بخش‌هایی که افزونه‌ها اضافه می‌کنند آیکونِ عمومی می‌گیرند. */
	function ks_acc_endpoint_icon( $endpoint ) {
		$map = array(
			'dashboard'       => 'grid',
			'orders'          => 'box',
			'downloads'       => 'download',
			'edit-address'    => 'pin',
			'payment-methods' => 'card',
			'edit-account'    => 'user',
			'customer-logout' => 'logout',
		);
		$name = $map[ $endpoint ] ?? ( false !== strpos( $endpoint, 'wish' ) ? 'heart' : 'note' );
		return ks_cart_icon( $name );
	}
}

if ( ! function_exists( 'ks_acc_menu_items' ) ) {
	/**
	 * همان منوی ووکامرس (wc_get_account_menu_items، با تغییراتِ افزونه‌ها).
	 * «دانلودها» فقط وقتی دیده می‌شود که مشتری واقعاً فایلی برای دانلود دارد (فروشگاه کالای دانلودی ندارد).
	 */
	function ks_acc_menu_items() {
		$items = wc_get_account_menu_items();
		if ( isset( $items['downloads'] ) && function_exists( 'wc_get_customer_available_downloads' ) && ! wc_get_customer_available_downloads( get_current_user_id() ) ) {
			unset( $items['downloads'] );
		}
		return $items;
	}
}

if ( ! function_exists( 'ks_acc_phone' ) ) {
	/** موبایلِ مشتری برای نمایش (صورتحساب، یا شمارهٔ ورودِ پیامکی)؛ +98 به 0 تبدیل می‌شود. */
	function ks_acc_phone( $user_id ) {
		foreach ( array( 'billing_phone', 'digits_phone', 'digits_phone_no' ) as $key ) {
			$v = trim( (string) get_user_meta( $user_id, $key, true ) );
			if ( '' !== $v ) {
				$v = preg_replace( '/[^\d+]/', '', $v );
				if ( 0 === strpos( $v, '+98' ) ) { $v = '0' . substr( $v, 3 ); }
				if ( 0 === strpos( $v, '98' ) && 12 === strlen( $v ) ) { $v = '0' . substr( $v, 2 ); }
				if ( 10 === strlen( $v ) && '9' === $v[0] ) { $v = '0' . $v; }
				return $v;
			}
		}
		return '';
	}
}

if ( ! function_exists( 'ks_acc_name' ) ) {
	function ks_acc_name( $user ) {
		$name = trim( $user->first_name . ' ' . $user->last_name );
		return '' !== $name ? $name : $user->display_name;
	}
}

if ( ! function_exists( 'ks_acc_user_card' ) ) {
	/** کارتِ کاربر بالای منو: حرفِ اولِ نام، نام، موبایل/ایمیل. */
	function ks_acc_user_card() {
		$user = wp_get_current_user();
		if ( ! $user || ! $user->ID ) { return; }
		$name    = ks_acc_name( $user );
		$phone   = ks_acc_phone( $user->ID );
		$initial = function_exists( 'mb_substr' ) ? mb_substr( $name, 0, 1, 'UTF-8' ) : substr( $name, 0, 1 );
		$letter  = preg_match( '/^[\p{L}]$/u', $initial ); // اگر نام عدد است (ورود با موبایل)، آیکون
		$looks_phone = (bool) preg_match( '/^[\d+\s۰-۹]+$/u', $name );
		?>
		<div class="ks-acc-user">
			<span class="ks-acc-user__av" aria-hidden="true"><?php echo $letter && ! $looks_phone ? esc_html( $initial ) : ks_cart_icon( 'user' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			<span class="ks-acc-user__txt">
				<strong class="ks-acc-user__name"><?php echo esc_html( $looks_phone ? 'کاربر خانه سعادت' : $name ); ?></strong>
				<?php if ( $phone ) : ?>
					<span class="ks-acc-user__sub" dir="ltr"><?php echo esc_html( ks_fa_digits( $phone ) ); ?></span>
				<?php elseif ( $user->user_email && false === strpos( $user->user_email, '@digits' ) ) : ?>
					<span class="ks-acc-user__sub" dir="ltr"><?php echo esc_html( $user->user_email ); ?></span>
				<?php endif; ?>
			</span>
		</div>
		<?php
	}
}

if ( ! function_exists( 'ks_acc_date' ) ) {
	/** تاریخِ شمسیِ یک WC_DateTime (به وقتِ سایت). */
	function ks_acc_date( $dt, $with_year = true ) {
		return $dt ? ks_jdate( $dt->date( 'Y-n-j' ), $with_year ) : '';
	}
}

if ( ! function_exists( 'ks_acc_status' ) ) {
	/** برچسبِ رنگیِ وضعیتِ سفارش (نامِ وضعیت همان ترجمهٔ ووکامرس است). */
	function ks_acc_status( $order ) {
		$st   = $order->get_status();
		$tone = array(
			'pending'    => 'wait',
			'on-hold'    => 'wait',
			'processing' => 'run',
			'completed'  => 'done',
			'cancelled'  => 'off',
			'refunded'   => 'off',
			'failed'     => 'bad',
		);
		return '<span class="ks-st ks-st--' . esc_attr( $tone[ $st ] ?? 'other' ) . ' ks-st--' . esc_attr( $st ) . '">' . esc_html( wc_get_order_status_name( $st ) ) . '</span>';
	}
}

if ( ! function_exists( 'ks_acc_thumbs' ) ) {
	/** عکسِ چند کالای اولِ سفارش (+ تعدادِ بقیه). */
	function ks_acc_thumbs( $order, $max = 3 ) {
		$items = $order->get_items();
		$out   = '';
		$i     = 0;
		foreach ( $items as $item ) {
			if ( $i++ >= $max ) { break; }
			$p    = $item->get_product();
			$img  = $p ? $p->get_image( 'woocommerce_gallery_thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) ) : ( function_exists( 'wc_placeholder_img' ) ? wc_placeholder_img( 'woocommerce_gallery_thumbnail' ) : '' );
			$out .= '<span class="ks-thumbs__i" title="' . esc_attr( $item->get_name() ) . '">' . $img . '</span>';
		}
		if ( count( $items ) > $max ) {
			$out .= '<span class="ks-thumbs__i ks-thumbs__more">+' . esc_html( ks_fa_digits( count( $items ) - $max ) ) . '</span>';
		}
		return '<span class="ks-thumbs">' . $out . '</span>';
	}
}

if ( ! function_exists( 'ks_acc_title' ) ) {
	/** عنوانِ بخشِ فعلی (پیشخوان، سفارش‌ها، سفارش #۱۲۳، …) با ارقامِ فارسی. */
	function ks_acc_title() {
		$ep = ( function_exists( 'WC' ) && WC()->query ) ? WC()->query->get_current_endpoint() : '';
		if ( ! $ep ) {
			$items = wc_get_account_menu_items();
			return $items['dashboard'] ?? 'پیشخوان';
		}
		$t = WC()->query->get_endpoint_title( $ep );
		if ( ! $t ) {
			$items = wc_get_account_menu_items();
			$t     = $items[ $ep ] ?? '';
		}
		return ks_fa_digits( wp_strip_all_tags( $t ) );
	}
}

if ( ! function_exists( 'ks_acc_help' ) ) {
	function ks_acc_help() {
		echo '<a class="ks-cart-help ks-acc-help" href="tel:+989001110562">' . ks_cart_icon( 'phone' ) . '<span>سؤال یا مشکلی درباره سفارش دارید؟ <strong>۰۹۰۰۱۱۱۰۵۶۲</strong></span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
