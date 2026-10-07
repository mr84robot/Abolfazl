<?php
/**
 * سکشن «کاروسل محصولات بر اساس دسته» — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/products.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/products' );
 *
 * مثل کاروسل تخفیف‌دار، اما با تبِ دسته‌ها: چای‌ساز/اسپرسوساز/سرخ‌کن/فلاسک/اتو بخار.
 *   - تعویض دسته و «لودِ ادامهٔ لیست در انتهای ریل» هر دو با AJAXِ WooCommerce Store API (same-origin).
 *   - لودِ اولِ دستهٔ اول سمت سرور رندر می‌شود (SEO + بدون‌JS)؛ بقیه کلاینتی.
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
		$ks_tabs[] = array( 'id' => (int) $term->term_id, 'slug' => $c['slug'], 'label' => $c['label'] );
	}
}
if ( empty( $ks_tabs ) ) { return; } // هیچ‌کدام از دسته‌ها نبود

$ks_per   = 8;
$ks_first = $ks_tabs[0];

$ks_pq = new WP_Query( array(
	'post_type'           => 'product',
	'post_status'         => 'publish',
	'posts_per_page'      => $ks_per,
	'orderby'             => 'date',
	'order'               => 'DESC',
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'tax_query'           => array( array( 'taxonomy' => 'product_cat', 'field' => 'term_id', 'terms' => $ks_first['id'] ) ),
) );

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
<style id="ks-prod-css">
.ks-prod{--brand:#005949;--accent:#F2A900;--ink:#1f2b28;--muted:#5b6472;--line:#e7eae9;--r:4px;background:#f4f4f4;color:var(--ink);direction:rtl;font-family:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif}
.ks-prod *{box-sizing:border-box}
.ks-prod__in{max-width:1310px;margin-inline:auto;padding:clamp(48px,7vw,84px) clamp(16px,3vw,32px)}
.ks-prod__head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:18px}
.ks-prod__eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:13px;font-weight:700;color:#8a6100}
.ks-prod__eyebrow::before{content:"";width:24px;height:2px;background:var(--accent)}
.ks-prod__title{margin:12px 0 6px;font-size:clamp(22px,3.2vw,30px);font-weight:800;color:#101828;line-height:1.4;white-space:nowrap}
.ks-prod__sub{margin:0;font-size:14.5px;color:var(--muted)}
.ks-prod__nav{display:flex;align-items:center;gap:10px;flex:0 0 auto}
.ks-prod__arrows{display:flex;gap:8px}
.ks-prod__arrow{width:42px;height:42px;border-radius:var(--r);border:1px solid #d4dbd8;background:#fff;color:var(--brand);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:border-color .2s,background .2s}
.ks-prod__arrow:hover{border-color:var(--brand);background:#f3f8f6}
.ks-prod__arrow svg{width:19px;height:19px}
.ks-prod__all{display:inline-flex;align-items:center;gap:8px;min-height:42px;padding:0 18px;border:1px solid #d4dbd8;border-radius:var(--r);background:#fff;font-size:14px;font-weight:700;color:var(--brand);text-decoration:none;transition:border-color .2s,background .2s}
.ks-prod__all:hover{border-color:var(--brand);background:#f3f8f6}
.ks-prod__all svg{width:16px;height:16px}
.ks-prod__tabs{display:flex;gap:8px;margin:0 0 22px;overflow-x:auto;scrollbar-width:none;padding-bottom:2px}
.ks-prod__tabs::-webkit-scrollbar{display:none}
.ks-prod__tab{flex:0 0 auto;appearance:none;cursor:pointer;border:1px solid #d4dbd8;background:#fff;color:var(--ink);border-radius:var(--r);padding:9px 16px;font-family:inherit;font-size:14px;font-weight:700;white-space:nowrap;transition:background .2s,border-color .2s,color .2s}
.ks-prod__tab:hover{border-color:var(--brand);color:var(--brand)}
.ks-prod__tab.is-active{background:var(--brand);border-color:var(--brand);color:#fff}
.ks-prod__railwrap{position:relative}
.ks-prod__rail{display:flex;gap:16px;overflow-x:auto;scroll-snap-type:x proximity;padding:4px 2px 12px;scrollbar-width:none;transition:opacity .2s ease}
.ks-prod__rail::-webkit-scrollbar{display:none}
.ks-prod.is-loading .ks-prod__rail{opacity:.5}
.ks-prod__card{flex:0 0 240px;scroll-snap-align:start;display:flex;flex-direction:column;background:#fff;border:1px solid var(--line);border-radius:var(--r);overflow:hidden;transition:border-color .2s,transform .2s,box-shadow .2s}
.ks-prod__card:hover{border-color:#b9d2cb;transform:translateY(-3px);box-shadow:0 16px 34px -20px rgb(0 89 73 / .4)}
.ks-prod__link{text-decoration:none;color:inherit;display:block}
.ks-prod__media{position:relative;aspect-ratio:1/1;background:#f6f7f7}
.ks-prod__media img{width:100%;height:100%;object-fit:contain;padding:14px;mix-blend-mode:multiply}
.ks-prod__badge{position:absolute;inset-block-start:10px;inset-inline-start:10px;background:var(--accent);color:#2a1d00;font-size:12.5px;font-weight:800;padding:4px 9px;border-radius:var(--r);line-height:1}
.ks-prod__name{margin:0;padding:12px 13px 0;font-size:14px;font-weight:600;line-height:1.65;color:var(--ink);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;min-height:2.95em}
.ks-prod__foot{margin-top:auto;display:flex;align-items:center;justify-content:space-between;gap:8px;padding:10px 13px 14px}
.ks-prod__price{font-size:13px;line-height:1.35;min-width:0}
.ks-prod__price del{color:#9aa3a0;font-size:11.5px;font-weight:500}
.ks-prod__price ins{text-decoration:none;color:var(--brand);font-weight:800;font-size:15px;display:block}
.ks-prod__cart{flex:0 0 auto;width:40px;height:40px;border-radius:var(--r);background:var(--brand);color:#fff;display:inline-flex;align-items:center;justify-content:center;text-decoration:none;transition:background .2s}
.ks-prod__cart:hover{background:#00493b}
.ks-prod__cart svg{width:19px;height:19px}
.ks-prod__empty{padding:40px 10px;text-align:center;color:var(--muted);font-size:14px;width:100%}
@media (max-width:640px){.ks-prod__card{flex-basis:62vw;max-width:270px}.ks-prod__arrows{display:none}.ks-prod__head{flex-direction:column;align-items:flex-start;gap:12px;margin-bottom:14px}.ks-prod__sub{font-size:13px}}
@media (prefers-reduced-motion:reduce){.ks-prod__card:hover{transform:none}.ks-prod__rail{scroll-behavior:auto}}
</style>

<section class="ks-prod" id="home-sec-6" aria-label="محصولات بر اساس دسته"
         data-ks-prod data-store="<?php echo esc_attr( $ks_store ); ?>" data-per="<?php echo (int) $ks_per; ?>" data-active="<?php echo (int) $ks_first['id']; ?>">
  <div class="ks-prod__in">
    <div class="ks-prod__head">
      <div>
        <span class="ks-prod__eyebrow">با خانه سعادت بروز باش</span>
        <h2 class="ks-prod__title">جدیدترین محصولات خانه سعادت</h2>
        <p class="ks-prod__sub">تازه‌ترین لوازم خانگیِ اصل را زودتر از همه ببینید؛ با بهترین قیمت و گارانتی رسمی.</p>
      </div>
      <div class="ks-prod__nav">
        <div class="ks-prod__arrows">
          <button class="ks-prod__arrow" data-ks-prod-next aria-label="بعدی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 6 6 6-6 6"/></svg></button>
          <button class="ks-prod__arrow" data-ks-prod-prev aria-label="قبلی" type="button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 6-6 6 6 6"/></svg></button>
        </div>
      </div>
    </div>

    <div class="ks-prod__tabs" role="tablist" data-ks-prod-tabs>
      <?php foreach ( $ks_tabs as $i => $t ) : ?>
      <button class="ks-prod__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" type="button" role="tab" data-cat-id="<?php echo (int) $t['id']; ?>" data-cat-slug="<?php echo esc_attr( $t['slug'] ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $t['label'] ); ?></button>
      <?php endforeach; ?>
    </div>

    <div class="ks-prod__railwrap">
      <div class="ks-prod__rail" data-ks-prod-rail>
        <?php
        if ( $ks_pq->have_posts() ) :
            while ( $ks_pq->have_posts() ) : $ks_pq->the_post();
                $p = wc_get_product( get_the_ID() );
                if ( $p ) { echo ks_prod_card( $p, $ks_cart_svg ); } // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p class="ks-prod__empty">محصولی در این دسته نیست.</p>';
        endif;
        ?>
      </div>
    </div>
  </div>
</section>

<script id="ks-prod-js">
(function(){
  var sec = document.querySelector('[data-ks-prod]');
  if ( ! sec ) { return; }
  var rail = sec.querySelector('[data-ks-prod-rail]');
  var base = sec.getAttribute('data-store');
  var per  = parseInt( sec.getAttribute('data-per') || '8', 10 );
  var state = { cat: sec.getAttribute('data-active'), page: 1, loading: false, done: false };
  var CART = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.4"/><circle cx="17.5" cy="20" r="1.4"/><path d="M3 4h2.2l2 11a1.6 1.6 0 0 0 1.6 1.3h7.8a1.6 1.6 0 0 0 1.6-1.2L20.4 8H6"/></svg>';

  function esc(s){ return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];}); }
  function faNum(n){ return String(n).replace(/[0-9]/g,function(d){return '۰۱۲۳۴۵۶۷۸۹'[+d];}); }
  function money(pr){
    var mu = parseInt(pr.currency_minor_unit||0,10), pre = pr.currency_prefix||'', suf = pr.currency_suffix||'';
    function f(v){ v = parseInt(v||'0',10)/Math.pow(10,mu); return pre + v.toLocaleString('fa-IR') + suf; }
    return { f:f };
  }
  function priceHtml(p){
    var pr = p.prices||{}, m = money(pr);
    if ( p.on_sale && pr.regular_price && pr.regular_price !== pr.price ) {
      return '<del>'+m.f(pr.regular_price)+'</del><ins>'+m.f(pr.price)+'</ins>';
    }
    return '<ins>'+m.f(pr.price)+'</ins>';
  }
  function badge(p){
    var pr = p.prices||{};
    if ( p.on_sale && +pr.regular_price > 0 && +pr.price < +pr.regular_price ) {
      return Math.round((1 - (+pr.price)/(+pr.regular_price))*100);
    }
    return 0;
  }
  function cartBtn(p){
    if ( p.type==='simple' && p.is_purchasable && p.is_in_stock && p.add_to_cart && p.add_to_cart.url ) {
      return '<a href="'+esc(p.add_to_cart.url)+'" data-quantity="1" rel="nofollow" class="ks-prod__cart add_to_cart_button ajax_add_to_cart" data-product_id="'+p.id+'" aria-label="افزودن به سبد">'+CART+'</a>';
    }
    return '<a href="'+esc(p.permalink)+'" class="ks-prod__cart" aria-label="مشاهده">'+CART+'</a>';
  }
  function card(p){
    var img = (p.images && p.images[0]) ? (p.images[0].src) : '';
    var pct = badge(p);
    return '<div class="ks-prod__card"><a class="ks-prod__link" href="'+esc(p.permalink)+'">'
      + '<div class="ks-prod__media">' + (img ? '<img src="'+esc(img)+'" alt="'+esc(p.name)+'" loading="lazy" decoding="async">' : '')
      + (pct>0 ? '<span class="ks-prod__badge">'+faNum(pct)+'٪</span>' : '') + '</div>'
      + '<h3 class="ks-prod__name">'+esc(p.name)+'</h3></a>'
      + '<div class="ks-prod__foot"><div class="ks-prod__price">'+priceHtml(p)+'</div>'+cartBtn(p)+'</div></div>';
  }

  function load(replace){
    if ( state.loading || (state.done && !replace) ) { return; }
    state.loading = true; sec.classList.add('is-loading');
    var url = base + '?category=' + encodeURIComponent(state.cat) + '&per_page=' + per + '&page=' + state.page + '&orderby=popularity';
    fetch(url, { headers:{ 'Accept':'application/json' }, credentials:'same-origin' })
      .then(function(r){ return r.ok ? r.json() : []; })
      .then(function(list){
        if ( replace ) { rail.innerHTML = ''; }
        if ( !list || !list.length ) {
          state.done = true;
          if ( replace ) { rail.innerHTML = '<p class="ks-prod__empty">محصولی در این دسته نیست.</p>'; }
        } else {
          if ( list.length < per ) { state.done = true; }
          var html = ''; list.forEach(function(p){ html += card(p); });
          rail.insertAdjacentHTML('beforeend', html);
        }
      })
      .catch(function(){ /* شکست شبکه: رندرِ سمت‌سرور باقی می‌ماند */ })
      .then(function(){ state.loading = false; sec.classList.remove('is-loading'); });
  }

  // تب‌ها: تعویض دسته
  sec.querySelectorAll('[data-cat-id]').forEach(function(btn){
    btn.addEventListener('click', function(){
      if ( btn.classList.contains('is-active') ) { return; }
      sec.querySelectorAll('[data-cat-id]').forEach(function(b){ b.classList.remove('is-active'); b.setAttribute('aria-selected','false'); });
      btn.classList.add('is-active'); btn.setAttribute('aria-selected','true');
      state.cat = btn.getAttribute('data-cat-id'); state.page = 1; state.done = false;
      try { rail.scrollTo({ left:0, behavior:'auto' }); } catch(e){ rail.scrollLeft = 0; }
      load(true);
    });
  });

  // لودِ ادامهٔ لیست در انتهای ریل (RTL: scrollLeft منفی)
  rail.addEventListener('scroll', function(){
    if ( state.loading || state.done ) { return; }
    var nearEnd = Math.abs(rail.scrollLeft) + rail.clientWidth >= rail.scrollWidth - 320;
    if ( nearEnd ) { state.page += 1; load(false); }
  }, { passive:true });

  // فلش‌ها
  function s(dir){ rail.scrollBy({ left: dir*512, behavior:'smooth' }); }
  var n = sec.querySelector('[data-ks-prod-next]'), p = sec.querySelector('[data-ks-prod-prev]');
  n && n.addEventListener('click', function(){ s(1); });
  p && p.addEventListener('click', function(){ s(-1); });
})();
</script>
