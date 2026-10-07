/*!
 * خانه سعادت — اسکریپتِ صفحهٔ اصلی (همهٔ سکشن‌ها در یک فایل)
 * محل:  wp-content/themes/hello-child/assets/js/home.js
 * با defer در فوتر لود می‌شود. هر بخش یک IIFEِ مستقل است و اگر سکشنش در صفحه نباشد کاری نمی‌کند.
 */

/* ===================== core ===================== */
/* ابزارهای مشترکِ کاروسل‌ها: کارت از پاسخِ Store API، اسکلتون، کارتِ پایانی، بارگذاریِ محدود، کشیدن با موس. */
var ksHome = (function(){
  var root = document.documentElement;
  root.classList.add('ks-js');

  var CART = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="20" r="1.4"/><circle cx="17.5" cy="20" r="1.4"/><path d="M3 4h2.2l2 11a1.6 1.6 0 0 0 1.6 1.3h7.8a1.6 1.6 0 0 0 1.6-1.2L20.4 8H6"/></svg>';
  var ARROW = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>';
  var MORE = 6; // کاروسل بی‌پایان نیست: فقط یک بار، ۶ محصولِ دیگر

  function esc(s){ return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];}); }
  function faNum(n){ return String(n).replace(/[0-9]/g,function(d){return '۰۱۲۳۴۵۶۷۸۹'[+d];}); }
  // همان مارک‌آپِ قیمتِ ووکامرس (واحد در span جدا تا مثلِ کارت‌های سمتِ سرور کوچک‌تر نمایش داده شود)
  function money(pr){ var mu=parseInt(pr.currency_minor_unit||0,10),pre=(pr.currency_prefix||'').trim(),suf=(pr.currency_suffix||'').trim(); return function(v){ v=parseInt(v||'0',10)/Math.pow(10,mu);
    var sym=function(t){ return '<span class="woocommerce-Price-currencySymbol">'+esc(t)+'</span>'; };
    return '<span class="woocommerce-Price-amount amount"><bdi>'+(pre?sym(pre)+'&nbsp;':'')+v.toLocaleString('fa-IR')+(suf?'&nbsp;'+sym(suf):'')+'</bdi></span>'; }; }
  function priceHtml(p){ var pr=p.prices||{},f=money(pr); if(p.on_sale&&pr.regular_price&&pr.regular_price!==pr.price){return '<del>'+f(pr.regular_price)+'</del><ins>'+f(pr.price)+'</ins>';} return '<ins>'+f(pr.price)+'</ins>'; }
  // برای محصولِ متغیر Store API کمترین قیمت‌ها را می‌دهد؛ همان مبنای درصد است (درصدِ دقیقِ هر متغیر را سرور در کارت‌های سمتِ سرور می‌گذارد)
  function badge(p){ var pr=p.prices||{}, reg=+pr.regular_price, now=+pr.price; if(p.on_sale&&reg>0&&now>0&&now<reg){return Math.round((1-now/reg)*100);} return 0; }

  /* px = پیشوندِ کلاس: 'ks-sale' یا 'ks-prod' — مارک‌آپ عیناً مثلِ رندرِ سمتِ سرور */
  function card(px, p){
    var img = (p.images && p.images[0]) ? p.images[0].src : '';
    var pct = badge(p);
    var cart = ( p.type==='simple' && p.is_purchasable && p.is_in_stock && p.add_to_cart && p.add_to_cart.url )
      ? '<a href="'+esc(p.add_to_cart.url)+'" data-quantity="1" rel="nofollow" class="'+px+'__cart add_to_cart_button ajax_add_to_cart" data-product_id="'+p.id+'" aria-label="افزودن به سبد: '+esc(p.name)+'">'+CART+'</a>'
      : '<a href="'+esc(p.permalink)+'" class="'+px+'__cart" aria-label="مشاهده محصول: '+esc(p.name)+'">'+CART+'</a>';
    return '<div class="'+px+'__card"><a class="'+px+'__link" href="'+esc(p.permalink)+'">'
      + '<div class="'+px+'__media'+(img?'':' is-loaded')+'">'+(img?'<img src="'+esc(img)+'" alt="'+esc(p.name)+'" loading="lazy" decoding="async">':'')
      + (pct>0?'<span class="'+px+'__badge">'+faNum(pct)+'٪</span>':'')+'</div>'
      + '<h3 class="'+px+'__name">'+esc(p.name)+'</h3></a>'
      + '<div class="'+px+'__foot"><div class="'+px+'__price">'+priceHtml(p)+'</div>'+cart+'</div></div>';
  }
  function skeleton(px, n){
    var one = '<div class="'+px+'__card ks-skelcard" aria-hidden="true"><div class="'+px+'__media ks-skel"></div><div class="ks-skelcard__body">'
      + '<span class="ks-skel ks-skelcard__line"></span><span class="ks-skel ks-skelcard__line ks-skelcard__line--s"></span><span class="ks-skel ks-skelcard__line ks-skelcard__line--p"></span></div></div>';
    var h=''; for (var i=0;i<n;i++){ h+=one; } return h;
  }
  function endCard(px, href, label){
    return '<a class="'+px+'__card ks-endcard" href="'+esc(href)+'"><span class="ks-endcard__ic">'+ARROW+'</span>'
      + '<span class="ks-endcard__t">مشاهده همه</span>'+(label?'<span class="ks-endcard__s">'+esc(label)+'</span>':'')+'</a>';
  }
  function storeUrl(base, params){
    var q=[]; for (var k in params){ if (params[k]!=='' && params[k]!=null) q.push(encodeURIComponent(k)+'='+encodeURIComponent(params[k])); }
    return base + (base.indexOf('?')>-1 ? '&' : '?') + q.join('&');
  }

  /* اسکلتونِ عکس‌ها: ظرفِ هر عکس تا لود شدن برق می‌زند */
  var MEDIA = '.ks-sale__media,.ks-prod__media,.ks-blog__media,.ks-vid__media,.ks-cat__ic';
  function markMedia(scope){
    (scope||document).querySelectorAll(MEDIA).forEach(function(m){
      var img = m.querySelector('img');
      if ( ! img || ( img.complete && img.naturalWidth > 0 ) ) { m.classList.add('is-loaded'); }
    });
  }
  function onImg(e){ var t=e.target; if ( t && t.tagName==='IMG' ) { var m=t.closest(MEDIA); if (m) { m.classList.add('is-loaded'); } } }
  document.addEventListener('load', onImg, true);
  document.addEventListener('error', onImg, true);
  markMedia();

  /*
   * بارگذاریِ محدود: فقط یک بار MORE محصولِ دیگر می‌آید؛ بعد کارتِ «مشاهده همه».
   * fetchMore(ids) باید Promise<آرایهٔ محصول> بدهد. نتیجه سمتِ مرورگر با accept() فیلتر و با ids بدون تکرار می‌شود،
   * تا اگر سرور فیلتر را نادیده گرفت، محصولِ اشتباه داخلِ ریل نیاید.
   *
   * گیر کردنِ کشیدن در موبایل: قبلاً درخواست وقتی می‌رفت که کاربر وسطِ کشیدن به نزدیکِ انتها می‌رسید و کارت‌ها
   * (اسکلتون و بعد محصولات) همان لحظه به ریل اضافه می‌شدند؛ تغییرِ عرضِ ریل وسطِ حرکتِ انگشت/اینرسی، اسنپ را
   * دوباره حساب می‌کرد و حرکت می‌ایستاد. حالا:
   *   - درخواست همین که ریل نزدیکِ دید رسید فرستاده می‌شود (قبل از این‌که کاربر شروع به کشیدن کند)؛
   *   - هیچ تغییری در ریل داده نمی‌شود تا وقتی انگشت روی ریل است یا ریل هنوز در حالِ حرکت است.
   */
  function finite(rail, px, fetchMore, accept){
    var ids = (rail.getAttribute('data-ids')||'').split(',').filter(Boolean).map(Number);
    var href = rail.getAttribute('data-all'), label = rail.getAttribute('data-all-label');
    var state = rail.getAttribute('data-more') === '1' ? 'idle' : 'done';
    if ( state === 'done' ) { if ( href ) { rail.insertAdjacentHTML('beforeend', endCard(px, href, label)); } return { check:function(){} }; }

    var touching = false, moving = false, idleT = null, pending = null;
    function busy(){ return touching || moving; }
    function settle(){ clearTimeout(idleT); idleT = setTimeout(function(){ moving = false; flush(); }, 220); }
    rail.addEventListener('touchstart', function(){ touching = true; }, { passive:true });
    rail.addEventListener('touchend', function(){ touching = false; settle(); }, { passive:true });
    rail.addEventListener('touchcancel', function(){ touching = false; settle(); }, { passive:true });
    rail.addEventListener('scroll', function(){ moving = true; settle(); run(); }, { passive:true });

    function flush(){
      if ( pending === null || busy() ) { return; }
      var h = pending; pending = null;
      rail.querySelectorAll('.ks-skelcard').forEach(function(n){ n.remove(); });
      rail.insertAdjacentHTML('beforeend', h);
    }
    function run(){
      if ( state !== 'idle' || rail.hidden ) { return; }
      state = 'loading';
      if ( ! busy() ) { rail.insertAdjacentHTML('beforeend', skeleton(px, Math.min(MORE, 3))); }
      fetchMore(ids).then(function(list){
        // تکراری با شناسه یا آدرس (مثلاً متغیرِ همان محصول) هم حذف می‌شود
        var seen = {}; ids.forEach(function(i){ seen['i'+i]=1; });
        rail.querySelectorAll('a[href]').forEach(function(a){ seen['u'+a.href]=1; });
        return (list||[]).filter(function(p){
          if ( ! p || seen['i'+p.id] || seen['u'+p.permalink] || ! accept(p) ) { return false; }
          seen['i'+p.id]=1; seen['u'+p.permalink]=1; return true;
        }).slice(0, MORE);
      }).catch(function(){ return []; }).then(function(list){
        var h=''; list.forEach(function(p){ h += card(px, p); });
        if ( href ) { h += endCard(px, href, label); }
        pending = h; state = 'done';
        flush();
      });
    }
    if ( 'IntersectionObserver' in window ) {
      var io = new IntersectionObserver(function(es){ es.forEach(function(e){ if ( e.isIntersecting ) { io.disconnect(); run(); } }); }, { rootMargin:'300px 0px' });
      io.observe(rail);
    } else { run(); }
    return { check: run };
  }
  function fetchJSON(url){
    return fetch(url, { headers:{ 'Accept':'application/json' }, credentials:'same-origin' })
      .then(function(r){ return r.ok ? r.json() : []; })
      .then(function(d){ return Array.isArray(d) ? d : []; });
  }

  /* فلش‌ها: «بعدی» = جلو رفتن در محتوا؛ در RTL محتوای بعدی سمت چپ است و scrollLeft منفی می‌شود */
  function step(rail, dir){
    if ( ! rail ) { return; }
    var rtl = getComputedStyle(rail).direction === 'rtl';
    rail.scrollBy({ left: ( rtl ? -dir : dir ) * Math.max( rail.clientWidth * 0.8, 260 ), behavior:'smooth' });
  }

  /* کشیدن با موس: محتوا دنبالِ موس می‌آید، با رها کردن روی نزدیک‌ترین کارت می‌نشیند؛ کلیک بعد از کشیدن لغو می‌شود */
  function drag(rail){
    var down=false, moved=false, sx=0, sl=0;
    function grabbable(){ rail.classList.toggle('ks-rail-grab', rail.scrollWidth > rail.clientWidth + 2); }
    // ResizeObserver بعد از layoutِ خودِ مرورگر صدا زده می‌شود، پس خواندنِ scrollWidth رفلوی اجباری نمی‌سازد
    // (قبلاً موقعِ لود ۵ ریل پشتِ هم layout را جلو می‌انداختند). نمایانِ شدنِ تبِ پنهان هم همین‌جا دیده می‌شود.
    if ( 'ResizeObserver' in window ) { new ResizeObserver(grabbable).observe(rail); }
    else { grabbable(); window.addEventListener('resize', grabbable, { passive:true }); }
    rail.addEventListener('pointerenter', grabbable);
    new MutationObserver(grabbable).observe(rail, { childList:true });
    rail.addEventListener('pointerdown', function(e){
      if ( e.pointerType !== 'mouse' || e.button !== 0 || rail.scrollWidth <= rail.clientWidth + 2 ) { return; }
      down=true; moved=false; sx=e.clientX; sl=rail.scrollLeft;
    });
    window.addEventListener('pointermove', function(e){
      if ( ! down ) { return; }
      var dx = e.clientX - sx;
      if ( ! moved && Math.abs(dx) > 6 ) { moved=true; rail.classList.add('ks-rail-dragging'); }
      if ( moved ) { rail.scrollLeft = sl - dx; e.preventDefault(); }
    });
    function up(){
      if ( ! down ) { return; }
      down=false;
      if ( ! moved ) { return; }
      settle();
      setTimeout(function(){ moved=false; }, 0);
    }
    function settle(){
      var cs = getComputedStyle(rail), rb = rail.getBoundingClientRect(), rtl = cs.direction === 'rtl';
      var edge = rtl ? rb.right - parseFloat(cs.paddingRight) : rb.left + parseFloat(cs.paddingLeft);
      var best = null;
      Array.prototype.forEach.call(rail.children, function(c){
        if ( ! c.offsetWidth ) { return; }
        var r = c.getBoundingClientRect(), d = ( rtl ? r.right : r.left ) - edge;
        if ( best === null || Math.abs(d) < Math.abs(best) ) { best = d; }
      });
      rail.scrollTo({ left: rail.scrollLeft + (best||0), behavior:'smooth' });
      setTimeout(function(){ rail.classList.remove('ks-rail-dragging'); }, 450);
    }
    window.addEventListener('pointerup', up);
    window.addEventListener('pointercancel', up);
    rail.addEventListener('click', function(e){ if ( moved ) { e.preventDefault(); e.stopPropagation(); } }, true);
    rail.addEventListener('dragstart', function(e){ e.preventDefault(); });
  }
  document.querySelectorAll('.ks-sale__rail,.ks-prod__rail,.ks-blog__rail,.ks-vid__rail,.ks-cats__rail').forEach(drag);

  return { card:card, finite:finite, fetchJSON:fetchJSON, storeUrl:storeUrl, step:step, markMedia:markMedia };
})();

