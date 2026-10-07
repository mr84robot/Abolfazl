<?php
/**
 * سکشن «برندها» — logo-cloudِ متنی (بدون تصویر لوگو) — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/brands.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/brands' );  (بعد از تخفیف‌دار)
 *
 * طراحی: گریدِ لبه‌دار (۲ ستون موبایل / ۴ ستون دسکتاپ) با پلاس‌مارکِ گوشه‌ها (الهام از کامپوننت logo-cloud).
 *   - هر سلول = نامِ انگلیسیِ برند با فونت بولد (Montserrat) و زیرش نامِ فارسی ریز؛ لینک مستقیم به صفحهٔ برند.
 *     نامِ فارسی متنِ واقعیِ لینک است (نه aria-label)، چون کاربر بیشتر «دلمونتی» را جست‌وجو می‌کند تا «Delmonti».
 *   - افکت هاور: متن از خاکستری به سبزِ برند، کمی بزرگ‌تر و بالاتر؛ پس‌زمینهٔ سلول تینتِ سبزِ ملایم.
 *   - فقط رنگ‌های اصلی، گوشهٔ ۴px، بدون JS.
 *   نکتهٔ پرفورمنس: فونت Montserrat از گوگل لود می‌شود؛ مثل Yekan Bakh بهتر است بعداً self-host شود.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_brands = array(
	array( 'name' => 'Unique',   'fa' => 'یونیک', 'url' => 'https://khanehsaadat.com/brand/unique/' ),
	array( 'name' => 'MGS',      'fa' => 'ام جی اس', 'url' => 'https://khanehsaadat.com/brand/mgs/' ),
	array( 'name' => 'HONIRA',   'fa' => 'هونیرا', 'url' => 'https://khanehsaadat.com/brand/honira/' ),
	array( 'name' => 'MIGEL',    'fa' => 'میگل', 'url' => 'https://khanehsaadat.com/brand/migel/' ),
	array( 'name' => 'SAPOR',    'fa' => 'ساپر', 'url' => 'https://khanehsaadat.com/brand/sapor/' ),
	array( 'name' => 'Delmonti', 'fa' => 'دلمونتی', 'url' => 'https://khanehsaadat.com/brand/delmonti/' ),
	array( 'name' => 'BEEM',     'fa' => 'بیم', 'url' => 'https://khanehsaadat.com/brand/beem/' ),
	array( 'name' => 'JANOME',   'fa' => 'ژانومه', 'url' => 'https://khanehsaadat.com/brand/janome/' ),
);
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
<style id="ks-brands-css">
.ks-brands{--brand:#005949;--accent:#F2A900;--ink:#1f2b28;--muted:#5b6472;--line:#e7eae9;--r:4px;background:#f4f4f4;color:var(--ink);direction:rtl;font-family:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif}
.ks-brands *{box-sizing:border-box}
.ks-brands__in{max-width:1310px;margin-inline:auto;padding:clamp(48px,7vw,84px) clamp(16px,3vw,32px)}
.ks-brands__head{text-align:center;margin-bottom:34px}
.ks-brands__eyebrow{display:inline-flex;align-items:center;gap:10px;font-size:13px;font-weight:700;color:#8a6100}
.ks-brands__eyebrow::before,.ks-brands__eyebrow::after{content:"";width:22px;height:2px;background:var(--accent)}
.ks-brands__title{margin:12px 0 6px;font-size:clamp(22px,3.2vw,30px);font-weight:800;color:#101828;line-height:1.4}
.ks-brands__sub{margin:0;font-size:14.5px;color:var(--muted)}
.ks-brands__gridwrap{position:relative}
.ks-brands__grid{display:grid;grid-template-columns:repeat(2,1fr);border-top:1px solid var(--line);border-inline-start:1px solid var(--line);border-radius:var(--r);overflow:hidden;background:#fff}
@media (min-width:768px){.ks-brands__grid{grid-template-columns:repeat(4,1fr)}}
.ks-brand{position:relative;display:flex;flex-direction:column;gap:9px;align-items:center;justify-content:center;min-height:clamp(92px,12vw,128px);padding:22px 14px;background:#fff;border-inline-end:1px solid var(--line);border-block-end:1px solid var(--line);text-decoration:none;transition:background .25s ease}
.ks-brand__wm{font-family:"Montserrat","Helvetica Neue",Arial,sans-serif;font-weight:800;font-size:clamp(18px,2.3vw,25px);letter-spacing:.06em;text-transform:uppercase;color:#adb5b2;line-height:1;transition:color .25s ease,transform .25s cubic-bezier(.16,1,.3,1)}
.ks-brand__fa{font-size:12.5px;font-weight:600;color:#8a948f;line-height:1;transition:color .25s ease}
.ks-brand:hover{background:rgb(0 89 73 / .04)}
.ks-brand:hover .ks-brand__fa{color:var(--ink)}
.ks-brand:hover .ks-brand__wm{color:var(--brand);transform:translateY(-2px) scale(1.05)}
.ks-brand:focus-visible{outline:2px solid var(--brand);outline-offset:-2px}
.ks-brands__plus{position:absolute;width:13px;height:13px;transform:translate(-50%,-50%);z-index:2;pointer-events:none;color:#c6cdca}
.ks-brands__plus::before,.ks-brands__plus::after{content:"";position:absolute;background:currentColor}
.ks-brands__plus::before{inset-inline:0;top:50%;height:1px;transform:translateY(-50%)}
.ks-brands__plus::after{inset-block:0;left:50%;width:1px;transform:translateX(-50%)}
.ks-brands__plus--d{display:none}
@media (min-width:768px){.ks-brands__plus--m{display:none}.ks-brands__plus--d{display:block}}
@media (prefers-reduced-motion:reduce){.ks-brand:hover .ks-brand__wm{transform:none}}
</style>

<section class="ks-brands" id="home-sec-5" aria-label="برندهای لوازم خانگی">
  <div class="ks-brands__in">
    <div class="ks-brands__head">
      <span class="ks-brands__eyebrow">برندها</span>
      <h2 class="ks-brands__title">برندهای معتبر لوازم خانگی</h2>
      <p class="ks-brands__sub">دلمونتی، ژانومه، یونیک و برندهای دیگر؛ همه اصل و با گارانتی رسمی.</p>
    </div>

    <div class="ks-brands__gridwrap">
      <div class="ks-brands__grid">
        <?php foreach ( $ks_brands as $b ) : ?>
        <a class="ks-brand" href="<?php echo esc_url( $b['url'] ); ?>">
          <span class="ks-brand__wm" lang="en"><?php echo esc_html( $b['name'] ); ?></span>
          <span class="ks-brand__fa"><?php echo esc_html( $b['fa'] ); ?></span>
        </a>
        <?php endforeach; ?>
      </div>

      <?php /* پلاس‌مارکِ تقاطع‌های داخلی — موبایل (۲×۴) و دسکتاپ (۴×۲) */ ?>
      <span class="ks-brands__plus ks-brands__plus--m" style="left:50%;top:25%"></span>
      <span class="ks-brands__plus ks-brands__plus--m" style="left:50%;top:50%"></span>
      <span class="ks-brands__plus ks-brands__plus--m" style="left:50%;top:75%"></span>
      <span class="ks-brands__plus ks-brands__plus--d" style="left:25%;top:50%"></span>
      <span class="ks-brands__plus ks-brands__plus--d" style="left:50%;top:50%"></span>
      <span class="ks-brands__plus ks-brands__plus--d" style="left:75%;top:50%"></span>
    </div>
  </div>
</section>
