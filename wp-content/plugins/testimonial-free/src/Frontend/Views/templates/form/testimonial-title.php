<?php
/**
 * Testimonial title
 *
 * This template can be overridden by copying it to yourtheme/testimonial-free/templates/form/testimonial-title.php
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

?>
<div class="sp-tpro-form-field sp-tpro-form-title" id="<?php echo esc_attr( $form_id ); ?>">
<div class="sp-testimonial-label-section">
	<?php if ( $testimonial_title_label ) { ?>
		<label for="tpro_testimonial_title<?php echo esc_attr( $form_id ); ?>"><?php echo esc_html( $testimonial_title_label ); ?></label>
		<?php } if ( $testimonial_title_required ) { ?>
		<span class="sp-required-asterisk-symbol">*</span>
	<?php } ?>
</div> <!-- end of sp-testimonial-label-section -->
<div class="sp-testimonial-input-field testimonial-title">
	<?php if ( ! empty( $before ) ) { ?>
		<span class="tpro_client_before"><?php echo esc_html( $before ); ?></span>
		<?php
	}
	self::render_text_length_counter( 'sp-maximum_length', $title_char_limit, $title_word_limit, $title_length_type );
	?>
	</span>
		<input type="text" name="tpro_testimonial_title" id="tpro_testimonial_title<?php echo esc_attr( $form_id ); ?>" <?php echo esc_html( $testimonial_title_required ); ?> placeholder="<?php echo esc_html( $testimonial_title['placeholder'] ); ?>" />
	<?php if ( ! empty( $after ) ) { ?>
		<span class="tpro_client_after"><?php echo esc_html( $after ); ?></span>
	<?php } ?>
</div> <!-- end of sp-testimonial-input-field -->
</div> <!-- end of sp-tpro-form-field -->
