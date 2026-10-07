/* =====================================================================
   پیگیری سفارش — خانه سعادت
   محل: wp-content/themes/hello-child/assets/js/track.js   (inc/order-track.php صفش می‌کند، defer)

   - باز شدن: دکمهٔ ثابتِ صفحهٔ اصلی، هر [data-ks-trk-open]، هر لینک با href="#ks-track" و آدرسِ …/#ks-track.
   - <dialog> بومی: فوکوس داخلِ پاپ‌آپ می‌ماند و Esc می‌بندد؛ کلیک روی پس‌زمینه هم می‌بندد.
   - ارقامِ فارسی هنگامِ تایپ انگلیسی می‌شوند؛ آخرین موبایلِ واردشده در همین مرورگر یادش می‌ماند.
   - پاسخِ سرور فقط متن است و با textContent گذاشته می‌شود (هیچ HTMLی از سرور وارد صفحه نمی‌شود).
   ===================================================================== */
(function(){
  'use strict';
  var dlg = document.querySelector('[data-ks-trk]');
  if ( ! dlg ) { return; }
  var form = dlg.querySelector('[data-ks-trk-form]'), res = dlg.querySelector('[data-ks-trk-res]');
  var err = dlg.querySelector('[data-ks-trk-err]'), go = dlg.querySelector('[data-ks-trk-go]');
  var phone = form.elements.phone, num = form.elements.order;
  var FA = '۰۱۲۳۴۵۶۷۸۹', AR = '٠١٢٣٤٥٦٧٨٩', KEY = 'ks-trk-phone', busy = false;

  function latin(s){ return String(s).replace(/[۰-۹٠-٩]/g, function(d){ var i = FA.indexOf(d); return String(i > -1 ? i : AR.indexOf(d)); }); }
  function el(tag, cls, text){ var e = document.createElement(tag); if ( cls ) { e.className = cls; } if ( text != null ) { e.textContent = text; } return e; }
  function store(v){ try { if ( v == null ) { return localStorage.getItem(KEY) || ''; } localStorage.setItem(KEY, v); } catch (e) {} return ''; }

  /* ---------- باز و بسته ---------- */
  function open(){
    if ( dlg.open ) { return; }
    if ( ! phone.value ) { phone.value = store(); }
    if ( typeof dlg.showModal === 'function' ) { dlg.showModal(); } else { dlg.setAttribute('open', ''); }
    document.documentElement.classList.add('ks-trk-lock');
    setTimeout(function(){ ( res.hidden ? ( phone.value ? num : phone ) : res.querySelector('button') || dlg ).focus(); }, 30);
  }
  function close(){
    if ( typeof dlg.close === 'function' && dlg.open ) { dlg.close(); } else { dlg.removeAttribute('open'); onClose(); }
  }
  function onClose(){
    document.documentElement.classList.remove('ks-trk-lock');
    if ( location.hash === '#ks-track' && history.replaceState ) { history.replaceState(null, '', location.pathname + location.search); }
  }
  dlg.addEventListener('close', onClose);
  dlg.addEventListener('click', function(e){
    if ( e.target === dlg || e.target.closest('[data-ks-trk-close]') ) { close(); } // کلیک روی پس‌زمینه = خودِ dialog
  });
  document.addEventListener('click', function(e){
    var t = e.target.closest && e.target.closest('[data-ks-trk-open],a[href$="#ks-track"]');
    if ( t ) { e.preventDefault(); open(); }
  });
  if ( location.hash === '#ks-track' ) { open(); }
  window.addEventListener('hashchange', function(){ if ( location.hash === '#ks-track' ) { open(); } });

  /* ---------- فرم ---------- */
  [phone, num].forEach(function(i){
    i.addEventListener('input', function(){
      var v = latin(i.value); if ( v !== i.value ) { var p = i.selectionStart; i.value = v; try { i.setSelectionRange(p, p); } catch (x) {} }
      i.removeAttribute('aria-invalid'); err.hidden = true;
    });
  });
  function fail(msg, field){
    err.textContent = msg; err.hidden = false;
    if ( field ) { field.setAttribute('aria-invalid', 'true'); field.focus(); }
  }
  form.addEventListener('submit', function(e){
    e.preventDefault();
    if ( busy ) { return; }
    var p = latin(phone.value).replace(/[^\d+]/g, ''), n = latin(num.value).replace(/^#/, '').trim();
    if ( ! /^(\+98|0098|98|0)?9\d{9}$/.test(p) && ! /^0\d{10}$/.test(p) ) { return fail('شماره موبایل را درست وارد کنید (مثلاً 09121234567).', phone); }
    if ( ! n ) { return fail('شماره سفارش را وارد کنید.', num); }
    busy = true; go.disabled = true; go.classList.add('is-busy'); err.hidden = true;
    var body = new FormData(); body.append('phone', p); body.append('order', n);
    fetch(dlg.getAttribute('data-url'), { method: 'POST', body: body, credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
      .then(function(r){ return r.json().catch(function(){ return { success: false }; }); })
      .then(function(j){
        if ( j && j.success && j.data ) { store(p); show(j.data); }
        else { fail( ( j && j.data && j.data.msg ) || 'پیگیری انجام نشد؛ دوباره امتحان کنید.' ); }
      })
      .catch(function(){ fail('اتصال برقرار نشد؛ اینترنت را بررسی کنید و دوباره امتحان کنید.'); })
      .then(function(){ busy = false; go.disabled = false; go.classList.remove('is-busy'); });
  });

  /* ---------- نتیجه ---------- */
  function show(d){
    res.textContent = '';
    var card = el('div', 'ks-trk__card');
    var top = el('div', 'ks-trk__top');
    top.appendChild(el('strong', 'ks-trk__no', 'سفارش #' + d.number));
    top.appendChild(el('span', 'ks-trk__st ks-trk__st--' + d.tone, d.status));
    card.appendChild(top);

    var facts = el('ul', 'ks-trk__facts');
    [['تاریخ ثبت', d.date], ['مبلغ کل', d.total], ['روش پرداخت', d.pay]].forEach(function(f){
      if ( ! f[1] ) { return; }
      var li = el('li'); li.appendChild(el('span', '', f[0])); li.appendChild(el('strong', '', f[1])); facts.appendChild(li);
    });
    card.appendChild(facts);

    if ( d.bad ) {
      card.appendChild(el('p', 'ks-trk__bad', 'این سفارش «' + d.status + '» است. اگر سؤالی دارید با پشتیبانی تماس بگیرید.'));
    } else if ( d.steps && d.steps.length ) {
      var ol = el('ol', 'ks-trk__steps'); ol.setAttribute('aria-label', 'مراحل سفارش');
      d.steps.forEach(function(s, i){
        var li = el('li', 'ks-trk__step' + ( s[1] ? ' is-' + s[1] : '' ));
        if ( s[1] === 'current' ) { li.setAttribute('aria-current', 'step'); }
        li.appendChild(el('span', 'ks-trk__n', s[1] === 'done' ? '✓' : FA.charAt(i + 1)));
        li.appendChild(el('span', 'ks-trk__sl', s[0]));
        ol.appendChild(li);
      });
      card.appendChild(ol);
    }

    if ( d.items && d.items.length ) {
      var ul = el('ul', 'ks-trk__items');
      d.items.forEach(function(it){ var li = el('li'); li.appendChild(el('span', '', it.name)); li.appendChild(el('b', '', '× ' + it.qty)); ul.appendChild(li); });
      if ( d.more ) { ul.appendChild(el('li', 'ks-trk__more', 'و ' + d.more + ' کالای دیگر')); }
      card.appendChild(ul);
    }

    ( d.notes || [] ).forEach(function(n){
      var box = el('div', 'ks-trk__note');
      box.appendChild(el('span', '', 'پیامِ فروشگاه — ' + n.date));
      box.appendChild(el('p', '', n.text));
      card.appendChild(box);
    });

    var act = el('div', 'ks-trk__act');
    if ( d.url ) { var a = el('a', 'ks-trk__btn', 'جزئیات در حساب کاربری'); a.href = d.url; act.appendChild(a); }
    var again = el('button', 'ks-trk__btn ks-trk__btn--ghost', 'پیگیری سفارش دیگر'); again.type = 'button';
    again.addEventListener('click', function(){ res.hidden = true; form.hidden = false; num.value = ''; num.focus(); });
    act.appendChild(again);
    card.appendChild(act);

    res.appendChild(card);
    form.hidden = true; res.hidden = false;
    again.focus();
  }
})();
