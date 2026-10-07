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
	'title_a' => 'قسطی بخر،',
	'title_b' => 'بدون دردسر!',
	'desc'    => 'با «سعادت‌پی» لوازم خانگی‌تان را از ۴ تا ۲۴ قسط با کمترین سود تهیه کنید.',
	'cta'     => 'شرایط و ثبت‌نام',
	'url'     => home_url( '/installments/' ),
	'limit'   => 'تا ۵۰۰٬۰۰۰٬۰۰۰ تومان',
);
?>
<style id="ks-pay-css">
.ks-pay{--brand:#005949;--accent:#F2A900;background:#f4f4f4;direction:rtl;font-family:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif}
.ks-pay *{box-sizing:border-box}
.ks-pay__in{max-width:1310px;margin-inline:auto;padding:clamp(34px,4.5vw,56px) clamp(16px,3vw,32px) clamp(34px,4vw,52px)}
.ks-pay__box{position:relative;border-radius:6px;display:grid;grid-template-columns:1.4fr .76fr;gap:clamp(18px,3vw,38px);align-items:center;padding:clamp(18px,2.3vw,28px) clamp(22px,2.8vw,36px)}
.ks-pay__bg{position:absolute;inset:0;z-index:0;border-radius:inherit;overflow:hidden;background:linear-gradient(200deg,#02392F 32%,#017A64 100%)}
.ks-pay__bg::after{content:"";position:absolute;inset-block-start:-34%;inset-inline-end:-6%;width:340px;height:340px;border-radius:50%;background:radial-gradient(circle,rgb(242 169 0 / .2),transparent 60%)}
.ks-pay__body{position:relative;z-index:2;color:#fff;padding-block:6px}
.ks-pay__brandtag{display:inline-flex;align-items:center;gap:9px;font-size:13px;font-weight:800;color:var(--accent);letter-spacing:.04em}
.ks-pay__brandtag::before{content:"";width:20px;height:2px;background:var(--accent)}
.ks-pay__title{margin:12px 0 10px;font-size:clamp(28px,5.4vw,50px);font-weight:900;line-height:1.1;letter-spacing:-.015em;color:#fff}
.ks-pay__title .ks-pay__accent{color:var(--accent)}
.ks-pay__desc{margin:0;max-width:34rem;font-size:clamp(14px,1.1vw,16px);line-height:1.9;color:rgb(255 255 255 / .84)}
.ks-pay__cta{display:inline-flex;align-items:center;gap:9px;margin-top:20px;min-height:50px;padding:0 26px;border-radius:8px;background:var(--accent);color:#2a1d00;font-size:15px;font-weight:800;text-decoration:none;animation:ks-pay-live 2.4s ease-in-out infinite;will-change:box-shadow,transform}
.ks-pay__cta:hover{animation:none;transform:translateY(-2px);box-shadow:0 0 60px -6px rgb(242 169 0 / .98)}
.ks-pay__cta svg{width:18px;height:18px}
@keyframes ks-pay-live{0%,100%{box-shadow:0 0 34px -12px rgb(242 169 0 / .5);transform:translateY(0)}50%{box-shadow:0 0 58px -4px rgb(242 169 0 / .95);transform:translateY(-2px)}}
.ks-pay__art{position:relative;z-index:2;align-self:center;display:flex;align-items:center;justify-content:center}
.ks-pay__card{position:relative;overflow:hidden;width:min(116%,348px);min-height:clamp(224px,24vw,250px);margin-block:calc(-1 * clamp(24px,3.2vw,42px));display:flex;flex-direction:column;justify-content:space-between;gap:14px;border-radius:16px;padding:22px;background:linear-gradient(140deg,rgb(255 255 255 / .22) 0%,rgb(255 255 255 / .07) 55%,rgb(255 255 255 / .13) 100%);border:1px solid rgb(255 255 255 / .34);box-shadow:0 40px 72px -30px rgb(0 0 0 / .78),inset 0 1px 0 rgb(255 255 255 / .4);transform:rotate(-4deg) translateX(-3%)}
.ks-pay__card::before{content:"";position:absolute;inset:0;background:linear-gradient(118deg,transparent 32%,rgb(255 255 255 / .14) 47%,transparent 61%);pointer-events:none}
.ks-pay__card-top{display:flex;align-items:center;justify-content:space-between}
.ks-pay__chip{width:44px;height:33px;border-radius:7px;background:linear-gradient(135deg,#F2A900,#cf8a04);position:relative}
.ks-pay__chip::after{content:"";position:absolute;inset:7px 9px;border:1px solid rgb(0 0 0 / .22);border-radius:3px}
.ks-pay__card-brand{font-size:clamp(21px,2.5vw,27px);font-weight:800;color:var(--accent);letter-spacing:.01em;line-height:1}
.ks-pay__card-amtwrap{display:flex;flex-direction:column;gap:3px}
.ks-pay__card-cap{font-size:11px;color:rgb(255 255 255 / .62)}
.ks-pay__card-amt{font-size:clamp(21px,2.7vw,29px);font-weight:800;color:#fff;white-space:nowrap;line-height:1.08}
.ks-pay__card-foot{display:flex;align-items:flex-end;justify-content:space-between;gap:12px}
.ks-pay__card-col{display:flex;flex-direction:column;gap:2px}
.ks-pay__card-lbl{font-size:10px;color:rgb(255 255 255 / .55)}
.ks-pay__card-val{font-size:12.5px;font-weight:700;color:#fff}
@media (max-width:820px){
  .ks-pay__in{padding:clamp(28px,5vw,40px) 0 0}
  .ks-pay__box{grid-template-columns:1fr;gap:12px;border-radius:0;padding:clamp(26px,6vw,34px) clamp(18px,5vw,26px) clamp(30px,7vw,40px)}
  .ks-pay__art{order:-1}
  .ks-pay__card{transform:rotate(-3deg);width:min(90%,320px);margin-block:4px -8px}
}
@media (prefers-reduced-motion:reduce){.ks-pay__cta{animation:none}}
</style>

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
