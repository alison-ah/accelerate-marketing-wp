<?php
/**
 * The plugin gutenberg block Initializer.
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.1
 *
 * @package    testimonial_free
 * @subpackage testimonial_free/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Admin\GutenbergBlock;

use ShapedPlugin\TestimonialFree\Frontend\Helper;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutenberg_Block_Init' ) ) {
	/**
	 * Sp_Testimonial_free_Gutenberg_Block_Init class.
	 */
	class Gutenberg_Block_Init {
		/**
		 * Script and style suffix
		 *
		 * @since 2.5.3
		 * @access protected
		 * @var string
		 */
		protected $suffix;
		/**
		 * Custom Gutenberg Block Initializer.
		 */
		public function __construct() {
			$this->suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
			add_action( 'plugins_loaded', array( $this, 'sptf_testimonial_plugin_loaded' ) );
			add_action( 'init', array( $this, 'sptf_gutenberg_shortcode_block' ) );
			add_action( 'init', array( $this, 'sptf_gutenberg_form_shortcode_block' ) );
			add_action( 'enqueue_block_editor_assets', array( $this, 'sptf_block_editor_assets' ) );
		}

		/**
		 * Register category for Testimonial blocks
		 */
		public function sptf_testimonial_plugin_loaded() {
			if ( version_compare( $GLOBALS['wp_version'], '5.7', '<' ) ) {
				add_filter( 'block_categories', array( $this, 'sptf_testimonial_block_category' ) );
			} else {
				add_filter( 'block_categories_all', array( $this, 'sptf_testimonial_block_category' ) );
			}
		}

		/**
		 * Method to register testimonial blocks category
		 *
		 * @param $categories
		 *
		 * @return $categories array
		 */
		public function sptf_testimonial_block_category( $categories ) {
			return array_merge(
				array(
					array(
						'slug'  => 'testimonial-free',
						'title' => __( 'Real Testimonial', 'testimonial-free' ),
					),
				),
				$categories
			);
		}

		/**
		 * Register block editor script for backend.
		 */
		public function sptf_block_editor_assets() {
			wp_enqueue_script(
				'sp-testimonial-pro-shortcode-block',
				plugins_url( '/GutenbergBlock/build/index.js', __DIR__ ),
				array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ),
				SP_TFREE_VERSION,
				true
			);

			/**
			 * Register block editor css file enqueue for backend.
			 */
			wp_enqueue_style( 'sp-testimonial-swiper' );
			wp_enqueue_style( 'tfree-font-awesome' );
			wp_enqueue_style( 'tfree-deprecated-style' );
			wp_enqueue_style( 'tfree-style' );
		}

		/**
		 * Testimonials Shortcode list.
		 *
		 * @return array
		 */
		public function sptf_post_list() {
			$shortcodes = get_posts(
				array(
					'post_type'      => 'spt_shortcodes',
					'post_status'    => 'publish',
					'posts_per_page' => 9999,
				)
			);

			if ( count( $shortcodes ) < 1 ) {
				return array();
			}

			return array_map(
				function ( $shortcode ) {
						return (object) array(
							'id'    => absint( $shortcode->ID ),
							'title' => esc_html( $shortcode->post_title ),
						);
				},
				$shortcodes
			);
		}
		/**
		 * Forms Shortcode list.
		 *
		 * @return array
		 */
		public function sptf_form_shortcode_list() {
			$shortcodes = get_posts(
				array(
					'post_type'      => 'spt_testimonial_form',
					'post_status'    => 'publish',
					'posts_per_page' => 9999,
				)
			);

			if ( count( $shortcodes ) < 1 ) {
				return array();
			}

			return array_map(
				function ( $shortcode ) {
						return (object) array(
							'id'    => absint( $shortcode->ID ),
							'title' => esc_html( $shortcode->post_title ),
						);
				},
				$shortcodes
			);
		}

		/**
		 * Register Gutenberg shortcode block.
		 */
		public function sptf_gutenberg_shortcode_block() {
			/**
			 * Register block editor js file enqueue for backend.
			 */
			wp_register_script( 'tfree-swiper-active', SP_TFREE_URL . 'Frontend/assets/js/sp-scripts.min.js', array( 'jquery' ), SP_TFREE_VERSION, true );

			wp_localize_script(
				'tfree-swiper-active',
				'sp_testimonial_free',
				array(
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'url'           => esc_url( SP_TFREE_URL ),
					'loadScript'    => SP_TFREE_URL . 'Frontend/assets/js/sp-scripts.min.js',
					'link'          => esc_url( admin_url( 'post-new.php?post_type=spt_shortcodes' ) ),
					'shortCodeList' => $this->sptf_post_list(),
				)
			);
			/**
			 * Register Gutenberg block on server-side.
			 */
			register_block_type(
				'sp-testimonial-pro/shortcode',
				array(
					'attributes'      => array(
						'shortcode'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'showInputShortcode' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'preview'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'is_admin'           => array(
							'type'    => 'boolean',
							'default' => is_admin(),
						),
					),
					'example'         => array(
						'attributes' => array(
							'preview' => true,
						),
					),
					// Enqueue blocks.editor.build.js in the editor only.
					'editor_script'   => array(
						'sp-testimonial-swiper-js',
						'tfree-swiper-active',
					),
					// Enqueue blocks.editor.build.css in the editor only.
					'editor_style'    => array(),
					'render_callback' => array( $this, 'sp_testimonial_free_render_shortcode' ),
				)
			);
		}
		/**
		 * Register Gutenberg form shortcode block.
		 */
		public function sptf_gutenberg_form_shortcode_block() {
			wp_register_style( 'tfree-form-css', SP_TFREE_URL . 'Frontend/assets/css/form.css', array(), SP_TFREE_VERSION, '' );

			wp_localize_script(
				'tfree-swiper-active',
				'sp_testimonial_form_free',
				array(
					'ajax_url'      => admin_url( 'admin-ajax.php' ),
					'url'           => esc_url( SP_TFREE_URL ),
					'loadScript'    => SP_TFREE_PATH . 'Frontend/assets/css/form.min.css',
					'link'          => esc_url( admin_url( 'post-new.php?post_type=spt_shortcodes' ) ),
					'shortCodeList' => $this->sptf_form_shortcode_list(),
				)
			);
			/**
			 * Register Gutenberg block on server-side.
			 */
			register_block_type(
				'sp-testimonial-pro/form',
				array(
					'attributes'      => array(
						'shortcode'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'showInputShortcode' => array(
							'type'    => 'boolean',
							'default' => true,
						),
						'preview'            => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'is_admin'           => array(
							'type'    => 'boolean',
							'default' => is_admin(),
						),
					),
					'example'         => array(
						'attributes' => array(
							'preview' => true,
						),
					),
					'editor_style'    => array( 'tfree-form-css' ),
					'render_callback' => array( $this, 'sp_testimonial_free_render_form_shortcode' ),
				)
			);
		}

		/**
		 * Render callback.
		 *
		 * @param string $attributes Shortcode.
		 * @return string
		 */
		public function sp_testimonial_free_render_shortcode( $attributes ) {
			$class_name = '';
			if ( ! empty( $attributes['className'] ) ) {
				$class_name = $attributes['className'];
			}

			if ( ! $attributes['is_admin'] ) {
				return '<div class="' . esc_attr( $class_name ) . '">' . do_shortcode( '[sp_testimonial id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
			}

			$edit_page_link = get_edit_post_link( sanitize_text_field( $attributes['shortcode'] ) );

			return '<div id="' . uniqid() . '" class="' . $class_name . '"><a href="' . $edit_page_link . '" target="_blank" class="sp_testimonial_block_edit_button">Edit View</a>' . do_shortcode( '[sp_testimonial id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}

		/**
		 * Render testimonial form's callback.
		 *
		 * @param string $attributes Shortcode.
		 * @return string
		 */
		public function sp_testimonial_free_render_form_shortcode( $attributes ) {
			$class_name       = '';
			$load_google_font = '';
			$form_id          = (int) $attributes['shortcode'];
			$form_data        = get_post_meta( $form_id, 'sp_tpro_form_options', true );
			$setting_options  = get_option( 'sp_tpro_form_options' );

			if ( ! empty( $attributes['className'] ) ) {
				$class_name = $attributes['className'];
			}

			if ( ! $attributes['is_admin'] ) {
				return '<div class="' . esc_attr( $class_name ) . '">' . do_shortcode( '[sp_testimonial_form id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
			}

			$dynamic_style    = Helper::load_form_dynamic_style( $form_id, $form_data, $setting_options );
			$enqueue_fonts    = Helper::load_google_fonts( $dynamic_style['typography'] );
			$load_google_font = '';

			// Google font link enqueue.
			if ( ! empty( $enqueue_fonts ) ) {
				$load_google_font .= '<link rel="stylesheet" href="' . esc_url( 'https://fonts.googleapis.com/css?family=' . implode( '|', $enqueue_fonts ) ) . '" media="all">';
			}

			$edit_page_link = get_edit_post_link( sanitize_text_field( $attributes['shortcode'] ) );
			return $load_google_font . '<div id="testimonial_form_' . $form_id . ' " class="' . $class_name . '"><a href="' . $edit_page_link . '" target="_blank" class="sp_testimonial_block_edit_button">Edit View</a>' . do_shortcode( '[sp_testimonial_form id="' . sanitize_text_field( $attributes['shortcode'] ) . '"]' ) . '</div>';
		}
	}
}
