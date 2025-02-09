<?php
/**
 * The plugin gutenberg block.
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.1
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Gutenberg_Block' ) ) {

	/**
	 * Custom Gutenberg Block.
	 */
	class Gutenberg_Block {

		/**
		 * Block Initializer.
		 */
		public function __construct() {
			new GutenbergBlock\Gutenberg_Block_Init();
		}
	}
}
