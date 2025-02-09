<?php
/**
 * Real Testimonials form page.
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
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_code_opts = 'sp_tfree_form_upper_section';

//
// Form shortcode.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_code_opts,
	array(
		'title'           => __( 'How To Use', 'testimonial-free' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'normal',
		'enqueue_webfont' => false,
	)
);

$prefix_form_elements_opts = 'sp_tpro_form_elements_options';
//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_elements_opts = 'sp_tpro_form_elements_options';

//
// Form metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_elements_opts,
	array(
		'title'           => __( 'Testimonial Form Fields', 'testimonial-free' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'side',
		'enqueue_webfont' => false,
	)
);

//
// Form Editor section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_elements_opts,
	array(
		'fields' => array(
			array(
				'id'      => 'form_elements',
				'type'    => 'checkbox',
				'options' => array(
					'name'              => __( 'Full Name', 'testimonial-free' ),
					'email'             => __( 'E-mail Address', 'testimonial-free' ),
					'position'          => __( 'Designation', 'testimonial-free' ),
					'testimonial_title' => __( 'Testimonial Title', 'testimonial-free' ),
					'testimonial'       => __( 'Testimonial Content', 'testimonial-free' ),
					'image'             => __( 'Image', 'testimonial-free' ),
					'company'           => __( 'Company Name (Pro)', 'testimonial-free' ),
					'groups'            => __( 'Groups (Pro)', 'testimonial-free' ),
					'location'          => __( 'Location (Pro)', 'testimonial-free' ),
					'phone_mobile'      => __( 'Phone or Mobile (Pro)', 'testimonial-free' ),
					'website'           => __( 'Website (Pro)', 'testimonial-free' ),
					'video_url'         => __( 'Video Record/URL (Pro)', 'testimonial-free' ),
					'rating'            => __( 'Star Rating (Pro)', 'testimonial-free' ),
					'social_profile'    => __( 'Social Profile (Pro)', 'testimonial-free' ),
					'recaptcha'         => __( 'reCAPTCHA (Pro)', 'testimonial-free' ),
				),
				'default' => array( 'name', 'email', 'position', 'testimonial_title', 'testimonial', 'image' ),
			),
			array(
				'type'    => 'notice',
				'class'   => 'form-fields-notice',
				/* translators: 1: start link tag, 2: close tag. */
				'content' => sprintf( __( 'To access more fields, %1$sGet Pro Now!%2$s', 'testimonial-free' ), '<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1"><b>', '</b></a>' ),
			),
		),
	)
);

//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_code_opts = 'sp_tpro_form_code_options';

/**
 * Preview metabox.
 *
 * @param string $prefix The metabox main Key.
 * @return void
 */
SPFTESTIMONIAL::createMetabox(
	'sp_tpro_form_live_preview',
	array(
		'title'             => __( 'Live Preview', 'testimonial-free' ),
		'post_type'         => 'spt_testimonial_form',
		'show_restore'      => false,
		'sp_tpro_shortcode' => false,
		'context'           => 'normal',
	)
);
SPFTESTIMONIAL::createSection(
	'sp_tpro_form_live_preview',
	array(
		'fields' => array(
			array(
				'type' => 'preview',
			),
		),
	)
);

//
// Form shortcode.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_code_opts,
	array(
		'title'           => __( 'How To Use', 'testimonial-free' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'side',
		'enqueue_webfont' => false,
	)
);

//
// Testimonial Form Code section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_code_opts,
	array(
		'fields' => array(

			array(
				'id'        => 'form_shortcode',
				'type'      => 'shortcode',
				'shortcode' => 'form',
			),

		),
	)
);
//
// Metabox of the testimonial form generator.
// Set a unique slug-like ID.
//
$prefix_form_opts = 'sp_tpro_form_options';

//
// Form metabox.
//
SPFTESTIMONIAL::createMetabox(
	$prefix_form_opts,
	array(
		'title'           => __( 'Form Options', 'testimonial-free' ),
		'post_type'       => 'spt_testimonial_form',
		'context'         => 'normal',
		'enqueue_webfont' => false,
	)
);

