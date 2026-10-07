<?php
/**
 * شبیه‌سازِ مشترکِ وردپرس/ووکامرس/CodeLock برای ساختِ پیش‌نمایش‌ها (build.php و build-cart.php).
 * داده: data/prods.json و data/cats.json.
 */

$ROOT    = dirname( __DIR__, 2 );
$THEME   = $ROOT . '/hello-child';
$HOSTILE = in_array( '--hostile', $argv, true );
define( 'ABSPATH', '/' );

/* ---------------- داده ---------------- */
$U     = 'https://khanehsaadat.com';
$CATS  = json_decode( file_get_contents( $ROOT . '/data/cats.json' ), true );
$PRODS = array();
foreach ( json_decode( file_get_contents( $ROOT . '/data/prods.json' ), true ) as $p ) {
	$PRODS[ $p['id'] ] = array(
		'name'  => $p['name'], 'link' => $p['permalink'], 'price' => (int) $p['prices']['price'], 'regular' => (int) $p['prices']['regular_price'],
		'img'   => $p['images'][0]['src'] ?? '', 'type' => $p['type'], 'stock' => (bool) $p['is_in_stock'],
		'cats'  => array_map( function ( $c ) { return $c['slug']; }, $p['categories'] ), 'date' => $p['id'],
	);
}
// تب‌هایی که در داده محصول ندارند: نمونهٔ پیش‌نمایش با عکسِ همان دسته
$STAND = array( 'espersosaz' => array( 'اسپرسوساز', 'Artboard-1-copy-4.webp' ), 'sorkhkon' => array( 'سرخ‌کن', 'Artboard-1-copy-5.webp' ),
                'flask' => array( 'فلاسک', 'Artboard-1.webp' ), 'otobokhar' => array( 'اتو بخار', 'Artboard-1-copy-6.webp' ) );
$nid = 90000;
foreach ( $STAND as $slug => $x ) {
	for ( $i = 1; $i <= 6; $i++ ) {
		$price = ( 3 + $i * 1.35 ) * 1000000;
		$PRODS[ ++$nid ] = array( 'name' => $x[0] . ' نمونهٔ پیش‌نمایش ' . $i, 'link' => $U . '/product/sample-' . $slug . '-' . $i . '/', 'price' => (int) round( $price, -4 ),
			'regular' => (int) round( $price, -4 ), 'img' => $U . '/wp-content/uploads/2026/05/' . $x[1], 'type' => 'simple', 'stock' => true, 'cats' => array( $slug ), 'date' => $nid );
	}
}
// تخفیف: چند محصول با قیمتِ قبلیِ بالاتر (فقط برای دیدنِ کاروسلِ تخفیف‌دار)
$k = 0;
foreach ( $PRODS as $id => &$p ) { if ( $k++ % 2 === 0 ) { $p['regular'] = (int) round( $p['price'] * 1.18, -4 ); } }
unset( $p );

