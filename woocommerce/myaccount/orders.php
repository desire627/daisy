<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>
	<?php
		foreach ( $customer_orders->orders as $customer_order ) :
		$order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$item_count = $order->get_item_count() - $order->get_item_count_refunded();
	?>
		<div class="order-item">
			<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
				<thead>
					<tr>
						<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
							<th class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
								<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
									<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

								<?php elseif ( 'order-number' === $column_id ) : ?>
									<div class="order-title"><?php esc_html_e('Order No:', 'travelami') ?></div>
									<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
										<?php echo esc_html( _x( '#', 'hash before order number', 'travelami' ) . $order->get_order_number() ); ?>
									</a>

								<?php elseif ( 'order-date' === $column_id ) : ?>
									<div class="order-title"><?php esc_html_e('Date:', 'travelami') ?></div>
									<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

								<?php elseif ( 'order-status' === $column_id ) : ?>
									<div class="order-title"><?php esc_html_e('Status:', 'travelami') ?></div>
									<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

								<?php elseif ( 'order-total' === $column_id ) : ?>
									<div class="order-title"><?php esc_html_e('Order Amount:', 'travelami') ?></div>
									<?php
									/* translators: 1: formatted order total 2: total order items */
									echo wp_kses( sprintf( _n( '%1$s', '%1$s', $item_count, 'travelami' ), $order->get_formatted_order_total() ),'post' );
									?>

								<?php endif; ?>
							</td>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="3" class="order-list-image">
							<ul>
								<?php
									foreach ($customer_order->get_items() as $key => $lineItem) {

										$product_id = $lineItem['product_id'];
										$product = wc_get_product( $product_id );
										echo '<li><a href="'. esc_url( $product->get_permalink() ) .'">' . $product->get_image() . '</a></li>';
									}

									$number_item = count( $customer_order->get_items() );
									$number_total = $number_item - 5;

									if ( $number_item > 5 ) {
										echo sprintf('<li class="item-plus"><span>+%s<span>%s</span></span></li>', esc_html( $number_total ), esc_html__( 'more', 'travelami' ) );
									}
								?>
							</ul>
						</td>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-order-actions" data-title="<?php echo esc_attr__( 'Actions', 'travelami' ); ?>">
							<?php
							$actions = wc_get_account_orders_actions( $order );

							if ( ! empty( $actions ) ) {
								foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
									echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
								}
							}
							?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	<?php endforeach; ?>
	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'travelami' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'travelami' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Browse products', 'travelami' ); ?></a>
		<?php esc_html_e( 'No order has been made yet.', 'travelami' ); ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
