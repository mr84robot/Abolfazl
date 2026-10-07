# خانه سعادت — قالب فرزند صفحهٔ اصلی

بازطراحی صفحهٔ اصلی فروشگاه خانه سعادت (khanehsaadat.com) به‌صورت قالب فرزند وردپرس (`hello-child`).

## ساختار

- `hello-child/front-page.php` — صفحهٔ اصلی؛ هر سکشن یک partial.
- `hello-child/header.php` و `hello-child/footer.php` — هدر و فوترِ سراسری (همهٔ صفحه‌ها).
- `hello-child/template-parts/home/` — سکشن‌های صفحهٔ اصلی (hero, trust, categories, sale, brands, products, saadat-pay, blog, seo-guide, videos). فقط HTML/PHP؛ هیچ CSS یا JSِ درون‌خطی ندارند.
- `hello-child/assets/css/home.css` — استایلِ همهٔ سکشن‌ها در یک فایل.
- `hello-child/assets/js/home.js` — اسکریپتِ همهٔ سکشن‌ها در یک فایل (defer، در فوتر).
- `hello-child/assets/css/site.css` و `assets/js/site.js` — استایل و اسکریپتِ هدر و فوترِ سراسری.
- `hello-child/assets/fonts/montserrat-800-caps.woff2` — فونتِ نام برندها (۴.۷KB، روی خود سایت).
- `preview/home-preview.html` — پیش‌نمایش؛ از روی خودِ کدِ PHP ساخته می‌شود: `php tools/preview/build.php > preview/home-preview.html`
- `tools/preview/build.php` — شبیه‌سازِ وردپرس/ووکامرس/CodeLock برای ساختِ پیش‌نمایش (`--hostile` برای تستِ مقاومت در برابر استایلِ قالب).
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
| `template-parts/home/*.php` | ۱۰ سکشن |
| `assets/css/home.css` | یک فایل CSS |
| `assets/js/home.js` | یک فایل JS |
| `assets/css/site.css`، `assets/js/site.js` | هدر و فوتر |
| `assets/fonts/montserrat-800-caps.woff2` | فونت برندها |

نسخهٔ CSS/JS از زمانِ تغییرِ فایل ساخته می‌شود، پس بعد از هر آپلود کشِ مرورگرِ کاربران خودکار تازه می‌شود. اگر افزونهٔ کش دارید (LiteSpeed، WP Rocket و…)، بعد از آپلود کشِ آن را پاک کنید.

فایل‌های قدیمیِ زیر دیگر استفاده نمی‌شوند و باید از روی سرور پاک شوند: `template-parts/home/showroom.php`، `assets/css/header.css`، `assets/js/header.js`.

`preview/` و `tools/` فقط برای توسعه‌اند و روی سایت آپلود نمی‌شوند.
