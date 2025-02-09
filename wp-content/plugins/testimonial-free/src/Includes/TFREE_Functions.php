<?php
/**
 * Functions file.
 *
 * @link http://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_free.
 * @subpackage Testimonial_free/includes.
 */

namespace ShapedPlugin\TestimonialFree\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

/**
 * Functions
 */
class TFREE_Functions {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_filter( 'post_updated_messages', array( $this, 'sp_tfree_change_default_post_update_message' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer' ), 1, 2 );
		add_filter( 'update_footer', array( $this, 'sprt_admin_footer_version' ), 11 );
		// Post thumbnails.
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'tf-client-image-size', 120, 120, true );
	}

	/**
	 * Post update messages for Shortcode Generator
	 *
	 * @param string $message post update message.
	 */
	public function sp_tfree_change_default_post_update_message( $message ) {
		$screen = get_current_screen();
		if ( 'spt_shortcodes' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'View updated.', 'testimonial-free' );
			$message['post'][4]  = esc_html__( 'View updated.', 'testimonial-free' );
			$message['post'][6]  = esc_html__( 'View published.', 'testimonial-free' );
			$message['post'][8]  = esc_html__( 'View submitted.', 'testimonial-free' );
			$message['post'][10] = esc_html__( 'View draft updated.', 'testimonial-free' );
		} elseif ( 'spt_testimonial' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'Testimonial updated.', 'testimonial-free' );
			$message['post'][4]  = esc_html__( 'Testimonial updated.', 'testimonial-free' );
			$message['post'][6]  = esc_html__( 'Testimonial published.', 'testimonial-free' );
			$message['post'][8]  = esc_html__( 'Testimonial submitted.', 'testimonial-free' );
			$message['post'][10] = esc_html__( 'Testimonial draft updated.', 'testimonial-free' );
		} elseif ( 'spt_testimonial_form' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'Form updated.', 'testimonial-free' );
			$message['post'][4]  = esc_html__( 'Form updated.', 'testimonial-free' );
			$message['post'][6]  = esc_html__( 'Form published.', 'testimonial-free' );
			$message['post'][8]  = esc_html__( 'Form submitted.', 'testimonial-free' );
			$message['post'][10] = esc_html__( 'Form draft updated.', 'testimonial-free' );
		}

		return $message;
	}

	/**
	 * Review Text
	 *
	 * @param string $text Footer review text.
	 *
	 * @return string
	 */
	public function admin_footer( $text ) {
		$screen = get_current_screen();
		if ( 'spt_testimonial' === $screen->post_type || 'spt_shortcodes' === $screen->post_type || 'spt_testimonial_form' === $screen->post_type ) {
			$url  = 'https://wordpress.org/support/plugin/testimonial-free/reviews/?filter=5#new-post';
			$text = sprintf(
				/* translators: 1: start strong tag, 2: close strong tag, 3: start span and a tag, 4: close a tag. */
				__( 'Enjoying %1$sReal Testimonials?%2$s Please rate us %3$sWordPress.org%4$s. Your positive feedback will help us grow more. Thank you! 😊', 'testimonial-free' ),
				'<strong>',
				'</strong>',
				'<span class="sprtf-footer-text-star">★★★★★</span> <a href="' . esc_url( $url ) . '" target="_blank">',
				'</a>'
			);
		}
		return $text;
	}
	/**
	 * Version Text
	 *
	 * @param string $text Footer version text.
	 *
	 * @return string
	 */
	public function sprt_admin_footer_version( $text ) {
		$screen = get_current_screen();
		if ( 'spt_testimonial' === $screen->post_type || 'spt_testimonial_form' === $screen->post_type ) {
			$text = 'Real Testimonials ' . SP_TFREE_VERSION;
		}
		return $text;
	}
}
