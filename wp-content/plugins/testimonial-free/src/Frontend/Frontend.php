<?php
/**
 * The Frontend class to manage all output and enqueue Scripts and styles files of the plugin.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_free.
 * @subpackage Testimonial_free/Frontend.
 */

namespace ShapedPlugin\TestimonialFree\Frontend;

use ShapedPlugin\TestimonialFree\Frontend\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; }  // if direct access

/**
 * Frontend class
 */
class Frontend {

	/**
	 * Single instance of the class.
	 *
	 * @var null
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Frontend Instance.
	 *
	 * @return Frontend
	 * @since 1.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'register_all_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_front_scripts' ) );
		add_shortcode( 'sp_testimonial', array( $this, 'shortcode_render' ) );
		add_shortcode( 'sp_testimonial_form', array( $this, 'frontend_form' ) );
		add_action( 'save_post', array( $this, 'delete_page_testimonial_option_on_save' ) );
		add_filter( 'sp_testimonial_review_content', array( $this, 'enable_wpautop' ) );
	}

	/**
	 * Shortcode render.
	 *
	 * @param array $attributes Shortcode attributes.
	 *
	 * @return string
	 * @since 2.0
	 */
	public function shortcode_render( $attributes ) {

		shortcode_atts(
			array(
				'id' => '',
			),
			$attributes,
			'sp_testimonial'
		);

		$post_id = esc_attr( intval( $attributes['id'] ) );
		// Check the shortcode status.
		if ( empty( $post_id ) || 'spt_shortcodes' !== get_post_type( $post_id ) || 'trash' === get_post_status( $post_id ) ) {
			return;
		}
		$setting_options    = get_option( 'sp_testimonial_pro_options' );
		$shortcode_data     = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
		$layout_data        = get_post_meta( $post_id, 'sp_tpro_layout_options', true );
		$main_section_title = get_the_title( $post_id );
		ob_start();
		// Stylesheet loading problem solving here. Shortcode id to push page id option for getting how many shortcode in the page.
		// Get the existing shortcode ids from the current page.
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		// This shortcode id not in page id option. Enqueue stylesheets in shortcode.
		if ( ! is_array( $found_generator_id ) || ! $found_generator_id || ! in_array( $post_id, $found_generator_id ) ) {
			wp_enqueue_style( 'sp-testimonial-swiper' );
			wp_enqueue_style( 'tfree-font-awesome' );
			wp_enqueue_style( 'tfree-deprecated-style' );
			wp_enqueue_style( 'tfree-style' );
			$dynamic_style = self::load_dynamic_style( $post_id, $shortcode_data, $layout_data );
			// Load dynamic style.
			echo '<style id="sp_testimonial_dynamic_css' . esc_attr( $post_id ) . '">' . $dynamic_style['dynamic_css'] . '</style>';
		}

		// Update options if the existing shortcode id option not found.
		self::testimonial_update_options( $post_id, $get_page_data );

		Helper::sp_testimonial_html_show( $post_id, $setting_options, $shortcode_data, $layout_data, $main_section_title );
		return Helper::minify_output( ob_get_clean() );
	}

	/**
	 * Plugin Scripts and Styles
	 */
	public function front_scripts() {
		// Get the existing shortcode ids from the current page.
		$get_page_data      = self::get_page_data();
		$found_generator_id = $get_page_data['generator_id'];
		// CSS Files.
		if ( $found_generator_id ) {

			wp_enqueue_style( 'sp-testimonial-swiper' );
			wp_enqueue_style( 'tfree-font-awesome' );
			wp_enqueue_style( 'tfree-deprecated-style' );
			wp_enqueue_style( 'tfree-style' );

			$dynamic_style = self::load_dynamic_style( $found_generator_id );
			wp_add_inline_style( 'tfree-style', $dynamic_style['dynamic_css'] );
		}
	}

	/**
	 * Plugin Scripts and Styles
	 */
	public function admin_front_scripts() {
		$wpscreen = get_current_screen();
		if ( 'spt_shortcodes' === $wpscreen->post_type ) {
			// CSS Files.
			wp_enqueue_style( 'sp-testimonial-swiper' );
			wp_enqueue_style( 'tfree-font-awesome' );

			wp_enqueue_style( 'tfree-deprecated-style' );
			wp_enqueue_style( 'tfree-style' );
			wp_enqueue_script( 'sp-testimonial-swiper-js' );
		}

		// Load form style's CSS.
		if ( 'spt_testimonial_form' === $wpscreen->post_type ) {
			wp_enqueue_style( 'tfree-form' );
		}
	}

