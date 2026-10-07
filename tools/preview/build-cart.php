<?php
/**
 * ساختِ پیش‌نمایشِ صفحهٔ سبد خرید از روی خودِ کدِ PHPِ قالب (page-cart.php + woocommerce/cart/*.php).
 *
 * WC()->cart و توابعِ ووکامرسی که قالب‌ها صدا می‌زنند با داده‌ی نمونه شبیه‌سازی می‌شوند؛ ترتیبِ هوک‌ها
 * مثلِ خودِ ووکامرس است (اعلان‌ها، پیشنهادهای مکمل، خلاصهٔ سبد، دکمهٔ ادامه).
 *
 * اجرا:   php tools/preview/build-cart.php > preview/cart-preview.html
 *         php tools/preview/build-cart.php --empty > preview/cart-empty-preview.html
 *         --hostile  ← استایلِ «مزاحمِ» قالب هم اضافه می‌شود
 */

require __DIR__ . '/stubs.php';

require __DIR__ . '/stubs-shop.php';
$IS_CART = true;
$BODY = 'page-template page-template-page-cart page woocommerce-cart woocommerce-page';

require $THEME . '/page-cart.php';
