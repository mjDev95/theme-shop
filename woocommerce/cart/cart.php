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
								$attributes_output .= '<div class="text-body-secondary fs-sm me-3">'
													. $label . ': <span class="text-dark fw-medium">' . esc_html( $attr_value ) . '</span>'
													. '</div>';
							}
						}
						?>
						
						<div class="d-sm-flex align-items-center pb-4 <?php echo $loop_index > 1 ? 'border-top' : ''; ?>">
							<a class="d-inline-block flex-shrink-0 bg-secondary rounded-1 p-sm-2 p-md-3 mb-2 mb-sm-0" href="<?php echo esc_url( $product_permalink ); ?>">
								<?php echo $thumbnail; ?>
							</a>
							<div class="w-100 pt-1 ps-sm-4">
								<div class="d-flex">
									<div class="me-3">
										<h3 class="h5 mb-2">
											<a href="<?php echo esc_url( $product_permalink ); ?>">
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
											<del class="text-body-secondary ms-auto precio-original">
												<?php echo wc_price( $regular_price ); ?>
											</del>
										<?php endif; ?>
									</div>
								</div>
								
								<div class="count-input ms-n3 d-inline-flex align-items-center">
									<button class="btn btn-icon fs-xl minus" type="button"><i class="bi bi-dash"></i></button>
									<input 
										class="form-control qty border-0 w-auto px-0 text-center"
										type="number"
										name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]"
										value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
										min="1"
										max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ); ?>"
										step="1"
									>
									<button class="btn btn-icon fs-xl plus" type="button"><i class="bi bi-plus"></i></button>
								</div>

								<div class="nav justify-content-end mt-n5 mt-sm-n3">
									<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="nav-link fs-xl p-2" aria-label="%s" data-product_id="%s" data-product_sku="%s">
												<i class="ai-trash"></i>
											</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'woocommerce' ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>
								</div>
								<!-- Botón eliminar con tooltip -->
								<div class="nav justify-content-end mt-2">
									<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="nav-link fs-xl p-2" data-bs-toggle="tooltip" aria-label="%s" data-bs-original-title="%s">
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
                    <button type="submit" class="btn btn-primary rounded-pill px-4 update-cart-btn" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" disabled>
                        <i class="bi bi-arrow-repeat me-1"></i> <?php esc_html_e( 'Actualizar carrito', 'woocommerce' ); ?>
                    </button>
                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
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