/* ===================== header on home ===================== */
(function(){
  var h = document.querySelector('[data-ks-hdr]');
  if ( ! h ) { return; }
  function on(){ h.classList.toggle('ks-hdr--shown', window.scrollY > 80); }
  on(); window.addEventListener('scroll', on, { passive:true });
})();

/* ===================== hero ===================== */
(function(){
  // ویدیوی هیرو با شروعِ پخش محو می‌شود و همین که اسکریپت اجرا شد (بعد از ساختِ صفحه) وصل می‌شود؛
  // قبلاً در موبایل تا رویدادِ load (لودِ همهٔ عکس‌ها) صبر می‌کرد و دیر پخش می‌شد.
  // اگر data-src-m (نسخهٔ سبکِ موبایل) باشد، در صفحه‌های زیر 768px همان دانلود می‌شود.
  // با «کاهش حرکت» یا «صرفه‌جویی داده»/اینترنت 2G اصلاً دانلود نمی‌شود و هیرو تیره می‌ماند.
  var v = document.querySelector('[data-ks-hero-video]');
  if ( ! v ) { return; }
  if ( window.matchMedia('(prefers-reduced-motion: reduce)').matches ) { return; }
  var c = navigator.connection;
  if ( c && ( c.saveData || /2g/.test( c.effectiveType || '' ) ) ) { return; }
  var mobile = window.matchMedia('(max-width: 767px)').matches;
  // آیفون/آیپد (همهٔ مرورگرهایش WebKit است) و سافاری: WebM را یا اصلاً پخش نمی‌کنند یا کُدکش (VP9/AV1) را ندارند؛
  // اگر نسخهٔ mp4 گذاشته شده باشد، WebM اصلاً به آن‌ها داده نمی‌شود تا مستقیم mp4 را بگیرند.
  var ua = navigator.userAgent;
  var apple = /iPhone|iPad|iPod/.test(ua) || ( /Macintosh/.test(ua) && navigator.maxTouchPoints > 1 ) || ( /Safari/.test(ua) && ! /Chrome|Chromium|Edg|OPR|Android/.test(ua) );
  var hasMp4 = !! v.querySelector('source[type="video/mp4"][data-src]');
  function play(){ var p = v.play(); return ( p && p.catch ) ? p : { catch:function(){} }; }
  function start(){
    v.muted = true; v.defaultMuted = true; v.playsInline = true; // iOS فقط ویدیوی بی‌صدا و inline را خودکار پخش می‌کند
    v.querySelectorAll('source[data-src]').forEach(function(s){
      if ( apple && hasMp4 && s.type === 'video/webm' ) { s.remove(); return; }
      s.src = ( mobile && s.getAttribute('data-src-m') ) || s.getAttribute('data-src');
    });
    v.addEventListener('playing', function(){ v.classList.add('is-playing'); }, { once:true });
    v.load();
    // پخشِ خودکار رد شد (مثلاً «حالت کم‌مصرف» آیفون): با اولین لمس/کلیکِ کاربر دوباره امتحان می‌شود
    play().catch(function(){
      var ev = ['touchend','click','keydown'];
      function retry(){ ev.forEach(function(t){ document.removeEventListener(t, retry, true); }); play().catch(function(){}); }
      ev.forEach(function(t){ document.addEventListener(t, retry, { capture:true, passive:true }); });
    });
  }
  start();
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
  ksHome.finite(rail, 'ks-sale', function(ids){
    return ksHome.fetchJSON( ksHome.storeUrl(base, { on_sale:'true', stock_status:'instock', per_page:12, orderby:'date', order:'desc', exclude:ids.join(',') }) );
  }, function(p){ return !! p.on_sale && p.is_in_stock !== false; });
  var n = sec.querySelector('[data-ks-sale-next]'), p = sec.querySelector('[data-ks-sale-prev]');
  n && n.addEventListener('click', function(){ ksHome.step(rail, 1); });
  p && p.addEventListener('click', function(){ ksHome.step(rail, -1); });
})();

