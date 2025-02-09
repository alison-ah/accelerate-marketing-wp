<?php
/**
 * The testimonial settings configuration.
 *
 * @link https://shapedplugin.com
 * @since 2.0.0
 *
 * @package Testimonial_free
 * @subpackage Testimonial_free/admin/views
 */

use ShapedPlugin\TestimonialFree\Admin\Views\Framework\Classes\SPFTESTIMONIAL;

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

//
// Set a unique slug-like ID.
//
$prefix = 'sp_testimonial_pro_options';

//
// Review text.
//
$url  = 'https://wordpress.org/support/plugin/testimonial-free/reviews/?filter=5#new-post';
$text = sprintf(
	/* translators: 1: start strong tag, 2: close strong tag, 3: start and close a tag. */
	__( 'If you like %1$sReal Testimonials%2$s please leave us a %3$s rating. Your Review is very important to us as it helps us to grow more.', 'testimonial-free' ),
	'<strong>',
	'</strong>',
	'<a href="' . esc_url( $url ) . '" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
);

//
// Create a settings page.
//
SPFTESTIMONIAL::createOptions(
	$prefix,
	array(
		'menu_title'       => __( 'Settings', 'testimonial-free' ),
		'menu_parent'      => 'edit.php?post_type=spt_testimonial',
		'menu_type'        => 'submenu', // menu, submenu, options, theme, etc.
		'menu_slug'        => 'spt_settings',
		'theme'            => 'light',
		'class'            => 'spt-main-class',
		'show_all_options' => false,
		'show_bar_menu'    => false,
		'show_search'      => false,
		'show_reset_all'   => false,
		'show_footer'      => false,
		'footer_credit'    => $text,
		'framework_title'  => __( 'Settings', 'testimonial-free' ),
	)
);

// Field: reCAPTCHA.
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'id'     => 'google_recaptcha',
		'title'  => __( 'reCAPTCHA', 'testimonial-free' ),
		'icon'   => 'fa fa-shield',
		'fields' => array(
			array(
				'type'    => 'submessage',
				'class'   => 'testimonial_backend-notice',
				'style'   => 'info',
				'content' => sprintf(
					/* translators: 1: start link tag, 2: close tag. */
					__(
						'%1$sreCAPTCHA%2$s is a free anti-spam service of Google that protects your website from spam and abuse. %3$s Get your API Keys%2$s. %4$s(Available in Pro)%5$s',
						'testimonial-free'
					),
					'<a href="https://www.google.com/recaptcha" target="_blank">',
					'</a>',
					'<a href="https://www.google.com/recaptcha/admin#list" target="_blank">',
					'<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1"><b>',
					'</b></a>'
				),
			),
			array(
				'id'      => 'captcha_version',
				'class'   => 'pro_only_field',
				'type'    => 'radio',
				'title'   => __( 'reCAPTCHA', 'testimonial-free' ),
				'options' => array(
					'v2' => __( 'v2', 'testimonial-free' ),
					'v3' => __( 'v3', 'testimonial-free' ),
				),
				'default' => 'v2',
				'inline'  => true,
			),
			array(
				'id'         => 'captcha_site_key',
				'type'       => 'text',
				'class'      => 'pro_only_field',
				'attributes' => array( 'disabled' => 'disabled' ),
				'title'      => __( 'Site Key', 'testimonial-free' ),
				'sanitize'   => 'spftestimonial_sanitize_text',
			),
			array(
				'id'         => 'captcha_secret_key',
				'type'       => 'text',
				'class'      => 'pro_only_field',
				'attributes' => array( 'disabled' => 'disabled' ),
				'title'      => __( 'Secret Key', 'testimonial-free' ),
				'sanitize'   => 'spftestimonial_sanitize_text',
			),
		),
	)
);

