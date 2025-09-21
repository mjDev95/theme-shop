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

<nav id="main-nav" class="navbar navbar-expand-xl navbar-dark bg-white text-primary shadow-sm py-4" aria-labelledby="main-nav-label">

	<h2 id="main-nav-label" class="screen-reader-text">
		<?php esc_html_e( 'Main Navigation', 'understrap' ); ?>
	</h2>


	<div class="container-xl">

		<!-- Your site branding in the menu -->
		<?php get_template_part( 'global-templates/navbar-branding' ); ?>

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
		<div class="botones-menu header-sticky d-flex justify-content-between">
			<button
				class="navbar-toggler order-1 ms-auto"
				type="button"
				data-bs-toggle="offcanvas"
				data-bs-target="#navbarNavOffcanvas"
				aria-controls="navbarNavOffcanvas"
				aria-expanded="false"
				aria-label="<?php esc_attr_e( 'Open menu', 'understrap' ); ?>"
			>
				<span class="navbar-toggler-animation">
					<span></span>
					<span></span>
					<span></span>
				</span>
			</button>
			<!-- Botón de búsqueda -->
			<button type="button" class=" btn text-white btn btn-add search btn-info position-relative p-3 rounded-4 text-center" data-bs-toggle="modal" data-bs-target="#search-overlay">
				<i class="fa fa-search"></i>
			</button>
			<!-- Botón de cuenta -->
			<button  type="button" class="btn text-white btn-add account btn-secondary position-relative p-3 rounded-4 text-center" data-bs-toggle="modal"  data-bs-target="#accountModal">
				<i class="fa fa-user"></i>
			</button>
			<!-- Botón de carrito -->
			<a href="/carrito" class="btn text-white btn-add cart btn-primary position-relative p-3 rounded-4 text-center">
				<i class="fa fa-shopping-cart"></i>
				<span id="cart-count" class="d-none small position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white">
					0
				</span>
			</a>
		</div>
	</div><!-- .container(-fluid) -->

</nav><!-- #main-nav -->
