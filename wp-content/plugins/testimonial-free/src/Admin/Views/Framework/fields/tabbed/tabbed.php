<?php
/**
 * Framework tabbed field file.
 *
 * @link https://shapedplugin.com
 * @since 2.8.2
 *
 * @package Testimonial_Free
 * @subpackage Testimonial_Free/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.

use ShapedPlugin\TestimonialFree\Admin\Views\Framework\Classes\SPFTESTIMONIAL;

/**
 *
 * Field: tabbed
 *
 * @since 2.8.2
 * @version 2.8.2
 */
if ( ! class_exists( 'SPFTESTIMONIAL_Field_tabbed' ) ) {
	/**
	 *
	 * Field: 2.8.2
	 *
	 * @since 2.8.2
	 * @version 1.0.8
	 */
	class SPFTESTIMONIAL_Field_tabbed extends SPFTESTIMONIAL_Fields {

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
		 * Render field
		 *
		 * @return void
		 */
		public function render() {
			$unallows = array( 'tabbed' );
			echo wp_kses_post( $this->field_before() );
			echo '<div class="spftestimonial-tabbed-nav">';
			foreach ( $this->field['tabs'] as $key => $tab ) {

				$tabbed_icon   = ( ! empty( $tab['icon'] ) ) ? $tab['icon'] : '';
				$tabbed_active = ( empty( $key ) ) ? ' class="spftestimonial-tabbed-active"' : '';

				echo '<a class="sp_testimonial-tab-item-' . esc_attr( $key ) . '" href="#"' . wp_kses_post( $tabbed_active ) . '>' . $tabbed_icon . wp_kses_post( $tab['title'] ) . '</a>';
			}
			echo '</div>';

			echo '<div class="spftestimonial-tabbed-sections">';
			foreach ( $this->field['tabs'] as $key => $tab ) {

				$tabbed_hidden = ( ! empty( $key ) ) ? ' hidden' : '';
				echo '<div class="spftestimonial-tabbed-section' . esc_attr( $tabbed_hidden ) . '">';

				foreach ( $tab['fields'] as $field ) {
					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true;
					}
					$field_id      = ( isset( $field['id'] ) ) ? $field['id'] : '';
					$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';
					$field_value   = ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : $field_default;
					$unique_id     = ( ! empty( $this->unique ) ) ? $this->unique : '';

					SPFTESTIMONIAL::field( $field, $field_value, $unique_id, 'field/tabbed' );
				}
				echo '</div>';
			}
			echo '</div>';
			echo wp_kses_post( $this->field_after() );
		}
	}
}
