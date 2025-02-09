<?php
/**
 * Testimonial submit form.
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

use ShapedPlugin\TestimonialPro\Frontend\Helper;

if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['action'] ) && 'testimonial_form' . $form_id === $_POST['action'] ) {
	$pid   = false;
	$nonce = isset( $_POST['testimonial_form_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['testimonial_form_nonce'] ) ) : '';
	if ( wp_verify_nonce( $nonce, 'testimonial_form' ) ) {
		$tpro_client_name                    = isset( $_POST['tpro_client_name'] ) ? wp_strip_all_tags( wp_unslash( $_POST['tpro_client_name'] ) ) : '';
		$tpro_client_email                   = isset( $_POST['tpro_client_email'] ) ? sanitize_email( wp_unslash( $_POST['tpro_client_email'] ) ) : '';
		$tpro_client_designation             = isset( $_POST['tpro_client_designation'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_designation'] ) ) : '';
		$tpro_company_name                   = isset( $_POST['tpro_client_company_name'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_company_name'] ) ) : '';
		$tpro_location                       = isset( $_POST['tpro_client_location'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_client_location'] ) ) : '';
		$tpro_phone                          = isset( $_POST['tpro_client_phone'] ) ? preg_replace( '/[^0-9+-]/', '', sanitize_text_field( wp_unslash( $_POST['tpro_client_phone'] ) ) ) : '';
		$tpro_website                        = isset( $_POST['tpro_client_website'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_client_website'] ) ) ) : '';
		$tpro_video_url                      = isset( $_POST['tpro_client_video_url'] ) ? esc_url( sanitize_text_field( wp_unslash( $_POST['tpro_client_video_url'] ) ) ) : '';
		$tpro_client_testimonial_cat         = isset( $_POST['tpro_client_testimonial_cat'] ) ? wp_kses_post_deep( wp_unslash( $_POST['tpro_client_testimonial_cat'] ) ) : '';
		$tpro_testimonial_title              = isset( $_POST['tpro_testimonial_title'] ) ? sanitize_text_field( wp_unslash( $_POST['tpro_testimonial_title'] ) ) : '';
		$tpro_testimonial_text               = isset( $_POST['tpro_client_testimonial'] ) ? sanitize_textarea_field( wp_unslash( $_POST['tpro_client_testimonial'] ) ) : '';
		$tpro_rating_star                    = isset( $_POST['tpro_client_rating'] ) ? sanitize_key( $_POST['tpro_client_rating'] ) : '';
		$tpro_client_video_upload            = isset( $_POST['tpro_client_video_upload'] ) ? sanitize_key( $_POST['tpro_client_video_upload'] ) : '';
		$tpro_social_profiles                = isset( $_POST['tpro_social_profiles'] ) ? wp_unslash( $_POST['tpro_social_profiles'] ) : '';
		$tpro_client_checkbox                = isset( $_POST['tpro_client_checkbox'] ) && $_POST['tpro_client_checkbox'] ? '1' : '0';
		$tpro_auto_publish_ratings           = isset( $form_data['tpro_auto_publish_rating'] ) ? $form_data['tpro_auto_publish_rating'] : '';
		$tpro_admin_email                    = isset( $form_data['submission_email_to'] ) ? $form_data['submission_email_to'] : '';
		$tpro_reviewer_awaiting_notification = isset( $form_data['reviewer_awaiting_notification'] ) ? $form_data['reviewer_awaiting_notification'] : '';
		$tpro_approval_notification          = isset( $form_data['approval_notification'] ) ? $form_data['approval_notification'] : '';
		$tpro_admin_email_from               = isset( $form_data['submission_email_from'] ) ? $form_data['submission_email_from'] : '';
		$tpro_post_status                    = isset( $form_data['testimonial_approval_status'] ) ? $form_data['testimonial_approval_status'] : 'pending';
		if ( in_array( $tpro_post_status, array( 'based_on_rating_star', 'publish' ), true ) ) {
			$tpro_post_status = 'pending';
		}
		// ADD THE FORM INPUT TO $testimonial_form ARRAY.
		$testimonial_form = array(
			'post_title'   => $tpro_testimonial_title,
			'post_content' => $tpro_testimonial_text,
			'post_status'  => $tpro_post_status,
			'post_type'    => 'spt_testimonial',
			'meta_input'   => array(
				'sp_tpro_meta_options' => array(
					'tpro_name'            => $tpro_client_name,
					'tpro_email'           => $tpro_client_email,
					'tpro_designation'     => $tpro_client_designation,
					'tpro_company_name'    => $tpro_company_name,
					'tpro_location'        => $tpro_location,
					'tpro_phone'           => $tpro_phone,
					'tpro_website'         => $tpro_website,
					'tpro_video_url'       => $tpro_video_url,
					'tpro_rating'          => $tpro_rating_star,
					'tpro_social_profiles' => $tpro_social_profiles,
					'tpro_client_checkbox' => $tpro_client_checkbox,
					'tpro_form_id'         => $form_id,
				),
			),
		);

		$tpro_redirect  = $form_data['tpro_redirect'];
		$response_data2 = false;

		// Save The Testimonial.
		$pid            = wp_insert_post( $testimonial_form );
		$validation_msg = '';
		if ( $pid ) {
			wp_set_post_terms( $pid, $tpro_client_testimonial_cat, 'testimonial_cat' );
			// Thanks message.

			$validation_msg .= $form_data['successful_message'];
			self::tpro_redirect( get_page_link() . '#submit' );
		}

		// Client Image and video.
		if ( ! function_exists( 'wp_generate_attachment_metadata' ) || class_exists( 'Astra_Sites_Importer' ) || ! function_exists( 'media_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}

		if ( $pid ) {
			if ( $_FILES ) {
				foreach ( $_FILES as $file => $array ) {
					if ( UPLOAD_ERR_OK !== $_FILES[ $file ]['error'] ) {
					} else {
						$attach_id = media_handle_upload( $file, $pid );
						if ( ! is_wp_error( $attach_id ) && $attach_id > 0 ) {
							// Set post image.
							if ( 'video/mp4' === $array['type'] || 'video/webm' === $array['type'] || 'video/x-matroska' === $array['type'] ) {
								$testimonial_data                   = get_post_meta( $pid, 'sp_tpro_meta_options', true );
								$testimonial_data['tpro_video_url'] = wp_get_attachment_url( $attach_id );
								update_post_meta( $pid, 'sp_tpro_meta_options', $testimonial_data );
							} else {
								update_post_meta( $pid, '_thumbnail_id', $attach_id );
							}
						}
					}
				}
			}
		}
	} else {
		wp_die( esc_html__( 'Our site is protected!', 'testimonial-free' ) );
	}
	$_POST = array();
} // END THE IF STATEMENT THAT STARTED THE WHOLE FORM.
