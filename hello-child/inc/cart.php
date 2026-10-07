<?php
/**
 * توابعِ کمکیِ صفحهٔ سبد خرید — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/inc/cart.php
 *
 * از قالب‌های woocommerce/cart/*.php با require_once لود می‌شود (به functions.php دست نمی‌زنیم).
 * همهٔ مبالغ با توابعِ خودِ ووکامرس (wc_price / wc_get_price_to_display) ساخته می‌شوند تا
 * واحدِ پول، مالیات و جداکننده‌ها دقیقاً مثلِ بقیهٔ فروشگاه باشد.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'ks_fa_digits' ) ) {
	function ks_fa_digits( $str ) {
		return str_replace( array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ), (string) $str );
	}
}

if ( ! function_exists( 'ks_cart_icon' ) ) {
	/** آیکونِ خطیِ ۲۴×۲۴ (همان سبکِ آیکون‌های صفحهٔ اصلی). */
	function ks_cart_icon( $name, $class = '' ) {
		$p = array(
			'trash'   => '<path d="M4 7h16"/><path d="M10 11v6M14 11v6"/><path d="m5.5 7 1 12.2A1.9 1.9 0 0 0 8.4 21h7.2a1.9 1.9 0 0 0 1.9-1.8l1-12.2"/><path d="M9 7V4.6a.6.6 0 0 1 .6-.6h4.8a.6.6 0 0 1 .6.6V7"/>',
			'plus'    => '<path d="M12 5v14M5 12h14"/>',
			'minus'   => '<path d="M5 12h14"/>',
			'arrow'   => '<path d="M19 12H5M11 18l-6-6 6-6"/>',
			'check'   => '<path d="m5 12.5 4.2 4.2L19 7"/>',
			'x'       => '<path d="M6 6l12 12M18 6 6 18"/>',
			'user'    => '<circle cx="12" cy="8" r="3.6"/><path d="M4.8 20a7.2 7.2 0 0 1 14.4 0"/>',
			'pin'     => '<path d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11z"/><circle cx="12" cy="10" r="2.3"/>',
			'note'    => '<path d="M5 3.5h10l4 4V20.5H5z"/><path d="M14.5 3.5V8H19"/><path d="M8.5 12h7M8.5 15.5h5"/>',
			'card'    => '<rect x="3" y="5.5" width="18" height="13" rx="1.5"/><path d="M3 10h18"/><path d="M7 15h3"/>',
			'edit'    => '<path d="M4 20h4l10.5-10.5-4-4L4 16z"/><path d="m13.5 6.5 4 4"/>',
			'tag'     => '<path d="M3.5 12.2V4.5a1 1 0 0 1 1-1h7.7l8.3 8.3a1 1 0 0 1 0 1.4l-7.3 7.3a1 1 0 0 1-1.4 0z"/><circle cx="8" cy="8" r="1.4"/>',
			'percent' => '<path d="M19 5 5 19"/><circle cx="7" cy="7" r="2.2"/><circle cx="17" cy="17" r="2.2"/>',
			'bag'     => '<path d="M7.5 3.5 4.8 7.2V19a1.8 1.8 0 0 0 1.8 1.8h10.8a1.8 1.8 0 0 0 1.8-1.8V7.2l-2.7-3.7z"/><path d="M4.8 7.2h14.4"/><path d="M15.6 10.6a3.6 3.6 0 0 1-7.2 0"/>',
			'phone'   => '<path d="M6.5 4.2h3l1.3 3.4-2 1.4a11 11 0 0 0 5.2 5.2l1.4-2 3.4 1.3v3a1.6 1.6 0 0 1-1.8 1.6C10.6 17.6 6.4 13.4 5 6.1a1.6 1.6 0 0 1 1.5-1.9z"/>',
			'lock'    => '<rect x="5" y="10.5" width="14" height="10" rx="1.5"/><path d="M8 10.5V7.6a4 4 0 0 1 8 0v2.9"/><path d="M12 14.6v2.2"/>',
			'install' => '<rect x="3" y="5" width="18" height="13" rx="2"/><path d="M3 9.2h18"/><path d="M6.4 14.3h3.4"/><path d="m13.6 14.8 1.7 1.7 3.1-3.3"/>',
			'shield'  => '<path d="M12 3 5 5.8v4.9c0 4.2 2.9 7.4 7 8.6 4.1-1.2 7-4.4 7-8.6V5.8z"/><path d="m9.1 11.6 2 2 3.9-4.1"/>',
			'truck'   => '<path d="M2.6 6.6h10.9v8.2H2.6z"/><path d="M13.5 9.4h3.3l2.6 2.7v2.7h-5.9z"/><circle cx="6.4" cy="17.3" r="1.7"/><circle cx="16" cy="17.3" r="1.7"/>',
		);
		if ( empty( $p[ $name ] ) ) { return ''; }
		return '<svg class="' . esc_attr( trim( 'ks-ic ' . $class ) ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p[ $name ] . '</svg>';
	}
}

