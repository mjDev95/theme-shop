
<?php
if ( ! defined( 'ABSPATH' ) ) {
		exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation mb-4" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
	<ul class="nav flex-column nav-pills rounded-4 shadow-sm bg-light py-3 px-2">
		<?php
		$icons = [
			'dashboard' => 'fa-home',
			'orders' => 'fa-shopping-bag',
			'downloads' => 'fa-cloud-download-alt',
			'edit-address' => 'fa-map-marker-alt',
			'payment-methods' => 'fa-credit-card',
			'edit-account' => 'fa-user-edit',
			'customer-logout' => 'fa-sign-out-alt',
		];
		foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
			$active = wc_is_current_account_menu_item( $endpoint ) ? 'active' : '';
			$icon = isset($icons[$endpoint]) ? $icons[$endpoint] : 'fa-circle';
		?>
			<li class="nav-item mb-2">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"
					 class="nav-link d-flex align-items-center rounded-3 fw-bold <?php echo $active; ?>"
					 <?php echo $active ? 'aria-current="page"' : ''; ?>
					 style="font-size:1.1rem;">
					<span class="me-2"><i class="fa <?php echo $icon; ?> text-info"></i></span>
					<span><?php echo esc_html( $label ); ?></span>
					<?php if ($active): ?>
						<span class="ms-auto badge bg-primary shadow-sm">🐾</span>
					<?php endif; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