	/**
	 * Register the All scripts for the public-facing side of the site.
	 *
	 * @since    2.0
	 */
	public function register_all_scripts() {
		$setting_options    = get_option( 'sp_testimonial_pro_options' );
		$dequeue_swiper_css = isset( $setting_options['tf_dequeue_slick_css'] ) ? $setting_options['tf_dequeue_slick_css'] : true;
		$dequeue_fa_css     = isset( $setting_options['tf_dequeue_fa_css'] ) ? $setting_options['tf_dequeue_fa_css'] : true;
		$custom_js          = isset( $setting_options['custom_js'] ) ? $setting_options['custom_js'] : '';
		/**
		 *  Register the All style for the public-facing side of the site.
		 */
		if ( $dequeue_swiper_css ) {
			wp_register_style( 'sp-testimonial-swiper', SP_TFREE_URL . 'Frontend/assets/css/swiper.min.css', array(), SP_TFREE_VERSION );
		}
		if ( $dequeue_fa_css ) {
			wp_register_style( 'tfree-font-awesome', SP_TFREE_URL . 'Frontend/assets/css/font-awesome.min.css', array(), SP_TFREE_VERSION );
		}
		wp_register_style( 'tfree-deprecated-style', SP_TFREE_URL . 'Frontend/assets/css/deprecated-style.min.css', array(), SP_TFREE_VERSION );
		wp_register_style( 'tfree-style', SP_TFREE_URL . 'Frontend/assets/css/style.min.css', array(), SP_TFREE_VERSION );
		wp_register_style( 'tfree-form', SP_TFREE_URL . 'Frontend/assets/css/form.min.css', array(), SP_TFREE_VERSION );

		/**
		 *  Register the All scripts for the public-facing side of the site.
		 */
		wp_register_script( 'sp-testimonial-swiper-js', SP_TFREE_URL . 'Frontend/assets/js/swiper.min.js', array( 'jquery' ), SP_TFREE_VERSION, true );
		wp_register_script( 'sp-testimonial-scripts', SP_TFREE_URL . 'Frontend/assets/js/sp-scripts.min.js', array( 'jquery' ), SP_TFREE_VERSION, true );
		wp_add_inline_script( 'sp-testimonial-scripts', $custom_js );
		wp_register_script( 'sp-testimonial-form-config', SP_TFREE_URL . 'Frontend/assets/js/form-config.min.js', array( 'jquery' ), SP_TFREE_VERSION, true );
	}

	/**
	 * Delete page shortcode ids array option on save
	 *
	 * @param  int $post_ID current post id.
	 * @return void
	 */
	public function delete_page_testimonial_option_on_save( $post_ID ) {
		if ( is_multisite() ) {
			$option_key = 'sp-testimonial_page_id' . get_current_blog_id() . $post_ID;
			if ( get_site_option( $option_key ) ) {
				delete_site_option( $option_key );
			}
		} elseif ( get_option( 'sp-testimonial_page_id' . $post_ID ) ) {
				delete_option( 'sp-testimonial_page_id' . $post_ID );
		}
		if ( get_option( 'sp-testimonial_page_id0' ) ) {
				delete_option( 'sp-testimonial_page_id0' );
		}
	}

	/**
	 * Add p tag in testimonial content.
	 *
	 * @param  string $content testimonial content.
	 * @return statement
	 */
	public function enable_wpautop( $content ) {
		$content = wpautop( $content );
		return $content;
	}

	/**
	 * Gets the existing shortcode-id, page-id and option-key from the current page.
	 *
	 * @return array
	 */
	public static function get_page_data() {
		$current_page_id    = get_queried_object_id();
		$option_key         = 'testimonial_page_id' . $current_page_id;
		$found_generator_id = get_option( $option_key );
		if ( is_multisite() ) {
			$option_key         = 'testimonial_page_id' . get_current_blog_id() . $current_page_id;
			$found_generator_id = get_site_option( $option_key );
		}
		$get_page_data = array(
			'page_id'      => $current_page_id,
			'generator_id' => $found_generator_id,
			'option_key'   => $option_key,
		);
		return $get_page_data;
	}