if ( ! function_exists( 'ks_cart_steps' ) ) {
	/** نوارِ مراحلِ خرید: سبد خرید ← اطلاعات ارسال ← پرداخت */
	/**
	 * نوارِ مراحلِ خرید (سبد خرید ← ارسال و پرداخت ← تکمیل خرید).
	 * $current: ۱ سبد، ۲ تسویه حساب، ۳ سفارش ثبت شد (همهٔ مراحل تیک می‌خورند).
	 * مرحلهٔ ۱ از صفحه‌های بعد به سبد لینک دارد و مرحلهٔ ۲ از سبد به تسویه حساب.
	 */
	function ks_cart_steps( $current = 1 ) {
		$steps = array(
			1 => array( 'سبد خرید', function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '' ),
			2 => array( 'ارسال و پرداخت', function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '' ),
			3 => array( 'تکمیل خرید', '' ),
		);
		echo '<ol class="ks-steps" aria-label="مراحل خرید">';
		foreach ( $steps as $n => $s ) {
			$done = $n < $current || 3 === $current;
			$cls  = $done ? 'is-done' : ( $n === $current ? 'is-current' : '' );
			$link = $s[1] && ( ( 1 === $n && $current > 1 && $current < 3 ) || ( 2 === $n && 1 === $current ) );
			echo '<li class="ks-steps__i ' . esc_attr( $cls ) . '"' . ( $n === $current ? ' aria-current="step"' : '' ) . '>';
			$label = '<span class="ks-steps__n">' . ( $done ? ks_cart_icon( 'check' ) : esc_html( ks_fa_digits( $n ) ) ) . '</span><span class="ks-steps__t">' . esc_html( $s[0] ) . '</span>';
			echo $link ? '<a class="ks-steps__a" href="' . esc_url( $s[1] ) . '">' . $label . '</a>' : '<span class="ks-steps__a">' . $label . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</li>';
		}
		echo '</ol>';
	}
}

if ( ! function_exists( 'ks_cart_amounts' ) ) {
	/**
	 * مبالغِ خلاصهٔ سبد (همه به شکلی که به مشتری نمایش داده می‌شود؛ با/بدونِ مالیات طبقِ تنظیماتِ ووکامرس):
	 *   items_save  = تخفیفِ خودِ کالاها (قیمتِ قبلی − قیمتِ فعلی) × تعداد
	 *   regular     = قیمتِ کالاها پیش از تخفیف
	 *   coupon_save = تخفیفِ کدهای تخفیف
	 *   total_save  = سودِ کلِ مشتری از این خرید
	 */
	function ks_cart_amounts() {
		$cart  = WC()->cart;
		$items = 0.0;
		foreach ( $cart->get_cart() as $item ) {
			$p = isset( $item['data'] ) ? $item['data'] : null;
			if ( ! $p || ! $p->is_on_sale() ) { continue; }
			$reg = (float) $p->get_regular_price();
			$qty = (int) $item['quantity'];
			if ( $reg <= 0 || $qty < 1 ) { continue; }
			$items += max( 0, (float) wc_get_price_to_display( $p, array( 'price' => $reg, 'qty' => $qty ) ) - (float) wc_get_price_to_display( $p, array( 'qty' => $qty ) ) );
		}
		$incl   = $cart->display_prices_including_tax();
		$sub    = (float) $cart->get_subtotal() + ( $incl ? (float) $cart->get_subtotal_tax() : 0 );
		$coupon = (float) $cart->get_discount_total() + ( $incl ? (float) $cart->get_discount_tax() : 0 );
		$reg    = $sub + $items;
		$save   = $items + $coupon;
		return array(
			'items_save'  => $items,
			'regular'     => $reg,
			'coupon_save' => $coupon,
			'total_save'  => $save,
			'save_pct'    => $reg > 0 ? (int) round( $save / $reg * 100 ) : 0,
		);
	}
}

