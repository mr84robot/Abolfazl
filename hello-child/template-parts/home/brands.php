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
 *   فونت: Montserrat روی خود سایت (assets/fonts) است؛ فقط وزن ۸۰۰ و فقط حروف بزرگ A–Z (۴.۷KB).
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
      <span class="ks-brands__plus ks-brands__plus--m ks-brands__plus--m1"></span>
      <span class="ks-brands__plus ks-brands__plus--m ks-brands__plus--m2"></span>
      <span class="ks-brands__plus ks-brands__plus--m ks-brands__plus--m3"></span>
      <span class="ks-brands__plus ks-brands__plus--d ks-brands__plus--d1"></span>
      <span class="ks-brands__plus ks-brands__plus--d ks-brands__plus--d2"></span>
      <span class="ks-brands__plus ks-brands__plus--d ks-brands__plus--d3"></span>
    </div>
  </div>
</section>
