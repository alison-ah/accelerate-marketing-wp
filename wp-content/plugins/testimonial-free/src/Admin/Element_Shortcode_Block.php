<?php
/**
 * The elementor block of the plugin.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/Admin
 */

namespace ShapedPlugin\TestimonialFree\Admin;

/**
 * Elementor shortcode block.
 */
class Element_Shortcode_Block {
	/**
	 * Instance
	 *
	 * @since 2.5.2
	 *
	 * @access private
	 * @static
	 *
	 * @var Element_Shortcode_Block The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.5.2
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 2.5.2
	 *
	 * @access public
	 */
	public function __construct() {
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'tfree_block_enqueue_style' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'tfree_block_enqueue_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'sprtp_element_block_icon' ) );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since    2.5.2
	 * @return void
	 */
	public function sprtp_element_block_icon() {
		wp_enqueue_style( 'sprtp_element_block_icon', SP_TFREE_URL . 'Admin/assets/css/fontello.min.css', array(), SP_TFREE_VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the elementor block area.
	 *
	 * @since    2.5.2
	 */
	public function tfree_block_enqueue_style() {
		wp_enqueue_style( 'sp-testimonial-swiper' );
		wp_enqueue_style( 'tfree-font-awesome' );

		wp_enqueue_style( 'tfree-deprecated-style' );
		wp_enqueue_style( 'tfree-style' );
	}

	/**
	 * Enqueue the JavaScript for the elementor block area.
	 *
	 * @since    2.2.5
	 */
	public function tfree_block_enqueue_scripts() {
		/**
		 * An instance of this class should be passed to the run() function
		 * defined in tfree_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Team_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'sp-testimonial-swiper-js' );
		wp_enqueue_script( 'sp-testimonial-scripts' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.5.2
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {
		add_action( 'elementor/init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.5.2
	 *
	 * @access public
	 */
	public function init() {
		// Add Plugin actions.
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 2.5.2
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register( new ElementAddons\Shortcode_Widget() );
	}
}

Element_Shortcode_Block::instance();
