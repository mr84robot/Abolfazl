# خانه سعادت — قالب فرزند (صفحهٔ اصلی، سبد خرید، تسویه حساب، حساب کاربری)

بازطراحی صفحهٔ اصلی، سبد خرید، تسویه حساب و حساب کاربریِ فروشگاه خانه سعادت (khanehsaadat.com) به‌صورت قالب فرزند وردپرس (`hello-child`).

## ساختار

- `hello-child/front-page.php` — صفحهٔ اصلی؛ هر سکشن یک partial.
- `hello-child/header.php` و `hello-child/footer.php` — هدر و فوترِ سراسری (همهٔ صفحه‌ها).
- `hello-child/page-cart.php` — صفحهٔ سبد خرید؛ سبدِ کلاسیکِ ووکامرس با قالب‌های `hello-child/woocommerce/cart/` (cart، cart-totals، cart-empty، proceed-to-checkout-button) و توابعِ `hello-child/inc/cart.php`.
- `hello-child/page-checkout.php` — تسویه حساب و «سفارش ثبت شد»؛ تسویه حسابِ کلاسیکِ ووکامرس با قالب‌های `hello-child/woocommerce/checkout/` (form-checkout، form-billing، form-shipping، review-order، payment، payment-method، form-coupon، thankyou).
- `hello-child/page-my-account.php` — حساب کاربری (پیشخوان، سفارش‌ها، مشاهدهٔ سفارش، آدرس‌ها، جزئیات حساب، ورود/عضویت)؛ حساب کاربریِ ووکامرس با قالب‌های `hello-child/woocommerce/myaccount/` (my-account، navigation، dashboard، orders، view-order، my-address) و توابعِ `hello-child/inc/account.php`. فرم‌های ورود، ویرایشِ آدرس و جزئیاتِ حساب بازنویسی نشده‌اند (فقط استایل) تا افزونه‌های ورود دست نخورند.
- `hello-child/inc/shop-setup.php` — تنظیماتِ فروشگاه که باید از functions.php لود شود: فقط ایران (فیلدِ کشور پنهان)، ایمیلِ اختیاری (تسویه حساب و جزئیات حساب)، موبایلِ اجباری (با اولویتِ ۹۹۹۹ تا افزونه‌ها برنگردانند)، و پیگیری سفارش. اگر لود نشده باشد، روی سبد/تسویه/حساب فقط به مدیر هشدار نشان داده می‌شود.
- `hello-child/inc/order-track.php` — پیگیری سفارش با شماره موبایل + شماره سفارش: پاپ‌آپ روی همهٔ صفحه‌ها (لینکِ «پیگیری سفارش» در فوتر یا هر لینک به `#ks-track`)، دکمهٔ ثابتِ پایین-چپ فقط روی صفحهٔ اصلی (موبایل: فقط آیکون)؛ درخواست به `/?wc-ajax=ks_track_order`، حداکثر ۱۰ تلاش در ۱۵ دقیقه برای هر IP. اسکریپت: `assets/js/track.js`، استایل: انتهای `site.css`.
- `hello-child/inc/fa.php` — ارقامِ فارسی و تاریخِ شمسی (`ks_jdate`) برای تاریخِ مقاله‌ها و سفارش‌ها.
- `hello-child/template-parts/home/` — سکشن‌های صفحهٔ اصلی (hero, trust, categories, sale, brands, products, saadat-pay, blog, seo-guide, videos). فقط HTML/PHP؛ هیچ CSS یا JSِ درون‌خطی ندارند.
- `hello-child/assets/css/home.css` — استایلِ همهٔ سکشن‌ها در یک فایل.
- `hello-child/assets/js/home.js` — اسکریپتِ همهٔ سکشن‌ها در یک فایل (defer، در فوتر).
- `hello-child/assets/css/site.css` و `assets/js/site.js` — استایل و اسکریپتِ هدر و فوترِ سراسری.
- `hello-child/assets/css/cart.css` و `assets/js/cart.js` — فقط روی صفحهٔ سبد خرید (از header.php لود می‌شوند). cart.css پایهٔ مشترکِ سبد و تسویه حساب هم هست.
- `hello-child/assets/css/checkout.css` و `assets/js/checkout.js` — فقط روی تسویه حساب (checkout.js ارقامِ فارسیِ موبایل/کد پستی را انگلیسی می‌کند و نوارِ چسبانِ «ثبت سفارش» موبایل را می‌گرداند).
- `hello-child/assets/css/account.css` و `assets/js/account.js` — فقط روی حساب کاربری (همراهِ cart.css که فرم‌ها و ورودی‌های مشترک را دارد).
- `hello-child/assets/fonts/montserrat-800-caps.woff2` — فونتِ نام برندها (۴.۷KB، روی خود سایت).
- `preview/home-preview.html` — پیش‌نمایش؛ از روی خودِ کدِ PHP ساخته می‌شود: `php tools/preview/build.php > preview/home-preview.html`
- `preview/cart-preview.html` و `preview/cart-empty-preview.html` — پیش‌نمایشِ سبد خرید (پر و خالی): `php tools/preview/build-cart.php [--empty] > …`
- `preview/checkout-preview.html`، `checkout-thankyou-preview.html`، `checkout-failed-preview.html` — پیش‌نمایشِ تسویه حساب: `php tools/preview/build-checkout.php [--thankyou|--failed|--errors] > …`
- `preview/account-*.html` — پیش‌نمایشِ حساب کاربری: `php tools/preview/build-account.php [--orders|--orders-empty|--view|--view-cancelled|--addresses|--edit-address|--edit-account|--login|--lost-password] > …`
- `tools/preview/stubs.php` (+ `stubs-shop.php` برای سبد/تسویه، `stubs-forms.php` برای فیلدهای آدرس) — شبیه‌سازِ مشترکِ وردپرس/ووکامرس/CodeLock؛ `build.php` (صفحهٔ اصلی)، `build-cart.php`، `build-checkout.php` و `build-account.php` از آن استفاده می‌کنند (`--hostile` برای تستِ مقاومت در برابر استایلِ قالب). `tools/preview/wc/myaccount/` نسخهٔ اصلیِ فرم‌های ووکامرس است که قالب بازنویسی نکرده (فقط برای پیش‌نمایش).
- `design/Main.dc.html` — طرح مرجع اولیه.
- `data/` — دادهٔ کاری توسعه (دسته‌ها، برندها، محصولات).