/* ===================== products ===================== */
(function(){
  var sec = document.querySelector('[data-ks-prod]');
  if ( ! sec ) { return; }
  var base  = sec.getAttribute('data-store');
  var rails = {}, loaders = {};
  // کارت‌های تب‌های پنهان در <template> هستند؛ اولین باری که تب باز شود ساخته می‌شوند،
  // و بارگذاریِ محدودِ همان تب هم بعد از آن راه می‌افتد (تا کارتِ «مشاهده همه» آخرِ ریل بنشیند).
  function init(slug){
    var rail = rails[slug];
    if ( ! rail || loaders[slug] ) { return loaders[slug]; }
    var tpl = rail.querySelector('template[data-ks-prod-tpl]');
    if ( tpl ) { rail.insertBefore(tpl.content, tpl); tpl.remove(); }
    var id = rail.getAttribute('data-cat-id');
    loaders[slug] = ksHome.finite(rail, 'ks-prod', function(ids){
      return ksHome.fetchJSON( ksHome.storeUrl(base, { category:id, stock_status:'instock', per_page:12, orderby:'date', order:'desc', exclude:ids.join(',') }) );
    }, function(p){
      // فقط محصولِ همین دسته؛ اگر سرور فیلترِ دسته را نادیده بگیرد، چیزِ اشتباهی وارد نمی‌شود
      return p.is_in_stock !== false && ( p.categories || [] ).some(function(c){ return String(c.id) === String(id) || c.slug === slug; });
    });
    return loaders[slug];
  }
  sec.querySelectorAll('[data-ks-prod-rail]').forEach(function(rail){
    var slug = rail.getAttribute('data-cat-slug');
    rails[slug] = rail;
    if ( ! rail.hidden ) { init(slug); }
  });
  function current(){ return sec.querySelector('[data-ks-prod-rail]:not([hidden])'); }

  // تب‌ها: همهٔ دسته‌ها سمتِ سرور رندر شده‌اند؛ تعویض فقط نمایش/پنهان است، بدونِ شبکه
  sec.querySelectorAll('[data-ks-prod-tab]').forEach(function(btn){
    btn.addEventListener('click', function(){
      if ( btn.classList.contains('is-active') ) { return; }
      var slug = btn.getAttribute('data-ks-prod-tab');
      sec.querySelectorAll('[data-ks-prod-tab]').forEach(function(b){ var on = b === btn; b.classList.toggle('is-active', on); b.setAttribute('aria-selected', on ? 'true' : 'false'); });
      Object.keys(rails).forEach(function(k){ rails[k].hidden = ( k !== slug ); });
      var r = rails[slug];
      if ( r ) { var l = init(slug); r.scrollLeft = 0; ksHome.markMedia(r); l.check(); }
    });
  });

  var n = sec.querySelector('[data-ks-prod-next]'), p = sec.querySelector('[data-ks-prod-prev]');
  n && n.addEventListener('click', function(){ ksHome.step(current(), 1); });
  p && p.addEventListener('click', function(){ ksHome.step(current(), -1); });
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