$POSTS = array(); // مقاله‌های وبلاگ
foreach ( array(
	array( 'what-size-air-fryer-should-i-buy', '2026-09-21', 'سرخ کن بدون روغن چند لیتری بخرم؟ راهنمای انتخاب ظرفیت', 'سوال «چند لیتری بخرم» ساده به نظر می رسد اما رایج ترین نقطه اشتباه در خرید سرخ کن است و خیلی‌ها بعد از خرید متوجهش می‌شوند.', '2026/09/Comparing-Air-Fryer-Capacities-What-Size-Should-You-Buy-768x538.webp' ),
	array( 'air-fryer-buying-guide', '2026-09-19', 'راهنمای خرید سرخ کن بدون روغن؛ توان، المنت و انتخاب مدل', 'راستش بیشتر کسانی که از خرید سرخ کن پشیمان می شوند، دستگاه بی کیفیت نخریده اند؛ دستگاهی خریده‌اند که به کارشان نمی‌آمد.', '2026/09/Air-Fryer-Buying-Guide-768x538.webp' ),
	array( 'delmonti-dl120-food-processor-review', '2026-09-15', 'آموزش کار، نقد و بررسی غذاساز دلمونتی ۲۰ کاره مدل DL120', 'اگر دنبال دستگاهی هستید که جای چند وسیله آشپزخانه را یک جا بگیرد، بررسی غذاساز دلمونتی DL120 نقطه شروع خوبی است.', '2026/09/ghazasaz-delmonti-dl120-768x538.webp' ),
	array( 'comparison-of-delmonti-food-processors', '2026-08-30', 'مقایسه غذاساز دلمونتی DL120 با بوش و فیلیپس از زبان خریداران', 'بیشتر مقایسه های غذاساز، جدولی از اعداد است؛ این‌جا تجربهٔ خریداران را کنار هم گذاشته‌ایم.', '2026/08/مقایسه-غذاساز-دلمونتی-DL120-با-بوش-و-فیلیپس-از-زبان-خریداران-768x538.webp' ),
	array( 'food-processor-buying-guide', '2026-08-22', 'راهنمای جامع خرید بهترین غذاساز خانگی و جهیزیه عروس', 'بیشتر کسانی که غذاساز می خرند، آن را به خاطر یک کار مشخص می‌خرند و بقیهٔ قابلیت‌ها را نمی‌شناسند.', '2026/08/راهنمای-جامع-خرید-بهترین-غذاساز-768x538.webp' ),
	array( 'how-to-wash-a-water-bottle', '2026-08-17', 'روش شستن قمقمه و از بین بردن بوی آن | خانه سعادت', 'هر روز آبکشش می کنید، هفته ای یک بار هم با جوش شیرین؛ ولی بو باز هم می‌ماند. علتش این است.', '2026/08/روش-شستن-قمقمه-و-از-بین-بردن-بوی-آن-768x538.webp' ),
) as $i => $b ) {
	$POSTS[ 500 + $i ] = array( 'type' => 'post', 'slug' => $b[0], 'date' => $b[1], 'title' => $b[2], 'excerpt' => $b[3], 'thumb' => $U . '/wp-content/uploads/' . $b[4] );
}
$VIDEOS = array( // محصولاتی که ویدیوی CodeLock دارند
	13649 => array( 'cover' => 'img/vid-dl880.webp', 'title' => 'بررسی کامل زودپز و پلوپز دلمونتی DL۸۸۰', 'text' => 'اگر دنبال یک زودپز و پلوپز همه‌کاره، با کیفیت و مناسب خانواده هستی، توی این ویدیو زودپز و پلوپز را کامل بررسی کردیم.', 'duration' => '6:45' ),
	13586 => array( 'cover' => 'img/vid-dl855.webp', 'title' => 'معرفی سرخ کن (هواپز) دلمونتی مدل DL۸۵۵', 'text' => 'در این ویدیو، با هم به بررسی جامع سرخ‌کن رژیمی دلمونتی مدل DL۸۵۵ می‌پردازیم؛ دستگاهی که با ظرفیت بالا…', 'duration' => '' ),
	13590 => array( 'cover' => 'img/vid-dl145.webp', 'title' => 'معرفی و تست خردکن دلمونتی مدل DL۱۴۵', 'text' => 'تو این ویدیو، خردکن دو کاسه معروف دلمونتی (مدل DL۱۴۵) رو از همه لحاظ تست کردم! سرعت و…', 'duration' => '3:20' ),
);

/* ---------------- هوک‌ها و صف‌ها ---------------- */
$HOOKS = array(); $STYLES = array(); $SCRIPTS = array();
function add_action( $h, $cb, $prio = 10 ) { $GLOBALS['HOOKS'][ $h ][ $prio ][] = $cb; }
function do_action( $h, ...$a ) { if ( empty( $GLOBALS['HOOKS'][ $h ] ) ) { return; } ksort( $GLOBALS['HOOKS'][ $h ] ); foreach ( $GLOBALS['HOOKS'][ $h ] as $cbs ) { foreach ( $cbs as $cb ) { $cb( ...$a ); } } }
function remove_action( $h, $cb, $prio = 10 ) { if ( empty( $GLOBALS['HOOKS'][ $h ][ $prio ] ) ) { return false; } $GLOBALS['HOOKS'][ $h ][ $prio ] = array_values( array_filter( $GLOBALS['HOOKS'][ $h ][ $prio ], function ( $x ) use ( $cb ) { return $x !== $cb; } ) ); return true; }
function has_action( $h, $cb = false ) { foreach ( (array) ( $GLOBALS['HOOKS'][ $h ] ?? array() ) as $cbs ) { if ( false === $cb ? $cbs : in_array( $cb, $cbs, true ) ) { return true; } } return false; }
function apply_filters( $h, $v, ...$a ) { return $v; }
function wp_enqueue_style( $h, $src, $deps = array(), $ver = '' ) { $GLOBALS['STYLES'][ $h ] = $src; }
function wp_enqueue_script( $h, $src, $deps = array(), $ver = '', $args = array() ) { $GLOBALS['SCRIPTS'][ $h ] = $src; }
function wp_dequeue_style( $h ) { unset( $GLOBALS['STYLES'][ $h ] ); }
function wp_dequeue_script( $h ) { unset( $GLOBALS['SCRIPTS'][ $h ] ); }
function wp_head() {
	echo '<link rel="stylesheet" href="preview.css">' . "\n";
	if ( $GLOBALS['HOSTILE'] ) { echo '<link rel="stylesheet" href="hostile-theme.css">' . "\n"; } // استایلِ قالب قبل از ماست، مثلِ سایت
	do_action( 'wp_enqueue_scripts' );
	foreach ( $GLOBALS['STYLES'] as $h => $src ) { echo '<link rel="stylesheet" id="' . $h . '-css" href="' . $src . '">' . "\n"; }
}
function wp_footer() { foreach ( $GLOBALS['SCRIPTS'] as $h => $src ) { echo '<script defer id="' . $h . '-js" src="' . $src . '"></script>' . "\n"; } }
function wp_body_open() { do_action( 'wp_body_open' ); }
function get_header() { require $GLOBALS['THEME'] . '/header.php'; }
function get_footer() { require $GLOBALS['THEME'] . '/footer.php'; }
function get_template_part( $slug ) { require $GLOBALS['THEME'] . '/' . $slug . '.php'; }

