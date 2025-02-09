<?php
/**
 * Designation.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-free/templates/form/position.php
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

?>
<div class="sp-tpro-form-field">
<div class="sp-testimonial-label-section">
	<?php if ( $identity_position_label ) { ?>
		<label for="tpro_client_designation<?php echo esc_attr( $form_id ); ?>"> <?php echo esc_html( $identity_position_label ); ?></label>
	<?php } ?>
</div> <!-- end of sp-testimonial-label-section -->
<div class="sp-testimonial-input-field">
	<?php if ( ! empty( $before ) ) { ?>
		<span class="tpro_client_before"><?php echo esc_html( $before ); ?></span>  
	<?php } ?>
	<input type="text" name="tpro_client_designation" id="tpro_client_designation<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $identity_position_required ); ?> placeholder="<?php echo esc_html( $identity_position['placeholder'] ); ?>" />
	<?php if ( ! empty( $after ) ) { ?>
		<span class="tpro_client_after"><?php echo esc_html( $after ); ?></span>
	<?php } ?>
</div> <!-- end of sp-testimonial-input-field -->
</div> <!-- end of sp-tpro-form-field -->
