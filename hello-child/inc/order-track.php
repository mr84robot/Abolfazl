<?php
/**
 * پیگیری سفارش با شمارهٔ موبایل — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/inc/order-track.php   (inc/shop-setup.php لودش می‌کند)
 *
 * پاپ‌آپِ «پیگیری سفارش» (روی همهٔ صفحه‌ها در footer.php) + دکمهٔ ثابتِ پایین-چپ روی صفحهٔ اصلی.
 * مشتری شمارهٔ موبایلِ ثبت‌شده در سفارش و شمارهٔ سفارش را وارد می‌کند و وضعیت، مراحل، کالاها و
 * آخرین پیامِ فروشگاه (یادداشتِ مشتری؛ مثلاً کد رهگیری پست) را می‌بیند.
 *
 * چرا شمارهٔ سفارش هم لازم است؟ شمارهٔ موبایل را خیلی‌ها دارند؛ اگر فقط موبایل کافی بود، هر کسی با
 * داشتنِ شمارهٔ دیگری خریدهایش را می‌دید. فرمِ پیگیریِ خودِ ووکامرس هم شمارهٔ سفارش + ایمیل می‌خواهد؛
 * این‌جا ایمیل با موبایل جایگزین شده.
 *
 * درخواست‌ها: /?wc-ajax=ks_track_order (POST: phone، order). نتیجهٔ ناموفق همیشه یک پیام است
 * (پیدا نشد / موبایل نمی‌خواند فرقی ندارد) و هر IP حداکثر ۱۰ تلاش در ۱۵ دقیقه دارد.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once __DIR__ . '/fa.php';

if ( ! function_exists( 'ks_track_latin' ) ) {
	/** ارقامِ فارسی/عربی ← انگلیسی */
	function ks_track_latin( $s ) {
		return strtr( (string) $s, array(
			'۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
			'٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
		) );
	}
}

if ( ! function_exists( 'ks_track_phone_key' ) ) {
	/** ۱۰ رقمِ آخرِ شماره بدونِ ۰ / ۹۸+ / ۰۰۹۸ (۰۹۱۲۱۲۳۴۵۶۷ و ۹۸۹۱۲۱۲۳۴۵۶۷+ یکی‌اند). */
	function ks_track_phone_key( $phone ) {
		$d = preg_replace( '/\D+/', '', ks_track_latin( $phone ) );
		$d = preg_replace( '/^(0098|98(?=\d{10}$)|0)/', '', $d );
		return strlen( $d ) >= 10 ? substr( $d, -10 ) : '';
	}
}

