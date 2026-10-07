<?php
/**
 * سکشن «سعادت‌پی» — باکس سبزِ اقساطی (کوتاه، لبه‌تیز، کارتِ بیرون‌زده از بالا و پایین) — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/saadat-pay.php
 *
 * - پس‌زمینهٔ سکشن سبزِ روشنِ خنثی (#f4f4f4) تا زیر باکس سفید نباشد.
 * - موبایل: باکس تمام‌عرض (بدون پدینگِ کناری، بدون گوشه).
 * - کارتِ تزئینیِ CSSِ اوریجینال؛ از لبهٔ بالا و پایینِ باکس بیرون می‌زند (margin منفی + overflow visible).
 * - دکمه انیمیشنِ «زنده» (نبضِ درخشش) دارد.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_pay = array(
	'brand'   => 'سعادت‌پی',
	'title_a' => 'قسطی بخرید،',
	'title_b' => 'بدون ضامن!',
	'desc'    => 'سعادت‌پی یعنی خرید اقساطی لوازم خانگی در ۴ تا ۲۴ قسط؛ فقط با چک صیادی و با کمترین سود.',
	'cta'     => 'شرایط و ثبت‌نام',
	'url'     => home_url( '/installments/' ),
	'limit'   => 'تا ۵۰۰٬۰۰۰٬۰۰۰ تومان',
);
?>

<section class="ks-pay" id="home-sec-7" aria-label="خرید اقساطی با سعادت‌پی">
  <div class="ks-pay__in">
    <div class="ks-pay__box">
      <span class="ks-pay__bg" aria-hidden="true"></span>

      <div class="ks-pay__body">
        <span class="ks-pay__brandtag"><?php echo esc_html( $ks_pay['brand'] ); ?></span>
        <h2 class="ks-pay__title"><?php echo esc_html( $ks_pay['title_a'] ); ?> <span class="ks-pay__accent"><?php echo esc_html( $ks_pay['title_b'] ); ?></span></h2>
        <p class="ks-pay__desc"><?php echo esc_html( $ks_pay['desc'] ); ?></p>
        <a class="ks-pay__cta" href="<?php echo esc_url( $ks_pay['url'] ); ?>">
          <?php echo esc_html( $ks_pay['cta'] ); ?>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
        </a>
      </div>

      <div class="ks-pay__art" aria-hidden="true">
        <div class="ks-pay__card">
          <div class="ks-pay__card-top"><span class="ks-pay__chip"></span><span class="ks-pay__card-brand"><?php echo esc_html( $ks_pay['brand'] ); ?></span></div>
          <div class="ks-pay__card-amtwrap"><span class="ks-pay__card-cap">اعتبارِ خرید</span><span class="ks-pay__card-amt"><?php echo esc_html( $ks_pay['limit'] ); ?></span></div>
          <div class="ks-pay__card-foot">
            <div class="ks-pay__card-col"><span class="ks-pay__card-lbl">اقساط</span><span class="ks-pay__card-val">۴ تا ۲۴ ماهه</span></div>
            <div class="ks-pay__card-col"><span class="ks-pay__card-lbl">سود</span><span class="ks-pay__card-val">کمترین</span></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
