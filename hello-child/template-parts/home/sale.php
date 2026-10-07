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
<style id="ks-sale-css">
.ks-sale{--brand:#005949;--accent:#F2A900;--ink:#1f2b28;--muted:#5b6472;--line:#e7eae9;--r:4px;background:#fff;color:var(--ink);direction:rtl;font-family:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif}
.ks-sale *{box-sizing:border-box}
.ks-sale__in{max-width:1310px;margin-inline:auto;padding:clamp(48px,7vw,84px) clamp(16px,3vw,32px)}
.ks-sale__head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:26px}
.ks-sale__eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:13px;font-weight:700;color:#8a6100}
.ks-sale__eyebrow::before{content:"";width:24px;height:2px;background:var(--accent)}
.ks-sale__title{margin:12px 0 6px;font-size:clamp(22px,3.2vw,30px);font-weight:800;color:#101828;line-height:1.4}
.ks-sale__sub{margin:0;font-size:14.5px;color:var(--muted)}
.ks-sale__nav{display:flex;align-items:center;gap:10px;flex:0 0 auto}
.ks-sale__all{display:inline-flex;align-items:center;gap:8px;min-height:42px;padding:0 18px;border:1px solid #d4dbd8;border-radius:var(--r);background:#fff;font-size:14px;font-weight:700;color:var(--brand);text-decoration:none;transition:border-color .2s,background .2s}
.ks-sale__all:hover{border-color:var(--brand);background:#f3f8f6}
.ks-sale__all svg{width:16px;height:16px}
.ks-sale__arrows{display:flex;gap:8px}
.ks-sale__arrow{width:42px;height:42px;border-radius:var(--r);border:1px solid #d4dbd8;background:#fff;color:var(--brand);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color .2s,background .2s}
.ks-sale__arrow:hover{border-color:var(--brand);background:#f3f8f6}
.ks-sale__arrow svg{width:19px;height:19px}
/* --cols کارتِ کامل دقیقاً عرض ریل را پر می‌کند؛ هیچ کارتِ نیمه‌ای لبِ قاب پیدا نمی‌شود */
.ks-sale__rail{--cols:5;--gap:16px;display:flex;gap:var(--gap);overflow-x:auto;scroll-snap-type:x mandatory;scroll-padding-inline:2px;padding:4px 2px 12px;scrollbar-width:none}
.ks-sale__rail::-webkit-scrollbar{display:none}
.ks-sale__card{flex:0 0 calc((100% - (var(--cols) - 1) * var(--gap)) / var(--cols));scroll-snap-align:start;display:flex;flex-direction:column;background:#fff;border:1px solid var(--line);border-radius:var(--r);overflow:hidden;transition:border-color .2s,transform .2s,box-shadow .2s}
.ks-sale__card:hover{border-color:#b9d2cb;transform:translateY(-3px);box-shadow:0 16px 34px -20px rgb(0 89 73 / .4)}
.ks-sale__link{text-decoration:none;color:inherit;display:block}
.ks-sale__media{position:relative;aspect-ratio:1/1;background:#f6f7f7}
.ks-sale__media img{width:100%;height:100%;object-fit:contain;padding:14px;mix-blend-mode:multiply}
.ks-sale__badge{position:absolute;inset-block-start:10px;inset-inline-start:10px;background:var(--accent);color:#2a1d00;font-size:12.5px;font-weight:800;padding:4px 9px;border-radius:var(--r);line-height:1}
.ks-sale__name{margin:0;padding:12px 13px 0;font-size:14px;font-weight:600;line-height:1.65;color:var(--ink);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.95em}
.ks-sale__foot{margin-top:auto;display:flex;align-items:center;justify-content:space-between;gap:8px;padding:10px 13px 14px}
.ks-sale__price{font-size:13px;line-height:1.35;min-width:0}
.ks-sale__price del{color:#9aa3a0;font-size:11.5px;font-weight:500}
.ks-sale__price ins{text-decoration:none;color:var(--brand);font-weight:800;font-size:15px;display:block}
.ks-sale__cart{flex:0 0 auto;width:40px;height:40px;border-radius:var(--r);background:var(--brand);color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;transition:background .2s}
.ks-sale__cart:hover{background:#00493b}
.ks-sale__cart svg{width:19px;height:19px}
@media (max-width:1180px){.ks-sale__rail{--cols:4}}
@media (max-width:900px){.ks-sale__rail{--cols:3}}
@media (max-width:640px){.ks-sale__rail{--cols:2}.ks-sale__arrows{display:none}}
@media (prefers-reduced-motion:reduce){.ks-sale__card:hover{transform:none}.ks-sale__rail{scroll-behavior:auto}}
</style>

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
          <button class="ks-sale__arrow" data-ks-sale-next aria-label="بعدی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <button class="ks-sale__arrow" data-ks-sale-prev aria-label="قبلی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
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

<script id="ks-sale-js">
(function(){
  var sec = document.querySelector('[data-ks-sale]');
  if ( ! sec ) { return; }
  var rail = sec.querySelector('[data-ks-sale-rail]');
  var base = sec.getAttribute('data-store');
  var per  = parseInt( sec.getAttribute('data-per') || '12', 10 );
  var st = { page: 1, loading: false, done: false };
  var CART = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.4"/><circle cx="17.5" cy="20" r="1.4"/><path d="M3 4h2.2l2 11a1.6 1.6 0 0 0 1.6 1.3h7.8a1.6 1.6 0 0 0 1.6-1.2L20.4 8H6"/></svg>';

  function esc(s){ return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];}); }
  function faNum(n){ return String(n).replace(/[0-9]/g,function(d){return '۰۱۲۳۴۵۶۷۸۹'[+d];}); }
  function money(pr){ var mu=parseInt(pr.currency_minor_unit||0,10),pre=pr.currency_prefix||'',suf=pr.currency_suffix||''; return function(v){ v=parseInt(v||'0',10)/Math.pow(10,mu); return pre+v.toLocaleString('fa-IR')+suf; }; }
  function priceHtml(p){ var pr=p.prices||{},f=money(pr); if(p.on_sale&&pr.regular_price&&pr.regular_price!==pr.price){return '<del>'+f(pr.regular_price)+'</del><ins>'+f(pr.price)+'</ins>';} return '<ins>'+f(pr.price)+'</ins>'; }
  function badge(p){ var pr=p.prices||{}; if(p.on_sale&&+pr.regular_price>0&&+pr.price<+pr.regular_price){return Math.round((1-(+pr.price)/(+pr.regular_price))*100);} return 0; }
  function cartBtn(p){ if(p.type==='simple'&&p.is_purchasable&&p.is_in_stock&&p.add_to_cart&&p.add_to_cart.url){ return '<a href="'+esc(p.add_to_cart.url)+'" data-quantity="1" rel="nofollow" class="ks-sale__cart add_to_cart_button ajax_add_to_cart" data-product_id="'+p.id+'" aria-label="افزودن به سبد">'+CART+'</a>'; } return '<a href="'+esc(p.permalink)+'" class="ks-sale__cart" aria-label="مشاهده محصول">'+CART+'</a>'; }
  function card(p){ var img=(p.images&&p.images[0])?p.images[0].src:''; var pct=badge(p);
    return '<div class="ks-sale__card"><a class="ks-sale__link" href="'+esc(p.permalink)+'"><div class="ks-sale__media">'+(img?'<img src="'+esc(img)+'" alt="'+esc(p.name)+'" loading="lazy" decoding="async">':'')+(pct>0?'<span class="ks-sale__badge">'+faNum(pct)+'٪</span>':'')+'</div><h3 class="ks-sale__name">'+esc(p.name)+'</h3></a><div class="ks-sale__foot"><div class="ks-sale__price">'+priceHtml(p)+'</div>'+cartBtn(p)+'</div></div>'; }

  function more(){
    if ( st.loading || st.done ) { return; }
    st.loading = true; st.page += 1;
    fetch( base + '?on_sale=true&per_page=' + per + '&page=' + st.page + '&orderby=date&order=desc', { headers:{ 'Accept':'application/json' }, credentials:'same-origin' } )
      .then(function(r){ return r.ok ? r.json() : []; })
      .then(function(list){
        if ( !list || !list.length ) { st.done = true; }
        else { if ( list.length < per ) { st.done = true; } var h=''; list.forEach(function(p){ h += card(p); }); rail.insertAdjacentHTML('beforeend', h); }
      })
      .catch(function(){ st.done = true; })
      .then(function(){ st.loading = false; });
  }

  rail.addEventListener('scroll', function(){
    if ( st.loading || st.done ) { return; }
    if ( Math.abs(rail.scrollLeft) + rail.clientWidth >= rail.scrollWidth - 320 ) { more(); }
  }, { passive:true });

  function s(dir){ rail.scrollBy({ left: dir*512, behavior:'smooth' }); }
  var n = sec.querySelector('[data-ks-sale-next]'), p = sec.querySelector('[data-ks-sale-prev]');
  n && n.addEventListener('click', function(){ s(1); });
  p && p.addEventListener('click', function(){ s(-1); });
})();
</script>
