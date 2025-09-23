<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.7.0
 */

defined( 'ABSPATH' ) || exit;
do_action( 'woocommerce_before_edit_account_form' );
?>

<div class="container my-4">
	<div class="row justify-content-center">
		<div class="col-lg-7 col-xl-6">
			<div class="card border-0 shadow-lg rounded-4">
				<div class="card-body p-4">
					<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >
						<?php do_action( 'woocommerce_edit_account_form_start' ); ?>
						<div class="row g-3">
							<div class="col-md-6">
								<label for="account_first_name" class="form-label"><i class="bi bi-person me-1"></i> <?php esc_html_e( 'First name', 'woocommerce' ); ?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" />
							</div>
							<div class="col-md-6">
								<label for="account_last_name" class="form-label"><i class="bi bi-person me-1"></i> <?php esc_html_e( 'Last name', 'woocommerce' ); ?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" />
							</div>
							<div class="col-12">
								<label for="account_display_name" class="form-label"><i class="bi bi-person-badge me-1"></i> <?php esc_html_e( 'Display name', 'woocommerce' ); ?> <span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr( $user->display_name ); ?>" aria-required="true" />
								<div id="account_display_name_description" class="form-text">
									<em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em>
								</div>
							</div>
							<div class="col-12">
								<label for="account_email" class="form-label"><i class="bi bi-envelope me-1"></i> <?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="text-danger">*</span></label>
								<input type="email" class="form-control" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" />
							</div>
						</div>
						<?php do_action( 'woocommerce_edit_account_form_fields' ); ?>
						<hr class="my-4">
						<fieldset>
							<legend class="mb-3"><i class="bi bi-key me-1"></i> <?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>
							<div class="row g-3">
								<div class="col-12">
									<label for="password_current" class="form-label"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
									<input type="password" class="form-control" name="password_current" id="password_current" autocomplete="off" />
								</div>
								<div class="col-md-6">
									<label for="password_1" class="form-label"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
									<input type="password" class="form-control" name="password_1" id="password_1" autocomplete="off" />
								</div>
								<div class="col-md-6">
									<label for="password_2" class="form-label"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
									<input type="password" class="form-control" name="password_2" id="password_2" autocomplete="off" />
								</div>
							</div>
						</fieldset>
						<?php do_action( 'woocommerce_edit_account_form' ); ?>
						<div class="d-flex justify-content-between align-items-center mt-4">
							<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
							<button type="submit" class="btn btn-primary px-4 py-2" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>">
								<i class="bi bi-save me-1"></i> <?php esc_html_e( 'Save changes', 'woocommerce' ); ?>
							</button>
							<input type="hidden" name="action" value="save_account_details" />
						</div>
						<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
