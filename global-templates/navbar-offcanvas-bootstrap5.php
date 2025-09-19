<?php
/**
 * Header Navbar (bootstrap5)
 *
 * @package Understrap
 * @since 1.1.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<nav id="main-nav" class="navbar navbar-expand-md navbar-dark bg-white text-primary shadow-sm" aria-labelledby="main-nav-label">

	<h2 id="main-nav-label" class="screen-reader-text">
		<?php esc_html_e( 'Main Navigation', 'understrap' ); ?>
	</h2>


	<div class="<?php echo esc_attr( $container ); ?>">

		<!-- Your site branding in the menu -->
		<?php get_template_part( 'global-templates/navbar-branding' ); ?>

		<button
			class="navbar-toggler"
			type="button"
			data-bs-toggle="offcanvas"
			data-bs-target="#navbarNavOffcanvas"
			aria-controls="navbarNavOffcanvas"
			aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Open menu', 'understrap' ); ?>"
		>
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="offcanvas offcanvas-end bg-primary" tabindex="-1" id="navbarNavOffcanvas">

			<div class="offcanvas-header justify-content-end">
				<button
					class="btn-close btn-close-white text-reset"
					type="button"
					data-bs-dismiss="offcanvas"
					aria-label="<?php esc_attr_e( 'Close menu', 'understrap' ); ?>"
				></button>
			</div><!-- .offcancas-header -->

			<!-- The WordPress Menu goes here -->
			<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'primary',
					'container_class' => 'offcanvas-body',
					'container_id'    => '',
					'menu_class'      => 'navbar-nav justify-content-end flex-grow-1 pe-3',
					'fallback_cb'     => '',
					'menu_id'         => 'main-menu',
					'depth'           => 2,
					'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
				)
			);
			?>
		</div><!-- .offcanvas -->

		<!-- Botón de búsqueda -->
		<button type="button" class=" btn text-white btn btn-add search btn-info position-relative p-3 rounded-4 text-center" data-bs-toggle="modal" data-bs-target="#search-overlay">
			<i class="fa fa-search"></i>
		</button>
		<!-- Botón de cuenta -->
		<a href="/mi-cuenta" class="btn text-white btn-add account btn-secondary position-relative p-3 rounded-4 text-center">
			<i class="fa fa-user"></i>
		</a>
		<!-- Botón de carrito -->
		<a href="/carrito" class="btn text-white btn-add cart btn-primary position-relative p-3 rounded-4 text-center">
			<i class="fa fa-shopping-cart"></i>
		</a>

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
								<p class="fw-bold mb-1">Ups… no encontramos nada con ese término.</p>
								<p class="small mb-3">Pero aquí tienes algunas sugerencias que podrían gustarte:</p>
							</div>

							<div id="error-msg" class="p-2 text-danger d-none"></div>
							<!-- Contenedor AJAX -->
							<div id="product-search-results" class="mt-3"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div><!-- .container(-fluid) -->

</nav><!-- #main-nav -->
