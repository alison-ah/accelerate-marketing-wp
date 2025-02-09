<?php
/**
 * Submit btn.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-free/templates/form/submit-btn.php
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

?>
<div class="sp-tpro-form-submit-button">
	<?php echo wp_nonce_field( 'testimonial_form', 'testimonial_form_nonce', true, false ); ?>
	<input type="submit" value="<?php echo esc_html( $submit_btn['label'] ); ?>" id="submit" name="submit" />
	<input type="hidden" name="action" value="testimonial_form<?php echo esc_attr( $form_id ); ?>" />
	</div> <!-- end of sp-tpro-form-submit-button -->