محل نصب روی سایت: `wp-content/themes/hello-child/`

## آپلود روی سایت

همهٔ این‌ها با همین مسیرها داخل `wp-content/themes/hello-child/` می‌روند:

| فایل | توضیح |
|---|---|
| `front-page.php` | صفحهٔ اصلی؛ `home.css` و `home.js` را فقط روی همین صفحه لود می‌کند و فایل‌های بلااستفادهٔ المنتور/CodeLock/ورود را روی آن لود نمی‌کند |
| `header.php` | هدرِ سراسری؛ `site.css` و `site.js` را لود می‌کند |
| `footer.php` | فوترِ سراسری |
| `page-cart.php` | صفحهٔ سبد خرید |
| `woocommerce/cart/*.php` | ۴ قالبِ سبد خرید (بازنویسیِ ووکامرس) |
| `page-checkout.php` | تسویه حساب و «سفارش ثبت شد» |
| `woocommerce/checkout/*.php` | ۸ قالبِ تسویه حساب (بازنویسیِ ووکامرس) |
| `page-my-account.php` | حساب کاربری |
| `woocommerce/myaccount/*.php` | ۶ قالبِ حساب کاربری (بازنویسیِ ووکامرس) |
| `inc/cart.php` | توابعِ کمکیِ سبد خرید و تسویه حساب (و آیکون‌ها) |
| `inc/account.php` | توابعِ کمکیِ حساب کاربری |
| `inc/fa.php` | ارقامِ فارسی و تاریخِ شمسی |
| `inc/shop-setup.php` | فقط ایران، ایمیلِ اختیاری، موبایلِ اجباری، پیگیری سفارش — **یک خط در functions.php لازم دارد (پایین)** |
| `inc/order-track.php`، `assets/js/track.js` | پیگیری سفارش (پاپ‌آپ + دکمهٔ ثابتِ صفحهٔ اصلی) |
| `assets/css/cart.css`، `assets/js/cart.js` | سبد خرید (cart.css روی تسویه حساب هم) |
| `assets/css/checkout.css`، `assets/js/checkout.js` | تسویه حساب |
| `assets/css/account.css`، `assets/js/account.js` | حساب کاربری |
| `template-parts/home/*.php` | ۱۰ سکشن |
| `assets/css/home.css` | یک فایل CSS |
| `assets/js/home.js` | یک فایل JS |
| `assets/css/site.css`، `assets/js/site.js` | هدر و فوتر |
| `assets/fonts/montserrat-800-caps.woff2` | فونت برندها |

**سبد خرید:** اگر آدرسِ برگهٔ سبد خرید `/cart/` باشد، `page-cart.php` خودکار استفاده می‌شود. در غیر این صورت (یا اگر برگه با قالبِ «تمام‌عرضِ» المنتور ذخیره شده)، در ویرایشِ برگهٔ سبد خرید از کادرِ «قالب» گزینهٔ «سبد خرید (خانه سعادت)» را انتخاب کنید.

**تسویه حساب:** همین‌طور؛ آدرسِ `/checkout/` خودکار، وگرنه در ویرایشِ برگهٔ تسویه حساب قالبِ «تسویه حساب (خانه سعادت)».

**حساب کاربری:** همین‌طور؛ آدرسِ `/my-account/` خودکار، وگرنه قالبِ «حساب کاربری (خانه سعادت)».

**functions.php:** این یک خط را انتهای `functions.php`ِ قالبِ فرزند اضافه کنید (حذفِ کشور، ایمیلِ اختیاری و موبایلِ اجباری باید هنگامِ ثبتِ سفارش هم اعمال شود، که در آن هیچ قالبی اجرا نمی‌شود؛ پیگیری سفارش هم از همین فایل لود می‌شود):

```php
require_once get_stylesheet_directory() . '/inc/shop-setup.php';
```

نسخهٔ CSS/JS از زمانِ تغییرِ فایل ساخته می‌شود، پس بعد از هر آپلود کشِ مرورگرِ کاربران خودکار تازه می‌شود. اگر افزونهٔ کش دارید (LiteSpeed، WP Rocket و…)، بعد از آپلود کشِ آن را پاک کنید.

فایل‌های قدیمیِ زیر دیگر استفاده نمی‌شوند و باید از روی سرور پاک شوند: `template-parts/home/showroom.php`، `assets/css/header.css`، `assets/js/header.js`.

`preview/` و `tools/` فقط برای توسعه‌اند و روی سایت آپلود نمی‌شوند.
