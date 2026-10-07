<?php
/**
 * سکشن «باکس راهنمای خرید / محتوای سئو» — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/seo-guide.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/seo-guide' );
 *
 * متن عیناً همان محتوای باکسِ صفحهٔ اصلیِ سایتِ زنده است (بدون تغییر در جمله‌ها).
 *
 * ⚡ بدون جاوااسکریپت: «مشاهده بیشتر» با چک‌باکسِ مخفی + سلکتور :checked کار می‌کند.
 *    پس بدون JS هم باز می‌شود و کرالر کلِ متن را در HTML می‌بیند (مهم برای سئو) —
 *    متن با max-height بریده می‌شود، نه با display:none.
 *
 * یادداشت‌ها نسبت به نسخهٔ قبلیِ همین باکس روی سایت:
 *   ۱) کلاس‌ها به قاعدهٔ بقیهٔ سکشن‌ها (پیشوند ks-) برگردانده شد و !important حذف شد؛
 *      استایل اسکوپ‌شده و صریح است، پس به !important نیازی نیست.
 *   ۲) لینک‌های داخلی با home_url()/ووکامرس ساخته می‌شوند تا دامنه هاردکد نشود.
 *   ۳) max-height حالتِ باز ریسپانسیو شد؛ مقدار ثابتِ قبلی روی موبایل انتهای متن را می‌بُرید.
 *   ۴) از چک‌باکس aria-hidden و tabindex دستی برداشته شد؛ قبلاً فوکوس‌پذیر بود ولی
 *      برای اسکرین‌ریدر پنهان، یعنی کنترلی بی‌نام. حالا چک‌باکسِ بومی با <label> نام می‌گیرد.
 *   ۵) id تیترها عیناً نگه داشته شد تا لینک‌های #anchor و فهرستِ مطالبِ موجود نشکند.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_seo = array(
	'shop_url' => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ),
	'inst_url' => home_url( '/installments/' ),
	'hmd_url'  => home_url( '/hamedan/' ),
);
?>
<style id="ks-seo-css">
.ks-seo{--r:4px;--brand:#005949;--gold:#f1a900;--gold-d:#cf9100;--ink:#10322a;--body:#46554f;--cream:#faf7f0;--line:#ece7dc;--soft:#e9f2ef;background:#f4f4f4;direction:rtl;text-align:right;font-family:"Yekan Bakh FaNum","Yekan Bakh","Vazirmatn",system-ui,-apple-system,Tahoma,sans-serif}
.ks-seo *{box-sizing:border-box;margin:0;padding:0}
.ks-seo__in{max-width:1310px;margin-inline:auto;padding:clamp(56px,8vw,96px) clamp(16px,3vw,32px) clamp(64px,9vw,96px)}
.ks-seo__wrap{background:var(--cream);border:1px solid var(--line);border-radius:var(--r);padding:clamp(24px,3.4vw,44px)}

.ks-seo__eyebrow{display:inline-flex;align-items:center;gap:8px;color:var(--gold-d);font-weight:800;font-size:14px;margin-bottom:10px}
.ks-seo__eyebrow::before{content:"";width:22px;height:2px;background:var(--gold)}
.ks-seo__title{color:var(--ink);font-weight:800;line-height:1.4;font-size:clamp(20px,2.6vw,26px);letter-spacing:-.01em;margin:0 0 6px}

/* چک‌باکسِ مخفیِ مکانیزم «مشاهده بیشتر» — بدون جاوااسکریپت */
.ks-seo__cb{position:absolute;width:1px;height:1px;opacity:0;margin:-1px;overflow:hidden;clip:rect(0 0 0 0);clip-path:inset(50%)}

.ks-seo__content{position:relative;overflow:hidden;max-height:300px;margin-top:16px;transition:max-height .6s cubic-bezier(.22,1,.36,1)}
/* حالتِ باز: مقدار سخاوتمند و ریسپانسیو تا انتهای متن بریده نشود */
.ks-seo__cb:checked ~ .ks-seo__content{max-height:3200px}
@media (max-width:900px){.ks-seo__cb:checked ~ .ks-seo__content{max-height:7000px}}
@media (max-width:480px){.ks-seo__cb:checked ~ .ks-seo__content{max-height:9600px}}

