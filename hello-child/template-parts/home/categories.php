<?php
/**
 * سکشن دسته‌بندی‌ها — صفحهٔ اصلی خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/categories.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/categories' );
 *
 * ۲ ردیفه با اسکرول افقی؛ کارت سفید + تصویرِ دسته روی تینت روشن + نام. فقط رنگ‌های اصلی.
 * منبع تصویر هر کارت به‌ترتیب:  ۱) فایلِ تعیین‌شده در $ks_cats  ۲) Thumbnail دستهٔ ووکامرس  ۳) آیکن خطی.
 * لینک هر دسته مستقیم از ووکامرس (get_term_link). فهرست/ترتیب را از آرایهٔ زیر کم/زیاد کن.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_img_base = 'https://khanehsaadat.com/wp-content/uploads/2026/05/';

/* هر ردیف: اسلاگ، شناسهٔ آیکنِ fallback، فایلِ تصویر (خالی=از Thumbnail ووکامرس/آیکن)، نامِ دلخواه (خالی=نام ترم) */
$ks_cats = array(
	array( 'flask',            'flask',     'Artboard-1.webp',        'فلاسک' ),
	array( 'kettle-and-teapot','teapot',    'Artboard-1-copy.webp',   'کتری و قوری' ),
	array( 'ghazasaz',         'processor', 'Artboard-1-copy-10.webp','' ),
	array( 'abmeve',           'juicer',    'Artboard-1-copy-11.webp','' ),
	array( 'jarobarghi',       'vacuum',    'Artboard-1-copy-8.webp', '' ),
	array( 'tosternan',        'toaster',   'Artboard-1-copy-7.webp', '' ),
	array( 'otobokhar',        'iron',      'Artboard-1-copy-6.webp', '' ),
	array( 'sorkhkon',         'fryer',     'Artboard-1-copy-5.webp', '' ),
	array( 'espersosaz',       'espresso',  'Artboard-1-copy-4.webp', '' ),
	array( 'pot',              'pot',       'Artboard-1-copy-3.webp', '' ),
	array( 'chaeisaz',         'teapot',    'Artboard-1-copy-2.webp', '' ),
	array( 'polopaz',          'rice',      'Artboard-1-copy-9.webp', '' ),
	array( 'microwave',        'microwave', 'Artboard-1-copy-22.webp','' ),
	array( 'water-jug',        'jug',       'Artboard-1-copy-20.webp','کلمن' ),
	array( 'sandevich',        'sandwich',  'Artboard-1-copy-19.webp','' ),
	array( 'zodpaz-barghi',    'pcooker',   'Artboard-1-copy-18.webp','' ),
	array( 'charkhgosht',      'grinder',   'Artboard-1-copy-16.webp','' ),
	array( 'asiab',            'blender',   'Artboard-1-copy-15.webp','' ),
	array( 'pankeestade',      'fan',       'Artboard-1-copy-13.webp','' ),
	array( 'goshtkob',         'stick',     'Artboard-1-copy-12.webp','' ),
);

