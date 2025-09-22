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


<div class="ondas position-relative mt-n1" style=""></div>
<footer class="footer bg-light pt-lg-5 py-lg-3">
    <div class="container pb-5 py-lg-4">
        <div class="row text-center row-cols-1 row-cols-md-3 g-4">

            <!-- Bloque 1: Preguntas frecuentes -->
            <div class="col">
                <h6 class="fw-bold text-dark mb-2">¿Tienes dudas?</h6>
                <a href="/faq" class="text-decoration-none text-primary fw-semibold">
                    Lee preguntas frecuentes →
                </a>
            </div>

            <!-- Bloque 2: Atención al cliente -->
            <div class="col">
                <h6 class="fw-bold text-dark mb-2">Atención al cliente</h6>
                <a href="tel:+17086395854" class="text-decoration-none text-muted fw-semibold">
                    (708) 639-5854
                </a>
            </div>

            <!-- Bloque 3: Guía de tallas -->
            <div class="col">
                <h6 class="fw-bold text-dark mb-2">¿No sabes qué talla elegir?</h6>
                <a href="/guia-de-tallas" class="text-decoration-none text-primary fw-semibold">
                    Consulta la guía de tallas →
                </a>
            </div>

        </div>            
        <div class="mt-5 border-bottom border-2 border-gray w-100 mx-auto"></div>
    </div>
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
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 justify-content-center">
                    <div class="col mb-4 mb-md-0">
                        <h6 class="fw-bold mb-4 h5">Conoce a Dingo</h6>
                        <ul class="nav flex-column">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'container' => false,
                                'items_wrap' => '%3$s',
                                'menu_class' => 'text-decoration-none',
                            ));
                            ?>
                        </ul>
                    </div>
                    <div class="col mb-4 mb-md-0">
                        <h6 class="fw-bold mb-4 h5">Lo Legal</h6>
                        <ul class="nav flex-column">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'legal',
                                'container' => false,
                                'items_wrap' => '%3$s',
                                'menu_class' => 'text-decoration-none',
                            ));
                            ?>
                        </ul>
                    </div>
                    <div class="col">
                        <h6 class="fw-bold mb-4 h5">Colecciones</h6>
                        <ul class="nav flex-column">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'colecciones',
                                'container' => false,
                                'items_wrap' => '%3$s',
                                'menu_class' => 'text-decoration-none',
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

<!-- Modal de cuenta WooCommerce -->
<div class="modal fade" id="accountModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="accountModalLabel">
          <?php echo is_user_logged_in() ? 'Mi cuenta' : 'Accede a tu cuenta'; ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">

        <!-- Sección usuario logueado -->
        <div class="logged-section <?php echo is_user_logged_in() ? '' : 'd-none'; ?>">
          <?php if ( is_user_logged_in() ) : 
            $current_user = wp_get_current_user(); 
            $logout_url = wp_logout_url( home_url() );
          ?>
            <p class="mb-4">
                👋 ¡Hola <strong id="logged-name"><?php echo esc_html( $current_user->display_name ); ?></strong>!  
                Ya estás dentro de tu cuenta Dingo 🐾
            </p>
            <div class="d-flex justify-content-between">
                <a href="<?php echo wc_get_page_permalink( 'myaccount' ); ?>" class="btn btn-primary">
                    Ir a Mi Cuenta
                </a>
                <a href="<?php echo esc_url( $logout_url ); ?>" class="btn btn-outline-danger">
                    Cerrar sesión
                </a>
            </div>
          <?php endif; ?>
        </div>

        <!-- Sección login/registro -->
        <div class="login-section <?php echo is_user_logged_in() ? 'd-none' : ''; ?>">
          <!-- Nav tabs -->
          <ul class="nav nav-pills nav-justified mb-3" id="account-tabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab">
                Ya tengo cuenta 🐶
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button" role="tab">
                Quiero unirme ✨
              </button>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <!-- Login -->
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
              <?php echo do_shortcode('[woocommerce_my_account]'); ?>
            </div>
            <!-- Registro -->
            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
              <?php echo do_shortcode('[woocommerce_my_account]'); ?>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>


<!-- Modal de búsqueda Bootstrap -->
<div class="modal fade" id="search-overlay" tabindex="-1" aria-labelledby="searchOverlayLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content rounded-5 border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="searchOverlayLabel">
                    <i class="fa fa-search"></i> Buscar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>

                    <div id="initial-search-msg" class="p-2 text-muted d-none">Escribe al menos 3 caracteres…</div>
                    <div id="no-results-msg" class="p-3 text-center text-muted d-none">
                        <p class="fw-bold h6 mb-1">Ups… no encontramos nada con ese término.</p>
                        <p class="small mb-3">Pero aquí tienes algunas sugerencias que podrían gustarte:</p>
                    </div>

                    <div id="error-msg" class="p-2 text-danger d-none"></div>
                    <!-- Contenedor AJAX -->
                    <div id="product-search-results"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php // Closing div#page from header.php. ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>

