<?php
/**
 * Update options for the version 3.0.0
 *
 * @link       https://shapedplugin.com
 *
 * @package    testimonial_free
 * @subpackage testimonial_free/Admin/updates
 */

update_option( 'testimonial_version', '3.0.0' );
update_option( 'testimonial_db_version', '3.0.0' );

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

		$old_navigation_data = isset( $shortcode_data['navigation'] ) ? $shortcode_data['navigation'] : 'true';
		// Use database updater for the "carousel navigation" and "hide on mobile" options.
		switch ( $old_navigation_data ) {
			case 'true':
				$shortcode_data['spt_carousel_navigation']['navigation']                = '1';
				$shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] = '0';
				break;
			case 'false':
				$shortcode_data['spt_carousel_navigation']['navigation']                = '0';
				$shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] = '0';
				break;
			case 'hide_on_mobile':
				$shortcode_data['spt_carousel_navigation']['navigation']                = '1';
				$shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] = '1';
				break;
		}

		$old_pagination_data = isset( $shortcode_data['pagination'] ) ? $shortcode_data['pagination'] : 'true';
		// Use database updater for the "carousel pagination" and "hide on mobile" options.
		switch ( $old_pagination_data ) {
			case 'true':
				$shortcode_data['spt_carousel_pagination']['pagination']                = '1';
				$shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] = '0';
				break;
			case 'false':
				$shortcode_data['spt_carousel_pagination']['pagination']                = '0';
				$shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] = '0';
				break;
			case 'hide_on_mobile':
				$shortcode_data['spt_carousel_pagination']['pagination']                = '1';
				$shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] = '1';
				break;
		}

		// Update old autoplay option according to the current changes.
		$slider_auto_play_data = isset( $shortcode_data['slider_auto_play'] ) ? $shortcode_data['slider_auto_play'] : 'true';
		switch ( $slider_auto_play_data ) {
			case 'true':
				$shortcode_data['carousel_autoplay']['slider_auto_play']           = true;
				$shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] = false;
				break;
			case 'off_on_mobile':
				$shortcode_data['carousel_autoplay']['slider_auto_play']           = true;
				$shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] = true;
				break;
			case 'false':
				$shortcode_data['carousel_autoplay']['slider_auto_play']           = false;
				$shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] = false;
				break;
		}

		// Set carousel layout style.
		$old_columns = isset( $shortcode_data['columns']['large_desktop'] ) ? $shortcode_data['columns'] : '';

		if ( 'slider' === $shortcode_data['layout'] && ( $old_columns['large_desktop'] > 1 || $old_columns['desktop'] > 1 || $old_columns['laptop'] > 1 || $old_columns['tablet'] > 1 || $old_columns['mobile'] > 1 ) ) {
			$shortcode_data['layout']        = 'carousel';
			$shortcode_data['carousel_mode'] = 'standard';
		}

		update_post_meta( $shortcode_id, 'sp_tpro_shortcode_options', $shortcode_data );
	}
}
