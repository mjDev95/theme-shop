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
		  <h2 class="pb-2 pt-md-2 my-4 mt-lg-5">
    <?php esc_html_e( 'Order summary', 'woocommerce' ); ?>
    <span class="fs-base fw-normal text-body-secondary">
      (<?php echo WC()->cart->get_cart_contents_count(); ?> <?php esc_html_e( 'items', 'woocommerce' ); ?>)
    </span>
  </h2>

  <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
      $_product   = $cart_item['data'];
      $product_id = $cart_item['product_id'];
      if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 ) : ?>
        
        <div class="d-sm-flex align-items-center border-top py-4 woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
          
          <!-- Imagen -->
          <a class="d-inline-block flex-shrink-0 bg-secondary rounded-1 p-sm-2 p-xl-3 mb-2 mb-sm-0" 
             href="<?php echo esc_url( $_product->get_permalink() ); ?>">
            <?php echo $_product->get_image( 'woocommerce_thumbnail', [ 'width' => 110 ] ); ?>
          </a>
          
          <!-- Info -->
          <div class="w-100 pt-1 ps-sm-4">
            <div class="d-flex">
              <div class="me-3">
                <h3 class="h5 mb-2">
                  <a href="<?php echo esc_url( $_product->get_permalink() ); ?>">
                    <?php echo $_product->get_name(); ?>
                  </a>
                </h3>
                <!-- Variaciones -->
                <div class="d-sm-flex flex-wrap small text-body-secondary">
                  <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                </div>
              </div>
              
              <!-- Precio unitario -->
              <div class="text-end ms-auto">
                <div class="fs-5 mb-2">
                  <?php echo WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ); ?>
                </div>
              </div>
            </div>

            <!-- Cantidad -->
            <div class="count-input ms-n3">
              <?php
                echo woocommerce_quantity_input( array(
                  'input_name'   => "cart[{$cart_item_key}][qty]",
                  'input_value'  => $cart_item['quantity'],
                  'max_value'    => $_product->get_max_purchase_quantity(),
                  'min_value'    => '0',
                  'product_name' => $_product->get_name(),
                ), $_product, false );
              ?>
            </div>

            <!-- Eliminar -->
            <div class="nav justify-content-end mt-n5 mt-sm-n3">
              <a class="nav-link fs-xl p-2" href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" 
                 aria-label="<?php esc_attr_e( 'Remove', 'woocommerce' ); ?>">
                <i class="ai-trash"></i>
              </a>
            </div>
          </div>
        </div>
      
      <?php endif; endforeach; ?>

    <!-- Cupón -->
    <?php if ( wc_coupons_enabled() ) : ?>
      <div class="border-top pt-4 mb-3">
        <div class="input-group input-group-sm border-dashed" style="max-width: 310px;">
          <input class="form-control text-uppercase" type="text" name="coupon_code" placeholder="<?php esc_attr_e( 'Your coupon code', 'woocommerce' ); ?>">
          <button class="btn btn-secondary" type="submit" name="apply_coupon">
            <?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?>
          </button>
        </div>
      </div>
    <?php endif; ?>
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
