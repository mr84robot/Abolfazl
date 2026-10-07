/* =====================================================================
   حساب کاربری — خانه سعادت
   محل: wp-content/themes/hello-child/assets/js/account.js   (فقط روی صفحه‌های حساب کاربری، defer)

   ارقامِ فارسی/عربی در فیلدهای عددیِ فرمِ آدرس و حساب (موبایل، کد پستی، کد ملی) همان لحظهٔ تایپ یا چسباندن
   به رقمِ انگلیسی تبدیل می‌شوند؛ اعتبارسنجیِ ووکامرس «۰۹۱۲…» را شمارهٔ نامعتبر می‌داند.
   ایمیلِ فرمِ «جزئیات حساب» وقتی inc/shop-setup.php فعال است اختیاری علامت می‌خورد.
   ===================================================================== */
(function(){
  'use strict';
  var FA = '۰۱۲۳۴۵۶۷۸۹', AR = '٠١٢٣٤٥٦٧٨٩';
  var SEL = '.ks-acc input[type="tel"],.ks-acc input[inputmode="numeric"],.ks-acc input[name$="_phone"],.ks-acc input[name$="_mobile"],.ks-acc input[name$="_postcode"],.ks-acc input[name*="national"],.ks-acc input[name*="melli"]';

  function latin(s){
    return String(s).replace(/[۰-۹٠-٩]/g, function(d){ var i = FA.indexOf(d); return String(i > -1 ? i : AR.indexOf(d)); });
  }
  function fix(el){
    if ( ! el || ! el.matches || ! el.matches(SEL) ) { return; }
    var v = el.value, n = latin(v);
    if ( n === v ) { return; }
    var p = el.selectionStart;
    el.value = n;
    try { if ( p != null && document.activeElement === el ) { el.setSelectionRange(p, p); } } catch (e) {}
  }

  document.addEventListener('input', function(e){ fix(e.target); }, true);
  document.addEventListener('change', function(e){ fix(e.target); }, true);
  document.querySelectorAll(SEL).forEach(fix);

  // ایمیلِ «جزئیات حساب» اختیاری است (inc/shop-setup.php)
  var em = document.getElementById('account_email');
  if ( em && document.body.classList.contains('ks-email-optional') ) { em.removeAttribute('aria-required'); em.removeAttribute('required'); }
})();