.ks-seo__content h3{color:var(--ink);font-weight:700;line-height:1.6;font-size:clamp(16px,1.8vw,18px);margin:22px 0 8px}
.ks-seo__content p{color:var(--body);line-height:2.05;font-size:16px;margin:0 0 12px}
.ks-seo__content ul{list-style:disc;padding-inline-start:1.4em;margin:6px 0 14px}
.ks-seo__content li{color:var(--body);line-height:1.95;font-size:16px;margin-bottom:6px}
.ks-seo__content a{color:var(--brand);font-weight:700;text-decoration:underline;text-underline-offset:3px}
.ks-seo__content a:hover{color:var(--gold-d)}

.ks-seo__fade{position:absolute;inset-inline:0;bottom:0;height:120px;pointer-events:none;background:linear-gradient(to top,var(--cream),rgb(250 247 240 / 0));transition:opacity .3s ease}
.ks-seo__cb:checked ~ .ks-seo__content .ks-seo__fade{opacity:0}

.ks-seo__btn{display:inline-flex;align-items:center;gap:8px;margin-top:16px;cursor:pointer;background:transparent;color:var(--brand);font-weight:800;font-size:15px;padding:11px 22px;border:2px solid rgb(0 89 73 / .25);border-radius:var(--r);transition:background-color .2s ease,border-color .2s ease;-webkit-user-select:none;user-select:none}
.ks-seo__btn:hover{background:var(--soft);border-color:var(--brand)}
.ks-seo__btn svg{width:18px;height:18px;fill:none;stroke:currentColor;stroke-width:2.4;stroke-linecap:round;stroke-linejoin:round;transition:transform .3s ease}
.ks-seo__cb:checked ~ .ks-seo__btn svg{transform:rotate(180deg)}
.ks-seo__less{display:none}
.ks-seo__cb:checked ~ .ks-seo__btn .ks-seo__more{display:none}
.ks-seo__cb:checked ~ .ks-seo__btn .ks-seo__less{display:inline}
.ks-seo__cb:focus-visible ~ .ks-seo__btn{outline:3px solid var(--gold);outline-offset:3px}

@media (prefers-reduced-motion:reduce){.ks-seo__content,.ks-seo__fade,.ks-seo__btn,.ks-seo__btn svg{transition:none}}
</style>

