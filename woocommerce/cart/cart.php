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
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>


<div class="row g-4">
	<div class="col-xl-8 col-lg-12 col-md-12 col-12">
		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>
			<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents table align-middle rounded-4 overflow-hidden" cellspacing="0" >
				<thead class="bg-info h5 text-white">
					<tr>
						<th class="product-thumbnail"></th>
						<th scope="col" class="product-name fw-semibold text-white"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
						<th scope="col" class="product-price fw-semibold text-white"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
						<th scope="col" class="product-quantity fw-semibold text-white"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
						<th scope="col" class="product-subtotal fw-semibold text-white"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
						<th class="product-remove"></th>
					</tr>
				</thead>
				<tbody>
					<?php do_action( 'woocommerce_before_cart_contents' ); ?>
					<?php
						$row_index = 0;
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
							$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
								$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
								$row_class = '';
								?>
								<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> border-bottom border-light-subtle align-middle" style="background: #fff;">
									<td class="product-thumbnail">
										<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
										if ( ! $product_permalink ) {
											echo $thumbnail;
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
										}
										?>
									<td scope="row" role="rowheader" class="product-name h6 text-decoration-none" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
										<?php
										$color = '';
										$talla = '';
										$genero = '';
										if ( isset( $cart_item['variation'] ) && is_array( $cart_item['variation'] ) ) {
											foreach ( $cart_item['variation'] as $var_key => $var_value ) {
												$label = strtolower( wc_attribute_label( str_replace( 'attribute_', '', $var_key ), $_product ) );
												if ( strpos( $label, 'color' ) !== false ) {
													$color = wc_clean( $var_value );
												} elseif ( strpos( $label, 'talla' ) !== false || strpos( $label, 'size' ) !== false ) {
													$talla = wc_clean( preg_replace('/^talla[-\s]*/i', '', $var_value) );
												} elseif ( strpos( $label, 'género' ) !== false || strpos( $label, 'genero' ) !== false || strpos( $label, 'gender' ) !== false ) {
													$genero = strtolower( wc_clean( $var_value ) );
												}
											}
										}
										$frase = '';
										if ( $color ) {
											$frase .= ' de color ' . $color;
										}
										if ( $talla ) {
											$frase .= ' en talla ' . $talla;
										}
										if ( $genero ) {
											if ( in_array( $genero, [ 'unisex' ] ) ) {
												$frase .= ' unisex';
											} elseif ( in_array( $genero, [ 'niña', 'nina', 'girl' ] ) ) {
												$frase .= ' para niña';
											} elseif ( in_array( $genero, [ 'niño', 'nino', 'boy' ] ) ) {
												$frase .= ' para niño';
											} else {
												$frase .= ' para ' . $genero;
											}
										}
										if ( ! $product_permalink ) {
											echo wp_kses_post( $product_name . $frase . '&nbsp;' );
										} else {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="text-decoration-none text-body" href="%s">%s%s</a>', esc_url( $product_permalink ), $_product->get_name(), $frase ), $cart_item, $cart_item_key ) );
										}
										$short_description = $_product->get_short_description();
										if ( $short_description ) {
											echo '<div class="text-muted small lh-sm">' . wp_strip_all_tags( $short_description ) . '</div>';
										}
										do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );
										$meta = wc_get_formatted_cart_item_data( $cart_item );
										if ( $meta ) {
											echo '<span style="display:none">' . $meta . '</span>';
										}
										if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
										}
										?>
									</td>
									<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
										<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
									</td>
									<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
										<?php
										if ( $_product->is_sold_individually() ) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 0;
											$max_quantity = $_product->get_max_purchase_quantity();
										}
										// Campo de cantidad moderno con botones + y -
										$input_id = 'quantity_' . esc_attr( $cart_item_key );
										$input_name = "cart[{$cart_item_key}][qty]";
										$input_value = $cart_item['quantity'];
										$min = $min_quantity;
										$max = $max_quantity ? 'max="' . esc_attr( $max_quantity ) . '"' : '';
										$step = 1;
										$label = sprintf( esc_html__( 'Cantidad de %s', 'woocommerce' ), $product_name );
										?>
															<div class="quantity d-flex align-items-center rounded-4 border">
																<button type="button" class="btn btn-transparent btn-sm minus" aria-label="Disminuir cantidad" style="width:40px; min-width:40px;"><i class="bi bi-dash"></i></button>
																<label class="screen-reader-text" for="<?php echo $input_id; ?>"><?php echo $label; ?></label>
																<input type="number" id="<?php echo $input_id; ?>" class="input-text qty text" name="<?php echo $input_name; ?>" value="<?php echo esc_attr( $input_value ); ?>" aria-label="<?php echo $label; ?>" min="<?php echo esc_attr( $min ); ?>" <?php echo $max; ?> step="<?php echo esc_attr( $step ); ?>" placeholder="" inputmode="numeric" autocomplete="off" style="border-color: transparent; box-shadow: none; background: transparent;">
																<button type="button" class="btn btn-transparent btn-sm plus" aria-label="Aumentar cantidad" style="width:40px; min-width:40px;"><i class="bi bi-plus"></i></button>
															</div>
															<?php
										?>
									</td>
									<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
										<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
									</td>
									<td class="product-remove">
										<?php
										$row_index++;
										echo apply_filters( 'woocommerce_cart_item_remove_link',
											sprintf(
												'<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="bi bi-x"></i></a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() )
											),
											$cart_item_key
										);
										?>
									</td>
								</tr>
								<?php
							}
						}
					?>
					<?php do_action( 'woocommerce_cart_contents' ); ?>
					<tr class="bg-white">
						<td colspan="6" class="actions rounded-bottom-4">
							<?php if ( wc_coupons_enabled() ) { ?>
								<div class="coupon">
									<label for="coupon_code" class="form-label visually-hidden"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
									<div class="input-group mb-2" style="max-width: 320px;">
										<input type="text" name="coupon_code" class="form-control rounded-4 me-1" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
										<button type="submit" class="btn btn-info text-white fw-semibold px-4" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
									</div>
									<?php do_action( 'woocommerce_cart_coupon' ); ?>
								</div>
							<?php } ?>
							<button type="submit" class="btn btn-info text-white <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_actions' ); ?>
							<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
						</td>
					</tr>
					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</tbody>
			</table>
			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>
	</div>
	<div class="col-xl-4 col-lg-12 col-md-12 col-12">
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
		<?php do_action( 'woocommerce_after_cart' ); ?>
	</div>
</div>
