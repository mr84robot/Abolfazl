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
 * تاریخ با get_the_date() می‌آید تا قالبِ تاریخِ خودِ سایت (شمسی، اگر افزونهٔ
 * تاریخ شمسی فعال باشد) رعایت شود؛ فقط ارقامش فارسی می‌شود.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_blog_q = new WP_Query( array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => 9,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );

if ( ! $ks_blog_q->have_posts() ) { return; } // هنوز مقاله‌ای منتشر نشده

$ks_blog_page = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' );

if ( ! function_exists( 'ks_fa_digits' ) ) {
	function ks_fa_digits( $str ) {
		return str_replace( array('0','1','2','3','4','5','6','7','8','9'), array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'), (string) $str );
	}
}
?>
<style id="ks-blog-css">
.ks-blog{--brand:#005949;--accent:#F2A900;--ink:#1f2b28;--muted:#5b6472;--line:#e7eae9;--r:4px;background:#f6f8f7;color:var(--ink);direction:rtl;font-family:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif}
.ks-blog *{box-sizing:border-box}
.ks-blog__in{max-width:1310px;margin-inline:auto;padding:clamp(48px,7vw,84px) clamp(16px,3vw,32px)}
.ks-blog__head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:26px}
.ks-blog__eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:13px;font-weight:700;color:#8a6100}
.ks-blog__eyebrow::before{content:"";width:24px;height:2px;background:var(--accent)}
.ks-blog__title{margin:12px 0 6px;font-size:clamp(22px,3.2vw,30px);font-weight:800;color:#101828;line-height:1.4}
.ks-blog__sub{margin:0;font-size:14.5px;color:var(--muted);line-height:1.8}
.ks-blog__nav{display:flex;align-items:center;gap:10px;flex:0 0 auto}
.ks-blog__all{display:inline-flex;align-items:center;gap:8px;min-height:42px;padding:0 18px;border:1px solid #d4dbd8;border-radius:var(--r);background:#fff;font-size:14px;font-weight:700;color:var(--brand);text-decoration:none;transition:border-color .2s,background .2s}
.ks-blog__all:hover{border-color:var(--brand);background:#f3f8f6}
.ks-blog__all svg{width:16px;height:16px}
.ks-blog__arrows{display:flex;gap:8px}
.ks-blog__arrow{width:42px;height:42px;border-radius:var(--r);border:1px solid #d4dbd8;background:#fff;color:var(--brand);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color .2s,background .2s}
.ks-blog__arrow:hover{border-color:var(--brand);background:#f3f8f6}
.ks-blog__arrow svg{width:19px;height:19px}

.ks-blog__rail{display:flex;gap:20px;overflow-x:auto;scroll-snap-type:x proximity;padding:4px 2px 12px;scrollbar-width:none}
.ks-blog__rail::-webkit-scrollbar{display:none}
.ks-blog__card{flex:0 0 clamp(300px,31%,406px);scroll-snap-align:start;display:flex;flex-direction:column;background:#fff;border:1px solid var(--line);border-radius:var(--r);overflow:hidden;transition:border-color .2s,transform .2s,box-shadow .2s}
.ks-blog__card:hover{border-color:#b9d2cb;transform:translateY(-3px);box-shadow:0 16px 34px -20px rgb(0 89 73 / .4)}
.ks-blog__link{text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%}

.ks-blog__media{position:relative;aspect-ratio:768/538;background:#e9efec;overflow:hidden}
.ks-blog__media img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .45s cubic-bezier(.16,1,.3,1)}
.ks-blog__card:hover .ks-blog__media img{transform:scale(1.05)}
.ks-blog__ph{position:absolute;inset:0;display:grid;place-items:center;background:linear-gradient(140deg,#0a5445 0%,#063d32 100%);color:rgb(255 255 255 / .34)}
.ks-blog__ph svg{width:36%;height:36%}
.ks-blog__date{position:absolute;inset-block-start:12px;inset-inline-start:12px;z-index:2;display:inline-flex;align-items:center;gap:6px;padding:6px 11px;border-radius:var(--r);background:rgb(255 255 255 / .94);color:var(--ink);font-size:12px;font-weight:700;line-height:1;box-shadow:0 4px 14px -6px rgb(0 0 0 / .35)}
.ks-blog__date svg{width:13px;height:13px;color:var(--brand)}

/* لایهٔ هاور: فقط «مطالعه مقاله» — متنِ کارت زیرِ عکس می‌ماند، نه رویش */
.ks-blog__ov{position:absolute;inset:0;z-index:1;display:grid;place-items:center;background:linear-gradient(to top,rgb(2 36 29 / .78),rgb(2 36 29 / .34));opacity:0;transition:opacity .3s ease}
.ks-blog__card:hover .ks-blog__ov,.ks-blog__link:focus-visible .ks-blog__ov{opacity:1}
.ks-blog__more{display:inline-flex;align-items:center;gap:7px;padding:10px 18px;border:1.5px solid rgb(255 255 255 / .75);border-radius:var(--r);color:#fff;font-size:14px;font-weight:700;transform:translateY(8px);transition:transform .3s cubic-bezier(.16,1,.3,1)}
.ks-blog__card:hover .ks-blog__more{transform:none}
.ks-blog__more svg{width:15px;height:15px}

.ks-blog__body{display:flex;flex-direction:column;gap:9px;padding:16px 16px 18px}
.ks-blog__name{margin:0;font-size:15.5px;font-weight:700;line-height:1.75;color:var(--ink);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:3.5em}
.ks-blog__card:hover .ks-blog__name{color:var(--brand)}
.ks-blog__ex{margin:0;font-size:13.5px;line-height:1.95;color:var(--muted);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

@media (max-width:860px){.ks-blog__card{flex-basis:78vw;max-width:360px}}
@media (max-width:640px){.ks-blog__arrows{display:none}}
@media (prefers-reduced-motion:reduce){.ks-blog__card:hover{transform:none}.ks-blog__card:hover .ks-blog__media img{transform:none}.ks-blog__rail{scroll-behavior:auto}.ks-blog__more{transform:none}}
</style>

<section class="ks-blog" id="home-sec-9" aria-label="تازه‌های وبلاگ سعادت" data-ks-blog>
  <div class="ks-blog__in">

    <div class="ks-blog__head">
      <div>
        <span class="ks-blog__eyebrow">وبلاگ سعادت</span>
        <h2 class="ks-blog__title">قبل از خرید، این‌ها را بخوانید</h2>
        <p class="ks-blog__sub">راهنماهای خرید و بررسی‌های واقعی، نوشتهٔ تیم خانه سعادت.</p>
      </div>

      <div class="ks-blog__nav">
        <div class="ks-blog__arrows">
          <button class="ks-blog__arrow" data-ks-blog-next aria-label="بعدی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <button class="ks-blog__arrow" data-ks-blog-prev aria-label="قبلی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
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
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( ks_fa_digits( get_the_date() ) ); ?></time>
              </span>

              <span class="ks-blog__ov" aria-hidden="true">
                <span class="ks-blog__more">
                  مطالعه مقاله
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="m14 6-6 6 6 6"/></svg>
                </span>
              </span>
            </span>

            <span class="ks-blog__body">
              <h3 class="ks-blog__name"><?php the_title(); ?></h3>
              <?php $ks_ex = wp_trim_words( get_the_excerpt(), 18, '…' ); ?>
              <?php if ( $ks_ex ) : ?><p class="ks-blog__ex"><?php echo esc_html( $ks_ex ); ?></p><?php endif; ?>
            </span>

          </a>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

  </div>
</section>

<script id="ks-blog-js">
(function(){
  var sec = document.querySelector('[data-ks-blog]');
  if ( ! sec ) { return; }
  var rail = sec.querySelector('[data-ks-blog-rail]');
  if ( ! rail ) { return; }
  // در RTL مقدار scrollLeft از صفر شروع و منفی می‌شود، پس «بعدی» باید منفی اسکرول کند.
  // جهت از روی direction محاسبه می‌شود تا در هر دو حالت درست بماند.
  function s(dir){
    var rtl  = getComputedStyle(rail).direction === 'rtl';
    var step = Math.max( rail.clientWidth * 0.8, 320 );
    rail.scrollBy({ left: ( rtl ? -dir : dir ) * step, behavior:'smooth' });
  }
  var n = sec.querySelector('[data-ks-blog-next]'), p = sec.querySelector('[data-ks-blog-prev]');
  n && n.addEventListener('click', function(){ s(1); });
  p && p.addEventListener('click', function(){ s(-1); });
})();
</script>
