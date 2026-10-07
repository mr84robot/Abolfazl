<?php
/**
 * سکشن «کاروسل محصولات بر اساس دسته» — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/products.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/products' );
 *
 * مثل کاروسل تخفیف‌دار، اما با تبِ دسته‌ها: چای‌ساز/اسپرسوساز/سرخ‌کن/فلاسک/اتو بخار.
 *   - هر پنج تب سمت سرور رندر می‌شوند (هر کدام یک ریل و $ks_per محصول)؛ تعویضِ تب فقط نمایش/پنهان است
 *     و به شبکه نیاز ندارد. قبلاً فقط تبِ اول سمت سرور بود و بقیه با AJAX می‌آمدند؛ روی سایت فیلترِ
 *     دسته در پاسخ اعمال نمی‌شد و همیشه پرفروش‌ها (چای‌سازها) برمی‌گشت.
 *   - کاروسل بی‌پایان نیست: در انتهای هر ریل فقط یک بار ۶ محصولِ دیگر با Store API می‌آید (home.js)،
 *     نتیجه سمتِ مرورگر دوباره بر اساسِ دسته فیلتر و تکراری‌ها حذف می‌شود، بعد کارتِ «مشاهده همه».
 *   - تصاویرِ تب‌های پنهان lazy هستند و تا تب باز نشود دانلود نمی‌شوند.
 *   - کارت و استایل عیناً مثل sale؛ گوشهٔ ۴px، فقط رنگ‌های اصلی.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! function_exists( 'wc_get_product' ) ) { return; } // ووکامرس فعال نیست

/* دسته‌های تب (اسلاگِ product_cat → برچسبِ نمایش) */
$ks_prod_cats = array(
	array( 'slug' => 'chaeisaz',   'label' => 'چای‌ساز' ),
	array( 'slug' => 'espersosaz', 'label' => 'اسپرسوساز' ),
	array( 'slug' => 'sorkhkon',   'label' => 'سرخ‌کن' ),
	array( 'slug' => 'flask',      'label' => 'فلاسک' ),
	array( 'slug' => 'otobokhar',  'label' => 'اتو بخار' ),
);

$ks_tabs = array();
foreach ( $ks_prod_cats as $c ) {
	$term = get_term_by( 'slug', $c['slug'], 'product_cat' );
	if ( $term && ! is_wp_error( $term ) ) {
		$ks_link = get_term_link( $term );
		$ks_tabs[] = array( 'id' => (int) $term->term_id, 'slug' => $c['slug'], 'label' => $c['label'], 'link' => is_wp_error( $ks_link ) ? '' : $ks_link );
	}
}
if ( empty( $ks_tabs ) ) { return; } // هیچ‌کدام از دسته‌ها نبود

$ks_per = 6; // محصولِ سمت‌سرورِ هر تب؛ home.js در انتهای ریل یک بار ۶تای دیگر می‌آورد

$ks_shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$ks_store    = esc_url_raw( rest_url( 'wc/store/v1/products' ) );