if ( ! function_exists( 'ks_cart_item_pct' ) ) {
	/** درصدِ تخفیفِ یک کالای داخلِ سبد (برای متغیرها، خودِ متغیرِ انتخاب‌شده). */
	function ks_cart_item_pct( $product ) {
		if ( ! $product || ! $product->is_on_sale() ) { return 0; }
		$reg = (float) $product->get_regular_price();
		$now = (float) $product->get_price();
		return ( $reg > 0 && $now > 0 && $now < $reg ) ? (int) round( ( 1 - $now / $reg ) * 100 ) : 0;
	}
}

if ( ! function_exists( 'ks_cart_trust' ) ) {
	/** کادرِ اطمینان زیرِ خلاصهٔ سبد (بیرون از .cart_totals تا با Ajaxِ ووکامرس عوض نشود). */
	function ks_cart_trust() {
		$items = array(
			array( 'shield', 'ضمانت اصالت کالا', 'گارانتی رسمی ۱۸ تا ۲۴ ماهه از خود برند' ),
			array( 'truck', 'ارسال به سراسر ایران', 'بسته‌بندی ایمن، تحویل سالم' ),
			array( 'lock', 'پرداخت امن', 'از طریق درگاه رسمی بانکی' ),
			array( 'install', 'خرید اقساطی بدون ضامن', '۴ قسط بدون سود، فقط با چک صیادی' ),
		);
		echo '<ul class="ks-cart-trust">';
		foreach ( $items as $it ) {
			echo '<li class="ks-cart-trust__i">' . ks_cart_icon( $it[0], 'ks-cart-trust__ic' ) . '<span><strong>' . esc_html( $it[1] ) . '</strong><small>' . esc_html( $it[2] ) . '</small></span></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</ul>';
		echo '<a class="ks-cart-help" href="tel:+989001110562">' . ks_cart_icon( 'phone' ) . '<span>سؤالی قبل از خرید دارید؟ <strong>۰۹۰۰۱۱۱۰۵۶۲</strong></span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'ks_cart_cross_sells' ) ) {
	/**
	 * «پیشنهادهای مکمل» زیرِ لیستِ سبد (به‌جای جای پیش‌فرضِ ووکامرس در ستونِ کناری).
	 * از محصولاتِ مرتبطِ (Cross-sells) همان کالاهای سبد؛ فقط موجود و قابلِ خرید. اگر نبود، چیزی چاپ نمی‌شود.
	 */
	function ks_cart_cross_sells() {
		if ( ! function_exists( 'WC' ) || ! WC()->cart ) { return; }
		$out = array();
		foreach ( array_unique( array_map( 'absint', (array) WC()->cart->get_cross_sells() ) ) as $id ) {
			$p = wc_get_product( $id );
			if ( $p && $p->is_visible() && $p->is_in_stock() && $p->is_purchasable() ) { $out[] = $p; }
			if ( count( $out ) >= 8 ) { break; }
		}
		if ( ! $out ) { return; }
		?>
		<section class="ks-cart-x" aria-labelledby="ks-cart-x-t">
			<h2 class="ks-cart-x__t" id="ks-cart-x-t">این‌ها هم کنار خریدتان به کار می‌آیند</h2>
			<div class="ks-cart-x__rail">
				<?php foreach ( $out as $p ) :
					$name = $p->get_name();
					$link = $p->get_permalink();
					?>
					<div class="ks-cart-x__card">
						<a class="ks-cart-x__link" href="<?php echo esc_url( $link ); ?>">
							<span class="ks-cart-x__media"><?php echo $p->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="ks-cart-x__name"><?php echo esc_html( $name ); ?></span>
						</a>
						<div class="ks-cart-x__foot">
							<span class="ks-cart-x__price"><?php echo wp_kses_post( $p->get_price_html() ); ?></span>
							<?php if ( $p->is_type( 'simple' ) ) : ?>
								<a class="ks-cart-x__add add_to_cart_button ajax_add_to_cart" href="<?php echo esc_url( $p->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $p->get_id() ); ?>" rel="nofollow" aria-label="<?php echo esc_attr( 'افزودن به سبد: ' . $name ); ?>"><?php echo ks_cart_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							<?php else : ?>
								<a class="ks-cart-x__add" href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( 'انتخاب مدل: ' . $name ); ?>"><?php echo ks_cart_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
	}
}
