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

/**
 * Hook - woocommerce_before_edit_account_form.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_before_edit_account_form' );
?>


<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >
	<legend class="form-label mt-4 mb-3 text-primary fs-5">
		<i class="bi bi-person-lines-fill me-1"></i>
		<?php esc_html_e( 'Edita tu nombre y correo electrónico', 'woocommerce' ); ?>
	</legend>

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first mb-3">
		<label for="account_first_name" class="form-label">
			<i class="bi bi-person-circle me-1"></i>
			<?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="required text-danger" aria-hidden="true">*</span>
		</label>
	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control rounded-4 bg-light border-0" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" placeholder="Ejemplo: Juan" />
	</p>
	<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last mb-3">
		<label for="account_last_name" class="form-label">
			<i class="bi bi-person me-1"></i>
			<?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="required text-danger" aria-hidden="true">*</span>
		</label>
	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control rounded-4 bg-light border-0" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" placeholder="Ejemplo: Pérez" />
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
		<label for="account_display_name" class="form-label">
			<i class="bi bi-person-badge me-1"></i>
			<?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="required text-danger" aria-hidden="true">*</span>
		</label>
	<input type="text" class="woocommerce-Input woocommerce-Input--text input-text form-control rounded-4 bg-light border-0" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr( $user->display_name ); ?>" aria-required="true" placeholder="Ejemplo: Juan Pérez" /> <span id="account_display_name_description"><em><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></em></span>
	</p>
	<div class="clear"></div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
		<label for="account_email" class="form-label">
			<i class="bi bi-envelope-at me-1"></i>
			<?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required text-danger" aria-hidden="true">*</span>
		</label>
	<input type="email" class="woocommerce-Input woocommerce-Input--email input-text form-control rounded-4 bg-light border-0" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" placeholder="ejemplo@email.com" />
	</p>

	<?php
		/**
		 * Hook where additional fields should be rendered.
		 *
		 * @since 8.7.0
		 */
		do_action( 'woocommerce_edit_account_form_fields' );
	?>

	<fieldset>
		<legend class="form-label mt-5 mb-2"><i class="bi bi-key me-1"></i> <?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
			<label for="password_current" class="form-label">
				<i class="bi bi-lock me-1"></i>
				<?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?>
			</label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control rounded-4 bg-light border-0" name="password_current" id="password_current" autocomplete="off"  />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
			<label for="password_1" class="form-label">
				<i class="bi bi-unlock me-1"></i>
				<?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?>
			</label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control rounded-4 bg-light border-0" name="password_1" id="password_1" autocomplete="off"  />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-3">
			<label for="password_2" class="form-label">
				<i class="bi bi-shield-lock me-1"></i>
				<?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?>
			</label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text form-control rounded-4 bg-light border-0" name="password_2" id="password_2" autocomplete="off" />
		</p>
	</fieldset>
	<div class="clear"></div>

	<?php
		/**
		 * My Account edit account form.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_edit_account_form' );
	?>

	<p>
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
	<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> btn bg-info text-white px-4 py-2" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>">
			<i class="bi bi-save me-1"></i>
			<?php esc_html_e( 'Save changes', 'woocommerce' ); ?>
		</button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
