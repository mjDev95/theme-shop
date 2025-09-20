<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>


<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

			<div class="col-md-12">
                <footer class="bd-footer py-4 py-md-5 mt-5 bg-body-tertiary">
                    <div class="container py-4 py-md-5 px-4 px-md-3 text-body-secondary">
                        <div class="row">
                            <div class="col-lg-3 mb-3">
                                <a class="d-inline-flex align-items-center mb-2 text-body-emphasis text-decoration-none" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Logo">
                                    <?php
                                    if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                                        the_custom_logo();
                                    } else {
                                        echo '<img src="' . get_template_directory_uri() . '/screenshot.png" alt="Logo" style="max-height:60px;">';
                                    }
                                    ?>
                                </a>
                                <ul class="list-unstyled small">
                                    <li class="mb-2">Diseñado y desarrollado por <a href="#">Tu Sitio</a>.</li>
                                    <li class="mb-2">&copy; <?php echo date('Y'); ?> Todos los derechos reservados.</li>
                                </ul>
                            </div>
                            <div class="col-6 col-lg-2 offset-lg-1 mb-3">
                                <h5>Menú Footer</h5>
                                <ul class="list-unstyled">
                                    <?php
                                    wp_nav_menu(array(
                                        'theme_location' => 'footer',
                                        'container' => false,
                                        'items_wrap' => '%3$s',
                                        'menu_class' => '',
                                        'menu_id'         => 'footer',
                                    ));
                                    ?>
                                </ul>
                            </div>
                            <div class="col-6 col-lg-2 mb-3">
                                <h5>Legal</h5>
                                <ul class="list-unstyled">
                                    <?php
                                    wp_nav_menu(array(
                                        'theme_location' => 'legal',
                                        'container' => false,
                                        'items_wrap' => '%3$s',
                                        'menu_class' => '',
                                        'menu_id'         => 'legal',
                                    ));
                                    ?>
                                </ul>
                            </div>
                            <div class="col-6 col-lg-2 mb-3">
                                <h5>Contacto</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><a href="mailto:info@tusitio.com">info@tusitio.com</a></li>
                                    <li class="mb-2"><a href="tel:+123456789">+1 234 567 89</a></li>
                                </ul>
                            </div>
                            <div class="col-6 col-lg-2 mb-3">
                                <h5>Redes</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2"><a href="#">Facebook</a></li>
                                    <li class="mb-2"><a href="#">Instagram</a></li>
                                    <li class="mb-2"><a href="#">Twitter</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
			</div><!-- col -->

		</div><!-- .row -->

	</div><!-- .container(-fluid) -->

</div><!-- #wrapper-footer -->

<?php // Closing div#page from header.php. ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>