if ( ! function_exists( 'ks_fa_digits' ) ) {
	function ks_fa_digits( $str ) {
		return str_replace( array('0','1','2','3','4','5','6','7','8','9'), array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'), (string) $str );
	}
}
$ks_cart_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.4"/><circle cx="17.5" cy="20" r="1.4"/><path d="M3 4h2.2l2 11a1.6 1.6 0 0 0 1.6 1.3h7.8a1.6 1.6 0 0 0 1.6-1.2L20.4 8H6"/></svg>';

if ( ! function_exists( 'ks_prod_card' ) ) {
	/** کارتِ محصول (رندر سمت سرور)؛ مارک‌آپ عیناً با رندرِ JS یکی است. */
	function ks_prod_card( $product, $cart_svg ) {
		$pid     = $product->get_id();
		$plink   = get_permalink( $pid );
		$name    = get_the_title( $pid );
		$regular = (float) $product->get_regular_price();
		$active  = (float) $product->get_price();
		$pct     = ( $product->is_on_sale() && $regular > 0 && $active > 0 && $active < $regular ) ? (int) round( ( 1 - $active / $regular ) * 100 ) : 0;
		ob_start(); ?>
		<div class="ks-prod__card">
			<a class="ks-prod__link" href="<?php echo esc_url( $plink ); ?>">
				<div class="ks-prod__media">
					<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'ks-prod__img', 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php if ( $pct > 0 ) : ?><span class="ks-prod__badge"><?php echo esc_html( ks_fa_digits( $pct ) ); ?>٪</span><?php endif; ?>
				</div>
				<h3 class="ks-prod__name"><?php echo esc_html( $name ); ?></h3>
			</a>
			<div class="ks-prod__foot">
				<div class="ks-prod__price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php
				if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
					printf(
						'<a href="%s" data-quantity="1" rel="nofollow" class="ks-prod__cart add_to_cart_button ajax_add_to_cart" data-product_id="%s" aria-label="%s">%s</a>',
						esc_url( $product->add_to_cart_url() ), esc_attr( $pid ), esc_attr( 'افزودن به سبد: ' . $name ), $cart_svg // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					);
				} else {
					printf( '<a href="%s" class="ks-prod__cart" aria-label="%s">%s</a>', esc_url( $plink ), esc_attr( 'مشاهدهٔ ' . $name ), $cart_svg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
?>

<section class="ks-prod" id="home-sec-6" aria-label="محصولات بر اساس دسته"
         data-ks-prod data-store="<?php echo esc_attr( $ks_store ); ?>">
  <div class="ks-prod__in">
    <div class="ks-prod__head">
      <div>
        <span class="ks-prod__eyebrow">تازه‌های فروشگاه</span>
        <h2 class="ks-prod__title">جدیدترین محصولات</h2>
        <p class="ks-prod__sub">تازه‌ترین مدل‌ها، اصل و با گارانتی رسمی؛ دسته‌ی دلخواهتان را انتخاب کنید.</p>
      </div>
      <div class="ks-prod__nav">
        <div class="ks-prod__arrows">
          <button class="ks-prod__arrow" data-ks-prod-prev aria-label="قبلی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <button class="ks-prod__arrow" data-ks-prod-next aria-label="بعدی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
        </div>
      </div>
    </div>

    <div class="ks-prod__tabs" role="tablist" data-ks-prod-tabs>
      <?php foreach ( $ks_tabs as $i => $t ) : ?>
      <button class="ks-prod__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" id="ks-prod-tab-<?php echo esc_attr( $t['slug'] ); ?>" aria-controls="ks-prod-panel-<?php echo esc_attr( $t['slug'] ); ?>" data-ks-prod-tab="<?php echo esc_attr( $t['slug'] ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $t['label'] ); ?></button>
      <?php endforeach; ?>
    </div>

    <div class="ks-prod__railwrap">
      <?php foreach ( $ks_tabs as $i => $t ) :
        $ks_pq = new WP_Query( array(
          'post_type'           => 'product',
          'post_status'         => 'publish',
          'posts_per_page'      => $ks_per,
          'orderby'             => 'date',
          'order'               => 'DESC',
          'ignore_sticky_posts' => true,
          'no_found_rows'       => true,
          'tax_query'           => array( array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $t['id'] ) ),
        ) );
        $ks_ids = wp_list_pluck( $ks_pq->posts, 'ID' );
        ?>
      <div class="ks-prod__rail" data-ks-prod-rail role="tabpanel" id="ks-prod-panel-<?php echo esc_attr( $t['slug'] ); ?>" aria-labelledby="ks-prod-tab-<?php echo esc_attr( $t['slug'] ); ?>"
           data-cat-id="<?php echo (int) $t['id']; ?>" data-cat-slug="<?php echo esc_attr( $t['slug'] ); ?>"
           data-ids="<?php echo esc_attr( implode( ',', $ks_ids ) ); ?>" data-more="<?php echo count( $ks_ids ) >= $ks_per ? '1' : '0'; ?>"
           data-all="<?php echo esc_url( $t['link'] ? $t['link'] : $ks_shop_url ); ?>" data-all-label="<?php echo esc_attr( $t['label'] ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
        <?php
        if ( $ks_pq->have_posts() ) :
            while ( $ks_pq->have_posts() ) : $ks_pq->the_post();
                $p = wc_get_product( get_the_ID() );
                if ( $p ) { echo ks_prod_card( $p, $ks_cart_svg ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p class="ks-prod__empty">فعلاً محصولی در این دسته موجود نیست.</p>';
        endif;
        ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

