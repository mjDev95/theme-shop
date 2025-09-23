

<?php
if ( ! defined( 'ABSPATH' ) ) {
		exit;
}
do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation mb-4" aria-label="Página de Mi Cuenta">
	<ul class="nav flex-column nav-pills rounded-4 shadow-none bg-light p-3">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
			$active = wc_is_current_account_menu_item( $endpoint );
			$classes = 'nav-link d-flex align-items-center rounded-3 fw-bold';
			if ( $active ) {
				$classes .= ' active p-3';
			}
			else {
				$classes .= ' ';
			}
		?>
			<li class="nav-item mb-2">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"
					 class="<?php echo $classes; ?>"
					 <?php echo $active ? 'aria-current="page"' : ''; ?>
					 style="font-size:1.1rem;">
					<span><?php echo esc_html( $label ); ?></span>
								<?php if ($active): ?>
									<span class="ms-auto badge bg-transparent rounded-4">
										<i class="bi bi-arrow-right-circle-fill fs-4 text-primary"></i>
									</span>
								<?php endif; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