/* ---------------- وردپرس ---------------- */
function home_url( $p = '/' ) { return $GLOBALS['U'] . $p; }
function get_stylesheet_directory() { return $GLOBALS['THEME']; }
function get_stylesheet_directory_uri() { return '../hello-child'; }
function rest_url( $p ) { return $GLOBALS['U'] . '/wp-json/' . $p; }
function language_attributes() { echo 'lang="fa-IR" dir="rtl"'; }
function bloginfo( $k ) { echo 'charset' === $k ? 'UTF-8' : 'خانه سعادت'; }
function get_bloginfo( $k ) { return 'خانه سعادت'; }
$BODY = 'home page-template-default page';
function body_class() { echo 'class="' . $GLOBALS['BODY'] . '"'; }
function esc_url( $u ) { return htmlspecialchars( (string) $u, ENT_QUOTES ); }
function esc_url_raw( $u ) { return (string) $u; }
function esc_attr( $u ) { return htmlspecialchars( (string) $u, ENT_QUOTES ); }
function esc_html( $u ) { return htmlspecialchars( (string) $u, ENT_QUOTES ); }
function number_format_i18n( $n ) { return strtr( (string) $n, array( '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹' ) ); }
$OPTS = array(); // get_option: فقط مقدارهایی که یک پیش‌نمایش لازم دارد
function get_option( $k, $d = 0 ) { return $GLOBALS['OPTS'][ $k ] ?? $d; }
function is_wp_error( $x ) { return false; }
function is_front_page() { return ! empty( $GLOBALS['IS_FRONT'] ); }
function taxonomy_exists( $t ) { return true; }
function wp_list_pluck( $list, $f ) { return array_map( function ( $o ) use ( $f ) { return $o->$f; }, $list ); }
function wp_trim_words( $t, $n, $m ) { $w = preg_split( '/\s+/u', trim( $t ) ); return count( $w ) <= $n ? $t : implode( ' ', array_slice( $w, 0, $n ) ) . $m; }
$SC = array(); // شورت‌کدهای ثبت‌شده در پیش‌نمایش (مثلاً woocommerce_cart در build-cart.php)
function do_shortcode( $sc ) {
	if ( preg_match( '/^\[(\w+)\]$/', trim( $sc ), $m ) && isset( $GLOBALS['SC'][ $m[1] ] ) ) { return ( $GLOBALS['SC'][ $m[1] ] )(); }
	if ( false !== strpos( $sc, 'search' ) ) { return '<form class="pv-search" role="search"><input type="search" placeholder="جستجوی محصولات، برندها…" aria-label="جستجو"></form>'; }
	if ( false !== strpos( $sc, 'mega' ) ) { return '<button class="pv-mega" type="button">دسته‌بندی محصولات</button>'; }
	return '';
}
function update_post_thumbnail_cache( $q ) {}
function wp_reset_postdata() { $GLOBALS['CUR'] = null; }

