<?php
/**
 * Testimonial content.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-free/templates/form/testimonial-content.php
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

?>
<div class="sp-tpro-form-field sp-tpro-form-content">
<div class="sp-testimonial-label-section">
	<?php if ( $testimonial_label ) { ?>
		<label for="tpro_client_testimonial<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $testimonial_label ); ?></label>
		<?php } if ( $testimonial_required ) { ?>
		<span class="sp-required-asterisk-symbol">*</span>
	<?php } ?>
</div> <!-- end of sp-testimonial-label-section -->
<div class="sp-testimonial-input-field testimonial-content">
	<?php if ( ! empty( $before ) ) { ?>
		<span class="tpro_client_before"><?php echo esc_html( $before ); ?></span>  
		<?php
	}
	self::render_text_length_counter( 'sp-content_maximum_length', $content_char_limit, $content_word_limit, $content_length_type );
	?>
	<textarea rows="7" type="text" name="tpro_client_testimonial" id="tpro_client_testimonial<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $testimonial_required ); ?> placeholder="<?php echo esc_html( $testimonial['placeholder'] ); ?>"></textarea>
	<?php if ( ! empty( $after ) ) { ?>
		<span class="tpro_client_after"><?php echo esc_html( $after ); ?></span>
	<?php } ?>
</div> <!-- end of sp-testimonial-input-field -->
</div> <!-- end of sp-tpro-form-field -->