	/**
	 * Load dynamic style of the existing shortcode id.
	 *
	 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
	 * @param  mixed $shortcode_data to push all options.
	 * @param  mixed $layout_data to push all layout options.
	 * @return array dynamic style use in the existing shortcodes in the current page.
	 */
	public static function load_dynamic_style( $found_generator_id, $shortcode_data = '', $layout_data = '' ) {
		$setting_options = get_option( 'sp_testimonial_pro_options' );
		$outline         = '';
		// If multiple shortcode found in the current page.
		if ( is_array( $found_generator_id ) ) {
			foreach ( $found_generator_id  as $post_id ) {
				if ( $post_id && is_numeric( $post_id ) && get_post_status( $post_id ) !== 'trash' ) {
					$shortcode_data = get_post_meta( $post_id, 'sp_tpro_shortcode_options', true );
					$layout_data    = get_post_meta( $post_id, 'sp_tpro_layout_options', true );

					include SP_TFREE_PATH . 'Frontend/Views/partials/dynamic-style.php';
				}
			}
		} else {
			// If single shortcode found in the current page.
			$post_id = $found_generator_id;
			include SP_TFREE_PATH . 'Frontend/Views/partials/dynamic-style.php';
		}
		// Custom css merge with dynamic style.
		$custom_css = isset( $setting_options['custom_css'] ) ? trim( html_entity_decode( $setting_options['custom_css'] ) ) : '';
		if ( ! empty( $custom_css ) ) {
			$outline .= $custom_css;
		}
		$dynamic_style = array(
			'dynamic_css' => Helper::minify_output( $outline ),
		);
		return $dynamic_style;
	}

	/**
	 * If the option does not exist, it will be created.
	 *
	 * It will be serialized before it is inserted into the database.
	 *
	 * @param  string $post_id existing shortcode id.
	 * @param  array  $get_page_data get current page-id, shortcode-id and option-key from the the current page.
	 * @return void
	 */
	public static function testimonial_update_options( $post_id, $get_page_data ) {
		$found_generator_id = $get_page_data['generator_id'];
		$option_key         = $get_page_data['option_key'];
		$current_page_id    = $get_page_data['page_id'];
		if ( $found_generator_id ) {
			$found_generator_id = is_array( $found_generator_id ) ? $found_generator_id : array( $found_generator_id );
			if ( ! in_array( $post_id, $found_generator_id ) || empty( $found_generator_id ) ) {
				// If not found the shortcode id in the page options.
				array_push( $found_generator_id, $post_id );
				if ( is_multisite() ) {
					update_site_option( $option_key, $found_generator_id );
				} else {
					update_option( $option_key, $found_generator_id );
				}
			}
		} else {
			// If option not set in current page add option.
			if ( $current_page_id ) {
				if ( is_multisite() ) {
					add_site_option( $option_key, array( $post_id ) );
				} else {
					add_option( $option_key, array( $post_id ) );
				}
			}
		}
	}


	/**
	 * Frontend form.
	 *
	 * @param array $attributes attributes.
	 *
	 * @return string
	 * @since 2.0
	 */
	public function frontend_form( $attributes ) {
		$form_id = esc_attr( (int) $attributes['id'] );
		if ( empty( $form_id ) || 'spt_testimonial_form' !== get_post_type( $form_id ) || 'trash' === get_post_status( $form_id ) ) {
			return;
		}
		$setting_options = get_option( 'sp_testimonial_pro_options' );
		$form_elements   = get_post_meta( $form_id, 'sp_tpro_form_elements_options', true );
		$form_data       = get_post_meta( $form_id, 'sp_tpro_form_options', true );
		if ( ! is_array( $form_data ) ) {
			return;
		}
		wp_enqueue_script( 'tpro-validate-js' );
		$dynamic_style = Helper::load_form_dynamic_style( $form_id, $form_data, $setting_options );

		ob_start();
		echo '<style>' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';
		Helper::frontend_form_html( $form_id, $form_elements, $form_data );
		return ob_get_clean();
	}
}
