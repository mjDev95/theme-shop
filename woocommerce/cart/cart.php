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
			
			 <div class="cart-items-list">
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>
				<?php	$loop_index = 0; ?>
				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$loop_index++;
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) {
						$product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
						$product_name      = $_product->get_name();
						$thumbnail         = $_product->get_image( 'woocommerce_thumbnail', [ 'width' => 110 ] );
						$price             = WC()->cart->get_product_price( $_product );
						$subtotal          = WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] );

						// Precio regular vs en oferta
						$regular_price = $_product->get_regular_price();
						$sale_price    = $_product->get_sale_price();

						// Variaciones (ejemplo: color, talla, etc.)
						$attributes_output = '';
						if ( $_product->is_type( 'variation' ) && ! empty( $_product->get_variation_attributes() ) ) {
							foreach ( $_product->get_variation_attributes() as $attr_name => $attr_value ) {
								$label = wc_attribute_label( str_replace( 'attribute_', '', $attr_name ) );
								$attributes_output .= '<div class="small text-capitalize me-3">'
													. $label . ': <span class="text-muted small text-lowercase">' . esc_html( $attr_value ) . '</span>'
													. '</div>';
							}
						}
						?>
						
						<div class="d-sm-flex align-items-center py-4 <?php echo $loop_index > 1 ? 'border-top' : ''; ?>">
							<a class="d-inline-block flex-shrink-0 rounded-4 px-sm-2 px-md-3 mb-2 mb-sm-0" href="<?php echo esc_url( $product_permalink ); ?>">
								<?php echo $thumbnail; ?>
							</a>
							<div class="w-100 pt-1 ps-sm-4">
								<div class="d-flex">
									<div class="me-3">
										<h3 class="h5 mb-2">
											<a class="text-decoration-none text-danger" href="<?php echo esc_url( $product_permalink ); ?>">
												<?php echo esc_html( $product_name ); ?>
											</a>
										</h3>
										<div class="d-flex flex-wrap">
											<?php echo $attributes_output; ?>
										</div>
									</div>
									<div class="text-end ms-auto">
										<div class="fs-5 mb-2 precio-actual">
											<?php echo wc_price( $sale_price ? $sale_price : $regular_price ); ?>
										</div>
										<?php if ( $sale_price ) : ?>
											<del class="text-muted ms-auto precio-original">
												<?php echo wc_price( $regular_price ); ?>
											</del>
										<?php endif; ?>
									</div>
								</div>
								<!-- Controles de cantidad -->
								<div class="product-quantity"  data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
									
										<?php
										if ( $_product->is_sold_individually() ) {
											$min_quantity = 1;
											$max_quantity = 1;
										} else {
											$min_quantity = 0;
											$max_quantity = $_product->get_max_purchase_quantity();
										}

										$input_id = 'quantity_' . esc_attr( $cart_item_key );
										$input_name = "cart[{$cart_item_key}][qty]";
										$input_value = $cart_item['quantity'];
										$min = $min_quantity;
										$max = $max_quantity ? 'max="' . esc_attr( $max_quantity ) . '"' : '';
										$step = 1;
										$label = sprintf( esc_html__( 'Cantidad de %s', 'woocommerce' ), $product_name );
										?>
									<div class="count-input ms-n3 d-inline-flex align-items-center quantity">
										<button type="button" class="btn btn-transparent btn-sm minus" aria-label="<?php echo esc_attr( sprintf( 'Disminuir cantidad de %s', $product_name ) ); ?>" style="width:40px; min-width:40px;">
											<i class="bi bi-dash"></i>
										</button>
										<label class="screen-reader-text" for="<?php echo $input_id; ?>"><?php echo $label; ?></label>
										<input 
											type="number"
											id="<?php echo $input_id; ?>"
											class="input-text qty text form-control border-0 w-auto px-0 text-center"
											name="<?php echo $input_name; ?>"
											value="<?php echo esc_attr( $input_value ); ?>"
											aria-label="<?php echo $label; ?>"
											min="<?php echo esc_attr( $min ); ?>"
											<?php echo $max; ?>
											step="<?php echo esc_attr( $step ); ?>"
											placeholder=""
											inputmode="numeric"
											autocomplete="off"
											style="width: 1.5rem !important; "
										>
										<button type="button" class="btn btn-transparent btn-sm plus" aria-label="<?php echo esc_attr( sprintf( 'Aumentar cantidad de %s', $product_name ) ); ?>" style="width:40px; min-width:40px;">
											<i class="bi bi-plus"></i>
										</button>
									</div>
								</div>	
								<table>
									<thead></thead>
									<tbody>
										<tr>
											<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$min_quantity = 1;
							$max_quantity = 1;
						} else {
							$min_quantity = 0;
							$max_quantity = $_product->get_max_purchase_quantity();
						}

						$product_quantity = woocommerce_quantity_input(
							array(
								'input_name'   => "cart[{$cart_item_key}][qty]",
								'input_value'  => $cart_item['quantity'],
								'max_value'    => $max_quantity,
								'min_value'    => $min_quantity,
								'product_name' => $product_name,
							),
							$_product,
							false
						);

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
						?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
										</tr>
										<tr>
											<td colspan="6" class="actions">

												<?php if ( wc_coupons_enabled() ) { ?>
													<div class="coupon">
														<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
														<?php do_action( 'woocommerce_cart_coupon' ); ?>
													</div>
												<?php } ?>

												<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

												<?php do_action( 'woocommerce_cart_actions' ); ?>

												<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
											</td>
										</tr>				
									</tbody>
								</table>

								<!-- Botón eliminar con tooltip -->
								<div class="nav justify-content-end mt-2">
									<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link',
										sprintf(
									'<a href="%s" class="nav-link fs-xl p-2" aria-label="%s" data-bs-toggle="tooltip" title="%s">
												<i class="bi bi-trash"></i>
											</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr( sprintf( __( 'Eliminar %s del carrito', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
											esc_attr( $product_name )
										),
										$cart_item_key
									);
									?>
								</div>
							</div>
						</div>

						<?php
					}
				}?>

                <?php do_action( 'woocommerce_cart_contents' ); ?>

                <!-- Botón global actualizar -->
                <div class="cart-update text-end mt-3">
					<button type="submit" class="button update-cart update-cart-btn btn btn-primary px-4" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
						<i class="bi bi-arrow-repeat me-1"></i> <?php esc_html_e( 'Actualizar carrito', 'woocommerce' ); ?>
					</button>
				</div>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
            </div>
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
