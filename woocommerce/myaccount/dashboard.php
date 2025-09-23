<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

$current_user = wp_get_current_user();

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="row">
    <!-- Menú lateral -->
    <div class="col-md-3">
        <?php 
        // Llamada manual al menú lateral UNA SOLA VEZ
        wc_get_template( 'myaccount/navigation.php' ); 
        ?>
    </div>

    <!-- Contenido -->
    <div class="col-md-9">
        <div class="card shadow-sm p-4">
            <div class="row align-items-center mb-3">
                <div class="col-md-8">
                    <p class="mb-0 lead">
                        👋 Hola <strong><?php echo esc_html( wp_get_current_user()->display_name ); ?></strong>
                    </p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="btn btn-danger">
                        Cerrar sesión
                    </a>
                </div>
            </div>

            <!-- Contenido original del dashboard (párrafo de instrucciones) -->
            <p class="text-muted">
                <?php
                $allowed_html = array( 'a' => array( 'href' => array() ) );
                $dashboard_desc = __( 'Desde tu panel puedes ver tus <a href="%1$s">pedidos recientes</a>, administrar tus <a href="%2$s">direcciones de facturación</a>, y <a href="%3$s">editar tu contraseña y detalles de la cuenta</a>.', 'woocommerce' );
                if ( wc_shipping_enabled() ) {
                    $dashboard_desc = __( 'Desde tu panel puedes ver tus <a href="%1$s">pedidos recientes</a>, administrar tus <a href="%2$s">direcciones de envío y facturación</a>, y <a href="%3$s">editar tu contraseña y detalles de la cuenta</a>.', 'woocommerce' );
                }
                printf(
                    wp_kses( $dashboard_desc, $allowed_html ),
                    esc_url( wc_get_endpoint_url( 'orders' ) ),
                    esc_url( wc_get_endpoint_url( 'edit-address' ) ),
                    esc_url( wc_get_endpoint_url( 'edit-account' ) )
                );
                ?>
            </p>

            <!-- Mantener otros hooks sin que impriman menú -->
            <?php
            do_action( 'woocommerce_before_my_account' );
            do_action( 'woocommerce_after_my_account' );
            ?>
        </div>
    </div>
</div>
