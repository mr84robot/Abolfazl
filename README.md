# خانه سعادت — قالب فرزند صفحهٔ اصلی

بازطراحی صفحهٔ اصلی فروشگاه خانه سعادت (khanehsaadat.com) به‌صورت قالب فرزند وردپرس (`hello-child`).

## ساختار

- `hello-child/front-page.php` — صفحهٔ اصلی؛ هر سکشن یک partial.
- `hello-child/header.php` و `hello-child/footer.php` — هدر و فوترِ سراسری (همهٔ صفحه‌ها).
- `hello-child/page-cart.php` — صفحهٔ سبد خرید؛ سبدِ کلاسیکِ ووکامرس با قالب‌های `hello-child/woocommerce/cart/` (cart، cart-totals، cart-empty، proceed-to-checkout-button) و توابعِ `hello-child/inc/cart.php`.
- `hello-child/page-checkout.php` — تسویه حساب و «سفارش ثبت شد»؛ تسویه حسابِ کلاسیکِ ووکامرس با قالب‌های `hello-child/woocommerce/checkout/` (form-checkout، form-billing، form-shipping، review-order، payment، payment-method، form-coupon، thankyou).
- `hello-child/template-parts/home/` — سکشن‌های صفحهٔ اصلی (hero, trust, categories, sale, brands, products, saadat-pay, blog, seo-guide, videos). فقط HTML/PHP؛ هیچ CSS یا JSِ درون‌خطی ندارند.
- `hello-child/assets/css/home.css` — استایلِ همهٔ سکشن‌ها در یک فایل.
- `hello-child/assets/js/home.js` — اسکریپتِ همهٔ سکشن‌ها در یک فایل (defer، در فوتر).
- `hello-child/assets/css/site.css` و `assets/js/site.js` — استایل و اسکریپتِ هدر و فوترِ سراسری.
- `hello-child/assets/css/cart.css` و `assets/js/cart.js` — فقط روی صفحهٔ سبد خرید (از header.php لود می‌شوند). cart.css پایهٔ مشترکِ سبد و تسویه حساب هم هست.
- `hello-child/assets/css/checkout.css` و `assets/js/checkout.js` — فقط روی تسویه حساب (checkout.js ارقامِ فارسیِ موبایل/کد پستی را انگلیسی می‌کند).
- `hello-child/assets/fonts/montserrat-800-caps.woff2` — فونتِ نام برندها (۴.۷KB، روی خود سایت).
- `preview/home-preview.html` — پیش‌نمایش؛ از روی خودِ کدِ PHP ساخته می‌شود: `php tools/preview/build.php > preview/home-preview.html`
- `preview/cart-preview.html` و `preview/cart-empty-preview.html` — پیش‌نمایشِ سبد خرید (پر و خالی): `php tools/preview/build-cart.php [--empty] > …`
- `preview/checkout-preview.html`، `checkout-thankyou-preview.html`، `checkout-failed-preview.html` — پیش‌نمایشِ تسویه حساب: `php tools/preview/build-checkout.php [--thankyou|--failed|--errors] > …`
- `tools/preview/stubs.php` (+ `stubs-shop.php` برای سبد/تسویه) — شبیه‌سازِ مشترکِ وردپرس/ووکامرس/CodeLock؛ `build.php` (صفحهٔ اصلی)، `build-cart.php` و `build-checkout.php` از آن استفاده می‌کنند (`--hostile` برای تستِ مقاومت در برابر استایلِ قالب).
- `design/Main.dc.html` — طرح مرجع اولیه.
- `data/` — دادهٔ کاری توسعه (دسته‌ها، برندها، محصولات).

محل نصب روی سایت: `wp-content/themes/hello-child/`

## آپلود روی سایت

همهٔ این‌ها با همین مسیرها داخل `wp-content/themes/hello-child/` می‌روند:

| فایل | توضیح |
|---|---|
| `front-page.php` | صفحهٔ اصلی؛ `home.css` و `home.js` را فقط روی همین صفحه لود می‌کند |
| `header.php` | هدرِ سراسری؛ `site.css` و `site.js` را لود می‌کند |
| `footer.php` | فوترِ سراسری |
| `page-cart.php` | صفحهٔ سبد خرید |
| `woocommerce/cart/*.php` | ۴ قالبِ سبد خرید (بازنویسیِ ووکامرس) |
| `page-checkout.php` | تسویه حساب و «سفارش ثبت شد» |
| `woocommerce/checkout/*.php` | ۸ قالبِ تسویه حساب (بازنویسیِ ووکامرس) |
| `inc/cart.php` | توابعِ کمکیِ سبد خرید و تسویه حساب |
| `assets/css/cart.css`، `assets/js/cart.js` | سبد خرید (cart.css روی تسویه حساب هم) |
| `assets/css/checkout.css`، `assets/js/checkout.js` | تسویه حساب |
| `template-parts/home/*.php` | ۱۰ سکشن |
| `assets/css/home.css` | یک فایل CSS |
| `assets/js/home.js` | یک فایل JS |
| `assets/css/site.css`، `assets/js/site.js` | هدر و فوتر |
| `assets/fonts/montserrat-800-caps.woff2` | فونت برندها |

**سبد خرید:** اگر آدرسِ برگهٔ سبد خرید `/cart/` باشد، `page-cart.php` خودکار استفاده می‌شود. در غیر این صورت (یا اگر برگه با قالبِ «تمام‌عرضِ» المنتور ذخیره شده)، در ویرایشِ برگهٔ سبد خرید از کادرِ «قالب» گزینهٔ «سبد خرید (خانه سعادت)» را انتخاب کنید.

**تسویه حساب:** همین‌طور؛ آدرسِ `/checkout/` خودکار، وگرنه در ویرایشِ برگهٔ تسویه حساب قالبِ «تسویه حساب (خانه سعادت)».

نسخهٔ CSS/JS از زمانِ تغییرِ فایل ساخته می‌شود، پس بعد از هر آپلود کشِ مرورگرِ کاربران خودکار تازه می‌شود. اگر افزونهٔ کش دارید (LiteSpeed، WP Rocket و…)، بعد از آپلود کشِ آن را پاک کنید.

فایل‌های قدیمیِ زیر دیگر استفاده نمی‌شوند و باید از روی سرور پاک شوند: `template-parts/home/showroom.php`، `assets/css/header.css`، `assets/js/header.js`.

`preview/` و `tools/` فقط برای توسعه‌اند و روی سایت آپلود نمی‌شوند.
