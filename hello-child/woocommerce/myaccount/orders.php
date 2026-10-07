<?php
/**
 * سفارش‌های من — خانه سعادت (بازنویسیِ myaccount/orders.php)
 * محل نصب:  wp-content/themes/hello-child/woocommerce/myaccount/orders.php
 *
 * همان جدول و ستون‌های ووکامرس (wc_get_account_orders_columns، با ستون‌هایی که افزونه‌ها اضافه می‌کنند)،
 * هوکِ هر ستون (woocommerce_my_account_my_orders_column_{id})، دکمه‌های هر سفارش و صفحه‌بندی.
 * تغییرها: عکسِ کالاها کنارِ شمارهٔ سفارش، تاریخِ شمسی، برچسبِ رنگیِ وضعیت، و در موبایل هر سفارش یک کارت.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

require_once get_stylesheet_directory() . '/inc/account.php';

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table ks-orders">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th scope="col" class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php
					foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) :
						$is_order_number = 'order-number' === $column_id;
						?>
						<?php if ( $is_order_number ) : ?>
							<th class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>" scope="row">
						<?php else : ?>
							<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
						<?php endif; ?>

							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( $is_order_number ) : ?>
								<a class="ks-orders__no" href="<?php echo esc_url( $order->get_view_order_url() ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'View order number %s', 'woocommerce' ), $order->get_order_number() ) ); ?>">
									<?php echo ks_acc_thumbs( $order, 2 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<span><?php echo esc_html( ks_fa_digits( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ) ); ?></span>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( ks_acc_date( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo ks_acc_status( $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<span class="ks-orders__sum"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
								<small class="ks-orders__n"><?php echo esc_html( ks_fa_digits( $item_count ) ); ?> کالا</small>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
										if ( empty( $action['aria-label'] ) ) {
											// Generate the aria-label based on the action name.
											/* translators: %1$s Action name, %2$s Order number. */
											$action_aria_label = sprintf( __( '%1$s order number %2$s', 'woocommerce' ), $action['name'], $order->get_order_number() );
										} else {
											$action_aria_label = $action['aria-label'];
										}
										echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button' . esc_attr( $wp_button_class ) . ' button ' . sanitize_html_class( $key ) . '" aria-label="' . esc_attr( $action_aria_label ) . '">' . esc_html( $action['name'] ) . '</a>';
										unset( $action_aria_label );
									}
								}
								?>
							<?php endif; ?>

						<?php if ( $is_order_number ) : ?>
							</th>
						<?php else : ?>
							</td>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination ks-acc-pages">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
			<?php endif; ?>

			<span class="ks-acc-pages__n">صفحه <?php echo esc_html( ks_fa_digits( $current_page ) ); ?> از <?php echo esc_html( ks_fa_digits( (int) $customer_orders->max_num_pages ) ); ?></span>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<div class="ks-acc-empty">
		<span class="ks-acc-empty__ic" aria-hidden="true"><?php echo ks_cart_icon( 'box' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<p class="ks-acc-empty__t"><?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?></p>
		<p class="ks-acc-empty__d">سفارش‌هایی که ثبت می‌کنید اینجا نمایش داده می‌شوند و می‌توانید وضعیتشان را پیگیری کنید.</p>
		<a class="ks-acc-btn" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment ?>"><?php esc_html_e( 'Browse products', 'woocommerce' ); ?></a>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
