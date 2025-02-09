<?php
/**
 * Update options for the version 3.1.2
 *
 * @link       https://shapedplugin.com
 *
 * @package    testimonial_free
 * @subpackage testimonial_free/Admin/updates
 */

update_option( 'testimonial_version', '3.1.2' );
update_option( 'testimonial_db_version', '3.1.2' );

$post_ids = get_posts(
	array(
		'post_type'      => 'spt_shortcodes',
		'post_status'    => 'publish',
		'posts_per_page' => '600',
		'fields'         => 'ids',
	)
);

if ( count( $post_ids ) > 0 ) {
	foreach ( $post_ids as $shortcode_key => $shortcode_id ) {
		$shortcode_data = get_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', true );
		if ( ! is_array( $shortcode_data ) ) {
			continue;
		}
		$layout        = isset( $shortcode_data['layout'] ) ? $shortcode_data['layout'] : '';
		$carousel_mode = isset( $shortcode_data['carousel_mode'] ) ? $shortcode_data['carousel_mode'] : '';

		if ( $layout ) {
			$layouts_data['layout'] = $layout;
		}
		if ( 'carousel' === $layout ) {
			$layouts_data['carousel_mode'] = $carousel_mode;
		}
		$shortcode_data['tpro_star_icon'] = 'rating-star-1';
		$client_rating_color              = isset( $shortcode_data['testimonial_client_rating_color'] ) ? $shortcode_data['testimonial_client_rating_color'] : '#ffb900';
		if ( is_string( $client_rating_color ) ) {
			$shortcode_data['testimonial_client_rating_color'] = array(
				'color'       => $client_rating_color,
				'hover-color' => $client_rating_color,
			);
		}

		update_post_meta( $shortcode_id, 'sp_tpro_layout_options', $layouts_data );
		update_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', $shortcode_data );
	}
}
