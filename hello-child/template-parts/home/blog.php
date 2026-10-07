<?php
/**
 * سکشن «وبلاگ سعادت» — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/blog.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/blog' );
 *
 * کاروسلِ آخرین مقاله‌ها — عیناً هم‌سبکِ کاروسلِ تخفیف‌دار (sale):
 * همان هدر، همان فلش‌ها، همان ریلِ اسکرول‌اسنپ، همان کارتِ لبه‌تیز با --r.
 *
 * ⚠️ نسبت به کاروسلِ افزونه‌ای که الان روی سایت است:
 *   آنجا عنوان و خلاصه دو بار رندر می‌شد — یک بار روی عکس (بریده و ناخوانا)
 *   و یک بار زیر عکس. اینجا متن فقط در بدنهٔ کارت است؛ روی عکس فقط
 *   تاریخ و لایهٔ هاورِ «مطالعه مقاله» می‌نشیند.
 *
 * تاریخ شمسی نمایش داده می‌شود («۳۰ شهریور ۱۴۰۵»)؛ سایت افزونهٔ شمسی ندارد و قبلاً «۲۰۲۶-۰۹-۲۱» دیده می‌شد.
 * (inc/fa.php — اگر افزونهٔ شمسی نصب شود و سال را شمسی بدهد، همان استفاده می‌شود)
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require_once get_stylesheet_directory() . '/inc/fa.php';

$ks_blog_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 9,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'update_post_term_cache' => false, // دسته/برچسبِ مقاله نمایش داده نمی‌شود
) );

if ( ! $ks_blog_q->have_posts() ) { return; } // هنوز مقاله‌ای منتشر نشده

$ks_blog_page = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );

if ( ! function_exists( 'ks_fa_digits' ) ) {
	function ks_fa_digits( $str ) {
		return str_replace( array('0','1','2','3','4','5','6','7','8','9'), array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'), (string) $str );
	}
}
?>

<section class="ks-blog" id="home-sec-9" aria-label="تازه‌های وبلاگ سعادت" data-ks-blog>
  <div class="ks-blog__in">

    <div class="ks-blog__head">
      <div>
        <span class="ks-blog__eyebrow">وبلاگ سعادت</span>
        <h2 class="ks-blog__title">قبل از خرید، این‌ها را بخوانید</h2>
        <p class="ks-blog__sub">راهنمای انتخاب و بررسی محصولات، نوشته‌ی تیم خانه سعادت.</p>
      </div>

      <div class="ks-blog__nav">
        <div class="ks-blog__arrows">
          <button class="ks-blog__arrow" data-ks-blog-prev aria-label="قبلی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <button class="ks-blog__arrow" data-ks-blog-next aria-label="بعدی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
        </div>
        <a class="ks-blog__all" href="<?php echo esc_url( $ks_blog_page ); ?>">همه مقاله‌ها <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg></a>
      </div>
    </div>

    <div class="ks-blog__rail" data-ks-blog-rail>
      <?php while ( $ks_blog_q->have_posts() ) : $ks_blog_q->the_post(); ?>
        <article class="ks-blog__card">
          <a class="ks-blog__link" href="<?php the_permalink(); ?>">

            <span class="ks-blog__media">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium_large', array( 'class' => 'ks-blog__img', 'loading' => 'lazy', 'decoding' => 'async', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); ?>
              <?php else : ?>
                <span class="ks-blog__ph" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h11l3 3v13H5z"/><path d="M8 9h8M8 13h8M8 17h5"/></svg>
                </span>
              <?php endif; ?>

              <span class="ks-blog__date">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15.5" rx="2.5"/><path d="M3.5 9.5h17"/><path d="M8 3.5v3"/><path d="M16 3.5v3"/></svg>
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( ks_jdate( get_post_time( 'Y-n-j' ) ) ); ?></time>
              </span>

              <span class="ks-blog__ov" aria-hidden="true">
                <span class="ks-blog__more">
                  مطالعه مقاله
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="m14 6-6 6 6 6"/></svg>
                </span>
              </span>
            </span>

            <div class="ks-blog__body">
              <h3 class="ks-blog__name"><?php the_title(); ?></h3>
              <?php $ks_ex = wp_trim_words( get_the_excerpt(), 18, '…' ); ?>
              <?php if ( $ks_ex ) : ?><p class="ks-blog__ex"><?php echo esc_html( $ks_ex ); ?></p><?php endif; ?>
            </div>

          </a>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

  </div>
</section>

