<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */
use TemPlazaFramework\Functions;
defined( 'ABSPATH' ) || exit;
if ( !class_exists( 'TemPlazaFramework\TemPlazaFramework' )){
    $templaza_options = array();
}else{
    $templaza_options = Functions::get_theme_options();
}
$cart_auto      = isset($templaza_options['templaza-shop-cart-auto'])?filter_var($templaza_options['templaza-shop-cart-auto'], FILTER_VALIDATE_BOOLEAN):true;
do_action( 'woocommerce_before_cart' ); ?>
<div class="woocommrece-cart-content">
    <form class="woocommerce-cart-form templaza-shop-box" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

        <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
            <tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
                    <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                        <td class="product-thumbnail">
							<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo ! empty( $thumbnail ) ? $thumbnail : ''; // PHPCS: XSS ok.
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
							}
							?>
                        </td>

                        <td class="product-content">
                            <div class="product-top">
                                <div class="product-name">
									<?php
									if ( ! $product_permalink ) {
										echo wp_kses( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;','post' );
									} else {
										echo wp_kses( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ),'post' );
									}

									do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

									// Meta data.
									echo wp_kses(wc_get_formatted_cart_item_data( $cart_item ),'post'); // PHPCS: XSS ok.

									// Backorder notification.
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'travelami' ) . '</p>', $product_id ),'post' );
									}

									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
									}
									?>
                                </div>
                                <div class="product-price">
									<?php
									echo wp_kses(apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ),'post'); // PHPCS: XSS ok.
									?>
                                </div>
                            </div>
                            <div class="product-bottom">
                                <div class="product-qty">
									<?php
									echo '<div class="woocommerce-cart-item__qty" data-nonce="' . wp_create_nonce( 'templaza-update-cart-qty--' . $cart_item_key ) . '">';
									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
									echo '</div>';
									?>
                                </div>
                                <div class="product-remove">
									<?php
									echo wp_kses(apply_filters(
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s<span class="name">%s</span></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr__( 'Remove this item', 'travelami' ),
											esc_attr( $product_id ),
											esc_attr( $cart_item_key ),
											esc_attr( $_product->get_sku() ),
											'<i class="fas fa-times"></i>',
											esc_html__( 'Remove', 'travelami' )
										),
										$cart_item_key
									),'post');
									?>
                                </div>
                            </div>
                        </td>
                    </tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

            <tr class="coupon-form">
                <td colspan="6" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon">
                            <label for="coupon_code"><?php esc_html_e( 'Coupon code:', 'travelami' ); ?></label>
                            <div class="coupon-row">
                                <input type="text" name="coupon_code" class="input-text" id="coupon_code" value=""
                                       placeholder="<?php esc_attr_e( 'Enter coupon code', 'travelami' ); ?>"/>
                                <button type="submit" class="button" name="apply_coupon"
                                        value="<?php esc_attr_e( 'Apply coupon', 'travelami' ); ?>"><?php esc_html_e( 'Apply', 'travelami' ); ?></button>
                            </div>

							<?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
					<?php } ?>
                     <button type="submit"
                                class="templaza-button templaza-btn-outline button-medium <?php echo intval( $cart_auto ) ? 'hide' : ''; ?>"
                                name="update_cart"
                                value="<?php esc_attr_e( 'Update cart', 'travelami' ); ?>"><?php esc_html_e( 'Update cart', 'travelami' ); ?></button>
					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </td>
            </tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
            </tbody>
        </table>
		<?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

    <div class="cart-collaterals">
		<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
		?>
    </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