<section class="ks-seo" id="home-sec-10" aria-label="راهنمای خرید لوازم خانگی">
  <div class="ks-seo__in">
    <div class="ks-seo__wrap">
      <span class="ks-seo__eyebrow">راهنمای خرید</span>
      <h2 class="ks-seo__title" id="%d8%ae%d8%b1%db%8c%d8%af-%d9%84%d9%88%d8%a7%d8%b2%d9%85-%d8%ae%d8%a7%d9%86%da%af%db%8c%d8%9b-%d8%b1%d8%a7%d9%87%d9%86%d9%85%d8%a7%db%8c-%d8%a7%d9%86%d8%aa%d8%ae%d8%a7%d8%a8%d8%8c-%d9%82%db%8c%d9%85">خرید لوازم خانگی؛ راهنمای انتخاب، قیمت و خرید مطمئن</h2>

      <input type="checkbox" id="ks-seo-toggle" class="ks-seo__cb">

      <div class="ks-seo__content">
        <p>خرید لوازم خانگی یکی از تصمیم‌های مهم هر خانه است؛ از یخچال فریزر و ماشین لباسشویی گرفته تا لوازم برقی کوچک آشپزخانه، کیفیت و قیمت این کالاها مستقیماً روی راحتی زندگی روزمره اثر می‌گذارد. فروشگاه اینترنتی خانه سعادت با تنوع محصولات اصل، نمایش شفاف قیمت و امکان خرید آنلاین و اقساطی، مسیر خرید لوازم خانگی را ساده و مطمئن کرده است.</p>

        <h3 id="%d9%84%d9%88%d8%a7%d8%b2%d9%85-%d8%ae%d8%a7%d9%86%da%af%db%8c-%d8%b1%d8%a7-%d8%a2%d9%86%d9%84%d8%a7%db%8c%d9%86-%d8%a8%d8%ae%d8%b1%db%8c%d9%85-%db%8c%d8%a7-%d8%ad%d8%b6%d9%88%d8%b1%db%8c%d8%9f">لوازم خانگی را آنلاین بخریم یا حضوری؟</h3>
        <p>خرید اینترنتی لوازم خانگی این امکان را می‌دهد که ده‌ها مدل را کنار هم مقایسه کنید، قیمت‌ها را ببینید و در هر ساعت از شبانه‌روز سفارش دهید. در خانه سعادت می‌توانید محصول موردنظرتان را آنلاین انتخاب و ثبت کنید و بدون رفت‌وآمد، خریدتان را کامل کنید. خرید آنلاین لوازم خانگی هم در زمان صرفه‌جویی می‌کند و هم انتخاب را دقیق‌تر و آگاهانه‌تر می‌کند. برای شروع می‌توانید وارد <a href="<?php echo esc_url( $ks_seo['shop_url'] ); ?>">محصولات خانه سعادت</a> شوید و محصولات را ببینید.</p>

        <h3 id="%d9%82%db%8c%d9%85%d8%aa-%d9%84%d9%88%d8%a7%d8%b2%d9%85-%d8%ae%d8%a7%d9%86%da%af%db%8c-%d8%a8%d9%87-%da%86%d9%87-%da%86%db%8c%d8%b2%d9%87%d8%a7%db%8c%db%8c-%d8%a8%d8%b3%d8%aa%da%af%db%8c-%d8%af%d8%a7">قیمت لوازم خانگی به چه چیزهایی بستگی دارد؟</h3>
        <p>قیمت لوازم خانگی بیش از هر چیز به برند، ظرفیت، امکانات و اصل بودن کالا بستگی دارد؛ نوسان بازار و نرخ ارز هم روی قیمت روز لوازم خانگی اثر می‌گذارد. به همین دلیل بهتر است پیش از خرید، چند مدل هم‌رده را با هم مقایسه کنید و قیمت روز را ببینید. اگر به‌دنبال لوازم خانگی ارزان یا با قیمت مناسب هستید، مقایسه‌ی مدل‌ها و توجه به تناسب امکانات با نیازتان، کمک می‌کند بهترین انتخاب را با بودجه‌تان داشته باشید. خانه سعادت قیمت‌ها را شفاف نمایش می‌دهد تا با آگاهی کامل تصمیم بگیرید.</p>

        <h3 id="%d9%84%d9%88%d8%a7%d8%b2%d9%85-%d8%ae%d8%a7%d9%86%da%af%db%8c-%d8%a7%d8%b5%d9%84-%d9%88-%d8%a8%d8%a7%da%a9%db%8c%d9%81%db%8c%d8%aa-%d8%b1%d8%a7-%da%86%d8%b7%d9%88%d8%b1-%d8%aa%d8%b4%d8%ae%db%8c%d8%b5">لوازم خانگی اصل و باکیفیت را چطور تشخیص بدهیم؟</h3>
        <p>لوازم خانگی اصل معمولاً گارانتی رسمی، برچسب اصالت کالا و خدمات پس از فروش مشخص دارد. هنگام خرید لوازم خانگی با کیفیت، به برند معتبر، ضمانت اصالت و شرایط گارانتی توجه کنید. گارانتی یعنی تعهد رسمی برند به تعمیر یا تعویض کالا در یک بازه‌ی زمانی مشخص؛ همین موضوع خیال شما را از بابت خرید راحت می‌کند. خرید از یک فروشگاه معتبر مانند خانه سعادت، ریسک خرید کالای غیراصل را به‌شدت کم می‌کند.</p>

        <h3 id="%da%a9%d8%af%d8%a7%d9%85-%d9%84%d9%88%d8%a7%d8%b2%d9%85-%d8%ae%d8%a7%d9%86%da%af%db%8c-%d8%a8%d8%b1%d8%a7%db%8c-%d8%ae%d8%a7%d9%86%d9%87-%db%8c%d8%a7-%d8%ac%d9%87%db%8c%d8%b2%db%8c%d9%87-%d9%85%d9%86">کدام لوازم خانگی برای خانه یا جهیزیه مناسب است؟</h3>
        <p>برای تجهیز خانه یا تهیه‌ی جهیزیه، معمولاً به ترکیبی از لوازم بزرگ و لوازم برقی آشپزخانه نیاز دارید. بسته به سلیقه و بودجه می‌توانید بین مدل‌های ایرانی و خارجی انتخاب کنید. مهم‌ترین گروه‌های پرتقاضا عبارت‌اند از:</p>
        <ul>
          <li>یخچال فریزر، ماشین لباسشویی و ماشین ظرفشویی</li>
          <li>اجاق گاز و لوازم پخت‌وپز</li>
          <li>لوازم برقی آشپزخانه: جاروبرقی، سرخ‌کن، غذاساز، چای‌ساز، اسپرسوساز و اتو</li>
        </ul>
        <p>پیشنهاد می‌شود ابتدا فهرست نیازهای خانه را مشخص کنید و بعد بر اساس اولویت و بودجه، خرید لوازم خانگی جدید را مرحله‌به‌مرحله انجام دهید.</p>

        <h3 id="%d8%a2%db%8c%d8%a7-%d8%a7%d9%85%da%a9%d8%a7%d9%86-%d8%ae%d8%b1%db%8c%d8%af-%d8%a7%d9%82%d8%b3%d8%a7%d8%b7%db%8c-%d9%84%d9%88%d8%a7%d8%b2%d9%85-%d8%ae%d8%a7%d9%86%da%af%db%8c-%d9%88%d8%ac%d9%88%d8%af">آیا امکان خرید اقساطی لوازم خانگی وجود دارد؟</h3>
        <p>بله. خانه سعادت امکان خرید اقساطی لوازم خانگی بدون ضامن را فراهم کرده تا بتوانید یک خرید بزرگ را بدون فشار مالی انجام دهید و هزینه را مرحله‌ای بپردازید. جزئیات کامل، میزان پیش‌پرداخت و مدارک لازم در صفحه‌ی <a href="<?php echo esc_url( $ks_seo['inst_url'] ); ?>">خرید اقساطی</a> توضیح داده شده است.</p>

        <h3 id="%d8%a8%d9%87%d8%aa%d8%b1%db%8c%d9%86-%d9%81%d8%b1%d9%88%d8%b4%da%af%d8%a7%d9%87-%d9%84%d9%88%d8%a7%d8%b2%d9%85-%d8%ae%d8%a7%d9%86%da%af%db%8c-%da%86%d9%87-%d9%88%db%8c%da%98%da%af%db%8c%d9%87">بهترین فروشگاه لوازم خانگی چه ویژگی‌هایی دارد؟</h3>
        <p>بهترین فروشگاه لوازم خانگی معمولاً چند ویژگی مشترک دارد: تنوع کافی محصولات، نمایش شفاف قیمت، ضمانت اصالت کالا، پرداخت امن و خدمات پس از فروش قابل‌اتکا. یک سایت خرید لوازم خانگی معتبر باید امکان مقایسه‌ی محصولات و مشاوره‌ی پیش از خرید را هم فراهم کند تا با خیال راحت انتخاب کنید. وقتی این موارد کنار هم باشند، خرید اینترنتی لوازم خانگی به‌اندازه‌ی خرید حضوری مطمئن می‌شود.</p>

        <h3 id="%da%86%d8%b1%d8%a7-%d8%a7%d8%b2-%d9%81%d8%b1%d9%88%d8%b4%da%af%d8%a7%d9%87-%d8%a7%db%8c%d9%86%d8%aa%d8%b1%d9%86%d8%aa%db%8c-%d8%ae%d8%a7%d9%86%d9%87-%d8%b3%d8%b9%d8%a7%d8%af%d8%aa-%d8%ae%d8%b1%db%8c">چرا از فروشگاه اینترنتی خانه سعادت خرید کنیم؟</h3>
        <p>خانه سعادت یک فروشگاه اینترنتی لوازم خانگی با تنوع محصولات اصل، نمایش شفاف قیمت، امکان خرید آنلاین و اقساطی و خدمات پس از فروش است. تجربه‌ی چندین‌ساله در بازار لوازم خانگی باعث شده انتخاب مطمئن‌تر و مشاوره‌ی بهتری برای مشتریان فراهم شود. اگر ساکن همدان هستید، می‌توانید امکانات و شعب محلی را در صفحه‌ی <a href="<?php echo esc_url( $ks_seo['hmd_url'] ); ?>">لوازم خانگی همدان</a> ببینید و در صورت تمایل خرید حضوری داشته باشید. برای دیدن محصولات و قیمت‌ها همین حالا وارد فروشگاه شوید و خرید مطمئن لوازم خانگی را تجربه کنید.</p>

        <span class="ks-seo__fade" aria-hidden="true"></span>
      </div>

      <label for="ks-seo-toggle" class="ks-seo__btn">
        <span class="ks-seo__more">مشاهده بیشتر</span>
        <span class="ks-seo__less">بستن</span>
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"></path></svg>
      </label>
    </div>
  </div>
</section>
