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

                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'img-fluid rounded' ) ), $cart_item, $cart_item_key );
                        $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                        ?>
                        
                        <div class="row align-items-stretch border-bottom py-3 cart_item">
                            <!-- Imagen -->
                            <div class="col-3 d-flex align-items-center">
                                <?php
                                if ( ! $product_permalink ) {
                                    echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                } else {
                                    printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                                ?>
                            </div>

                            <!-- Nombre, atributos, cantidad -->
                            <div class="col-6">
                                <!-- Nombre -->
                                <div class="fw-bold mb-1">
                                    <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( $product_name );
                                    } else {
                                        echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $product_name ) );
                                    }
                                    ?>
                                </div>

                                <!-- Variaciones / atributos -->
                                <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>

                                <!-- Cantidad -->
                                <div class="mt-2">
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $min_quantity = 1;
                                        $max_quantity = 1;
                                    } else {
                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();
                                    }
                                    $input_id    = 'quantity_' . esc_attr( $cart_item_key );
                                    $input_name  = "cart[{$cart_item_key}][qty]";
                                    $input_value = $cart_item['quantity'];
                                    $min         = $min_quantity;
                                    $max         = $max_quantity ? 'max="' . esc_attr( $max_quantity ) . '"' : '';
                                    $step        = 1;
                                    $label       = sprintf( esc_html__( 'Cantidad de %s', 'woocommerce' ), $product_name );
                                    ?>
                                    <div class="quantity d-flex align-items-center rounded-4 border">
                                        <button type="button" class="btn btn-transparent btn-sm minus" aria-label="Disminuir cantidad" style="width:40px; min-width:40px;"><i class="bi bi-dash"></i></button>
                                        <label class="screen-reader-text" for="<?php echo $input_id; ?>"><?php echo $label; ?></label>
                                        <input type="number" id="<?php echo $input_id; ?>" class="input-text qty text" name="<?php echo $input_name; ?>" value="<?php echo esc_attr( $input_value ); ?>" aria-label="<?php echo $label; ?>" min="<?php echo esc_attr( $min ); ?>" <?php echo $max; ?> step="<?php echo esc_attr( $step ); ?>" inputmode="numeric" autocomplete="off" style="border-color: transparent; box-shadow: none; background: transparent;">
                                        <button type="button" class="btn btn-transparent btn-sm plus" aria-label="Aumentar cantidad" style="width:40px; min-width:40px;"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>

                            <!-- Precio + eliminar -->
                            <div class="col-3 d-flex flex-column justify-content-between text-end">
                                <div>
                                    <?php if ( $_product->is_on_sale() ) : ?>
                                        <div class="text-muted text-decoration-line-through small">
                                            <?php echo wc_price( $_product->get_regular_price() ); ?>
                                        </div>
                                        <div class="fw-bold">
                                            <?php echo wc_price( $_product->get_sale_price() ); ?>
                                        </div>
                                    <?php else : ?>
                                        <div class="fw-bold">
                                            <?php echo WC()->cart->get_product_price( $_product ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Eliminar -->
                                <div class="mt-2">
                                    <?php
                                    echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="btn btn-outline-danger btn-sm remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">
                                                <i class="bi bi-trash"></i>
                                            </a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_attr__( 'Eliminar este producto', 'woocommerce' ),
                                            esc_attr( $_product->get_id() ),
                                            esc_attr( $cart_item_key ),
                                            esc_attr( $_product->get_sku() )
                                        ),
                                        $cart_item_key
                                    );
                                    ?>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                } ?>

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