//
// Custom Menu
//
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'name'   => 'menu_settings',
		'title'  => __( 'Custom Menu', 'testimonial-free' ),
		'icon'   => 'fa fa-bars',
		'fields' => array(
			array(
				'id'       => 'tpro_singular_name',
				'type'     => 'text',
				'title'    => __( 'Singular name', 'testimonial-free' ),
				'default'  => 'Testimonial',
				'sanitize' => 'spftestimonial_sanitize_text',
			),
			array(
				'id'       => 'tpro_plural_name',
				'type'     => 'text',
				'title'    => __( 'Plural name', 'testimonial-free' ),
				'default'  => 'Testimonials',
				'sanitize' => 'spftestimonial_sanitize_text',
			),
		),
	)
);

//
// Advanced Settings section.
//
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'name'   => 'advanced_settings',
		'title'  => __( 'Advanced', 'testimonial-free' ),
		'icon'   => 'fa fa-wrench',
		'fields' => array(
			array(
				'id'         => 'testimonial_data_remove',
				'type'       => 'checkbox',
				'class'      => 'tpro-data-remove-check',
				'title'      => __( 'Clean up Data on Deletion', 'testimonial-free' ),
				'title_help' => __( 'Delete all Real Testimonials data from the database on plugin deletion.', 'testimonial-free' ),
				'default'    => false,
				'sanitize'   => 'rest_sanitize_boolean',
			),
			array(
				'id'         => 'tpro_dequeue_google_fonts',
				'type'       => 'switcher',
				'title'      => __( 'Google Fonts', 'testimonial-free' ),
				'text_on'    => __( 'Enqueued', 'testimonial-free' ),
				'text_off'   => __( 'Dequeued', 'testimonial-free' ),
				'text_width' => 99,
				'class'      => 'pro_switcher',
				'attributes' => array( 'disabled' => 'disabled' ),
				'default'    => false,
				'sanitize'   => 'rest_sanitize_boolean',
			),
		),
	)
);

//
// Control Assets section.
//
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'name'   => 'menu_settings',
		'title'  => __( 'Control Assets', 'testimonial-free' ),
		'icon'   => 'fa fa-tasks',
		'fields' => array(
			array(
				'id'         => 'tf_dequeue_slick_js',
				'type'       => 'switcher',
				'title'      => __( 'Swiper JS', 'testimonial-free' ),
				'text_on'    => __( 'Enqueued', 'testimonial-free' ),
				'text_off'   => __( 'Dequeued', 'testimonial-free' ),
				'text_width' => 99,
				'default'    => true,
				'sanitize'   => 'rest_sanitize_boolean',
			),
			array(
				'id'         => 'tf_dequeue_slick_css',
				'type'       => 'switcher',
				'title'      => __( 'Swiper CSS', 'testimonial-free' ),
				'text_on'    => __( 'Enqueued', 'testimonial-free' ),
				'text_off'   => __( 'Dequeued', 'testimonial-free' ),
				'text_width' => 99,
				'default'    => true,
				'sanitize'   => 'rest_sanitize_boolean',
			),
			array(
				'id'         => 'tf_dequeue_fa_css',
				'type'       => 'switcher',
				'title'      => __( 'Font Awesome CSS', 'testimonial-free' ),
				'text_on'    => __( 'Enqueued', 'testimonial-free' ),
				'text_off'   => __( 'Dequeued', 'testimonial-free' ),
				'text_width' => 99,
				'default'    => true,
				'sanitize'   => 'rest_sanitize_boolean',
			),
		),
	)
);

//
// Custom CSS section.
//
SPFTESTIMONIAL::createSection(
	$prefix,
	array(
		'name'   => 'custom_css_section',
		'title'  => __( 'Custom CSS & JS', 'testimonial-free' ),
		'icon'   => 'fa fa-file-code-o',
		'fields' => array(
			array(
				'id'       => 'custom_css',
				'type'     => 'code_editor',
				'sanitize' => 'wp_strip_all_tags',
				'settings' => array(
					'theme' => 'dracula',
					'mode'  => 'css',
				),
				'title'    => __( 'Custom CSS', 'testimonial-free' ),
			),
			array(
				'id'       => 'custom_js',
				'type'     => 'code_editor',
				'sanitize' => 'wp_strip_all_tags',
				'settings' => array(
					'theme' => 'dracula',
					'mode'  => 'javascript',
				),
				'title'    => __( 'Custom JS', 'testimonial-free' ),
			),
		),
	)
);