/* حلقهٔ پست‌ها */
$CUR = null;
class WP_Query {
	public $posts = array(); private $i = -1;
	function __construct( $a ) {
		$ids = array();
		if ( 'post' === $a['post_type'] ) { $ids = array_keys( $GLOBALS['POSTS'] ); }
		else {
			$ids = array_keys( $GLOBALS['PRODS'] );
			if ( ! empty( $a['post__in'] ) ) { $ids = array_values( array_intersect( $ids, $a['post__in'] ) ); }
			if ( ! empty( $a['tax_query'] ) ) {
				$slug = term_slug( $a['tax_query'][0]['terms'] );
				$ids  = array_values( array_filter( $ids, function ( $id ) use ( $slug ) { return in_array( $slug, $GLOBALS['PRODS'][ $id ]['cats'], true ); } ) );
			}
			foreach ( (array) ( $a['meta_query'] ?? array() ) as $mq ) {
				if ( '_codelock_video_url' === ( $mq['key'] ?? '' ) ) { $ids = array_values( array_intersect( $ids, array_keys( $GLOBALS['VIDEOS'] ) ) ); }
				if ( '_stock_status' === ( $mq['key'] ?? '' ) ) { $ids = array_values( array_filter( $ids, function ( $id ) { return $GLOBALS['PRODS'][ $id ]['stock']; } ) ); }
			}
		}
		$ids = array_slice( $ids, 0, $a['posts_per_page'] ?? 10 );
		foreach ( $ids as $id ) { $o = new stdClass(); $o->ID = $id; $this->posts[] = $o; }
	}
	function have_posts() { return $this->i + 1 < count( $this->posts ); }
	function the_post() { $this->i++; $GLOBALS['CUR'] = $this->posts[ $this->i ]->ID; }
}
function cur( $id = null ) { return $id ? $id : $GLOBALS['CUR']; }
function rec( $id = null ) { $id = cur( $id ); return $GLOBALS['POSTS'][ $id ] ?? $GLOBALS['PRODS'][ $id ]; }
function get_the_ID() { return cur(); }
function get_the_title( $id = null ) { $r = rec( $id ); return $r['title'] ?? $r['name']; }
function the_title() { echo esc_html( get_the_title() ); }
function the_title_attribute( $a ) { return get_the_title(); }
function get_permalink( $id = null ) { $r = rec( $id ); return isset( $r['slug'] ) ? $GLOBALS['U'] . '/blog/' . $r['slug'] . '/' : ( $r['link'] ?? $GLOBALS['U'] . '/blog/' ); }
function the_permalink() { echo esc_url( get_permalink() ); }
function get_post_time( $f = 'U' ) { $t = strtotime( rec()['date'] ); return date( $f, $t ); }
function get_the_date( $f = '' ) { $r = rec(); return 'c' === $f ? $r['date'] . 'T12:00:00+03:30' : $r['date']; }
function get_the_excerpt() { return rec()['excerpt']; }
function has_post_thumbnail() { return ! empty( rec()['thumb'] ); }
function the_post_thumbnail( $size, $attr ) { $r = rec(); echo '<img src="' . esc_url( $r['thumb'] ) . '" class="' . esc_attr( $attr['class'] ) . '" alt="' . esc_attr( $attr['alt'] ) . '" loading="lazy" decoding="async" width="768" height="538">'; }
function get_the_post_thumbnail_url( $id, $s ) { return $GLOBALS['PRODS'][ $id ]['img'] ?? ''; }

