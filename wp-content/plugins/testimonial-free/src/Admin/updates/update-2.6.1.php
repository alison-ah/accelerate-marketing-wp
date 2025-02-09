<?php
/**
 * Update border style for the testimonial.
 */

update_option( 'testimonial_version', '2.6.1' );
update_option( 'testimonial_db_version', '2.6.1' );

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
		if ( isset( $shortcode_data['testimonial_border_for_one'] ) ) {
			$shortcode_data['testimonial_border_for_one'] = array(
				'all'    => '0',
				'style'  => 'solid',
				'color'  => '#e3e3e3',
				'radius' => '0',
			);
		}
		update_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', $shortcode_data );
	}
}
