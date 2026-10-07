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
 *   - سرتیتر (ابرو، عنوان، توضیح) از تنظیماتِ ویدیوی افزونه می‌آید؛ همان متنِ صفحهٔ آرشیو.
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
	'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		array( 'key' => '_codelock_video_url', 'compare' => 'EXISTS' ),
		array( 'key' => '_codelock_video_url', 'value' => '', 'compare' => '!=' ),
	),
) );

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
	'eyebrow' => (string) CodeLock_Settings::get( 'videos_eyebrow' ),
	'title'   => (string) CodeLock_Settings::get( 'videos_title' ),
	'desc'    => (string) CodeLock_Settings::get( 'videos_description' ),
	'all'     => CodeLock_Videos::archive_url(),
);
if ( '' === $ks_vid_head['title'] ) { $ks_vid_head['title'] = 'ویدیوی محصولات'; }

if ( ! function_exists( 'ks_fa_digits' ) ) {
	function ks_fa_digits( $str ) {
		return str_replace( array('0','1','2','3','4','5','6','7','8','9'), array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'), (string) $str );
	}
}
?>
<style id="ks-vid-css">
.ks-vid{--brand:#005949;--accent:#F2A900;--ink:#1f2b28;--muted:#5b6472;--line:#e7eae9;--r:4px;background:#fff;color:var(--ink);direction:rtl;font-family:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif}
.ks-vid *{box-sizing:border-box}
.ks-vid__in{max-width:1310px;margin-inline:auto;padding:clamp(48px,7vw,84px) clamp(16px,3vw,32px)}
.ks-vid__head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:26px}
.ks-vid__eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:13px;font-weight:700;color:#8a6100}
.ks-vid__eyebrow::before{content:"";width:24px;height:2px;background:var(--accent)}
.ks-vid__title{margin:12px 0 6px;font-size:clamp(22px,3.2vw,30px);font-weight:800;color:#101828;line-height:1.4}
.ks-vid__sub{margin:0;font-size:14.5px;color:var(--muted);line-height:1.8}
.ks-vid__nav{display:flex;align-items:center;gap:10px;flex:0 0 auto}
.ks-vid__all{display:inline-flex;align-items:center;gap:8px;min-height:42px;padding:0 18px;border:1px solid #d4dbd8;border-radius:var(--r);background:#fff;font-size:14px;font-weight:700;color:var(--brand);text-decoration:none;transition:border-color .2s,background .2s}
.ks-vid__all:hover{border-color:var(--brand);background:#f3f8f6}
.ks-vid__all svg{width:16px;height:16px}
.ks-vid__arrows{display:flex;gap:8px}
.ks-vid__arrow{width:42px;height:42px;border-radius:var(--r);border:1px solid #d4dbd8;background:#fff;color:var(--brand);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color .2s,background .2s}
.ks-vid__arrow:hover{border-color:var(--brand);background:#f3f8f6}
.ks-vid__arrow svg{width:19px;height:19px}

/* --cols کارتِ کامل دقیقاً عرض ریل را پر می‌کند؛ هیچ کارتِ نیمه‌ای لبِ قاب پیدا نمی‌شود */
.ks-vid__rail{--cols:3;--gap:20px;display:flex;gap:var(--gap);overflow-x:auto;scroll-snap-type:x mandatory;scroll-padding-inline:2px;padding:4px 2px 12px;scrollbar-width:none}
.ks-vid__rail::-webkit-scrollbar{display:none}
.ks-vid__card{flex:0 0 calc((100% - (var(--cols) - 1) * var(--gap)) / var(--cols));scroll-snap-align:start;display:flex;flex-direction:column;background:#fff;border:1px solid var(--line);border-radius:var(--r);overflow:hidden;transition:border-color .2s,transform .2s,box-shadow .2s}
.ks-vid__card:hover{border-color:#b9d2cb;transform:translateY(-3px);box-shadow:0 16px 34px -20px rgb(0 89 73 / .4)}
.ks-vid__link{text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%}
.ks-vid__link:focus-visible{outline:3px solid var(--accent);outline-offset:-3px}

.ks-vid__media{position:relative;aspect-ratio:16/9;background:linear-gradient(140deg,#0a5445 0%,#063d32 100%);overflow:hidden}
.ks-vid__media img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .45s cubic-bezier(.16,1,.3,1)}
.ks-vid__card:hover .ks-vid__media img{transform:scale(1.05)}
.ks-vid__media::after{content:"";position:absolute;inset:0;background:rgb(0 0 0 / 0);transition:background .3s ease;pointer-events:none}
.ks-vid__card:hover .ks-vid__media::after{background:rgb(0 0 0 / .14)}
.ks-vid__play{position:absolute;inset:0;margin:auto;z-index:2;width:62px;height:62px;border-radius:50%;background:rgb(255 255 255 / .94);color:var(--brand);display:flex;align-items:center;justify-content:center;box-shadow:0 10px 28px -8px rgb(0 0 0 / .45);transition:transform .3s cubic-bezier(.16,1,.3,1),background .3s ease,color .3s ease}
.ks-vid__card:hover .ks-vid__play{transform:scale(1.1);background:var(--brand);color:#fff}
.ks-vid__play svg{width:24px;height:24px;margin-inline-start:4px}
.ks-vid__dur{position:absolute;inset-block-end:10px;inset-inline-end:10px;z-index:2;padding:4px 8px;border-radius:var(--r);background:rgb(0 0 0 / .72);color:#fff;font-size:12px;font-weight:700;line-height:1.3;direction:ltr}

.ks-vid__body{display:flex;flex-direction:column;gap:9px;padding:16px 16px 0;flex:1}
.ks-vid__name{margin:0;font-size:15.5px;font-weight:700;line-height:1.75;color:var(--ink);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.ks-vid__card:hover .ks-vid__name{color:var(--brand)}
.ks-vid__ex{margin:0;font-size:13.5px;line-height:1.95;color:var(--muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
.ks-vid__prod{margin-top:auto;display:flex;align-items:center;gap:7px;padding:12px 0 15px;border-top:1px solid var(--line);font-size:13px;color:#8a948f;line-height:1.6}
.ks-vid__sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);clip-path:inset(50%);white-space:nowrap}
.ks-vid__prod svg{flex:0 0 auto;width:15px;height:15px;color:var(--brand)}
.ks-vid__prod span{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}

@media (max-width:1024px){.ks-vid__rail{--cols:2}}
@media (max-width:640px){.ks-vid__rail{--cols:1}.ks-vid__arrows{display:none}}
@media (prefers-reduced-motion:reduce){.ks-vid__card:hover{transform:none}.ks-vid__card:hover .ks-vid__media img{transform:none}.ks-vid__card:hover .ks-vid__play{transform:none}.ks-vid__rail{scroll-behavior:auto}}
</style>

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
          <button class="ks-vid__arrow" data-ks-vid-next aria-label="بعدی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <button class="ks-vid__arrow" data-ks-vid-prev aria-label="قبلی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
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

<script id="ks-vid-js">
(function(){
  var sec = document.querySelector('[data-ks-vid]');
  if ( ! sec ) { return; }
  var rail = sec.querySelector('[data-ks-vid-rail]');
  if ( ! rail ) { return; }
  // در RTL مقدار scrollLeft منفی می‌شود، پس «بعدی» باید منفی اسکرول کند.
  function s(dir){
    var rtl  = getComputedStyle(rail).direction === 'rtl';
    var step = Math.max( rail.clientWidth * 0.8, 320 );
    rail.scrollBy({ left: ( rtl ? -dir : dir ) * step, behavior:'smooth' });
  }
  var n = sec.querySelector('[data-ks-vid-next]'), p = sec.querySelector('[data-ks-vid-prev]');
  n && n.addEventListener('click', function(){ s(1); });
  p && p.addEventListener('click', function(){ s(-1); });
})();
</script>
