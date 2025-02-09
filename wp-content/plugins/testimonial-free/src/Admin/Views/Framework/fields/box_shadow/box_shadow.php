<?php
/**
 * Framework box shadow field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! class_exists( 'SPFTESTIMONIAL_Field_box_shadow' ) ) {
	/**
	 *
	 * Field: border
	 *
	 * @since 2.0
	 * @version 2.0
	 */
	class SPFTESTIMONIAL_Field_box_shadow extends SPFTESTIMONIAL_Fields {
		/**
		 * Field constructor.
		 *
		 * @param array  $field The field type.
		 * @param string $value The values of the field.
		 * @param string $unique The unique ID for the field.
		 * @param string $where To where show the output CSS.
		 * @param string $parent The parent args.
		 */
		public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
			parent::__construct( $field, $value, $unique, $where, $parent );
		}

		/**
		 * Render
		 *
		 * @return void
		 */
		public function render() {

			$args = wp_parse_args(
				$this->field,
				array(
					'horizontal_icon'        => __( 'X offset', 'testimonial-free' ),
					'vertical_icon'          => __( 'Y offset', 'testimonial-free' ),
					'blur_icon'              => __( 'Blur', 'testimonial-free' ),
					'spread_icon'            => __( 'Spread', 'testimonial-free' ),
					'horizontal_placeholder' => 'h-offset',
					'vertical_placeholder'   => 'v-offset',
					'blur_placeholder'       => 'blur',
					'spread_placeholder'     => 'spread',
					'horizontal'             => true,
					'vertical'               => true,
					'blur'                   => true,
					'spread'                 => true,
					'color'                  => true,
					'hover_color'            => false,
					'style'                  => false,
					'unit'                   => 'px',
				)
			);

			$default_value = array(
				'horizontal'  => '0',
				'vertical'    => '0',
				'blur'        => '0',
				'spread'      => '0',
				'color'       => '#ddd',
				'hover_color' => '',
				'style'       => 'outset',
			);

			$default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

			$value = wp_parse_args( $this->value, $default_value );

			echo wp_kses_post( $this->field_before() );

			echo '<div class="spftestimonial--inputs">';

			$properties = array();

			foreach ( array( 'horizontal', 'vertical', 'blur', 'spread' ) as $prop ) {
				if ( ! empty( $args[ $prop ] ) ) {
					$properties[] = $prop;
				}
			}

			foreach ( $properties as $property ) {
				$placeholder = ( ! empty( $args[ $property . '_placeholder' ] ) ) ? $args[ $property . '_placeholder' ] : '';
				echo '<div class="spftestimonial--box_shadow">';
				echo ( ! empty( $args[ $property . '_icon' ] ) ) ? '<div class="spftestimonial--title">' . wp_kses_post( $args[ $property . '_icon' ] ) . '</div>' : '';
				echo '<div class="spftestimonial--input">';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[' . $property . ']' ) ) . '" value="' . esc_attr( $value[ $property ] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="spftestimonial-input-number spftestimonial--is-unit" />';
				echo ( ! empty( $args['unit'] ) ) ? '<span class="spftestimonial--label spftestimonial--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['style'] ) ) {
				echo '<div class="spftestimonial--input">';
				echo '<select name="' . esc_attr( $this->field_name( '[style]' ) ) . '">';
				foreach ( array( 'inset', 'outset' ) as $style ) {
					$selected = ( $value['style'] === $style ) ? ' selected' : '';
					echo '<option value="' . esc_attr( $style ) . '"' . esc_attr( $selected ) . '>' . esc_attr( ucfirst( $style ) ) . '</option>';
				}
				echo '</select>';
				echo '</div>';
			}

			echo '</div>';

			if ( ! empty( $args['color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['color'] ) . '"' : '';
				echo '<div class="spftestimonial--color">';
				echo '<div class="spftestimonial--title">' . esc_html__( 'Color', 'testimonial-free' ) . '</div>';
				echo '<div class="spftestimonial-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[color]' ) ) . '" value="' . esc_attr( $value['color'] ) . '" class="spftestimonial-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}

			if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="spftestimonial--color">';
				echo '<div class="spftestimonial--title">' . esc_html__( 'Hover Color', 'testimonial-free' ) . '</div>';
				echo '<div class="spftestimonial-field-color">';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="spftestimonial-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}

			echo '<div class="clear"></div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