/* همهٔ دسته‌ها با یک کوئری، سپس نگاشت بر اساس اسلاگ (برای لینک و نام). */
$ks_by_slug = array();
if ( taxonomy_exists( 'product_cat' ) ) {
	$ks_terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
	if ( ! is_wp_error( $ks_terms ) ) {
		foreach ( $ks_terms as $t ) { $ks_by_slug[ $t->slug ] = $t; }
	}
}
$ks_all_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
?>
<style id="ks-cats-css">
.ks-cats{--brand:#005949;--accent:#F2A900;--ink:#1f2b28;--muted:#5b6472;--line:#e5e7eb;--r:4px;background:#f4f4f4;color:var(--ink);direction:rtl;font-family:"Yekan Bakh FaNum","Vazirmatn",system-ui,sans-serif}
.ks-cats *{box-sizing:border-box}
.ks-cats__in{max-width:1310px;margin-inline:auto;padding:clamp(56px,8vw,96px) clamp(16px,3vw,32px)}
.ks-cats__head{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;margin-bottom:40px}
.ks-cats__eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:13px;font-weight:700;color:#8a6100}
.ks-cats__eyebrow::before{content:"";width:24px;height:2px;background:var(--accent)}
.ks-cats__title{margin:14px 0 8px;font-size:clamp(24px,3.4vw,32px);line-height:1.4;font-weight:800;color:#101828;white-space:nowrap}
.ks-cats__sub{margin:0;font-size:15px;color:var(--muted)}
.ks-cats__all{flex:0 0 auto;display:inline-flex;align-items:center;gap:8px;min-height:44px;padding:0 20px;border:1px solid #d4dbd8;border-radius:var(--r);background:#fff;font-size:14px;font-weight:700;color:var(--brand);text-decoration:none;transition:border-color .2s ease,background .2s ease}
.ks-cats__all:hover{border-color:var(--brand);background:#f3f8f6}
.ks-cats__all svg{width:16px;height:16px}
.ks-cats__railwrap{position:relative}
/* --cols ستونِ کامل دقیقاً عرض ریل را پر می‌کند؛ هیچ ستونِ نیمه‌ای لبِ قاب پیدا نمی‌شود */
.ks-cats__rail{--cols:3;--gap:20px;display:grid;grid-auto-flow:column;grid-template-rows:repeat(2,1fr);grid-auto-columns:calc((100% - (var(--cols) - 1) * var(--gap)) / var(--cols));gap:var(--gap);overflow-x:auto;scroll-snap-type:x mandatory;padding-bottom:6px;scrollbar-width:thin}
.ks-cats__rail::-webkit-scrollbar{height:6px}
.ks-cats__rail::-webkit-scrollbar-thumb{background:#cfd8d5;border-radius:3px}
.ks-cat{scroll-snap-align:start;display:block;background:#fff;border:1px solid var(--line);border-radius:var(--r);padding:20px 16px 16px;text-align:center;color:var(--ink);text-decoration:none;transition:border-color .2s ease,transform .2s ease,box-shadow .2s ease}
.ks-cat:hover{border-color:#b9d2cb;transform:translateY(-3px);box-shadow:0 14px 30px -18px rgb(0 89 73 / .4)}
.ks-cat__ic{display:grid;place-items:center;aspect-ratio:1/1;border-radius:var(--r);overflow:hidden}
.ks-cat__ic svg{width:34px;height:34px;color:var(--brand)}
.ks-cat__ic img{width:86%;height:86%;object-fit:contain;mix-blend-mode:multiply}
.ks-cat__name{display:block;margin-top:12px;font-size:clamp(11.5px,1vw,13.5px);font-weight:600;color:var(--ink);white-space:nowrap}
.ks-cats__arrow{position:absolute;top:calc(50% + 24px);transform:translateY(-50%);width:44px;height:44px;border-radius:50%;background:#fff;border:1px solid #d4dbd8;color:var(--brand);display:none;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 10px 24px -12px rgb(0 0 0 / .25);z-index:3}
.ks-cats__arrow svg{width:20px;height:20px}
.ks-cats__arrow:hover{background:#f3f8f6;border-color:var(--brand)}
.ks-cats__arrow--next{inset-inline-start:-6px}
.ks-cats__arrow--prev{inset-inline-end:-6px}
@media (min-width:1024px){.ks-cats__rail{grid-auto-flow:row;grid-template-columns:repeat(10,1fr);grid-template-rows:auto;grid-auto-columns:auto;gap:16px;overflow-x:visible;scroll-snap-type:none;padding-bottom:0}.ks-cat{padding:16px 10px 12px}.ks-cats__arrow{display:none}}
@media (max-width:600px){.ks-cats__rail{--cols:2}.ks-cats__head{flex-direction:column;align-items:flex-start;gap:14px;margin-bottom:28px}}
@media (prefers-reduced-motion:reduce){.ks-cat:hover{transform:none}}
</style>

<section class="ks-cats" id="home-sec-3" aria-label="دسته‌بندی لوازم خانگی">

  <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs>
    <g id="ks-ic-blender"><path d="M8 3h8l-1 10H9z"/><rect x="9.5" y="13" width="5" height="7" rx="1.5"/><path d="M9.5 20h5"/></g>
    <g id="ks-ic-grinder"><rect x="4" y="9" width="8" height="6" rx="1"/><path d="M12 11h4l3-2v8l-3-2h-4"/><path d="M8 15v4h4"/></g>
    <g id="ks-ic-processor"><rect x="7" y="4" width="10" height="9" rx="2"/><path d="M6 13h12l-1 7H7z"/><circle cx="12" cy="8.5" r="2.3"/></g>
    <g id="ks-ic-stick"><path d="M11 3h2v9h-2z"/><path d="M9.5 12h5l-1 4a1.5 1.5 0 0 1-3 0z"/></g>
    <g id="ks-ic-fan"><circle cx="12" cy="9" r="6"/><circle cx="12" cy="9" r="1.6"/><path d="M12 15v5"/><path d="M9 20h6"/></g>
    <g id="ks-ic-iron"><path d="M3 15a8 8 0 0 1 8-6h7l2 3v3z"/><path d="M3 18h17"/><path d="M8 9V6h7"/></g>
    <g id="ks-ic-vacuum"><circle cx="8" cy="16" r="4"/><path d="M8 16l6-9a3 3 0 0 1 5.5 1.7V12"/><circle cx="8" cy="16" r="1.1"/></g>
    <g id="ks-ic-pot"><path d="M5 10h14v6a3 3 0 0 1-3 3H8a3 3 0 0 1-3-3z"/><path d="M3 10h2M19 10h2"/><path d="M8.5 10V8h7v2"/></g>
    <g id="ks-ic-jug"><rect x="6" y="8" width="11" height="12" rx="2"/><path d="M17 11h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2h-2"/><path d="M8 8V6h7v2"/></g>
    <g id="ks-ic-flask"><path d="M9 3h6v4l2.6 10a2 2 0 0 1-2 2.5H8.4a2 2 0 0 1-2-2.5L9 7z"/><path d="M8.6 7h6.8"/></g>
    <g id="ks-ic-microwave"><rect x="3" y="6" width="18" height="12" rx="2"/><rect x="5.5" y="8.5" width="9" height="7" rx="1"/><path d="M17.5 12.5h1.5"/><circle cx="18.2" cy="9.5" r=".6" fill="currentColor" stroke="none"/></g>
    <g id="ks-ic-rice"><path d="M5 11h14v3a5 5 0 0 1-5 5h-4a5 5 0 0 1-5-5z"/><path d="M4 11a8 8 0 0 1 16 0"/><circle cx="12" cy="7.5" r="1"/></g>
    <g id="ks-ic-toaster"><path d="M4 11a8 8 0 0 1 16 0v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M9 7V5M14 7V5"/><path d="M16.5 13.5h2"/></g>
    <g id="ks-ic-pcooker"><rect x="5" y="9" width="14" height="9" rx="3"/><path d="M4 9h2M18 9h2"/><path d="M9 9V6h6v3"/><circle cx="12" cy="5.4" r="1.1"/></g>
    <g id="ks-ic-sandwich"><path d="M4 9h16l-2 3H6z"/><path d="M5 12h14v3a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2z"/><path d="M18 7l2.2-2"/></g>
    <g id="ks-ic-fryer"><rect x="6" y="8" width="12" height="10" rx="2"/><path d="M6 11.5h12"/><path d="M9 8V6h6v2"/><circle cx="15" cy="14.5" r="1"/></g>
    <g id="ks-ic-juicer"><path d="M8 4h8l-1 5H9z"/><rect x="9.5" y="9" width="5" height="4" rx="1"/><path d="M8 13h8l-1 6H9z"/><path d="M10 19h4"/></g>
    <g id="ks-ic-espresso"><rect x="5" y="4" width="14" height="7" rx="1.5"/><path d="M10 11v2h4v-2"/><path d="M11 13l-.8 5h3.6l-.8-5"/><path d="M5 20h14"/></g>
    <g id="ks-ic-teapot"><path d="M6 12h10.5a4 4 0 0 1-4 7H10a4 4 0 0 1-4-4z"/><path d="M16.5 13h2a2 2 0 0 1 0 4h-1.2"/><path d="M9 12c0-2 .8-3 .8-4M12.5 12c0-2 .8-3 .8-4"/></g>
  </defs></svg>

  <div class="ks-cats__in">
    <div class="ks-cats__head">
      <div>
        <span class="ks-cats__eyebrow">خرید بر اساس دسته</span>
        <h2 class="ks-cats__title">دسته‌بندی لوازم خانگی</h2>
        <p class="ks-cats__sub">از لوازم برقی آشپزخانه تا نظافت و سرمایش؛ از دسته‌ی مورد نظرتان شروع کنید.</p>
      </div>
    </div>

    <div class="ks-cats__railwrap">
      <div class="ks-cats__rail" data-ks-rail>
        <?php
        foreach ( $ks_cats as $c ) :
            list( $slug, $icon, $imgfile, $name_ovr ) = $c;
            if ( empty( $ks_by_slug[ $slug ] ) ) { continue; } // دستهٔ نبود را رد کن
            $term = $ks_by_slug[ $slug ];
            $link = get_term_link( $term );
            if ( is_wp_error( $link ) ) { continue; }
            $name = ( '' !== $name_ovr ) ? $name_ovr : $term->name;

            // منبع تصویر: فایلِ تعیین‌شده ← Thumbnail ووکامرس ← آیکن
            $img_url = '';
            if ( '' !== $imgfile ) {
                $img_url = $ks_img_base . $imgfile;
            } else {
                $tid = get_term_meta( $term->term_id, 'thumbnail_id', true );
                if ( $tid ) { $img_url = wp_get_attachment_image_url( (int) $tid, 'woocommerce_thumbnail' ); }
            }
            ?>
            <a class="ks-cat" href="<?php echo esc_url( $link ); ?>">
              <span class="ks-cat__ic">
                <?php if ( $img_url ) : ?>
                  <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $name ); ?>" width="148" height="148" loading="lazy" decoding="async">
                <?php else : ?>
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><use href="#ks-ic-<?php echo esc_attr( $icon ); ?>"/></svg>
                <?php endif; ?>
              </span>
              <span class="ks-cat__name"><?php echo esc_html( $name ); ?></span>
            </a>
        <?php endforeach; ?>
      </div>
      <button class="ks-cats__arrow ks-cats__arrow--prev" data-ks-rail-prev aria-label="قبلی"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
      <button class="ks-cats__arrow ks-cats__arrow--next" data-ks-rail-next aria-label="بعدی"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
    </div>
  </div>
</section>

<script id="ks-cats-js">
(function(){
  var rail=document.querySelector('[data-ks-rail]'); if(!rail) return;
  function s(dir){ rail.scrollBy({left:dir*470,behavior:'smooth'}); }
  var n=document.querySelector('[data-ks-rail-next]'), p=document.querySelector('[data-ks-rail-prev]');
  n&&n.addEventListener('click',function(){s(-1);}); p&&p.addEventListener('click',function(){s(1);});
})();
</script>
