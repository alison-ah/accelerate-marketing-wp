<?php
/**
 * Testimonial form styles.
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

$label_position         = isset( $form_data['label_position'] ) ? $form_data['label_position'] : '';
$testimonial_form_width = isset( $form_data['testimonial_form_width']['top'] ) ? $form_data['testimonial_form_width']['top'] : '680';
$label_color            = isset( $form_data['label_color'] ) ? $form_data['label_color'] : '';

$form_input_field_styles = isset( $form_data['form_input_field_styles'] ) ? $form_data['form_input_field_styles'] : array(
	'all'              => '1',
	'style'            => 'solid',
	'color'            => '#e3e3e3',
	'background_color' => '#FFFFFF',
	'radius'           => '0',
	'unit'             => '%',
);
$form_background_color   = isset( $form_data['form_background_color'] ) ? $form_data['form_background_color'] : '#FFFFFF';
$submit_button_color     = isset( $form_data['submit_button_color'] ) ? $form_data['submit_button_color'] : array(
	'color'            => '#ffffff',
	'hover-color'      => '#ffffff',
	'background'       => '#005BDF',
	'hover-background' => '#005BDF',
);
$testimonial_form_border = isset( $form_data['testimonial_form_border']['style'] ) ? $form_data['testimonial_form_border'] : array(
	'all'    => '0',
	'style'  => 'solid',
	'color'  => '#444444',
	'radius' => '6',
	'unit'   => '%',
);

$text_before_content_field = isset( $testimonial['before'] ) ? $testimonial['before'] : '';
$text_before_title_field   = isset( $testimonial_title['before'] ) ? $testimonial_title['before'] : '';

// styles when label position is top.
$form_selector = '#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form';
if ( empty( $text_before_content_field ) ) {
	$form_style .= $form_selector . ' .sp-tpro-form-field.sp-tpro-form-content .sp-testimonial-input-field .sp-maximum_length';
}
if ( empty( $text_before_title_field ) ) {
	$form_style .= ', ' . $form_selector . ' .sp-tpro-form-field.sp-tpro-form-title .sp-testimonial-input-field .sp-maximum_length';
}
$form_style .= ' {
    position: absolute;
    right: 0;
    top: -26px;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .tpro-social-profile-wrapper,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .chosen-container,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .sp-testimonial-input-field input,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field textarea{
    max-width: ' . esc_attr( $testimonial_form_width ) . 'px;
}';

$form_style .= '#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form {
    display: flex;
    justify-content: center;
    overflow: hidden;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-testimonial-form-container {
    padding: 32px;
    border: ' . esc_attr( $testimonial_form_border['all'] ) . 'px ' . esc_attr( $testimonial_form_border['style'] ) . ' ' . esc_attr( $testimonial_form_border['color'] ) . ';
    border-radius: ' . esc_attr( $testimonial_form_border['radius'] ) . 'px;
    background-color: ' . $form_background_color . ';
    width: ' . esc_attr( $testimonial_form_width ) . 'px;
    max-width: 100%;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field label{
    font-weight: 500;
}

#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .tpro-social-profile-wrapper .tpro-social-profile-item,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .chosen-container-single .chosen-single,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field:not(.tpro-category-list-field,.tpro-social-profiles-field) .sp-testimonial-input-field input,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field textarea,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field .chosen-container-multi .chosen-choices{
    border: ' . esc_attr( $form_input_field_styles['all'] ) . 'px ' . esc_attr( $form_input_field_styles['style'] ) . ' ' . esc_attr( $form_input_field_styles['color'] ) . ';
    border-radius: ' . esc_attr( $form_input_field_styles['radius'] ) . 'px;
    background-color: ' . esc_attr( $form_input_field_styles['background_color'] ) . ';
    box-sizing: border-box;
    width: 100% !important;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-field label{
        font-size: 16px;
        color: ' . esc_attr( $label_color ) . ';
    }
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-submit-button input[type=\'submit\']{
    color: ' . esc_attr( $submit_button_color['color'] ) . ';
    background: ' . esc_attr( $submit_button_color['background'] ) . ';
    padding: 15px 25px;
    text-transform: uppercase;
    font-size: 14px;
    transition: all 0.25s;
    text-decoration: none;
    line-height: 1;
    border-radius: 4px;
    margin-top: 6px;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-form-submit-button input[type=\'submit\']:hover{
    color: ' . esc_attr( $submit_button_color['hover-color'] ) . ';
    background: ' . esc_attr( $submit_button_color['hover-background'] ) . ';
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating>input:checked~label {
    color: #f3bb00;
}
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating:not(:checked)>label:hover,
#testimonial_form_' . esc_attr( $form_id ) . '.sp-tpro-fronted-form .sp-tpro-client-rating:not(:checked)>label:hover~label {
    color: #de7202;
}';
