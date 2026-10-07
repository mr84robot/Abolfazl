<?php
/**
 * فوتر کدنویسی‌شدهٔ خانه سعادت  (جایگزین تمپلیت فوتر المنتور #2526)
 * ---------------------------------------------------------------
 * محل نصب:  wp-content/themes/hello-child/footer.php
 *
 * این فایل، footer.php قالب مادر (Hello Elementor) را override می‌کند و منطقش را حفظ می‌کند:
 *   - اگر فوتر فعالی در Theme Builder المنتور باشد، همان رندر می‌شود (وسط مهاجرت چیزی نمی‌شکند).
 *   - وگرنه فوترِ دستی‌کدشدهٔ زیر رندر می‌شود.
 *   - روی قالب‌های Canvas/بدون‌هدر-فوتر چیزی نشان داده نمی‌شود.
 *   - wp_footer() و بستن </body></html> مثل قالب مادر انجام می‌شود.
 *
 * ستون‌های لینک روی موبایل آکاردئونی‌اند (details/summary):
 *   - بدون JS: همه باز (fallback سالم).  - با JS: موبایل جمع، دسکتاپ همیشه باز.
 * دکمهٔ «بازگشت به بالا» با اسکریپت ریزِ انتهای فایل، اسکرول نرم به بالا می‌کند
 * (fallback بدون JS: پرش به #top). فوتر به‌صورت باکسِ وسط‌چین (نه تمام‌عرض) است.
 *
 * استایل و اسکریپت در فایل‌های سراسریِ قالب است (هیچ CSS/JSِ درون‌خطی نمانده):
 *   assets/css/site.css و assets/js/site.js  ← header.php صفشان می‌کند (هدر + فوتر، همهٔ صفحه‌ها).
 *
 * نسبت به نسخهٔ قبل:
 *   - کلاسِ جعبهٔ نمادها از .ks-trust به .ks-footer__badges رفت؛ روی صفحهٔ اصلی با نوارِ «مزایا»
 *     (.ks-trust) تداخل داشت و پس‌زمینه/خطِ آن روی نمادها می‌نشست.
 *   - شناسهٔ آیکن‌ها ks-f-* شد؛ ks-i-phone در هدر هم بود و مرورگر آیکنِ خطیِ هدر را جای آیکنِ توپُرِ
 *     طلاییِ فوتر می‌نشاند.
 *   - لبه‌ها تیزتر و فاصله‌ها بیشتر.
 *
 * برای خروجِ کاملِ فوتر از المنتور، تمپلیت فوتر #2526 را در
 * Elementor ▸ Templates ▸ Theme Builder ▸ Footer به Draft ببر یا حذف کن.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
	if ( ! function_exists( 'hello_elementor_display_header_footer' ) || hello_elementor_display_header_footer() ) :
?>

<footer class="ks-footer" aria-label="فوتر سایت خانه سعادت">

  <svg class="ks-footer__sprite" width="0" height="0" aria-hidden="true" focusable="false">
    <symbol id="ks-f-up" viewBox="0 0 448 512"><path d="M240.971 130.524l194.343 194.343c9.373 9.373 9.373 24.569 0 33.941l-22.667 22.667c-9.357 9.357-24.522 9.375-33.901.04L224 227.495 69.255 381.516c-9.379 9.335-24.544 9.317-33.901-.04l-22.667-22.667c-9.373-9.373-9.373-24.569 0-33.941L207.03 130.525c9.372-9.373 24.568-9.373 33.941-.001z"/></symbol>
    <symbol id="ks-f-chevron" viewBox="0 0 448 512"><path d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"/></symbol>
    <symbol id="ks-f-phone" viewBox="0 0 512 512"><path d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z"/></symbol>
    <symbol id="ks-f-instagram" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></symbol>
  </svg>

  <div class="ks-footer__inner">

    <div class="ks-footer__top">
      <a class="ks-footer__logo" href="https://khanehsaadat.com/" aria-label="خانه سعادت - صفحه اصلی">
        <img src="https://khanehsaadat.com/wp-content/uploads/2026/10/w-logo-saadat.webp" width="993" height="354" alt="خانه سعادت" loading="lazy" decoding="async">
      </a>
      <div class="ks-footer__actions">
        <a class="ks-social ks-social--ig" href="https://www.instagram.com/khaneh.saadat" target="_blank" rel="noopener" aria-label="اینستاگرام خانه سعادت">
          <svg aria-hidden="true"><use href="#ks-f-instagram"></use></svg>
        </a>
        <a class="ks-backtotop" href="#top" aria-label="بازگشت به بالای صفحه">
          <svg class="ks-backtotop__icon" aria-hidden="true"><use href="#ks-f-up"></use></svg>
          <span>بازگشت به بالا</span>
        </a>
      </div>
    </div>

    <div class="ks-footer__cols">
      <div class="ks-col ks-col--about">
        <p><strong>فروشگاه اینترنتی لوازم خانگی</strong> سعادت از سال ۱۳۵۷ فعالیت خود را در عرصه لوازم خانه و آشپزخانه شروع کرد و تا سال ۱۴۰۳ در زمینه پخش و بنکداری لوازم خانه و آشپزخانه فعال بود و از سال ۱۴۰۳ به بعد شعبه جدیدی برای حذف واسطه‌ها راه‌اندازی کرد که این امر باعث شد تا ما امروز در اختیار شما عزیزان باشیم.</p>
      </div>

      <details class="ks-col ks-acc" open>
        <summary>
          <h3 class="ks-col__title" id="ks-col-main">صفحات اصلی سایت</h3>
          <svg class="ks-acc__chev" aria-hidden="true"><use href="#ks-f-chevron"></use></svg>
        </summary>
        <ul class="ks-links" aria-labelledby="ks-col-main">
          <li><a href="https://khanehsaadat.com/">صفحه اصلی</a></li>
          <li><a href="https://khanehsaadat.com/shop/">محصولات</a></li>
          <li><a href="https://khanehsaadat.com/hamedan/">خرید لوازم خانگی در همدان</a></li>
          <li><a href="https://khanehsaadat.com/installments/">خرید اقساطی</a></li>
          <li><a href="https://khanehsaadat.com/blog/">مقالات</a></li>
          <li><a href="https://khanehsaadat.com/about-us/">درباره ما</a></li>
          <li><a href="https://khanehsaadat.com/contact-us/">تماس با ما</a></li>
        </ul>
      </details>

      <details class="ks-col ks-acc" open>
        <summary>
          <h3 class="ks-col__title" id="ks-col-useful">لینک‌های مفید</h3>
          <svg class="ks-acc__chev" aria-hidden="true"><use href="#ks-f-chevron"></use></svg>
        </summary>
        <ul class="ks-links" aria-labelledby="ks-col-useful">
          <li><a href="https://khanehsaadat.com/product-category/food-preparation/ghazasaz/">خرید غذاساز</a></li>
          <li><a href="https://khanehsaadat.com/product-category/food-preparation/khordkon/">خرید خردکن</a></li>
          <li><a href="https://khanehsaadat.com/product-category/pokhopaz/sorkhkon/">خرید سرخ‌کن و ایرفرایر</a></li>
          <li><a href="https://khanehsaadat.com/product-category/noshidani/chaeisaz/">خرید چای‌ساز</a></li>
          <li><a href="https://khanehsaadat.com/product-category/flask/">خرید فلاسک</a></li>
        </ul>
      </details>

      <details class="ks-col ks-acc" open>
        <summary>
          <h3 class="ks-col__title" id="ks-col-brand">برندهای پرفروش</h3>
          <svg class="ks-acc__chev" aria-hidden="true"><use href="#ks-f-chevron"></use></svg>
        </summary>
        <ul class="ks-links" aria-labelledby="ks-col-brand">
          <li><a href="https://khanehsaadat.com/brand/mgs/">ام جی اس</a></li>
          <li><a href="https://khanehsaadat.com/brand/honira/">هونیرا</a></li>
          <li><a href="https://khanehsaadat.com/brand/sapor/">ساپر</a></li>
          <li><a href="https://khanehsaadat.com/brand/delmonti/">دلمونتی</a></li>
          <li><a href="https://khanehsaadat.com/brand/unique/">یونیک</a></li>
        </ul>
      </details>
    </div>

    <div class="ks-footer__contact">
      <div class="ks-contact">
        <h3 class="ks-col__title">تماس با ما</h3>
        <p>ساعت کاری: ۹ صبح الی ۷ شب</p>
        <p>فروشگاه: همدان - میدان عین‌القضات - بلوار علویان شرقی - بازار دباغ‌خانه بزرگ - خانه سعادت</p>
      </div>

      <nav class="ks-support" aria-labelledby="ks-col-support">
        <h3 class="ks-col__title" id="ks-col-support">پشتیبانی</h3>
        <ul class="ks-phones">
          <li><a href="tel:+989001110562"><svg aria-hidden="true"><use href="#ks-f-phone"></use></svg><span>فروش: <bdi>۰۹۰۰۱۱۱۰۵۶۲</bdi></span></a></li>
          <li><a href="tel:+989001110563"><svg aria-hidden="true"><use href="#ks-f-phone"></use></svg><span>خدمات: <bdi>۰۹۰۰۱۱۱۰۵۶۳</bdi></span></a></li>
          <li><a href="tel:+989001110564"><svg aria-hidden="true"><use href="#ks-f-phone"></use></svg><span>دیجیتال: <bdi>۰۹۰۰۱۱۱۰۵۶۴</bdi></span></a></li>
        </ul>
      </nav>

      <div class="ks-footer__badges">
        <a class="ks-footer__badge" href="https://trustseal.enamad.ir/?id=625532&amp;Code=vF2doA2XN6H6Vx8Z7IOTtbjVLzGJKaE5" target="_blank" rel="noopener nofollow" aria-label="نماد اعتماد الکترونیکی">
          <img src="https://khanehsaadat.com/wp-content/uploads/2025/07/logo.png" width="125" height="136" alt="نماد اعتماد الکترونیکی (اینماد)" loading="lazy" decoding="async">
        </a>
        <span class="ks-footer__badge">
          <img src="https://khanehsaadat.com/wp-content/uploads/2025/05/image-21.png" width="75" height="107" alt="نشان ملی ثبت" loading="lazy" decoding="async">
        </span>
      </div>
    </div>

    <p class="ks-footer__copy">تمام حقوق برای گروه سعادت محفوظ است. (طراحی و توسعه توسط کدلاک)</p>

  </div>
</footer>

<?php
	endif;
endif;

wp_footer();
?>
</body>
</html>
