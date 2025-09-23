<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_navigation' );
?>

<ul class="nav nav-pills flex-column mb-4" id="myaccount-nav">
    <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
        <li class="nav-item">
            <a class="nav-link <?php echo wc_get_account_endpoint_url( $endpoint ) === wc_get_account_endpoint_url( get_query_var('pagename') ) ? 'active' : ''; ?>" 
               href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>">
                <?php echo esc_html( $label ); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
