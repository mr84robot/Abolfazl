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

  document.addEventListener('input', function(e){ fix(e.target); }, true);
  document.addEventListener('change', function(e){ fix(e.target); }, true);
  // مقدارهایی که از قبل (پروفایلِ مشتری یا پرِ خودکار) در فرم بوده‌اند
  document.querySelectorAll(SEL).forEach(fix);
})();
