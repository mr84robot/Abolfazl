/*!
 * خانه سعادت — اسکریپتِ صفحهٔ اصلی (همهٔ سکشن‌ها در یک فایل)
 * محل:  wp-content/themes/hello-child/assets/js/home.js
 * با defer در فوتر لود می‌شود. هر بخش یک IIFEِ مستقل است و اگر سکشنش در صفحه نباشد کاری نمی‌کند.
 */

/* ===================== hero ===================== */
(function(){
  // ویدیوی هیرو بعد از لودِ کاملِ صفحه وصل می‌شود تا با پوستر (LCP)، CSS و فونت سرِ پهنای باند رقابت نکند.
  // با «کاهش حرکت» یا «صرفه‌جویی داده»/اینترنت 2G اصلاً دانلود نمی‌شود و فقط پوستر می‌ماند.
  var v = document.querySelector('[data-ks-hero-video]');
  if ( ! v ) { return; }
  if ( window.matchMedia('(prefers-reduced-motion: reduce)').matches ) { return; }
  var c = navigator.connection;
  if ( c && ( c.saveData || /2g/.test( c.effectiveType || '' ) ) ) { return; }
  function start(){
    v.querySelectorAll('source[data-src]').forEach(function(s){ s.src = s.getAttribute('data-src'); });
    v.addEventListener('playing', function(){ v.classList.add('is-playing'); }, { once:true });
    v.load();
    var p = v.play(); if ( p && p.catch ) { p.catch(function(){}); }
  }
  if ( document.readyState === 'complete' ) { start(); } else { window.addEventListener('load', start, { once:true }); }
})();

/* ===================== trust ===================== */
(function(){
	var sec = document.querySelector('[data-ks-trust]');
	if ( ! sec ) { return; }
	if ( window.matchMedia('(prefers-reduced-motion: reduce)').matches || ! ('IntersectionObserver' in window) ) { return; }
	sec.classList.add('ks-trust--anim');
	var io = new IntersectionObserver(function(entries){
		entries.forEach(function(en){ if ( en.isIntersecting ) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
	}, { threshold:.2 });
	sec.querySelectorAll('.ks-trust__grid > li').forEach(function(li){ io.observe(li); });
})();

/* ===================== categories ===================== */
(function(){
  var rail=document.querySelector('[data-ks-rail]'); if(!rail) return;
  function s(dir){ rail.scrollBy({left:dir*470,behavior:'smooth'}); }
  var n=document.querySelector('[data-ks-rail-next]'), p=document.querySelector('[data-ks-rail-prev]');
  n&&n.addEventListener('click',function(){s(-1);}); p&&p.addEventListener('click',function(){s(1);});
})();

/* ===================== sale ===================== */
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

  // «بعدی» = جلو رفتن در محتوا. در RTL محتوای بعدی سمت چپ است و scrollLeft منفی می‌شود.
  function s(dir){
    var rtl = getComputedStyle(rail).direction === 'rtl';
    rail.scrollBy({ left: ( rtl ? -dir : dir ) * 512, behavior:'smooth' });
  }
  var n = sec.querySelector('[data-ks-sale-next]'), p = sec.querySelector('[data-ks-sale-prev]');
  n && n.addEventListener('click', function(){ s(1); });
  p && p.addEventListener('click', function(){ s(-1); });
})();

/* ===================== products ===================== */
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
    return '<a href="'+esc(p.permalink)+'" class="ks-prod__cart" aria-label="مشاهده محصول">'+CART+'</a>';
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
          if ( replace ) { rail.innerHTML = '<p class="ks-prod__empty">فعلاً محصولی در این دسته موجود نیست.</p>'; }
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
  // «بعدی» = جلو رفتن در محتوا. در RTL محتوای بعدی سمت چپ است و scrollLeft منفی می‌شود.
  function s(dir){
    var rtl = getComputedStyle(rail).direction === 'rtl';
    rail.scrollBy({ left: ( rtl ? -dir : dir ) * 512, behavior:'smooth' });
  }
  var n = sec.querySelector('[data-ks-prod-next]'), p = sec.querySelector('[data-ks-prod-prev]');
  n && n.addEventListener('click', function(){ s(1); });
  p && p.addEventListener('click', function(){ s(-1); });
})();

/* ===================== showroom ===================== */
(function(){
  var frame = document.querySelector('[data-ks-show]');
  if ( ! frame ) { return; }
  var modal = document.querySelector('[data-ks-show-modal]');
  var mv    = document.querySelector('[data-ks-show-modal-video]');

  // ورودِ باابهت — کارت فقط از همین‌جا پنهان می‌شود (ks-show--anim)، پس بدون JS دیده می‌ماند
  if ( 'IntersectionObserver' in window && ! window.matchMedia('(prefers-reduced-motion: reduce)').matches ) {
    frame.closest('.ks-show').classList.add('ks-show--anim');
    var io = new IntersectionObserver(function(es){ es.forEach(function(e){ if ( e.isIntersecting ) { frame.classList.add('is-in'); io.disconnect(); } }); }, { threshold:.25 });
    io.observe(frame);
  } else {
    frame.classList.add('is-in');
  }

  function open(){
    if ( ! modal ) { return; }
    modal.hidden = false;
    document.body.style.overflow = 'hidden';
    // ویدیو تازه اینجا لود می‌شود (preload=none)
    try { mv.load(); var p = mv.play(); if ( p && p.catch ) { p.catch(function(){}); } } catch(e){}
  }
  function close(){
    if ( ! modal ) { return; }
    modal.hidden = true;
    document.body.style.overflow = '';
    try { mv.pause(); } catch(e){}
  }

  var playBtn = frame.querySelector('[data-ks-show-play]');
  if ( playBtn ) { playBtn.addEventListener('click', function(e){ e.stopPropagation(); open(); }); }
  frame.addEventListener('click', open);
  frame.addEventListener('keydown', function(e){ if ( e.key === 'Enter' || e.key === ' ' ) { e.preventDefault(); open(); } });

  var closeBtn = document.querySelector('[data-ks-show-close]');
  if ( closeBtn ) { closeBtn.addEventListener('click', close); }
  if ( modal ) { modal.addEventListener('click', function(e){ if ( e.target === modal ) { close(); } }); }
  document.addEventListener('keydown', function(e){ if ( e.key === 'Escape' && modal && ! modal.hidden ) { close(); } });
})();

/* ===================== blog ===================== */
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

/* ===================== videos ===================== */
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