//
// Form Editor section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Form Editor', 'testimonial-free' ),
		'icon'   => 'testimonial--icon sptfree-icon-form-editor',
		'fields' => array(
			array(
				'id'     => 'form_fields',
				'class'  => 'form_fields',
				'type'   => 'sortable',
				'fields' => array(
					array(
						'id'         => 'full_name',
						'type'       => 'accordion',
						'class'      => 'opened_accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Full Name', 'testimonial-free' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-free' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-free' ),
										'default' => __( 'Full Name', 'testimonial-free' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-free' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-free' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-free' ),
										'default' => __( 'What is your full name?', 'testimonial-free' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-free' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'name', true ),
					),
					array(
						'id'         => 'email_address',
						'type'       => 'accordion',
						// 'class'      => 'tfree_pro_only',
						'accordions' => array(
							array(
								'title'  => __( 'E-mail Address', 'testimonial-free' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-free' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-free' ),
										'default' => __( 'E-mail Address', 'testimonial-free' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-free' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-free' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-free' ),
										'default' => __( 'What is your e-mail address?', 'testimonial-free' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-free' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'email', true ),
					),
					array(
						'id'         => 'identity_position',
						'type'       => 'accordion',
						// 'class'      => 'tfree_pro_only',
						'accordions' => array(
							array(
								'title'  => __( 'Designation', 'testimonial-free' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-free' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-free' ),
										'default' => __( 'Designation', 'testimonial-free' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-free' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-free' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-free' ),
										'default' => __( 'What is your designation?', 'testimonial-free' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-free' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'position', true ),
					),
					array(
						'id'         => 'testimonial_title',
						'type'       => 'accordion',
						'accordions' => array(
							array(
								'title'  => __( 'Testimonial Title', 'testimonial-free' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-free' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-free' ),
										'default' => __( 'Testimonial Title', 'testimonial-free' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-free' ),
									),
									array(
										'id'         => 'title_length',
										'type'       => 'fieldset',
										'class'      => 'testimonial_text_limit title_limit',
										'title'      => __( 'Maximum Length', 'testimonial-free' ),
										'title_help' => __( 'Leave empty to skip limit', 'testimonial-free' ),
										'fields'     => array(
											array(
												'id'      => 'title_char_limit',
												'type'    => 'spinner',
												'class'   => 'character_limit',
												'default' => '50',
												'dependency' => array( 'title_length_type', '==', 'characters', true ),
											),
											array(
												'id'      => 'title_word_limit',
												'type'    => 'spinner',
												'class'   => 'word_limit',
												'unit'    => __( 'word', 'testimonial-free' ),
												'default' => '6',
												'dependency' => array( 'title_length_type', '==', 'words', true ),
											),
											array(
												'id'      => 'title_length_type',
												'type'    => 'select',
												'class'   => 'title-length_type',
												'options' => array(
													'characters' => __( 'Characters', 'testimonial-free' ),
													'words'      => __( 'Words', 'testimonial-free' ),
												),
												'default' => 'characters',
											),
										),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-free' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-free' ),
										'default' => __( 'A headline  or tagline for your testimonial.', 'testimonial-free' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-free' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'testimonial_title', true ),
					),
					array(
						'id'         => 'testimonial',
						'type'       => 'accordion',
						// 'class'      => 'tfree_pro_only',
						'accordions' => array(
							array(
								'title'  => __( 'Testimonial Content', 'testimonial-free' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-free' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-free' ),
										'default' => __( 'Testimonial Content', 'testimonial-free' ),
									),
									array(
										'id'    => 'placeholder',
										'type'  => 'text',
										'title' => __( 'Placeholder', 'testimonial-free' ),
									),
									array(
										'id'         => 'content_length',
										'type'       => 'fieldset',
										'class'      => 'testimonial_text_limit content_limit',
										'title'      => __( 'Maximum Length', 'testimonial-free' ),
										'title_help' => __( 'Leave empty to skip limit', 'testimonial-free' ),
										'fields'     => array(
											array(
												'id'      => 'content_char_limit',
												'type'    => 'spinner',
												'class'   => 'character_limit',
												'default' => '500',
												'dependency' => array( 'content_length_type', '==', 'characters', true ),
											),
											array(
												'id'      => 'content_word_limit',
												'type'    => 'spinner',
												'class'   => 'word_limit',
												'unit'    => __( 'word', 'testimonial-free' ),
												'default' => '80',
												'dependency' => array( 'content_length_type', '==', 'words', true ),
											),
											array(
												'id'      => 'content_length_type',
												'type'    => 'select',
												'class'   => 'content-length_type',
												'options' => array(
													'characters' => __( 'Characters', 'testimonial-free' ),
													'words'      => __( 'Words', 'testimonial-free' ),
												),
												'default' => 'characters',
											),
										),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-free' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-free' ),
										'default' => __( 'What do you think about us?', 'testimonial-free' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-free' ),
										'default' => true,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'testimonial', true ),
					),
					array(
						'id'         => 'featured_image',
						'type'       => 'accordion',
						// 'class'      => 'tfree_pro_only',
						'accordions' => array(
							array(
								'title'  => __( 'Image', 'testimonial-free' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-free' ),
										'desc'    => __( 'To hide this label, leave it empty.', 'testimonial-free' ),
										'default' => __( 'Photo', 'testimonial-free' ),
									),
									array(
										'id'    => 'before',
										'type'  => 'text',
										'title' => __( 'Before', 'testimonial-free' ),
									),
									array(
										'id'      => 'after',
										'type'    => 'text',
										'title'   => __( 'After', 'testimonial-free' ),
										'default' => __( 'Would you like to include photo?', 'testimonial-free' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-free' ),
										'default' => false,
									),
								),
							),
						),
						'dependency' => array( 'form_elements', 'any', 'image', true ),
					),
					array(
						'id'         => 'submit_btn',
						'type'       => 'accordion',
						// 'class'      => 'tfree_pro_only',
						'accordions' => array(
							array(
								'title'  => __( 'Submit Button', 'testimonial-free' ),
								'fields' => array(
									array(
										'id'      => 'label',
										'type'    => 'text',
										'title'   => __( 'Label', 'testimonial-free' ),
										'desc'    => __( 'Type submit button label.', 'testimonial-free' ),
										'default' => __( 'Submit Testimonial', 'testimonial-free' ),
									),
									array(
										'id'      => 'required',
										'type'    => 'checkbox',
										'title'   => __( 'Required', 'testimonial-free' ),
										'default' => true,
									),
								),
							),
						),
					),

				),
			),
			array(
				'type'    => 'notice',
				'class'   => 'tpro-form-notice form-editor-notice form_settings',
				'content' => sprintf(
					/* translators: 1: start link tag, 2: close link tag. */
					__( 'To create unlimited Testimonial Forms and enhance them with additional fields for collecting and displaying reviewer information to increase sales, %1$sUpgrade to Pro!%2$s', 'testimonial-free' ),
					'<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1"><b>',
					'</b></a>'
				),
			),
		),
	)
);

//
// Messages section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Labels & Messages', 'testimonial-free' ),
		'icon'   => 'testimonial--icon sptfree-icon-notification-messages',
		'fields' => array(
			array(
				'id'         => 'required_notice',
				'type'       => 'switcher',
				'title'      => __( 'Required Notice', 'testimonial-free' ),
				'subtitle'   => __( 'Display required notice at top of the form.', 'testimonial-free' ),
				'text_on'    => esc_html__( 'Enabled', 'testimonial-free' ),
				'text_off'   => esc_html__( 'Disabled', 'testimonial-free' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'         => 'notice_label',
				'type'       => 'text',
				'title'      => __( 'Notice Label', 'testimonial-free' ),
				'subtitle'   => __( 'Set a label for the required notice.', 'testimonial-free' ),
				'default'    => __( 'Red asterisk fields are required.', 'testimonial-free' ),
				'dependency' => array( 'required_notice', '==', 'true', true ),
			),
			array(
				'type'    => 'subheading',
				'content' => esc_html__( 'FORM ACTIONS', 'testimonial-free' ),
			),
			array(
				'id'         => 'ajax_form_submission',
				'type'       => 'switcher',
				'class'      => 'pro_switcher',
				'title'      => __( 'Ajax Form Submission', 'testimonial-free' ),
				'subtitle'   => __( 'Submit the form without reloading the page.', 'testimonial-free' ),
				'text_on'    => esc_html__( 'Enabled', 'testimonial-free' ),
				'text_off'   => esc_html__( 'Disabled', 'testimonial-free' ),
				'text_width' => 95,
				'default'    => true,
			),
			array(
				'id'       => 'tpro_redirect',
				'type'     => 'select',
				'title'    => __( 'Redirect', 'testimonial-free' ),
				'subtitle' => __( 'After successful submit, where the page will redirect to.', 'testimonial-free' ),
				'options'  => array(
					'same_page'  => __( 'Same page', 'testimonial-free' ),
					'to_a_page'  => __( 'To a page (Pro)', 'testimonial-free' ),
					'custom_url' => __( 'To a custom URL (Pro)', 'testimonial-free' ),
				),
				'default'  => 'same_page',
			),
			array(
				'id'         => 'successful_message',
				'type'       => 'text',
				'class'      => 'larger_text_input',
				'title'      => __( 'Successful Message', 'testimonial-free' ),
				'subtitle'   => __( 'Set a submission success message.', 'testimonial-free' ),
				'default'    => __( 'Thank you! Your testimonial is currently waiting to be approved.', 'testimonial-free' ),
				'dependency' => array( 'tpro_redirect', '==', 'same_page' ),
			),
			array(
				'id'       => 'error_message',
				'type'     => 'text',
				'title'    => __( 'Error Message', 'testimonial-free' ),
				'class'    => 'larger_text_input',
				'subtitle' => __( 'Set a submission error message.', 'testimonial-free' ),
				'default'  => __( 'We encountered an issue while processing your testimonial.', 'testimonial-free' ),
				// 'dependency'  => array( 'tpro_redirect', '==', 'same_page' ),
			),
			array(
				'id'         => 'tpro_message_position',
				'type'       => 'button_set',
				'title'      => __( 'Form Submission Message Position', 'testimonial-free' ),
				'subtitle'   => __( 'Set a form submission message position.', 'testimonial-free' ),
				'radio'      => true,
				'options'    => array(
					'top'    => __( 'Top', 'testimonial-free' ),
					'bottom' => __( 'Bottom', 'testimonial-free' ),
				),
				'default'    => 'bottom',
				'dependency' => array( 'tpro_redirect', '==', 'same_page' ),
			),
			array(
				'id'         => 'tpro_redirect_to_page',
				'type'       => 'select',
				'title'      => __( 'Page', 'testimonial-free' ),
				'subtitle'   => __( 'Select redirect page.', 'testimonial-free' ),
				'options'    => 'pages',
				'dependency' => array( 'tpro_redirect', '==', 'to_a_page' ),
			),
			array(
				'id'         => 'tpro_redirect_custom_url',
				'type'       => 'text',
				'title'      => __( 'Custom URL', 'testimonial-free' ),
				'subtitle'   => __( 'Type redirect custom url.', 'testimonial-free' ),
				'dependency' => array( 'tpro_redirect', '==', 'custom_url' ),
			),
			array(
				'type'    => 'subheading',
				'content' => esc_html__( 'FORM DISPLAY MODE', 'testimonial-free' ),
			),
			array(
				'id'       => 'tpro_form_display_mode',
				'type'     => 'button_set',
				'title'    => __( 'Display Mode', 'testimonial-free' ),
				'subtitle' => __( 'Choose a form display mode.', 'testimonial-free' ),
				'options'  => array(
					'on_page' => __( 'On Page', 'testimonial-free' ),
					'popup'   => array(
						'option_name' => __( 'Popup', 'testimonial-free' ),
						'pro_only'    => true,
					),
				),
				'default'  => 'on_page',
			),
			array(
				'type'    => 'notice',
				'class'   => 'tpro-form-notice form-editor-notice form_settings',
				'content' => sprintf(
					/* translators: 1: start link tag, 2: close tag. */
					__( 'To open the Testimonial Form in popup or lightbox, %1$sUpgrade to Pro!%2$s', 'testimonial-free' ),
					'<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1"><b>',
					'</b></a>'
				),
			),
		),
	)
);

$admin_email = get_option( 'admin_email' );
//
// Status & Notifications section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Status & Notifications', 'testimonial-free' ),
		'icon'   => 'testimonial--icon sptfree-icon-notifications',
		'fields' => array(
			array(
				'id'       => 'testimonial_approval_status',
				'type'     => 'select',
				'class'    => 'testimonial_approval_status',
				'title'    => __( 'Testimonial Status', 'testimonial-free' ),
				'subtitle' => __( 'Select testimonial approval status for the front-end form submission.', 'testimonial-free' ),
				'desc'     => sprintf(
					/* translators: 1: start link tag, 2: close tag. */
					__( '%1$sTo access this feature, %2$sUpgrade to Pro!%3$s', 'testimonial-free' ),
					'<div class="testimonial-approval-status-notice">',
					'<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1"><b>',
					'</b></a></div>'
				),
				'options'  => array(
					'pending'              => esc_html__( 'Pending Review', 'testimonial-free' ),
					'private'              => esc_html__( 'Private', 'testimonial-free' ),
					'draft'                => esc_html__( 'Draft', 'testimonial-free' ),
					'publish'              => esc_html__( 'Auto Publish', 'testimonial-free' ),
					'based_on_rating_star' => esc_html__( 'Auto Publish Based on Star Rating', 'testimonial-free' ),
				),
				'default'  => 'pending',
			),
			array(
				'id'         => 'tpro_auto_publish_rating',
				'type'       => 'checkbox',
				'only_pro'   => true,
				'class'      => 'tpro-rating-star pro_only_field',
				'title'      => __( 'Based on Star Rating (Pro)', 'testimonial-free' ),
				'subtitle'   => __( 'Check which star-rated testimonials will be automatically published ', 'testimonial-free' ),
				'options'    => array(
					'five_star'  => '  ',
					'four_star'  => '  ',
					'three_star' => '  ',
					'two_star'   => '  ',
					'one_star'   => '  ',
				),
				'default'    => array( 'five_star', 'four_star' ),
				'dependency' => array(
					'testimonial_approval_status',
					'==',
					'based_on_rating_star',
					true,
				),
			),

			array(
				'type'  => 'tabbed',
				'class' => 'sp_tabbed_horizontal',
				'tabs'  => array(
					array(
						'title'  => __( 'ADMIN NOTIFICATION', 'testimonial-free' ),
						'icon'   => '<i class="testimonial--icon sptfree-icon-admin-notification"></i>',
						'fields' => array(
							array(
								'type'    => 'notice',
								'class'   => 'tpro-form-notice form_settings',
								'content' => sprintf(
									/* translators: 1: start link tag, 2: close tag. */
									__( 'To receive admin notifications when a testimonial is submitted by a reviewer, %1$sUpgrade to Pro!%2$s', 'testimonial-free' ),
									'<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'submission_email_notification',
								'type'       => 'switcher',
								'only_pro'   => true,
								'class'      => 'pro_only_field form_settings',
								'title'      => __( 'Admin Notification', 'testimonial-free' ),
								'text_on'    => esc_html__( 'Enabled', 'testimonial-free' ),
								'text_off'   => esc_html__( 'Disabled', 'testimonial-free' ),
								'text_width' => 95,
								'default'    => true,
							),
							array(
								'id'         => 'submission_email_to',
								'type'       => 'text',
								'only_pro'   => true,
								'class'      => 'larger_text_input pro_only_field form_settings',
								'title'      => __( 'To', 'testimonial-free' ),
								'default'    => $admin_email,
								'dependency' => array( 'submission_email_notification', '==', 'true' ),
							),
							array(
								'id'         => 'submission_email_from',
								'type'       => 'text',
								'only_pro'   => true,
								'class'      => 'larger_text_input pro_only_field form_settings',
								'title'      => __( 'From', 'testimonial-free' ),
								'default'    => '{site_title} {' . $admin_email . '}',
								'dependency' => array( 'submission_email_notification', '==', 'true' ),
							),
							array(
								'id'         => 'submission_email_subject',
								'type'       => 'text',
								'only_pro'   => true,
								'class'      => 'larger_text_input pro_only_field form_settings',
								'title'      => __( 'Subject', 'testimonial-free' ),
								'default'    => 'A New Testimonial is Pending for {site_title}!',
								'dependency' => array( 'submission_email_notification', '==', 'true' ),
							),
							array(
								'id'         => 'submission_email_body',
								'type'       => 'wp_editor',
								'only_pro'   => true,
								'class'      => 'email_body_area pro_only_field form_settings',
								'title'      => __( 'Message Body', 'testimonial-free' ),
								'default'    => '<h2 style="text-align: center;font-size: 24px;">New Testimonial!</h2>
								Hi There,
								A new testimonial has been submitted to your website. Following is the reviewer\'s information.
								Name: {name}
								Email: {email}
								Testimonial Content: {testimonial_text}
								Rating: {rating}
								Please go <a href="' . esc_url( admin_url( 'edit.php?post_type=spt_testimonial' ) ) . '">admin dashboard</a> to review it and publish.
								Thank you!',
								'desc'       => '
									<div class="template-heading">
									Available Tags for Subject and Message for Email Template
									</div>
									<div class="template-content">
									{name} {email} {position} {company_name} {location} {phone} {website} {video_url} {testimonial_title} {testimonial_text} {groups} {rating} {site_title}
									</div>',
								// 'desc'       => '
								// Enter the text that will be sent as notification email for pending testimonial. HTML is accepted. Available template tags are:<br>
								// {name} - The reviewer\'s full name.<br>
								// {email} - The reviewer\'s email address.<br>
								// {position} - The reviewer\'s position.<br>
								// {company_name} - The reviewer\'s company name.<br>
								// {location} - The reviewer\'s location address.<br>
								// {phone} - The reviewer\'s phone number.<br>
								// {website} - The reviewer\'s company website URL.<br>
								// {video_url} - The reviewer\'s video URL.<br>
								// {testimonial_title} - Testimonial title.<br>
								// {testimonial_text} - Testimonial content.<br>
								// {groups} - Testimonial groups.<br>
								// {rating} - Testimonial rating.',
								'dependency' => array( 'submission_email_notification', '==', 'true' ),
							),

						),
					),
					array(
						'title'  => __( 'AWAITING NOTIFICATION', 'testimonial-free' ),
						'icon'   => '<i class="testimonial--icon sptfree-icon-awaiting-notification"></i>',
						'fields' => array(
							array(
								'type'    => 'notice',
								'class'   => 'tpro-form-notice form_settings',
								'content' => sprintf(
									/* translators: 1: start link tag, 2: close tag. */
									__( 'To send an awaiting message when a reviewer submits a testimonial, %1$sUpgrade to Pro!%2$s', 'testimonial-free' ),
									'<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'reviewer_awaiting_notification',
								'type'       => 'switcher',
								'only_pro'   => true,
								'class'      => 'pro_only_field form_settings',
								'title'      => __( 'Reviewer Awaiting Notification', 'testimonial-free' ),
								'text_on'    => esc_html__( 'Enabled', 'testimonial-free' ),
								'text_off'   => esc_html__( 'Disabled', 'testimonial-free' ),
								'text_width' => 95,
								'default'    => true,
							),
							array(
								'id'       => 'reviewer_awaiting_to',
								'type'     => 'text',
								'only_pro' => true,
								'class'    => 'larger_text_input pro_only_field form_settings',
								'title'    => __( 'To', 'testimonial-free' ),
								'default'  => '{email}',
							),
							array(
								'id'       => 'reviewer_awaiting_from',
								'type'     => 'text',
								'only_pro' => true,
								'class'    => 'larger_text_input pro_only_field form_settings',
								'title'    => __( 'From', 'testimonial-free' ),
								'default'  => '{site_title} {' . $admin_email . '}',
							),
							array(
								'id'       => 'reviewer_awaiting_email_subject',
								'type'     => 'text',
								'only_pro' => true,
								'class'    => 'larger_text_input pro_only_field form_settings',
								'title'    => __( 'Subject', 'testimonial-free' ),
								'default'  => 'Your Testimonial has been Received for {site_title}!',
							),
							array(
								'id'       => 'reviewer_awaiting_email_body',
								'type'     => 'wp_editor',
								'only_pro' => true,
								'class'    => 'email_body_area pro_only_field form_settings',
								'title'    => __( 'Message Body', 'testimonial-free' ),
								'default'  => '<h2 style="text-align:center;font-size:24px;">Your Testimonial Received!</h2>
								Hi {name},
								Thank you for submitting your testimonial. Your testimonial has been received and is awaiting approval from our website administrator.
								Thank you again for helping us improve our products and services.',
								'desc'     => '<div class="template-heading">Available Tags for Subject and Message for Email Template</div>
									<div class="template-content">
									{name} {email} {position} {company_name} {location} {phone} {website} {video_url} {testimonial_title} {testimonial_text} {groups} {rating} {site_title}
									</div>',
							),
						),
					),
					array(
						'title'  => __( 'APPROVAL NOTIFICATION', 'testimonial-free' ),
						'icon'   => '<i class="testimonial--icon sptfree-icon-approval-notification"></i>',
						'fields' => array(
							array(
								'type'    => 'notice',
								'class'   => 'tpro-form-notice form_settings',
								'content' => sprintf(
									/* translators: 1: start link tag, 2: close tag. */
									__( 'To send an approval email notification when a submitted testimonial is approved or published, %1$sUpgrade to Pro!%2$s', 'testimonial-free' ),
									'<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1"><b>',
									'</b></a>'
								),
							),
							array(
								'id'         => 'approval_notification',
								'type'       => 'switcher',
								'only_pro'   => true,
								'class'      => 'pro_only_field form_settings',
								'title'      => __( 'Testimonial Approval Notification', 'testimonial-free' ),
								'text_on'    => esc_html__( 'Enabled', 'testimonial-free' ),
								'text_off'   => esc_html__( 'Disabled', 'testimonial-free' ),
								'text_width' => 95,
								'default'    => true,
							),
							array(
								'id'         => 'approval_email_to',
								'type'       => 'text',
								'only_pro'   => true,
								'class'      => 'larger_text_input pro_only_field form_settings',
								'title'      => __( 'To', 'testimonial-free' ),
								'default'    => '{email}',
								'dependency' => array( 'approval_notification', '==', 'true' ),
							),
							array(
								'id'         => 'approval_email_from',
								'type'       => 'text',
								'only_pro'   => true,
								'class'      => 'larger_text_input pro_only_field form_settings',
								'title'      => __( 'From', 'testimonial-free' ),
								'default'    => '{site_title} {' . $admin_email . '}',
								'dependency' => array( 'approval_notification', '==', 'true' ),
							),
							array(
								'id'         => 'approval_email_subject',
								'type'       => 'text',
								'only_pro'   => true,
								'class'      => 'larger_text_input pro_only_field form_settings',
								'title'      => __( 'Subject', 'testimonial-free' ),
								'default'    => 'Congratulations! Your Testimonial has been Approved!',
								'dependency' => array( 'approval_notification', '==', 'true' ),
							),
							array(
								'id'         => 'approval_email_body',
								'type'       => 'wp_editor',
								'only_pro'   => true,
								'class'      => 'email_body_area pro_only_field form_settings',
								'title'      => __( 'Message Body', 'testimonial-free' ),
								'default'    => '<h2 style="text-align:center;font-size:24px;">Congrats, Your Testimonial Approved!</h2>
								Hi {name},
								Congratulations! We are delighted to inform you that your testimonial has been approved. We appreciate your input and value your loyalty. You can now view it on the website. 
								Thank you!',
								'desc'       => '<div class="template-heading">Available Tags for Subject and Message for Email Template</div>
									<div class="template-content">
									{name} {email} {position} {company_name} {location} {phone} {website} {video_url} {testimonial_title} {testimonial_text} {groups} {rating} {site_title}
									</div>',
								'dependency' => array( 'approval_notification', '==', 'true' ),
							),
						),
					),
				),
			),
		),
	)
);

//
// Form Styles section.
//
SPFTESTIMONIAL::createSection(
	$prefix_form_opts,
	array(
		'title'  => __( 'Form Styles', 'testimonial-free' ),
		'icon'   => 'testimonial--icon sptfree-icon-form-style',
		'fields' => array(
			array(
				'id'       => 'label_position',
				'type'     => 'image_select',
				'class'    => 'label_position',
				'title'    => __( 'Form Layout', 'testimonial-free' ),
				'subtitle' => __( 'Choose a testimonial form layout.', 'testimonial-free' ),
				'options'  => array(
					'top'  => array(
						'image' => SP_TFREE_URL . '/Admin/Views/Framework/assets/images/form_style_one.svg',
						'name'  => __( 'Style One', 'testimonial-free' ),
					),
					'left' => array(
						'image' => SP_TFREE_URL . '/Admin/Views/Framework/assets/images/form_style_two.svg',
						'name'  => __( 'Style Two', 'testimonial-free' ),
						'class' => 'pro-feature',
					),
				),
				'default'  => 'top',
			),
			array(
				'id'              => 'testimonial_form_width',
				'class'           => 'form_settings testimonial-slide-margin',
				'type'            => 'spacing',
				'title'           => __( 'Form Width', 'testimonial-free' ),
				'subtitle'        => __( 'Set a custom width for the testimonial form.', 'testimonial-free' ),
				'right'           => false,
				'top'             => true,
				'left'            => false,
				'bottom'          => false,
				'top_placeholder' => __( 'width', 'testimonial-free' ),
				'top_text'        => __( 'Width', 'testimonial-free' ),
				'top_icon'        => '<i class="fa fa-arrows-h"></i>',
				'unit'            => true,
				'units'           => array( 'px' ),
				'default'         => array(
					'top' => '680',
				),
			),
			array(
				'id'       => 'label_color',
				'type'     => 'color',
				'title'    => __( 'Label Color', 'testimonial-free' ),
				'subtitle' => __( 'Set color for the field label.', 'testimonial-free' ),
				'default'  => '#444444',
			),
			array(
				'id'               => 'form_input_field_styles',
				'type'             => 'border',
				'title'            => __( 'Input Field', 'testimonial-free' ),
				'subtitle'         => __( 'Set input field style.', 'testimonial-free' ),
				'all'              => true,
				'radius'           => true,
				'background_color' => true,
				'default'          => array(
					'all'              => '1',
					'style'            => 'solid',
					'color'            => '#444444',
					'background_color' => '#FFFFFF',
					'radius'           => '4',
				),
				'units'            => array(
					'px',
					'%',
				),
			),
			array(
				'id'       => 'form_background_color',
				'type'     => 'color',
				'title'    => __( 'Form Background', 'testimonial-free' ),
				'subtitle' => __( 'Set testimonial form background style.', 'testimonial-free' ),
				'default'  => '#FFFFFF',
			),
			array(
				'id'       => 'testimonial_form_border',
				'type'     => 'border',
				'title'    => __( 'Border', 'testimonial-free' ),
				'subtitle' => __( 'Set border for the testimonial form.', 'testimonial-free' ),
				'all'      => true,
				'radius'   => true,
				'default'  => array(
					'all'    => '1',
					'style'  => 'solid',
					'color'  => '#e3e3e3',
					'radius' => '6',
					'unit'   => '%',
				),
				'units'    => array(
					'px',
					'%',
				),
			),

			array(
				'id'       => 'record_button_color',
				'type'     => 'color_group',
				'only_pro' => true,
				'class'    => 'pro_only_field form_settings',
				'title'    => __( 'Record Button Color', 'testimonial-free' ),
				'subtitle' => __( 'Set color for the record button.', 'testimonial-free' ),
				'options'  => array(
					'color'            => esc_html__( 'Color', 'testimonial-free' ),
					'hover-color'      => esc_html__( 'Hover Color', 'testimonial-free' ),
					'background'       => esc_html__( 'Background', 'testimonial-free' ),
					'hover-background' => esc_html__( 'Hover Background', 'testimonial-free' ),
				),
				'default'  => array(
					'color'            => '#005BDF',
					'hover-color'      => '#005BDF',
					'background'       => 'rgba(0, 91, 223, 0.12)',
					'hover-background' => 'rgba(0, 91, 223, 0.18)',
				),
			),
			array(
				'id'       => 'form_rating_color',
				'type'     => 'color',
				'only_pro' => true,
				'class'    => 'pro_only_field form_settings',
				'title'    => __( 'Rating Color', 'testimonial-free' ),
				'subtitle' => __( 'Set color for the star rating.', 'testimonial-free' ),
				'default'  => '#d4d4d4',
			),
			array(
				'id'       => 'submit_button_color',
				'type'     => 'color_group',
				'only_pro' => true,
				'class'    => 'pro_only_field form_settings',
				'title'    => __( 'Submit Button Color', 'testimonial-free' ),
				'subtitle' => __( 'Set color for the submit button.', 'testimonial-free' ),
				'options'  => array(
					'color'            => esc_html__( 'Color', 'testimonial-free' ),
					'hover-color'      => esc_html__( 'Hover Color', 'testimonial-free' ),
					'background'       => esc_html__( 'Background', 'testimonial-free' ),
					'hover-background' => esc_html__( 'Hover Background', 'testimonial-free' ),
				),
				'default'  => array(
					'color'            => '#ffffff',
					'hover-color'      => '#ffffff',
					'background'       => '#005BDF',
					'hover-background' => '#005BDF',
				),
			),
			array(
				'id'       => 'form_shadow_type',
				'type'     => 'button_set',
				'only_pro' => true,
				'class'    => 'pro_only_field form_settings',
				'title'    => __( 'Box-Shadow', 'testimonial-free' ),
				'subtitle' => __( 'Choose box-shadow type for the testimonial form.', 'testimonial-free' ),
				'options'  => array(
					'inset'  => __( 'Inset', 'testimonial-free' ),
					'outset' => __( 'Outset', 'testimonial-free' ),
					'none'   => __( 'None', 'testimonial-free' ),
				),
				'default'  => 'none',
			),
			array(
				'id'         => 'testimonial_form_box_shadow_properties',
				'type'       => 'box_shadow',
				'only_pro'   => true,
				'class'      => 'pro_only_field form_settings',
				'title'      => __( 'Box-Shadow Values', 'testimonial-free' ),
				'subtitle'   => __( 'Set box-shadow property values for the testimonial form.', 'testimonial-free' ),
				'default'    => array(
					'horizontal' => '0',
					'vertical'   => '0',
					'blur'       => '6',
					'spread'     => '0',
					'color'      => 'rgba(10,10,10,0.1)',
				),
				'dependency' => array( 'form_shadow_type', '!=', 'none', true ),
			),
			array(
				'id'       => 'form_alignment',
				'type'     => 'button_set',
				'only_pro' => true,
				'class'    => 'button_set_smaller pro_only_field form_settings',
				'title'    => __( 'Form Alignment', 'testimonial-free' ),
				'subtitle' => __( 'Set alignment for the form.', 'testimonial-free' ),
				'options'  => array(
					'left'   => '<i class="fa fa-align-left" title="left"></i>',
					'center' => '<i class="fa fa-align-center" title="center"></i>',
					'right'  => '<i class="fa fa-align-right" title="right"></i>',
				),
				'default'  => 'center',
			),
			array(
				'id'          => 'form_inner_padding',
				'type'        => 'spacing',
				'only_pro'    => true,
				'class'       => 'testimonial_inner_padding pro_only_field form_settings',
				'title'       => __( 'Padding', 'testimonial-free' ),
				'subtitle'    => __( 'Set padding for testimonial form.', 'testimonial-free' ),
				'default'     => array(
					'top'    => '32',
					'right'  => '32',
					'bottom' => '32',
					'left'   => '32',
				),
				'top_text'    => __( 'Top', 'testimonial-free' ),
				'right_text'  => __( 'Right', 'testimonial-free' ),
				'bottom_text' => __( 'Bottom', 'testimonial-free' ),
				'left_text'   => __( 'Left', 'testimonial-free' ),
				'unit'        => true,
				'units'       => array( 'px' ),
			),
		),
	)
);
