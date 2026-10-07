/* =====================================================================
   تسویه حساب — خانه سعادت
   محل: wp-content/themes/hello-child/assets/js/checkout.js   (فقط روی صفحهٔ تسویه حساب، defer)

   ارقامِ فارسی/عربی در فیلدهای عددی (تلفن، موبایل، کد پستی، کد ملی) همان لحظهٔ تایپ یا چسباندن
   به رقمِ انگلیسی تبدیل می‌شوند؛ اعتبارسنجیِ ووکامرس «۰۹۱۲…» را شمارهٔ نامعتبر می‌داند.
   بقیهٔ کارها (به‌روزرسانیِ خلاصه، روش ارسال/پرداخت، کد تخفیف، خطاها) را checkout.jsِ خودِ ووکامرس انجام می‌دهد.
   ===================================================================== */
(function(){
  'use strict';
  var FA = '۰۱۲۳۴۵۶۷۸۹', AR = '٠١٢٣٤٥٦٧٨٩';
  var SEL = 'input[type="tel"],input[inputmode="numeric"],input[name$="_phone"],input[name$="_mobile"],input[name$="_postcode"],input[name*="national"],input[name*="melli"]';

  function latin(s){
    return String(s).replace(/[۰-۹٠-٩]/g, function(d){ var i = FA.indexOf(d); return String(i > -1 ? i : AR.indexOf(d)); });
  }
  function fix(el){
    if ( ! el || ! el.matches || ! el.matches(SEL) ) { return; }
    var v = el.value, n = latin(v);
    if ( n === v ) { return; }
    var p = el.selectionStart;
    el.value = n;
    try { if ( p != null && document.activeElement === el ) { el.setSelectionRange(p, p); } } catch (e) {} // هر رقم یک حرف است؛ جای مکان‌نما ثابت می‌ماند
  }

  document.documentElement.classList.add('ks-co-js');

  document.addEventListener('input', function(e){ fix(e.target); }, true);
  document.addEventListener('change', function(e){ fix(e.target); }, true);
  // مقدارهایی که از قبل (پروفایلِ مشتری یا پرِ خودکار) در فرم بوده‌اند
  document.querySelectorAll(SEL).forEach(fix);

  /* ---------- موبایل: نوارِ چسبانِ «ثبت سفارش» ----------
     - مبلغ = همان «مبلغ قابل پرداخت»ِ خلاصهٔ سفارش؛ متنِ دکمه = متنِ #place_order (درگاه‌ها عوضش می‌کنند).
     - وقتی خودِ دکمهٔ «ثبت سفارش» در صفحه دیده می‌شود، نوار کنار می‌رود تا دو دکمه کنارِ هم نباشد.
     - اگر «شرایط و قوانین» تیک نخورده، اول همان‌جا برده می‌شود؛ وگرنه همان دکمهٔ اصلیِ ووکامرس زده می‌شود. */
  var bar = document.querySelector('[data-ks-co-bar]');
  if ( ! bar ) { return; }
  var total = bar.querySelector('[data-ks-co-bar-total]'), go = bar.querySelector('[data-ks-co-bar-go]'), raf = 0;

  function sync(){
    var t = document.querySelector('.woocommerce-checkout-review-order-table .order-total td');
    if ( t ) { var s = t.querySelector('strong') || t; total.innerHTML = s.innerHTML; }
    var po = document.getElementById('place_order');
    if ( po && po.textContent.trim() ) { go.textContent = po.textContent.trim(); }
    place();
  }
  function place(){
    raf = 0;
    var po = document.getElementById('place_order');
    var r = po ? po.getBoundingClientRect() : null;
    var seen = !! r && r.bottom > 0 && r.top < window.innerHeight - bar.offsetHeight;
    bar.classList.toggle('is-off', seen);
  }
  function later(){ if ( ! raf ) { raf = requestAnimationFrame(place); } }
  window.addEventListener('scroll', later, { passive:true });
  window.addEventListener('resize', later, { passive:true });

  go.addEventListener('click', function(){
    var terms = document.getElementById('terms');
    if ( terms && ! terms.checked ) {
      var row = terms.closest('.form-row') || terms;
      row.scrollIntoView({ behavior:'smooth', block:'center' });
      row.classList.add('ks-attn');
      setTimeout(function(){ row.classList.remove('ks-attn'); }, 2200);
      try { terms.focus({ preventScroll:true }); } catch (e) {}
      return;
    }
    var po = document.getElementById('place_order');
    if ( po ) { po.click(); }
  });

  // خلاصهٔ سفارش و بخشِ پرداخت با Ajaxِ ووکامرس عوض می‌شوند
  if ( window.jQuery ) { window.jQuery(document.body).on('updated_checkout', sync); }
  sync();
})();
