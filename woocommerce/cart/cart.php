<?php
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="row g-4">
    <!-- Columna izquierda: listado de productos -->
    <div class="col-xl-8 col-lg-12 col-md-12 col-12">
        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="woocommerce-cart-items">
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
                    $product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <div class="cart-item d-flex align-items-center py-3 border-bottom <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

                            <!-- Eliminar -->
                            <div class="cart-col remove me-3">
                                <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a role="button" href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ),
                                        $cart_item_key
                                    );
                                ?>
                            </div>

                            <!-- Imagen -->
                            <div class="cart-col thumbnail me-3">
                                <?php
                                $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                echo $product_permalink ? sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ) : $thumbnail;
                                ?>
                            </div>

                            <!-- Nombre y meta -->
                            <div class="cart-col name flex-grow-1">
                                <?php
                                echo $product_permalink
                                    ? wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ) )
                                    : wp_kses_post( $product_name );

                                do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

                                // Meta data.
                                echo wc_get_formatted_cart_item_data( $cart_item );

                                // Backorder notification.
                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                                    echo wp_kses_post(
                                        apply_filters(
                                            'woocommerce_cart_item_backorder_notification',
                                            '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>',
                                            $product_id
                                        )
                                    );
                                }
                                ?>
                            </div>

                            <!-- Precio -->
                            <div class="cart-col price me-3">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                ?>
                            </div>

                            <!-- Cantidad -->
                            <div class="cart-col quantity me-3">
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

                                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                                ?>
                            </div>

                            <!-- Subtotal -->
                            <div class="cart-col subtotal">
                                <?php
                                echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php do_action( 'woocommerce_cart_contents' ); ?>

                <div class="cart-actions mt-3">
                    <?php if ( wc_coupons_enabled() ) { ?>
                        <div class="coupon d-flex align-items-center mb-2">
                            <input type="text" name="coupon_code" class="input-text me-2" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" />
                            <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                        </div>
                    <?php } ?>

                    <button type="submit" class="button update-cart" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
                        <?php esc_html_e( 'Update cart', 'woocommerce' ); ?>
                    </button>

                    <?php do_action( 'woocommerce_cart_actions' ); ?>
                    <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                </div>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?><?php
defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="row g-4">
    <!-- Columna izquierda: listado de productos -->
    <div class="col-xl-8 col-lg-12 col-md-12 col-12">
        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="cart-items-list">
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>
                <?php $loop_index = 0; ?>
                <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $loop_index++;
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) :
                        $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
                        $product_name      = $_product->get_name();
                        $thumbnail         = $_product->get_image( 'woocommerce_thumbnail', [ 'width' => 110 ] );
                        $regular_price     = $_product->get_regular_price();
                        $sale_price        = $_product->get_sale_price();
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
                                <div class="product-quantity mt-2" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
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
                                    ?>
                                    <div class="count-input d-inline-flex align-items-center quantity">
                                        <button type="button" class="btn btn-transparent btn-sm minus" aria-label="<?php echo esc_attr( sprintf( 'Disminuir cantidad de %s', $product_name ) ); ?>">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number"
                                            id="<?php echo $input_id; ?>"
                                            class="input-text qty text form-control border-0 w-auto px-0 text-center"
                                            name="<?php echo $input_name; ?>"
                                            value="<?php echo esc_attr( $input_value ); ?>"
                                            min="<?php echo esc_attr( $min_quantity ); ?>"
                                            max="<?php echo esc_attr( $max_quantity ); ?>"
                                            step="1"
                                        >
                                        <button type="button" class="btn btn-transparent btn-sm plus" aria-label="<?php echo esc_attr( sprintf( 'Aumentar cantidad de %s', $product_name ) ); ?>">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Botón eliminar -->
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

                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- Botón global actualizar -->
                <div class="cart-update text-end mt-3">
                    <button type="submit" class="button update-cart update-cart-btn btn btn-primary px-4" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
                        <i class="bi bi-arrow-repeat me-1"></i> <?php esc_html_e( 'Actualizar carrito', 'woocommerce' ); ?>
                    </button>
                </div>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>
            </div>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
        </form>
    </div>

    <!-- Columna derecha: Totales -->
    <div class="col-xl-4 col-lg-12 col-md-12 col-12">
        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
        <div class="cart-collaterals">
            <?php do_action( 'woocommerce_cart_collaterals' ); ?>
        </div>
        <?php do_action( 'woocommerce_after_cart' ); ?>
    </div>
</div>

<!-- JS para que los botones + y - activen el botón de actualizar -->
<script>
jQuery(document).ready(function($) {
    $('.plus, .minus').on('click', function() {
        var $input = $(this).siblings('.qty');
        var val = parseInt($input.val());
        var min = parseInt($input.attr('min')) || 0;
        var max = parseInt($input.attr('max')) || 9999;
        if ($(this).hasClass('plus') && val < max) $input.val(val + 1).trigger('change');
        if ($(this).hasClass('minus') && val > min) $input.val(val - 1).trigger('change');
    });

    // Detecta cambios en los inputs de cantidad y activa el botón
    $('.qty').on('change', function() {
        $('.update-cart-btn').prop('disabled', false);
    });
});
</script>

            </div>
            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>
    </div>

    <!-- Columna derecha: Totales -->
    <div class="col-xl-4 col-lg-12 col-md-12 col-12">
        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

        <div class="cart-collaterals">
            <?php do_action( 'woocommerce_cart_collaterals' ); ?>
        </div>

        <?php do_action( 'woocommerce_after_cart' ); ?>
    </div>
</div>
