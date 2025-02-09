<?php
/**
 * Export Import class.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.5
 *
 * @package    Testimonial_free
 * @subpackage Testimonial_free/includes
 */

namespace ShapedPlugin\TestimonialFree\Includes;

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Import Export
 */
class Import_Export {
	/**
	 * Export
	 *
	 * @param mixed  $shortcode_ids Export testimonials and shortcode ids.
	 * @param  mixed  $text_ids Export testimonials.
	 * @param string $file_type Export type.
	 *
	 * @return object
	 */
	public function export( $shortcode_ids, $text_ids, $file_type = 'json' ) {
		$export = array();
		if ( ! empty( $shortcode_ids ) ) {

			$post_type   = 'spt_testimonial';
			$post_status = array( 'inherit', 'publish' );
			if ( 'all_spt_shortcodes' === $shortcode_ids || 'select_shortcodes' === $text_ids ) {
				$post_type   = 'spt_shortcodes';
				$post_status = array( 'inherit', 'publish' );
			}

			$post_in    = 'select_forms' === $text_ids || 'select_shortcodes' === $text_ids ? $shortcode_ids : '';
			$args       = array(
				'post_type'        => $post_type,
				'post_status'      => array( 'inherit', 'publish' ),
				'orderby'          => 'modified',
				'suppress_filters' => 1, // wpml, ignore language filter.
				'posts_per_page'   => -1,
				'post__in'         => $post_in,
			);
			$shortcodes = get_posts( $args );
			if ( ! empty( $shortcodes ) ) {
				foreach ( $shortcodes as $shortcode ) {
					if ( 'all_testimonial' !== $shortcode_ids ) {
						$shortcode_export = array(
							'title'       => $shortcode->post_title,
							'original_id' => $shortcode->ID,
							'spt_post'    => $post_type,
							'meta'        => array(),
						);
					}
					if ( 'all_testimonial' === $shortcode_ids ) {
						$terms            = get_the_terms( $shortcode->ID, 'testimonial_cat' );
						$shortcode_export = array(
							'title'           => $shortcode->post_title,
							'post_date'       => $shortcode->post_date,
							'original_id'     => $shortcode->ID,
							'content'         => 'csv_file' === $file_type ? $this->filter_description_field( $shortcode->post_content ) : $shortcode->post_content,
							'image'           => get_the_post_thumbnail_url( $shortcode->ID, 'single-post-thumbnail' ),
							'category'        => $terms,
							'all_testimonial' => 'all_testimonial',
							'spt_post'        => $post_type,
							'meta'            => array(),
						);
					}

					foreach ( get_post_meta( $shortcode->ID ) as $metakey => $value ) {
						$shortcode_export['meta'][ $metakey ] = $value[0];
					}

					$export['shortcode'][] = $shortcode_export;

					unset( $shortcode_export );
				}
				$export['metadata'] = array(
					'version' => SP_TFREE_VERSION,
					'date'    => gmdate( 'Y/m/d' ),
				);
			}
			return $export;
		}
	}

