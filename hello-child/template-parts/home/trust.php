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
	array( 'icon' => 'truck',       'title' => 'ارسال سریع',      'desc' => 'ارسال به سراسر ایران' ),
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

<section class="ks-trust" id="home-sec-2" aria-label="مزایای خرید از خانه سعادت" data-ks-trust>
	<ul class="ks-trust__grid">
		<?php foreach ( $ks_trust as $i => $item ) : ?>
		<li>
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

