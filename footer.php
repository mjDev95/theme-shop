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



<footer class="footer bg-light py-5">
    <div class="container pt-md-2 pt-lg-3 pt-xl-4">
        <div class="row pb-5 pt-sm-2 mb-lg-2">
            <div class="col-sm-5 col-md-4 col-xl-3 pb-2 pb-sm-0 mb-4 mb-sm-0">
                <a class="navbar-brand py-0 mb-3 mb-md-4" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <span class="text-primary flex-shrink-0 me-2">
                        <?php
                        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
                            the_custom_logo();
                        } else {
                            echo '<img src="' . get_template_directory_uri() . '/screenshot.png" alt="Logo" style="max-height:60px;">';
                        }
                        ?>
                    </span>
                    <span class="text-light opacity-90">Tu Sitio</span>
                </a>
                <p class="my-4 h5">Ropa alegre para niños que se mueven, sueñan y sonríen.</p>
                <div class="d-flex social-links">
                    <a class="btn btn-icon  btn-secondary rounded-4 me-3" href="#" aria-label="Instagram">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a class="btn btn-icon  btn-secondary rounded-4 me-3" href="#" aria-label="Facebook">
                        <i class="fa fa-facebook-f"></i>
                    </a>
                    <a class="btn btn-icon  btn-secondary rounded-4" href="#" aria-label="YouTube">
                        <i class="fa fa-youtube"></i>
                    </a>
                </div>
            </div>
            <div class="col-sm-6 offset-sm-1 offset-md-2 offset-xl-3">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3">
                    <div class="col mb-4 mb-md-0">
                        <h6 class="text-primary fw-bold mb-3 h5">Conoce a Dingo</h6>
                        <ul class="nav flex-column">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'container' => false,
                                'items_wrap' => '%3$s',
                                'menu_class' => '',
                            ));
                            ?>
                        </ul>
                    </div>
                    <div class="col">
                        <h6 class="text-danger fw-bold mb-3 h5">Lo Legal, pero con Cariño</h6>
                        <ul class="nav flex-column">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'legal',
                                'container' => false,
                                'items_wrap' => '%3$s',
                                'menu_class' => '',
                            ));
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <p class=" w-100 d-md-inline-flex align-items-center justify-content-between small">
            <span class="text-body-secondary">&copy; <?php echo date('Y'); ?> Todos los derechos reservados.</span>
            <span >Diseño y Desarrollo Ing. Mario Galicia Blanco</span>
        </p>
    </div>
</footer>

<?php // Closing div#page from header.php. ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>

