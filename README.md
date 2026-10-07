# خانه سعادت — قالب فرزند صفحهٔ اصلی

بازطراحی صفحهٔ اصلی فروشگاه خانه سعادت (khanehsaadat.com) به‌صورت قالب فرزند وردپرس (`hello-child`).

## ساختار

- `hello-child/front-page.php` — صفحهٔ اصلی؛ هر سکشن یک partial.
- `hello-child/template-parts/home/` — سکشن‌های صفحهٔ اصلی (hero, trust, categories, sale, brands, products, saadat-pay, showroom, blog, seo-guide, videos). فقط HTML/PHP؛ هیچ CSS یا JSِ درون‌خطی ندارند.
- `hello-child/assets/css/home.css` — استایلِ همهٔ سکشن‌ها در یک فایل.
- `hello-child/assets/js/home.js` — اسکریپتِ همهٔ سکشن‌ها در یک فایل (defer، در فوتر).
- `hello-child/assets/fonts/montserrat-800-caps.woff2` — فونتِ نام برندها (۴.۷KB، روی خود سایت).
- `preview/home-preview.html` — پیش‌نمایش standalone مرورگری همهٔ سکشن‌ها.
- `design/Main.dc.html` — طرح مرجع اولیه.
- `data/` — دادهٔ کاری توسعه (دسته‌ها، برندها، محصولات).

محل نصب روی سایت: `wp-content/themes/hello-child/`

## آپلود روی سایت

همهٔ این‌ها با همین مسیرها داخل `wp-content/themes/hello-child/` می‌روند:

| فایل | توضیح |
|---|---|
| `front-page.php` | صفحهٔ اصلی؛ `home.css` و `home.js` را فقط روی همین صفحه لود می‌کند |
| `template-parts/home/*.php` | ۱۱ سکشن |
| `assets/css/home.css` | یک فایل CSS |
| `assets/js/home.js` | یک فایل JS |
| `assets/fonts/montserrat-800-caps.woff2` | فونت برندها |

نسخهٔ CSS/JS از زمانِ تغییرِ فایل ساخته می‌شود، پس بعد از هر آپلود کشِ مرورگرِ کاربران خودکار تازه می‌شود. اگر افزونهٔ کش دارید (LiteSpeed، WP Rocket و…)، بعد از آپلود کشِ آن را پاک کنید.

`preview/home-preview.html` فقط پیش‌نمایشِ توسعه است و روی سایت آپلود نمی‌شود.