	/**
	 * Export Real Testimonials by ajax.
	 *
	 * @return void
	 */
	public function export_shortcodes() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'spftestimonial_options_nonce' ) ) {
			wp_send_json_error(
				array( 'message' => __( 'Nonce verification failed!', 'testimonial-free' ) ),
				401
			);
		}

		$file_type     = isset( $_POST['file_type'] ) ? sanitize_text_field( wp_unslash( $_POST['file_type'] ) ) : 'json';
		$shortcode_ids = '';
		if ( isset( $_POST['lcp_ids'] ) ) {
			$shortcode_ids = is_array( $_POST['lcp_ids'] ) ? wp_unslash( array_map( 'absint', $_POST['lcp_ids'] ) ) : sanitize_text_field( wp_unslash( $_POST['lcp_ids'] ) );
		}

		$text_ids = '';
		if ( isset( $_POST['text_ids'] ) ) {
			$text_ids = is_array( $_POST['text_ids'] ) ? wp_unslash( array_map( 'absint', $_POST['text_ids'] ) ) : sanitize_text_field( wp_unslash( $_POST['text_ids'] ) );
		}

		$export = $this->export( $shortcode_ids, $text_ids, $file_type );
		if ( 'csv_file' === $file_type ) {
			foreach ( $export['shortcode'] as $key => $value ) {
				$export['shortcode'][ $key ]['meta']['sp_tpro_meta_options'] = isset( $export['shortcode'][ $key ]['meta']['sp_tpro_meta_options'] ) ? unserialize( $export['shortcode'][ $key ]['meta']['sp_tpro_meta_options'] ) : '';
			}
		}

		if ( is_wp_error( $export ) ) {
			wp_send_json_error(
				array(
					'message' => $export->get_error_message(),
				),
				400
			);
		}

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
            // @codingStandardsIgnoreLine
            echo wp_json_encode($export, JSON_PRETTY_PRINT);
			die;
		}

		wp_send_json( $export, 200 );
	}


	/**
	 * Get page by title
	 *
	 * @param string $page_title Page title.
	 * @param string $output Optional.
	 * @param string $post_type Post type.
	 * @return obj.
	 */
	public function sp_testimonial_get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
		global $wpdb;
		$sql  = $wpdb->prepare(
			"
			SELECT ID
			FROM $wpdb->posts
			WHERE post_title = %s
			AND post_type = %s
		",
			$page_title,
			$post_type
		);
		$page = $wpdb->get_var( $sql );
		if ( $page ) {
			return get_post( $page, $output );
		}
		return null;
	}

	/**
	 * Filter description field to export.
	 * Convert newlines to '\n'.
	 *
	 * @param string $description Description text to filter.
	 *
	 * @return string
	 */
	protected function filter_description_field( $description ) {
		$description = wpautop( $description );
		$description = str_replace( array( "\n", "\r" ), ' ', $description );
		$description = str_replace( array( '"' ), '\\dq', $description );
		return $description;
	}

	/**
	 * Insert an attachment from an URL address.
	 *
	 * @param  string $url remote url.
	 * @param  int    $parent_post_id parent post id.
	 * @return int    Attachment ID
	 */
	public function insert_attachment_from_url( $url, $parent_post_id = null ) {

		if ( ! class_exists( 'WP_Http' ) ) {
			include_once ABSPATH . WPINC . '/class-http.php';
		}
		$attachment_title = sanitize_file_name( pathinfo( $url, PATHINFO_FILENAME ) );
		// Does the attachment already exist ?
		if ( post_exists( $attachment_title, '', '', 'attachment' ) ) {
			$attachment = $this->sp_testimonial_get_page_by_title( $attachment_title, OBJECT, 'attachment' );
			if ( ! empty( $attachment ) ) {
				$attachment_id = $attachment->ID;
				return $attachment_id;
			}
		}
		$http     = new \WP_Http();
		$response = $http->request( $url );
		if ( is_wp_error( $response ) ) {
			return false;
		}
		$upload = wp_upload_bits( basename( $url ), null, $response['body'] );
		if ( ! empty( $upload['error'] ) ) {
			return false;
		}

		$file_path     = $upload['file'];
		$file_name     = basename( $file_path );
		$file_type     = wp_check_filetype( $file_name, null );
		$wp_upload_dir = wp_upload_dir();

		$post_info = array(
			'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
			'post_mime_type' => $file_type['type'],
			'post_title'     => $attachment_title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		// Create the attachment.
		$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

		// Include image.php.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Define attachment metadata.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

		// Assign metadata to attachment.
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return $attach_id;
	}

	/**
	 * Parse the description field's value
	 *
	 * @param string $description field value.
	 *
	 * @return string
	 */
	public function parse_description_field( $description ) {
		$description = str_replace( '\\dq', '"', $description );
		return $description;
	}

	/**
	 * Import Testimonial ans shortcode.
	 *
	 * @param  array $shortcodes Import Testimonials shortcode array.
	 * @param  array $file_type file type.
	 *
	 * @throws \Exception Error message.
	 * @return object
	 */
	public function import( $shortcodes, $file_type = 'json' ) {
		$errors        = array();
		$spt_post_type = 'spt_testimonial';
		foreach ( $shortcodes as $index => $shortcode ) {
			$errors[ $index ] = array();
			$new_shortcode_id = 0;

			$spt_post_type = isset( $shortcode['spt_post'] ) ? $shortcode['spt_post'] : '';
			try {
				$content = isset( $shortcode['content'] ) ? $shortcode['content'] : '';
				if ( 'csv' === $file_type ) {
					$content = $this->parse_description_field( $content );
				}
				$new_shortcode_id = wp_insert_post(
					array(
						'post_title'   => isset( $shortcode['title'] ) ? $shortcode['title'] : '',
						'post_date'    => isset( $shortcode['post_date'] ) ? $shortcode['post_date'] : gmdate( 'Y/m/d' ),
						'post_content' => isset( $shortcode['content'] ) ? $shortcode['content'] : '',
						'post_status'  => 'publish',
						'post_type'    => $spt_post_type,
					),
					true
				);

				if ( isset( $shortcode['all_testimonial'] ) ) {
					$url = isset( $shortcode['image'] ) && ! empty( $shortcode['image'] ) ? $shortcode['image'] : '';
					// Insert attachment id.
					$thumb_id                           = $this->insert_attachment_from_url( $url, $new_shortcode_id );
					$shortcode['meta']['_thumbnail_id'] = $thumb_id;
				}
				if ( is_wp_error( $new_shortcode_id ) ) {
					throw new \Exception( $new_shortcode_id->get_error_message() );
				}

				if ( isset( $shortcode['all_testimonial'] ) ) {
					$url = isset( $shortcode['image'] ) && ! empty( $shortcode['image'] ) ? $shortcode['image'] : '';
					if ( $url ) {
						// Insert attachment id.
						$thumb_id                           = $this->insert_attachment_from_url( $url, $new_shortcode_id );
						$shortcode['meta']['_thumbnail_id'] = $thumb_id;
					}
				}
				if ( is_wp_error( $new_shortcode_id ) ) {
					throw new \Exception( $new_shortcode_id->get_error_message() );
				}

				if ( isset( $shortcode['meta'] ) && is_array( $shortcode['meta'] ) ) {
					foreach ( $shortcode['meta'] as $key => $value ) {
						if ( is_string( $value ) ) {
							$value = maybe_unserialize( str_replace( '{#ID#}', $new_shortcode_id, $value ) );
						}
						update_post_meta(
							$new_shortcode_id,
							$key,
							$value
						);
					}
				}
			} catch ( \Exception $e ) {
				array_push( $errors[ $index ], $e->getMessage() );

				// If there was a failure somewhere, clean up.
				wp_trash_post( $new_shortcode_id );
			}

			// If no errors, remove the index.
			if ( ! count( $errors[ $index ] ) ) {
				unset( $errors[ $index ] );
			}

			// External modules manipulate data here.
			do_action( 'testimonial_shortcode_imported', $new_shortcode_id );
		}

		$errors = reset( $errors );
		return isset( $errors[0] ) ? new \WP_Error( 'import_testimonials_error', $errors[0] ) : $spt_post_type;
	}

	/**
	 * Import Real Testimonials by ajax.
	 *
	 * @return void
	 */
	public function import_shortcodes() {
		$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'spftestimonial_options_nonce' ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce verification failed!', 'testimonial-free' ),
				),
				401
			);
		}

		// Don't worry sanitize after JSON decode below.
		// phpcs:ignore
		$data         = isset( $_POST['shortcode'] ) ? wp_unslash( $_POST['shortcode'] ) : '';
		$file_type    = isset( $_POST['file_type'] ) ? wp_unslash( sanitize_key( $_POST['file_type'] ) ) : '';
		$data         = json_decode( $data );
		$data         = json_decode( $data, true );
		$import_value = apply_filters( 'sp_wp_tabs_allow_import_tags', false );
		$shortcodes   = $import_value ? $data['shortcode'] : wp_kses_post_deep( $data['shortcode'] );
		if ( ! $data ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nothing to import.', 'testimonial-free' ),
				),
				400
			);
		}

		$status = $this->import( $shortcodes, $file_type );

		if ( is_wp_error( $status ) ) {
			wp_send_json_error(
				array(
					'message' => $status->get_error_message(),
				),
				400
			);
		}

		wp_send_json_success( $status, 200 );
	}
}
