<?php
defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();
$shipping_enabled = ! wc_ship_to_billing_address_only() && wc_shipping_enabled();
$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		$shipping_enabled ?
				array(
						'shipping' => __( 'Dirección de entrega', 'woocommerce' ),
						'billing'  => __( 'Dirección de facturación', 'woocommerce' ),
				) :
				array(
						'billing' => __( 'Dirección de facturación', 'woocommerce' ),
				),
		$customer_id
);

// Comprobar si las direcciones son iguales
$shipping_address = wc_get_account_formatted_address( 'shipping' );
$billing_address = wc_get_account_formatted_address( 'billing' );
$same_address = $shipping_address === $billing_address && !empty($shipping_address);
?>
<div class="container my-4">
	<p class="mb-4">
		<?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'Las siguientes direcciones se usarán por defecto en el proceso de compra.', 'woocommerce' ) ); ?>
	</p>
	<?php if ( $shipping_enabled ) : ?>
		<ul class="nav nav-tabs mb-3" id="addressTabs" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="true">
					<i class="bi bi-truck me-1"></i> Entrega
				</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab" aria-controls="billing" aria-selected="false">
					<i class="bi bi-receipt me-1"></i> Facturación
				</button>
			</li>
		</ul>
		<div class="tab-content" id="addressTabsContent">
			<div class="tab-pane fade show active" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
				<div class="card mb-4 shadow-none border-0 rounded-4">
					<div class="card-body">
						<h5 class="card-title mb-3"><i class="bi bi-truck me-2"></i> Dirección de entrega</h5>
						<address>
							<?php echo $shipping_address ? wp_kses_post( $shipping_address ) : esc_html_e( 'No has configurado esta dirección aún.', 'woocommerce' ); ?>
						</address>
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping' ) ); ?>" class="btn btn-outline-primary">
							<i class="bi bi-pencil-square me-1"></i> Editar dirección de entrega
						</a>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
				<div class="card mb-4 shadow-sm border-0 rounded-4">
					<div class="card-body">
						<h5 class="card-title mb-3"><i class="bi bi-receipt me-2"></i> Dirección de facturación</h5>
						<address>
							<?php echo $billing_address ? wp_kses_post( $billing_address ) : esc_html_e( 'No has configurado esta dirección aún.', 'woocommerce' ); ?>
						</address>
						<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" class="btn btn-outline-info">
							<i class="bi bi-pencil-square me-1"></i> Editar dirección de facturación
						</a>
					</div>
				</div>
			</div>
		</div>
	<?php else : ?>
		<div class="card mb-4 shadow-none border-0 rounded-4">
			<div class="card-body">
				<h5 class="card-title mb-3"><i class="bi bi-receipt me-2"></i> Dirección de facturación</h5>
				<address>
					<?php echo $billing_address ? wp_kses_post( $billing_address ) : esc_html_e( 'No has configurado esta dirección aún.', 'woocommerce' ); ?>
				</address>
				<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing' ) ); ?>" class="btn btn-outline-info">
					<i class="bi bi-pencil-square me-1"></i> Editar dirección de facturación
				</a>
			</div>
		</div>
	<?php endif; ?>
</div>
