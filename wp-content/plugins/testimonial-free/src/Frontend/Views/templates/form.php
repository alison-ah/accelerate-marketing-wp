<?php
/**
 * The Testimonial Form.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-free/templates/form.php
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

?>
<div id="testimonial_form_<?php echo esc_attr( $form_id ); ?>" class="sp-tpro-fronted-form" data-form_id='<?php echo esc_attr( $form_id ); ?>'>
<div class="sp-testimonial-form-container">
	<?php if ( $required_notice ) { ?>
		<div class="sp-testimonial-required-message">
			<?php echo esc_html( $required_notice_label ); ?>
		</div>
	<?php } ?>
	<form id="testimonial_form" name="testimonial_form" method="post" action="" enctype="multipart/form-data">
		<?php
		if ( ! empty( $validation_msg ) && 'top' === $form_data['tpro_message_position'] ) {
			include self::sp_testimonial_locate_template( 'form/validation-msg.php' );
		}
		foreach ( $form_fields as $field_id => $form_field ) {
			switch ( $field_id ) {
				case 'full_name':
					if ( in_array( 'name', $form_element, true ) ) {
						$full_name_label = isset( $full_name['label'] ) ? $full_name['label'] : '';
						$before          = isset( $full_name['before'] ) ? $full_name['before'] : '';
						$after           = isset( $full_name['after'] ) ? $full_name['after'] : '';
						include self::sp_testimonial_locate_template( 'form/full-name.php' );
					}
					break;
				case 'email_address':
					if ( in_array( 'email', $form_element, true ) ) {
						$email_address_label = isset( $email_address['label'] ) ? $email_address['label'] : '';
						$before              = isset( $email_address['before'] ) ? $email_address['before'] : '';
						$after               = isset( $email_address['after'] ) ? $email_address['after'] : '';
						include self::sp_testimonial_locate_template( 'form/email.php' );
					}
					break;
				case 'identity_position':
					if ( in_array( 'position', $form_element, true ) ) {
						$identity_position_label = isset( $identity_position['label'] ) ? $identity_position['label'] : '';
						$before                  = isset( $identity_position['before'] ) ? $identity_position['before'] : '';
						$after                   = isset( $identity_position['after'] ) ? $identity_position['after'] : '';
						$required                = isset( $identity_position['required'] ) ? $identity_position['required'] : '';
						include self::sp_testimonial_locate_template( 'form/position.php' );
					}
					break;
				case 'testimonial_title':
					if ( in_array( 'testimonial_title', $form_element, true ) ) {
						$testimonial_title_label = isset( $testimonial_title['label'] ) ? $testimonial_title['label'] : '';
						$before                  = isset( $testimonial_title['before'] ) ? $testimonial_title['before'] : '';
						$after                   = isset( $testimonial_title['after'] ) ? $testimonial_title['after'] : '';
						$title_length_type       = isset( $testimonial_title['title_length']['title_length_type'] ) ? $testimonial_title['title_length']['title_length_type'] : 'characters';
						$title_word_limit        = isset( $testimonial_title['title_length']['title_word_limit'] ) ? $testimonial_title['title_length']['title_word_limit'] : '';
						$title_char_limit        = isset( $testimonial_title['title_length']['title_char_limit'] ) ? $testimonial_title['title_length']['title_char_limit'] : '';
						include self::sp_testimonial_locate_template( 'form/testimonial-title.php' );
					}
					break;
				case 'testimonial':
					if ( in_array( 'testimonial', $form_element, true ) ) {
						$testimonial_label   = isset( $testimonial['label'] ) ? $testimonial['label'] : '';
						$before              = isset( $testimonial['before'] ) ? $testimonial['before'] : '';
						$after               = isset( $testimonial['after'] ) ? $testimonial['after'] : '';
						$content_length_type = isset( $testimonial['content_length']['content_length_type'] ) ? $testimonial['content_length']['content_length_type'] : 'characters';
						$content_word_limit  = isset( $testimonial['content_length']['content_word_limit'] ) ? $testimonial['content_length']['content_word_limit'] : '';
						$content_char_limit  = isset( $testimonial['content_length']['content_char_limit'] ) ? $testimonial['content_length']['content_char_limit'] : '';
						include self::sp_testimonial_locate_template( 'form/testimonial-content.php' );
					}
					break;
				case 'featured_image':
					if ( in_array( 'image', $form_element, true ) ) {
						$featured_image_label = isset( $featured_image['label'] ) ? $featured_image['label'] : '';
						$before               = isset( $featured_image['before'] ) ? $featured_image['before'] : '';
						$after                = isset( $featured_image['after'] ) ? $featured_image['after'] : '';
						include self::sp_testimonial_locate_template( 'form/image.php' );
					}
					break;
				case 'submit_btn':
					include self::sp_testimonial_locate_template( 'form/submit-btn.php' );
					break;
			}
		}

		if ( ! empty( $validation_msg ) && ( 'bottom' === $form_data['tpro_message_position'] ) ) {
			include self::sp_testimonial_locate_template( 'form/validation-msg.php' );
		}
		?>
	</form>
</div>
</div>