if ( ! function_exists( 'ks_track_find' ) ) {
	/** سفارش با شمارهٔ سفارش؛ فیلترِ ووکامرس افزونه‌های شماره‌گذاریِ سفارشی را هم پوشش می‌دهد. */
	function ks_track_find( $number ) {
		$order = wc_get_order( apply_filters( 'woocommerce_shortcode_order_tracking_order_id', $number ) ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment — هوکِ خودِ ووکامرس
		if ( ! $order || ! is_a( $order, 'WC_Order' ) || in_array( $order->get_status(), array( 'checkout-draft', 'trash', 'auto-draft' ), true ) ) {
			return null;
		}
		return $order;
	}
}

if ( ! function_exists( 'ks_track_payload' ) ) {
	/** داده‌ای که پاپ‌آپ نمایش می‌دهد (همه متنِ ساده؛ JS با textContent می‌گذارد). */
	function ks_track_payload( $order ) {
		$st   = $order->get_status();
		$tone = array( 'pending' => 'wait', 'on-hold' => 'wait', 'processing' => 'run', 'completed' => 'done', 'cancelled' => 'off', 'refunded' => 'off', 'failed' => 'bad' );
		$bad  = in_array( $st, array( 'cancelled', 'refunded', 'failed' ), true );
		$paid = $order->is_paid();
		$done = 'completed' === $st;
		$text = function ( $html ) { return trim( preg_replace( '/\s+/u', ' ', html_entity_decode( wp_strip_all_tags( (string) $html ), ENT_QUOTES, 'UTF-8' ) ) ); };

		$items = array();
		foreach ( $order->get_items() as $item ) {
			$items[] = array( 'name' => $text( $item->get_name() ), 'qty' => ks_fa_digits( $item->get_quantity() ) );
		}
		$more  = max( 0, count( $items ) - 5 );
		$items = array_slice( $items, 0, 5 );

		$notes = array();
		foreach ( array_slice( (array) $order->get_customer_order_notes(), 0, 2 ) as $note ) {
			$t       = strtotime( $note->comment_date );
			$notes[] = array( 'date' => ks_jdate( gmdate( 'Y-n-j', $t ) ) . '، ساعت ' . ks_fa_digits( gmdate( 'H:i', $t ) ), 'text' => $text( $note->comment_content ) );
		}

		$created = $order->get_date_created();
		$data    = array(
			'number' => ks_fa_digits( $order->get_order_number() ),
			'date'   => $created ? ks_jdate( $created->date( 'Y-n-j' ) ) : '',
			'status' => wc_get_order_status_name( $st ),
			'tone'   => $tone[ $st ] ?? 'other',
			'total'  => ks_fa_digits( $text( $order->get_formatted_order_total() ) ),
			'pay'    => $text( $order->get_payment_method_title() ),
			'bad'    => $bad,
			'steps'  => $bad ? array() : array(
				array( 'ثبت سفارش', 'done' ),
				array( 'تأیید پرداخت', $paid ? 'done' : 'current' ),
				array( 'آماده‌سازی و ارسال', $done ? 'done' : ( $paid ? 'current' : '' ) ),
				array( 'تحویل', $done ? 'done' : '' ),
			),
			'items'  => $items,
			'more'   => $more ? ks_fa_digits( $more ) : '',
			'notes'  => $notes,
			// لینکِ جزئیات فقط برای صاحبِ سفارش که وارد حسابش شده
			'url'    => ( get_current_user_id() && (int) $order->get_customer_id() === get_current_user_id() ) ? $order->get_view_order_url() : '',
		);
		return apply_filters( 'ks_track_payload', $data, $order );
	}
}

if ( ! function_exists( 'ks_track_ajax' ) ) {
	function ks_track_ajax() {
		$phone  = ks_track_phone_key( wp_unslash( $_POST['phone'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing — جستجوی فقط‌خواندنی؛ با شمارهٔ سفارش + موبایل و محدودیتِ تلاش محافظت می‌شود (nonce در کشِ صفحه کهنه می‌شد)
		$number = ltrim( trim( ks_track_latin( wc_clean( wp_unslash( $_POST['order'] ?? '' ) ) ) ), '#' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( ! $phone || '' === $number || strlen( $number ) > 32 ) {
			wp_send_json_error( array( 'msg' => 'شماره موبایل و شماره سفارش را کامل وارد کنید.' ), 400 );
		}

		$key   = 'ks_trk_' . md5( class_exists( 'WC_Geolocation' ) ? WC_Geolocation::get_ip_address() : ( $_SERVER['REMOTE_ADDR'] ?? '' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$tries = (int) get_transient( $key );
		if ( $tries >= 10 ) {
			wp_send_json_error( array( 'msg' => 'تعداد تلاش‌ها زیاد شد؛ چند دقیقهٔ دیگر دوباره امتحان کنید.' ), 429 );
		}
		set_transient( $key, $tries + 1, 15 * MINUTE_IN_SECONDS );

		$order = ks_track_find( $number );
		$match = $order && in_array( $phone, array_filter( array( ks_track_phone_key( $order->get_billing_phone() ), ks_track_phone_key( $order->get_shipping_phone() ) ) ), true );
		if ( ! $match ) {
			wp_send_json_error( array( 'msg' => 'سفارشی با این شماره موبایل و شماره سفارش پیدا نشد. شماره‌ها را دوباره بررسی کنید.' ), 404 );
		}
		wp_send_json_success( ks_track_payload( $order ) );
	}
}
add_action( 'wc_ajax_ks_track_order', 'ks_track_ajax' );

if ( ! function_exists( 'ks_track_modal' ) ) {
	/**
	 * پاپ‌آپ (و روی صفحهٔ اصلی دکمهٔ ثابت). footer.php صدایش می‌زند.
	 * هر لینکی با href="#ks-track" یا دکمه‌ای با data-ks-trk-open هم پاپ‌آپ را باز می‌کند (مثلاً آیتمِ منو).
	 */
	function ks_track_modal( $with_button = false ) {
		$url = class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( 'ks_track_order' ) : home_url( '/?wc-ajax=ks_track_order' );
		$dir = get_stylesheet_directory();
		wp_enqueue_script( 'ks-track', get_stylesheet_directory_uri() . '/assets/js/track.js', array(), (string) @filemtime( $dir . '/assets/js/track.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		$ic = function ( $p ) { return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p . '</svg>'; };
		$truck = '<path d="M2.6 6.6h10.9v8.2H2.6z"/><path d="M13.5 9.4h3.3l2.6 2.7v2.7h-5.9z"/><circle cx="6.4" cy="17.3" r="1.7"/><circle cx="16" cy="17.3" r="1.7"/>';
		?>
		<dialog class="ks-trk" id="ks-track" aria-labelledby="ks-trk-title" data-ks-trk data-url="<?php echo esc_url( $url ); ?>">
			<div class="ks-trk__panel">
				<header class="ks-trk__head">
					<span class="ks-trk__ic"><?php echo $ic( $truck ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="ks-trk__ht">
						<strong class="ks-trk__t" id="ks-trk-title">پیگیری سفارش</strong>
						<span class="ks-trk__d">شماره موبایلِ ثبت‌شده در سفارش و شماره سفارش را وارد کنید.</span>
					</span>
					<button type="button" class="ks-trk__x" data-ks-trk-close aria-label="بستن"><?php echo $ic( '<path d="M6 6l12 12M18 6 6 18"/>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				</header>

				<form class="ks-trk__form" data-ks-trk-form novalidate>
					<label class="ks-trk__f">
						<span>شماره موبایل</span>
						<input type="tel" name="phone" inputmode="numeric" autocomplete="tel" placeholder="09xxxxxxxxx" maxlength="16" dir="ltr" required>
					</label>
					<label class="ks-trk__f">
						<span>شماره سفارش</span>
						<input type="text" name="order" inputmode="numeric" autocomplete="off" placeholder="مثلاً 4521" maxlength="20" dir="ltr" required>
					</label>
					<p class="ks-trk__hint">شماره سفارش در پیامک و صفحهٔ «سفارش ثبت شد» آمده است.</p>
					<p class="ks-trk__err" data-ks-trk-err role="alert" hidden></p>
					<button type="submit" class="ks-trk__go" data-ks-trk-go><span>پیگیری سفارش</span></button>
				</form>

				<div class="ks-trk__res" data-ks-trk-res aria-live="polite" hidden></div>
			</div>
		</dialog>
		<?php if ( $with_button ) : ?>
			<button type="button" class="ks-trk-fab" data-ks-trk-open aria-haspopup="dialog" aria-controls="ks-track">
				<?php echo $ic( $truck ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span>پیگیری سفارش</span>
			</button>
		<?php endif; ?>
		<?php
	}
}
