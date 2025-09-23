
<?php
if ( ! defined( 'ABSPATH' ) ) {
		exit;
}
?>

<div class="container my-4">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card border-0 rounded-4">
				<div class="card-body p-4">
					<div class="d-flex align-items-center mb-3">
						<div class="flex-shrink-0 me-3">
							<i class="fa fa-user-circle fa-2x text-primary"></i>
						</div>
						<div>
							<h4 class="mb-0">¡Hola <?php echo esc_html( $current_user->display_name ); ?>!</h4>
							<small class="text-muted">No eres tú? <a href="<?php echo esc_url( wc_logout_url() ); ?>" class="text-danger">Cerrar sesión</a></small>
						</div>
					</div>
					<div class="row g-3 mb-4">
						<div class="col-md-4">
							<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="btn btn-outline-primary w-100 py-3">
								<i class="fa fa-shopping-bag me-2"></i> Mis pedidos
							</a>
						</div>
						<div class="col-md-4">
							<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="btn btn-outline-secondary w-100 py-3">
								<i class="fa fa-map-marker-alt me-2"></i> Direcciones
							</a>
						</div>
						<div class="col-md-4">
							<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="btn btn-outline-info w-100 py-3">
								<i class="fa fa-user-edit me-2"></i> Editar cuenta
							</a>
						</div>
					</div>
					<div class="mb-2">
						<p class="text-muted">
							Desde tu panel puedes ver tus <strong>pedidos recientes</strong>, gestionar <strong>direcciones</strong> y <strong>editar tu cuenta</strong>.
						</p>
					</div>
					<?php
						do_action( 'woocommerce_account_dashboard' );
						do_action( 'woocommerce_before_my_account' );
						do_action( 'woocommerce_after_my_account' );
					?>
				</div>
			</div>
		</div>
	</div>
</div>
