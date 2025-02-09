<?php
/**
 * Image.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-free/templates/form/image.php
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

?>
<div class="sp-tpro-form-field">
<div class="sp-testimonial-label-section">
	<?php if ( $featured_image_label ) { ?>
		<label for="tpro_client_image<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $featured_image_label ); ?></label><?php } if ( $featured_image_required ) { ?>
		<span class="sp-required-asterisk-symbol">*</span>
	<?php } ?>
</div>
<div class="sp-testimonial-input-field-img">
	<?php if ( ! empty( $before ) ) { ?>
		<span class="tpro_client_before"><?php echo esc_html( $before ); ?></span>  
	<?php } ?>
	<input type="file" name="tpro_client_image" id="tpro_client_image<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $featured_image_required ); ?> accept="image/jpeg,image/jpg,image/png," />
	<?php if ( ! empty( $after ) ) { ?>
		<span class="tpro_client_after photo"><?php echo esc_html( $after ); ?></span>
	<?php } ?>
</div> <!-- end of sp-testimonial-input-field -->
</div> <!-- end of sp-tpro-form-field -->
