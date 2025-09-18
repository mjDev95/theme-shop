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
		<button type="button" class="btn-add search bg-info position-relative p-3 rounded-4" id="btn-search-overlay">
			<span class="bi bi-search"></span>
		</button>
		<!-- Botón de cuenta -->
		<a href="/mi-cuenta" class="btn-add account bg-secondary position-relative p-3 rounded-4">
			<span class="bi bi-person"></span>
		</a>
		<!-- Botón de carrito -->
		<a href="/carrito" class="btn-add cart bg-primary position-relative p-3 rounded-4">
			<span class="bi bi-cart"></span>
		</a>

		<!-- Overlay de búsqueda -->
		<div id="search-overlay" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.85);z-index:9999;align-items:center;justify-content:center;">
			<div style="max-width:600px;width:90vw;margin:auto;">
				<button type="button" id="close-search-overlay" style="float:right;font-size:2rem;background:none;border:none;color:#fff;">&times;</button>
				<div style="padding:2rem;">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
		<script>
		document.addEventListener('DOMContentLoaded', function() {
			var btnSearch = document.getElementById('btn-search-overlay');
			var overlay = document.getElementById('search-overlay');
			var closeBtn = document.getElementById('close-search-overlay');
			if(btnSearch && overlay && closeBtn) {
				btnSearch.addEventListener('click', function() {
					overlay.style.display = 'flex';
				});
				closeBtn.addEventListener('click', function() {
					overlay.style.display = 'none';
				});
			}
		});
		</script>

	</div><!-- .container(-fluid) -->

</nav><!-- #main-nav -->
