/* =====================================================================
   سبد خرید — خانه سعادت
   محل: wp-content/themes/hello-child/assets/js/cart.js   (فقط روی صفحهٔ سبد خرید، defer)

   - دکمه‌های + و − کنارِ input.qtyِ خودِ ووکامرس.
   - تغییرِ تعداد خودکار ثبت می‌شود: بعد از مکثی کوتاه همان دکمهٔ «به‌روزرسانی سبد»ِ ووکامرس زده
     می‌شود؛ cart.jsِ ووکامرس آن را با Ajax می‌فرستد و فرم و خلاصهٔ سبد را تازه می‌کند.
     اگر اسکریپتِ ووکامرس نبود، همان فرم معمولی ارسال می‌شود (صفحه تازه می‌شود) — باز هم کار می‌کند.
   - همهٔ رویدادها روی document گرفته می‌شوند، چون ووکامرس بعد از هر به‌روزرسانی فرم را عوض می‌کند.
   ===================================================================== */
(function(){
  'use strict';
  document.documentElement.classList.add('ks-cart-js');

  var WAIT = 650, timer = null;

  function num(v, d){ var n = parseFloat(v); return isNaN(n) ? d : n; }
  function limits(input){
    var max = num(input.max, NaN);
    return { min: Math.max(1, num(input.min, 1)), max: ( max > 0 ? max : Infinity ), step: num(input.step, 1) || 1 };
  }
  function sync(wrap){
    var input = wrap && wrap.querySelector('input.qty');
    if ( ! input ) { return; }
    var v = num(input.value, 0), l = limits(input);
    var dec = wrap.querySelector('[data-ks-qty="-1"]'), inc = wrap.querySelector('[data-ks-qty="1"]');
    if ( dec ) { dec.disabled = v <= l.min; }
    if ( inc ) { inc.disabled = v >= l.max; }
  }
  function submit(){
    var form = document.querySelector('form.woocommerce-cart-form');
    var btn  = form && form.querySelector('[name="update_cart"]');
    if ( ! btn ) { return; }
    btn.disabled = false; btn.removeAttribute('aria-disabled');
    btn.click(); // ووکامرس: Ajax؛ بدونِ آن: ارسالِ معمولیِ فرم
  }
  function schedule(){ clearTimeout(timer); timer = setTimeout(submit, WAIT); }

  document.addEventListener('click', function(e){
    var btn = e.target.closest ? e.target.closest('[data-ks-qty]') : null;
    if ( ! btn || btn.disabled ) { return; }
    var wrap  = btn.closest('.ks-qty');
    var input = wrap && wrap.querySelector('input.qty');
    if ( ! input || input.readOnly ) { return; }
    e.preventDefault();
    var l = limits(input), v = num(input.value, l.min);
    var n = Math.min(l.max, Math.max(l.min, v + num(btn.getAttribute('data-ks-qty'), 0) * l.step));
    if ( n === v ) { return; }
    input.value = n;
    // ووکامرس با همین رویدادها دکمهٔ به‌روزرسانی را فعال می‌کند
    input.dispatchEvent(new Event('input', { bubbles:true }));
    input.dispatchEvent(new Event('change', { bubbles:true }));
  });

  // تعدادِ تایپ‌شده یا تغییرکرده با دکمه‌ها → بعد از مکث ثبت شود
  document.addEventListener('change', function(e){
    var t = e.target;
    if ( ! t.matches || ! t.matches('.woocommerce-cart-form input.qty') ) { return; }
    sync(t.closest('.ks-qty'));
    schedule();
  });
  // اگر کاربر خودش Enter زد یا کد تخفیف ثبت کرد، ارسالِ زمان‌بندی‌شده لازم نیست
  document.addEventListener('submit', function(e){
    if ( e.target && e.target.matches && e.target.matches('form.woocommerce-cart-form') ) { clearTimeout(timer); }
  }, true);

})();