/* ---------------- ووکامرس ---------------- */
function term_slug( $id ) { foreach ( $GLOBALS['CATS'] as $c ) { if ( (int) $c['id'] === (int) $id ) { return $c['slug']; } } return 'x' . $id; }
function get_term_by( $f, $v, $tax ) {
	foreach ( $GLOBALS['CATS'] as $c ) { if ( $c['slug'] === $v ) { return (object) array( 'term_id' => $c['id'], 'slug' => $c['slug'], 'name' => $c['name'] ); } }
	return (object) array( 'term_id' => crc32( $v ) % 100000, 'slug' => $v, 'name' => $v ); // دسته‌ای که در داده نیست
}
function get_terms( $a ) { return array_map( function ( $c ) { return (object) array( 'term_id' => $c['id'], 'slug' => $c['slug'], 'name' => $c['name'] ); }, $GLOBALS['CATS'] ); }
function get_term_link( $t ) { return $GLOBALS['U'] . '/product-category/' . $t->slug . '/'; }
function get_term_meta( $id, $k, $s ) { return $id; }
function wp_get_attachment_image_url( $id, $s ) { foreach ( $GLOBALS['CATS'] as $c ) { if ( (int) $c['id'] === (int) $id && ! empty( $c['image']['src'] ) ) { return $c['image']['src']; } } return ''; }
function wc_get_page_permalink( $p ) { return $GLOBALS['U'] . '/' . $p . '/'; }
function wc_get_cart_url() { return $GLOBALS['U'] . '/cart/'; }
$WCOBJ = null;
function WC() { return $GLOBALS['WCOBJ']; }
function woocommerce_mini_cart() { echo '<p class="woocommerce-mini-cart__empty-message">سبد خرید خالی است.</p>'; }
function wc_get_product_ids_on_sale() { return array_keys( array_filter( $GLOBALS['PRODS'], function ( $p ) { return $p['regular'] > $p['price']; } ) ); }
function wc_get_product( $id ) { return isset( $GLOBALS['PRODS'][ $id ] ) ? new PV_Product( $id ) : false; }
function pv_money( $v ) { return number_format_i18n( number_format( $v ) ) . '&nbsp;<span class="woocommerce-Price-currencySymbol">تومان</span>'; }
class PV_Product {
	private $id, $d;
	function __construct( $id ) { $this->id = $id; $this->d = $GLOBALS['PRODS'][ $id ]; }
	function get_id() { return $this->id; }
	function get_regular_price() { return 'variable' === $this->d['type'] ? '' : $this->d['regular']; } // مثلِ ووکامرس: والدِ متغیر قیمت ندارد
	function get_variation_prices( $display = false ) { return array( 'regular_price' => array( 1 => $this->d['regular'] ), 'price' => array( 1 => $this->d['price'] ), 'sale_price' => array( 1 => $this->d['price'] ) ); }
	function get_price() { return $this->d['price']; }
	function is_on_sale() { return $this->d['regular'] > $this->d['price']; }
	function is_type( $t ) { return $this->d['type'] === $t; }
	function is_purchasable() { return true; }
	function is_in_stock() { return $this->d['stock']; }
	function add_to_cart_url() { return '?add-to-cart=' . $this->id; }
	function get_image( $size = 'woocommerce_thumbnail', $attr = array() ) { return '<img src="' . esc_url( $this->d['img'] ) . '" class="' . esc_attr( trim( 'attachment-woocommerce_thumbnail ' . ( $attr['class'] ?? '' ) ) ) . '" alt="' . esc_attr( $this->d['name'] ) . '" width="300" height="300" loading="lazy" decoding="async">'; }
	// برای سبد خرید
	function get_name() { return $this->d['name']; }
	function exists() { return true; }
	function is_visible() { return true; }
	function get_permalink( $item = null ) { return $this->d['link']; }
	function get_sku() { return 'SKU' . $this->id; }
	function is_sold_individually() { return ! empty( $this->d['solo'] ); }
	function get_max_purchase_quantity() { return isset( $this->d['stock_qty'] ) ? $this->d['stock_qty'] : -1; }
	function backorders_require_notification() { return false; }
	function is_on_backorder( $q = 1 ) { return false; }
	function managing_stock() { return isset( $this->d['stock_qty'] ); }
	function get_stock_quantity() { return $this->d['stock_qty'] ?? null; }
	function get_price_html() {
		$p = '<span class="woocommerce-Price-amount amount"><bdi>' . pv_money( $this->d['price'] ) . '</bdi></span>';
		return $this->is_on_sale() ? '<del aria-hidden="true"><span class="woocommerce-Price-amount amount"><bdi>' . pv_money( $this->d['regular'] ) . '</bdi></span></del> <ins>' . $p . '</ins>' : $p;
	}
}

/* ---------------- CodeLock ---------------- */
function hello_elementor_display_header_footer() { return true; }
class CodeLock_Settings { static function is_on( $k ) { return true; } static function get( $k ) { return ''; } }
class CodeLock_Product_Meta { static function get_video( $id ) { $v = $GLOBALS['VIDEOS'][ $id ] ?? null; return $v ? $v + array( 'url' => 'x', 'points' => array() ) : null; } }
class CodeLock_Videos { static function video_url( $id ) { return get_permalink( $id ) . '#cl-pp-video'; } static function archive_url() { return $GLOBALS['U'] . '/videos/'; } }

/* پیگیری سفارش: مثلِ وقتی inc/shop-setup.php از functions.php لود شده (پاپ‌آپ در footer.php؛ دکمهٔ ثابت فقط صفحهٔ اصلی) */
require_once $THEME . '/inc/order-track.php';
