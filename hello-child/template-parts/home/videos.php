<?php
/**
 * سکشن «ویدیوی محصولات» — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/videos.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/videos' );
 *
 * کاروسلِ آخرین ویدیوهای محصولات — هم‌سبکِ کاروسلِ وبلاگ (هدر، فلش، ریلِ اسنپ، کارتِ لبه‌تیز).
 *
 * منبعِ داده افزونهٔ CodeLock است، نه شورت‌کدش:
 *   - ویدیو پست‌تایپ جدا ندارد؛ متای خودِ محصول است (_codelock_video_url و …).
 *   - داده با CodeLock_Product_Meta::get_video() خوانده می‌شود و لینکِ هر کارت با
 *     CodeLock_Videos::video_url() ساخته می‌شود؛ پس تنظیمِ «لینک به صفحهٔ محصول / صفحهٔ ویدیو»
 *     در افزونه همین‌جا هم رعایت می‌شود و منطق دو جا تکرار نمی‌شود.
 *   - سرتیترِ این سکشن مخصوصِ صفحهٔ اصلی است و همین‌جا نوشته شده (کلیدواژه‌دار)؛ تنظیماتِ
 *     ابرو/عنوان/توضیحِ افزونه همچنان برای صفحهٔ آرشیوِ ویدیو استفاده می‌شود.
 *   - شورت‌کد را صدا نمی‌زنیم چون گریدِ صفحه‌بندی‌شده با CSS خودش می‌دهد، نه کاروسل.
 *
 * اگر افزونه یا ماژولِ ویدیوی آن خاموش باشد، یا محصولی ویدیو نداشته باشد، سکشن چاپ نمی‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'wc_get_product' )
	|| ! class_exists( 'CodeLock_Videos' )
	|| ! class_exists( 'CodeLock_Product_Meta' )
	|| ! class_exists( 'CodeLock_Settings' )
	|| ! CodeLock_Settings::is_on( 'enable_videos' ) ) {
	return;
}

// همان شرطِ کوئریِ آرشیوِ افزونه: محصولاتی که لینک ویدیوی غیرخالی دارند
$ks_vid_q = new WP_Query( array(
	'post_type'           => 'product',
	'post_status'         => 'publish',
	'posts_per_page'      => 9,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'update_post_term_cache' => false, // دسته‌های محصول اینجا لازم نیست
	'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		array( 'key' => '_codelock_video_url', 'compare' => 'EXISTS' ),
		array( 'key' => '_codelock_video_url', 'value' => '', 'compare' => '!=' ),
	),
) );

// عکسِ شاخصِ همهٔ محصول‌ها با یک کوئری، نه یکی‌یکی داخل حلقه
update_post_thumbnail_cache( $ks_vid_q );

$ks_vids = array();
foreach ( $ks_vid_q->posts as $ks_p ) {
	$ks_v = CodeLock_Product_Meta::get_video( $ks_p->ID );
	if ( ! $ks_v ) { continue; }
	$ks_vids[] = array(
		'url'      => CodeLock_Videos::video_url( $ks_p->ID ),
		'cover'    => '' !== $ks_v['cover'] ? $ks_v['cover'] : (string) get_the_post_thumbnail_url( $ks_p->ID, 'medium_large' ),
		'title'    => '' !== $ks_v['title'] ? $ks_v['title'] : get_the_title( $ks_p->ID ),
		'text'     => $ks_v['text'],
		'duration' => $ks_v['duration'],
		'product'  => get_the_title( $ks_p->ID ),
	);
}
if ( empty( $ks_vids ) ) { return; }

$ks_vid_head = array(
	'eyebrow' => 'ویدیوها',
	'title'   => 'بررسی ویدیویی محصولات',
	'desc'    => 'هر محصول را قبل از خرید در عمل ببینید؛ از کیفیت ساخت تا طرز کار.',
	'all'     => CodeLock_Videos::archive_url(),
);

if ( ! function_exists( 'ks_fa_digits' ) ) {
	function ks_fa_digits( $str ) {
		return str_replace( array('0','1','2','3','4','5','6','7','8','9'), array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'), (string) $str );
	}
}
?>

<section class="ks-vid" id="home-sec-11" aria-label="<?php echo esc_attr( $ks_vid_head['title'] ); ?>" data-ks-vid>
  <div class="ks-vid__in">

    <div class="ks-vid__head">
      <div>
        <?php if ( '' !== $ks_vid_head['eyebrow'] ) : ?><span class="ks-vid__eyebrow"><?php echo esc_html( $ks_vid_head['eyebrow'] ); ?></span><?php endif; ?>
        <h2 class="ks-vid__title"><?php echo esc_html( $ks_vid_head['title'] ); ?></h2>
        <?php if ( '' !== $ks_vid_head['desc'] ) : ?><p class="ks-vid__sub"><?php echo esc_html( $ks_vid_head['desc'] ); ?></p><?php endif; ?>
      </div>

      <div class="ks-vid__nav">
        <div class="ks-vid__arrows">
          <button class="ks-vid__arrow" data-ks-vid-prev aria-label="قبلی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <button class="ks-vid__arrow" data-ks-vid-next aria-label="بعدی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
        </div>
        <a class="ks-vid__all" href="<?php echo esc_url( $ks_vid_head['all'] ); ?>">همه ویدیوها <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg></a>
      </div>
    </div>

    <div class="ks-vid__rail" data-ks-vid-rail>
      <?php foreach ( $ks_vids as $ks_v ) : ?>
        <article class="ks-vid__card">
          <a class="ks-vid__link" href="<?php echo esc_url( $ks_v['url'] ); ?>">
            <span class="ks-vid__media">
              <?php if ( $ks_v['cover'] ) : ?>
                <img src="<?php echo esc_url( $ks_v['cover'] ); ?>" width="768" height="432" alt="<?php echo esc_attr( $ks_v['title'] ); ?>" loading="lazy" decoding="async">
              <?php endif; ?>
              <span class="ks-vid__play" aria-hidden="true"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5.14v13.72a1 1 0 0 0 1.52.86l10.5-6.86a1 1 0 0 0 0-1.72L9.52 4.28A1 1 0 0 0 8 5.14z"/></svg></span>
              <?php if ( '' !== $ks_v['duration'] ) : ?>
                <span class="ks-vid__dur"><span class="ks-vid__sr">مدت ویدیو: </span><?php echo esc_html( ks_fa_digits( $ks_v['duration'] ) ); ?></span>
              <?php endif; ?>
            </span>

            <div class="ks-vid__body">
              <h3 class="ks-vid__name"><?php echo esc_html( $ks_v['title'] ); ?></h3>
              <?php if ( '' !== $ks_v['text'] ) : ?><p class="ks-vid__ex"><?php echo esc_html( wp_trim_words( $ks_v['text'], 18, '…' ) ); ?></p><?php endif; ?>
              <span class="ks-vid__prod">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.4"/></svg>
                <span><?php echo esc_html( $ks_v['product'] ); ?></span>
              </span>
            </div>

          </a>
        </article>
      <?php endforeach; ?>
    </div>

  </div>
</section>

