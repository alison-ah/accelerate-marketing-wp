<?php
/**
 * Framework icon field file.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_Free
 * @subpackage Testimonial_Free/Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.


if ( ! class_exists( 'SPFTESTIMONIAL_Field_icon' ) ) {
	/**
	 *
	 * Field: icon
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SPFTESTIMONIAL_Field_icon extends SPFTESTIMONIAL_Fields {
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
					'button_title' => esc_html__( 'Add Icon', 'testimonial-free' ),
					'remove_title' => esc_html__( 'Remove Icon', 'testimonial-free' ),
				)
			);

			echo wp_kses_post( $this->field_before() );

			$nonce  = wp_create_nonce( 'spftestimonial_icon_nonce' );
			$hidden = ( empty( $this->value ) ) ? ' hidden' : '';

			echo '<div class="spftestimonial-icon-select">';
			echo '<span class="spftestimonial-icon-preview' . esc_attr( $hidden ) . '"><i class="' . esc_attr( $this->value ) . '"></i></span>';
			echo '<a href="#" class="button button-primary spftestimonial-icon-add" data-nonce="' . esc_attr( $nonce ) . '">' . wp_kses_post( $args['button_title'] ) . '</a>';
			echo '<a href="#" class="button spftestimonial-warning-primary spftestimonial-icon-remove' . esc_attr( $hidden ) . '">' . wp_kses_post( $args['remove_title'] ) . '</a>';
			echo '<input type="hidden" name="' . esc_attr( $this->field_name() ) . '" value="' . esc_attr( $this->value ) . '" class="spftestimonial-icon-value"' . $this->field_attributes() . ' />';// phpcs:ignore
			echo '</div>';

			echo wp_kses_post( $this->field_after() );
		}

		/**
		 * Enqueue
		 *
		 * @return void
		 */
		public function enqueue() {
			add_action( 'admin_footer', array( 'SPFTESTIMONIAL_Field_icon', 'add_footer_modal_icon' ) );
		}

		/**
		 * Add_footer_modal_icon
		 *
		 * @return void
		 */
		public static function add_footer_modal_icon() {
			?>
<div id="spftestimonial-modal-icon" class="spftestimonial-modal spftestimonial-modal-icon hidden">
	<div class="spftestimonial-modal-table">
		<div class="spftestimonial-modal-table-cell">
			<div class="spftestimonial-modal-overlay"></div>
			<div class="spftestimonial-modal-inner">
				<div class="spftestimonial-modal-title">
					<?php esc_html_e( 'Add Icon', 'testimonial-free' ); ?>
					<div class="spftestimonial-modal-close spftestimonial-icon-close"></div>
				</div>
				<div class="spftestimonial-modal-header">
					<input type="text" placeholder="<?php esc_html_e( 'Search...', 'testimonial-free' ); ?>"
						class="spftestimonial-icon-search" />
				</div>
				<div class="spftestimonial-modal-content">
					<div class="spftestimonial-modal-loading">
						<div class="spftestimonial-loading"></div>
					</div>
					<div class="spftestimonial-modal-load"></div>
				</div>
			</div>
		</div>
	</div>
</div>
			<?php
		}

	}
}
