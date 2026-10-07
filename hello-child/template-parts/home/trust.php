<?php
/**
 * سکشن «مزایا / اعتماد» صفحهٔ اصلی — خانه سعادت
 * محل نصب:  wp-content/themes/hello-child/template-parts/home/trust.php
 * فراخوانی در front-page.php:  get_template_part( 'template-parts/home/trust' );  (بینِ هیرو و کتگوری)
 *
 * طراحی: نوارِ چهارتاییِ شناور که روی لبهٔ پایینِ هیرو می‌نشیند («چسب هیرو»).
 *   - پالت فقط دو رنگ اصلی: سبز #005949 و کهربایی #F2A900 (قفلِ هماهنگیِ رنگ).
 *   - گوشهٔ ۴px، هماهنگ با سکشن کتگوری (قفلِ هماهنگیِ شکل).
 *   - ریویلِ حرکتی با IntersectionObserver (نه scroll-listener)؛ احترام به prefers-reduced-motion.
 *   - بدون em-dash، بدون scroll-cue تزئینی (مطابق گایدلاین‌های دیزاین).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$ks_trust = array(
	array( 'icon' => 'installment', 'title' => 'خرید اقساطی بدون ضامن', 'desc' => '۴ قسط بدون سود، فقط با چک صیادی' ),
	array( 'icon' => 'shield',      'title' => 'گارانتی رسمی',    'desc' => '۱۸ تا ۲۴ ماه، از خود برند' ),
	array( 'icon' => 'truck',       'title' => 'ارسال سریع',      'desc' => 'همدان همان‌روز؛ سراسر ایران' ),
	array( 'icon' => 'support',     'title' => 'مشاوره تخصصی',    'desc' => 'حضوری در دو شعبه یا تلفنی' ),
);

if ( ! function_exists( 'ks_trust_icon' ) ) {
	/** آیکون‌های خطیِ ایستا و مورد اعتماد (هماهنگ با واژگانِ آیکونیِ بقیهٔ سایت). */
	function ks_trust_icon( $name ) {
		$icons = array(
			'installment' => '<rect x="3" y="5" width="18" height="13" rx="2"/><path d="M3 9.2h18"/><path d="M6.4 14.3h3.4"/><path d="m13.6 14.8 1.7 1.7 3.1-3.3"/>',
			'shield'      => '<path d="M12 3 5 5.8v4.9c0 4.2 2.9 7.4 7 8.6 4.1-1.2 7-4.4 7-8.6V5.8z"/><path d="m9.1 11.6 2 2 3.9-4.1"/>',
			'truck'       => '<path d="M2.6 6.6h10.9v8.2H2.6z"/><path d="M13.5 9.4h3.3l2.6 2.7v2.7h-5.9z"/><circle cx="6.4" cy="17.3" r="1.7"/><circle cx="16" cy="17.3" r="1.7"/><path d="M1 9.6h2.2M.4 12.4h2"/>',
			'support'     => '<path d="M5 12.6v-1.1a7 7 0 0 1 14 0v1.1"/><rect x="3.3" y="12.4" width="3.4" height="6" rx="1.6"/><rect x="17.3" y="12.4" width="3.4" height="6" rx="1.6"/><path d="M18.8 18.4a4 4 0 0 1-3.8 2.8h-2"/><circle cx="12.6" cy="21.2" r="1"/>',
		);
		$p = isset( $icons[ $name ] ) ? $icons[ $name ] : '';
		return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p . '</svg>';
	}
}
?>
<style id="ks-trust-css">
.ks-trust{--brand:#005949;--accent:#F2A900;--ink:#1f2b28;--muted:#5b6472;--trust-font:"Yekan Bakh FaNum","Vazirmatn",system-ui,-apple-system,sans-serif;position:relative;z-index:6;background:#fff;color:var(--ink);direction:rtl;font-family:var(--trust-font);border-bottom:1px solid #ededed}
.ks-trust *{box-sizing:border-box}
.ks-trust__grid{list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(4,1fr)}
.ks-trust__grid>li{border-inline-start:1px solid #ededed}
.ks-trust__grid>li:first-child{border-inline-start:0}
.ks-trust__card{height:100%;display:flex;align-items:center;gap:13px;padding:clamp(16px,2.2vw,26px) clamp(14px,2vw,30px)}
.ks-trust__ic{flex:0 0 auto;width:46px;height:46px;display:grid;place-items:center;color:var(--brand)}
.ks-trust__ic svg{width:30px;height:30px}
.ks-trust__grid>li:nth-child(even) .ks-trust__ic{color:#c9870a}
.ks-trust__tx{min-width:0}
.ks-trust__t{margin:0;font-size:clamp(14px,1vw,15.5px);font-weight:800;color:#101828;line-height:1.5}
.ks-trust__d{margin:3px 0 0;font-size:clamp(12px,.9vw,13px);color:var(--muted);line-height:1.6}
.ks-trust--anim .ks-trust__grid>li{opacity:0;transform:translateY(16px);transition:opacity .55s cubic-bezier(.16,1,.3,1),transform .55s cubic-bezier(.16,1,.3,1);transition-delay:calc(var(--i,0) * 70ms)}
.ks-trust--anim .ks-trust__grid>li.is-in{opacity:1;transform:none}
@media (max-width:640px){.ks-trust__card{flex-direction:column;text-align:center;gap:8px;padding:16px 4px}.ks-trust__ic{width:40px;height:40px}.ks-trust__ic svg{width:26px;height:26px}.ks-trust__t{font-size:clamp(9.5px,2.95vw,12px);line-height:1.35;white-space:nowrap}.ks-trust__d{display:none}}
@media (prefers-reduced-motion:reduce){.ks-trust--anim .ks-trust__grid>li{opacity:1;transform:none;transition:none}}
</style>

<section class="ks-trust" id="home-sec-2" aria-label="مزایای خرید از خانه سعادت" data-ks-trust>
	<ul class="ks-trust__grid">
		<?php foreach ( $ks_trust as $i => $item ) : ?>
		<li style="--i:<?php echo (int) $i; ?>">
			<div class="ks-trust__card">
				<span class="ks-trust__ic"><?php echo ks_trust_icon( $item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — SVGِ ایستا و مورد اعتماد ?></span>
				<div class="ks-trust__tx">
					<h3 class="ks-trust__t"><?php echo esc_html( $item['title'] ); ?></h3>
					<p class="ks-trust__d"><?php echo esc_html( $item['desc'] ); ?></p>
				</div>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</section>

<script id="ks-trust-js">
(function(){
	var sec = document.querySelector('[data-ks-trust]');
	if ( ! sec ) { return; }
	if ( window.matchMedia('(prefers-reduced-motion: reduce)').matches || ! ('IntersectionObserver' in window) ) { return; }
	sec.classList.add('ks-trust--anim');
	var io = new IntersectionObserver(function(entries){
		entries.forEach(function(en){ if ( en.isIntersecting ) { en.target.classList.add('is-in'); io.unobserve(en.target); } });
	}, { threshold:.2 });
	sec.querySelectorAll('.ks-trust__grid > li').forEach(function(li){ io.observe(li); });
})();
</script>
