<?php
/**
 * سکشن «لوازم خانگی تخفیف‌دار» — کاروسل ساده و لبه‌تیز — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/sale.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/sale' );  (بعد از کتگوری)
 *
 * داده: محصولاتِ حراجِ ووکامرس (wc_get_product_ids_on_sale). اگر ووکامرس نبود یا حراجی نبود، هیچ رندر نمی‌شود.
 * کاروسل: اسکرول افقی + scroll-snap + فلش (RTL)، وانیلا JS. گوشهٔ ۴px، فقط رنگ‌های اصلی (سبز/کهربایی).
 * قیمت از get_price_html خودِ ووکامرس (ارز/تخفیف درست)؛ درصد تخفیف جدا محاسبه و روی بَج کهربایی.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! function_exists( 'wc_get_product_ids_on_sale' ) ) { return; } // ووکامرس فعال نیست

$ks_sale_ids = wc_get_product_ids_on_sale();
if ( empty( $ks_sale_ids ) ) { return; } // حراجی موجود نیست

$ks_sale_q = new WP_Query( array(
	'post_type'           => 'product',
	'post_status'         => 'publish',
	'post__in'            => $ks_sale_ids,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'posts_per_page'      => 12,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );
if ( ! $ks_sale_q->have_posts() ) { wp_reset_postdata(); return; }

$ks_shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$ks_store    = esc_url_raw( rest_url( 'wc/store/v1/products' ) );

if ( ! function_exists( 'ks_fa_digits' ) ) {
	function ks_fa_digits( $str ) {
		return str_replace( array('0','1','2','3','4','5','6','7','8','9'), array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'), (string) $str );
	}
}

$ks_cart_svg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.4"/><circle cx="17.5" cy="20" r="1.4"/><path d="M3 4h2.2l2 11a1.6 1.6 0 0 0 1.6 1.3h7.8a1.6 1.6 0 0 0 1.6-1.2L20.4 8H6"/></svg>';
?>

<section class="ks-sale" id="home-sec-4" aria-label="لوازم خانگی تخفیف‌دار" data-ks-sale data-store="<?php echo esc_attr( $ks_store ); ?>" data-per="12">
  <div class="ks-sale__in">
    <div class="ks-sale__head">
      <div>
        <span class="ks-sale__eyebrow">پیشنهاد ویژه</span>
        <h2 class="ks-sale__title">لوازم خانگی تخفیف‌دار</h2>
        <p class="ks-sale__sub">قیمت‌های ویژه‌ی این روزها، تا وقتی موجودی تمام نشده.</p>
      </div>
      <div class="ks-sale__nav">
        <div class="ks-sale__arrows">
          <button class="ks-sale__arrow" data-ks-sale-prev aria-label="قبلی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <button class="ks-sale__arrow" data-ks-sale-next aria-label="بعدی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
        </div>
        <a class="ks-sale__all" href="<?php echo esc_url( $ks_shop_url ); ?>">همه محصولات <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg></a>
      </div>
    </div>

    <div class="ks-sale__rail" data-ks-sale-rail>
      <?php
      while ( $ks_sale_q->have_posts() ) :
          $ks_sale_q->the_post();
          $product = wc_get_product( get_the_ID() );
          if ( ! $product ) { continue; }
          $regular = (float) $product->get_regular_price();
          $active  = (float) $product->get_price();
          $pct     = ( $regular > 0 && $active > 0 && $active < $regular ) ? (int) round( ( 1 - $active / $regular ) * 100 ) : 0;
          $plink   = get_permalink();
          ?>
          <div class="ks-sale__card">
            <a class="ks-sale__link" href="<?php echo esc_url( $plink ); ?>">
              <div class="ks-sale__media">
                <?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'ks-sale__img', 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php if ( $pct > 0 ) : ?><span class="ks-sale__badge"><?php echo esc_html( ks_fa_digits( $pct ) ); ?>٪</span><?php endif; ?>
              </div>
              <h3 class="ks-sale__name"><?php echo esc_html( get_the_title() ); ?></h3>
            </a>
            <div class="ks-sale__foot">
              <div class="ks-sale__price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — خروجی امنِ ووکامرس ?></div>
              <?php
              if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
                  printf(
                      '<a href="%s" data-quantity="1" rel="nofollow" class="ks-sale__cart add_to_cart_button ajax_add_to_cart" data-product_id="%s" aria-label="%s">%s</a>',
                      esc_url( $product->add_to_cart_url() ),
                      esc_attr( $product->get_id() ),
                      esc_attr( 'افزودن به سبد: ' . get_the_title() ),
                      $ks_cart_svg // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — SVGِ ایستا
                  );
              } else {
                  printf(
                      '<a href="%s" class="ks-sale__cart" aria-label="%s">%s</a>',
                      esc_url( $plink ),
                      esc_attr( 'مشاهدهٔ ' . get_the_title() ),
                      $ks_cart_svg // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — SVGِ ایستا
                  );
              }
              ?>
            </div>
          </div>
          <?php
      endwhile;
      wp_reset_postdata();
      ?>
    </div>
  </div>
</section>

