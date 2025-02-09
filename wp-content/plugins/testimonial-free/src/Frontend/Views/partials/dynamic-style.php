<?php
/**
 * Testimonial dynamic frontend styles.
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

	$layout = isset( $layout_data['layout'] ) ? $layout_data['layout'] : 'slider';
	// Slider Pagination.
	$slider_pagination         = isset( $shortcode_data['spt_carousel_pagination']['pagination'] ) ? $shortcode_data['spt_carousel_pagination']['pagination'] : true;
	$pagination_hide_on_mobile = isset( $shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] ) ? $shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] : '';
	// Slider Navigation.
	$show_navigation    = isset( $shortcode_data['spt_carousel_navigation']['navigation'] ) ? $shortcode_data['spt_carousel_navigation']['navigation'] : false;
	$nav_hide_on_mobile = isset( $shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] ) ? $shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] : false;

	$star_rating       = isset( $shortcode_data['testimonial_client_rating'] ) ? $shortcode_data['testimonial_client_rating'] : '';
	$show_client_image = isset( $shortcode_data['client_image'] ) ? $shortcode_data['client_image'] : false;
	$reviewer_position = isset( $shortcode_data['client_designation'] ) ? $shortcode_data['client_designation'] : '';
	$client_image_bg   = isset( $shortcode_data['client_image_bg'] ) ? $shortcode_data['client_image_bg'] : '#ffffff';

	$image_padding           = isset( $shortcode_data['image_padding']['all'] ) ? (int) $shortcode_data['image_padding']['all'] : '0';
	$image_border            = isset( $shortcode_data['image_border'] ) ? $shortcode_data['image_border'] : array(
		'all'   => '0',
		'style' => 'solid',
		'color' => '#dddddd',
		'unit'  => 'px',
	);
	$client_image_box_shadow = isset( $shortcode_data['client_image_border_shadow'] ) ? $shortcode_data['client_image_border_shadow'] : 'none';
	$client_image_position   = isset( $shortcode_data['client_image_position'] ) ? $shortcode_data['client_image_position'] : 'center';

	// Grid pagination.
	$grid_pagination = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : false;

	// Typography.
	$section_title         = isset( $shortcode_data['section_title'] ) ? $shortcode_data['section_title'] : '';
	$testimonial_title     = isset( $shortcode_data['testimonial_title'] ) ? $shortcode_data['testimonial_title'] : '';
	$testimonial_title_tag = isset( $shortcode_data['testimonial_title_tag'] ) ? $shortcode_data['testimonial_title_tag'] : 'h3';
	$testimonial_text      = isset( $shortcode_data['testimonial_text'] ) ? $shortcode_data['testimonial_text'] : '';
	$reviewer_name         = isset( $shortcode_data['testimonial_client_name'] ) ? $shortcode_data['testimonial_client_name'] : '';

	/* Carousel pagination styles */
	if ( $slider_pagination && ( 'slider' === $layout || 'carousel' === $layout ) ) { // Load all styles of carousel pagination  if pagination enabled and layout is slider.
		$pagination_colors = isset( $shortcode_data['pagination_colors'] ) && is_array( $shortcode_data['pagination_colors'] ) ? $shortcode_data['pagination_colors'] : array(
			'color'        => '#cccccc',
			'active-color' => '#1595CE',
		); // pagination color fields.

		$outline .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .testimonial-pagination span.swiper-pagination-bullet{
		background: ' . $pagination_colors['color'] . ';
	}
	#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .testimonial-pagination span.swiper-pagination-bullet.swiper-pagination-bullet-active{
		background: ' . $pagination_colors['active-color'] . ';
	}';
	}

	/* Carousel Navigation styles */
	if ( $show_navigation && ( 'slider' === $layout || 'carousel' === $layout ) ) { // Load all styles of carousel navigation  if navigation enabled and layout is slider.

		// carousel navigation color, hover color, background and background hover color.
		$navigation_colors = isset( $shortcode_data['navigation_color'] ) && is_array( $shortcode_data['navigation_color'] ) ? $shortcode_data['navigation_color'] : array(
			'color'            => '#777777',
			'hover-color'      => '#ffffff',
			'background'       => 'transparent',
			'hover-background' => '#1595ce',
		);
		// carousel navigation border styles.
		$navigation_border = isset( $shortcode_data['navigation_border'] ) && is_array( $shortcode_data['navigation_border'] ) ? $shortcode_data['navigation_border'] : array(
			'all'         => '1',
			'style'       => 'solid',
			'color'       => '#777777',
			'hover-color' => '#1595CE',
		);

		$outline .= ' #sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .testimonial-nav-arrow{
		background: ' . $navigation_colors['background'] . ';
		border: ' . $navigation_border['all'] . 'px ' . $navigation_border['style'] . ' ' . $navigation_border['color'] . ';
		color: ' . $navigation_colors['color'] . ';
	}
	#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .testimonial-nav-arrow:hover {
		background: ' . $navigation_colors['hover-background'] . ';
		border-color: ' . $navigation_border['hover-color'] . ';
		color: ' . $navigation_colors['hover-color'] . ';
	}';
	}

	// Grid pagination styles.
	if ( 'grid' === $layout && $grid_pagination ) { // Load all styles of pagination if pagination enabled.
		// pagination button color, hover color, background & background hover.
		$grid_pagination_colors = isset( $shortcode_data['grid_pagination_colors'] ) ? $shortcode_data['grid_pagination_colors'] : array(
			'color'            => '#5e5e5e',
			'hover-color'      => '#ffffff',
			'background'       => '#ffffff',
			'hover-background' => '#1595CE',
		);
		// pagination button border.
		$grid_pagination_border = isset( $shortcode_data['grid_pagination_border'] ) ? $shortcode_data['grid_pagination_border'] : array(
			'all'         => '2',
			'style'       => 'solid',
			'color'       => '#bbbbbb',
			'hover-color' => '#1595CE',
		);
		// pagination button margin properties.
		$grid_pagination_margin    = isset( $shortcode_data['grid_pagination_margin'] ) ? $shortcode_data['grid_pagination_margin'] : array(
			'all'         => '2',
			'style'       => 'solid',
			'color'       => '#bbbbbb',
			'hover-color' => '#1595CE',
		);
		$grid_pagination_alignment = isset( $shortcode_data['grid_pagination_alignment'] ) ? $shortcode_data['grid_pagination_alignment'] : 'left';

		$outline .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section ul.sp-tfree-pagination li a, #sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section ul.sp-tfree-pagination li span.current{
		color: ' . $grid_pagination_colors['color'] . ';
		background: ' . $grid_pagination_colors['background'] . ';
		border: ' . $grid_pagination_border['all'] . 'px ' . $grid_pagination_border['style'] . ' ' . $grid_pagination_border['color'] . ';
	}
	#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section ul.sp-tfree-pagination li span.current, #sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section ul.sp-tfree-pagination li a:hover{
		background: ' . $grid_pagination_colors['hover-background'] . ';
        color: ' . $grid_pagination_colors['hover-color'] . ';
        border-color: ' . $grid_pagination_border['hover-color'] . ';
	}
	#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section ul.sp-tfree-pagination {
			margin: ' . $grid_pagination_margin['top'] . 'px ' . $grid_pagination_margin['right'] . 'px ' . $grid_pagination_margin['bottom'] . 'px ' . $grid_pagination_margin['left'] . 'px;
			padding:0;
	}
	#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-tfree-pagination-area {
			text-align: ' . $grid_pagination_alignment . ';
			margin:0;
	}';
	}

	if ( ( 'slider' === $layout || 'carousel' === $layout ) && ( $show_navigation || $nav_hide_on_mobile ) ) {
		$outline .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section{
		margin: 0 50px;
	}';
	}

	if ( ( 'slider' === $layout || 'carousel' === $layout ) && ( $slider_pagination || $pagination_hide_on_mobile ) ) {
		$outline .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section{
			margin-bottom: 50px;
		}';
	}

	// styles for hiding pagination in mobile.
	if ( ( 'slider' === $layout || 'carousel' === $layout ) && ( $slider_pagination && $pagination_hide_on_mobile ) ) {
		$outline .= '@media only screen and (max-width: 480px){
		#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section{
			margin-bottom: 0px;
		}
		#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .testimonial-pagination{ 
			display: none; 
		}
	}';
	}

	// styles for hiding navigation in mobile.
	if ( $nav_hide_on_mobile && ( 'slider' === $layout || 'carousel' === $layout ) ) {
		$outline .= '@media only screen and (max-width: 480px){
		#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section{
			margin-left: 0;
			margin-right: 0;
		}
		#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .testimonial-nav-arrow{ 
			display: none; 
		}
	}';
	}

	if ( $show_client_image ) {
		$outline .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-image img {
			background: ' . $client_image_bg . ';
			border: ' . $image_border['all'] . 'px ' . $image_border['style'] . ' ' . $image_border['color'] . ';
			padding: ' . $image_padding . 'px;
		}
		#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-image {
			text-align: ' . $client_image_position . ';
		}';

		if ( 'none' !== $client_image_box_shadow ) {
			$client_image_box_shadow_property = isset( $shortcode_data['client_image_box_shadow_property'] ) ? $shortcode_data['client_image_box_shadow_property'] : array(
				'horizontal' => '0',
				'vertical'   => '0',
				'blur'       => '7',
				'spread'     => '0',
				'color'      => '#888888',
			);

			$img_shadow_type_property = ( 'shadow_inset' === $client_image_box_shadow ) ? ' inset' : '';
			$outline                 .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-image img {
				background: ' . $client_image_bg . ';
				padding: ' . $image_padding . 'px;
				box-shadow: ' . $client_image_box_shadow_property['horizontal'] . 'px ' . $client_image_box_shadow_property['vertical'] . 'px ' . $client_image_box_shadow_property['blur'] . 'px ' . $client_image_box_shadow_property['spread'] . 'px ' . $client_image_box_shadow_property['color'] . $img_shadow_type_property . ';
				margin: 7px;
			}';
		}
	}

	if ( $star_rating ) { // Load testimonial rating color if rating found.
		$star_rating_color = isset( $shortcode_data['testimonial_client_rating_color']['color'] ) ? $shortcode_data['testimonial_client_rating_color'] : array(
			'color'       => '#bbc2c7',
			'hover-color' => '#ffb900',
		);
		$gap               = isset( $shortcode_data['rating_icon_gap']['all'] ) ? $shortcode_data['rating_icon_gap']['all'] : '2';
		$font_size         = isset( $shortcode_data['rating_icon_size']['all'] ) ? $shortcode_data['rating_icon_size']['all'] : '19';
		$outline          .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-rating{
		    display: inline-flex;
            gap: ' . $gap . 'px;
		}
		#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-rating i.fa{
		    color: ' . $star_rating_color['color'] . ';
		    font-size: ' . $font_size . 'px;
		}
		#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-rating i.fa-star{
			color: ' . $star_rating_color['hover-color'] . ';
		}';
	}
	if ( $reviewer_position ) { // Load testimonial designation color if designation/position found.
		$client_designation_color = isset( $shortcode_data['client_designation_company_typography'] ) ? $shortcode_data['client_designation_company_typography']['color'] : '#444444';
		$outline                 .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-designation{
		color: ' . $client_designation_color . ';
	}';
	}
	if ( $reviewer_name ) { // Load testimonial name color if name found.
		$client_name_color = isset( $shortcode_data['client_name_typography'] ) ? $shortcode_data['client_name_typography']['color'] : '#333333';
		$outline          .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-name{
		color: ' . $client_name_color . ';
	}';
	}
	if ( $testimonial_text ) { // Load testimonial content color if content found.
		$testimonial_text_color = isset( $shortcode_data['testimonial_text_typography'] ) ? $shortcode_data['testimonial_text_typography']['color'] : '#333333';
		$outline               .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-client-testimonial{
		color: ' . $testimonial_text_color . ';
	}';
	}
	if ( $testimonial_title ) { // Load testimonial title color if title found.
		$testimonial_title_color = isset( $shortcode_data['testimonial_title_typography'] ) ? $shortcode_data['testimonial_title_typography']['color'] : '#333333';
		$outline                .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section .sp-testimonial-title .sp-testimonial-post-title{
		color: ' . $testimonial_title_color . ';
	}';
	}
	if ( $section_title ) { // Load section title color style if section title exist.
		$section_title_color = isset( $shortcode_data['section_title_typography'] ) ? $shortcode_data['section_title_typography']['color'] : '#444444';
		$outline            .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free-section-title{
		color: ' . $section_title_color . ';
	}';
	}

	$testimonial_border        = isset( $shortcode_data['testimonial_border_for_one'] ) ? $shortcode_data['testimonial_border_for_one'] : array(
		'all'    => '1',
		'style'  => 'solid',
		'color'  => 'transparent',
		'radius' => '0',
	);
	$testimonial_background    = isset( $shortcode_data['testimonial_bg_for_one'] ) ? $shortcode_data['testimonial_bg_for_one'] : 'transparent';
	$testimonial_border_radius = isset( $testimonial_border['radius'] ) ? $testimonial_border['radius'] : 0;

	$outline .= '#sp-testimonial-free-wrapper-' . $post_id . ' .sp-testimonial-free {
	background : ' . $testimonial_background . ';
	border: ' . $testimonial_border['all'] . 'px ' . $testimonial_border['style'] . ' ' . $testimonial_border['color'] . ';
	border-radius: ' . $testimonial_border_radius . 'px;
}';
	if ( 'grid' === $layout ) {
		$space       = isset( $shortcode_data['testimonial_margin']['top'] ) ? $shortcode_data['testimonial_margin'] : array(
			'top'   => '20',
			'right' => '20',
		);
		$space_top   = isset( $space['top'] ) ? $space['top'] : 20;
		$space_right = isset( $space['right'] ) ? $space['right'] : 20;

		$outline .= '#sp-testimonial-free-wrapper-' . $post_id . ' .tfree-grid-items {
		margin-right: -' . (int) $space_top / 2 . 'px;
		margin-left: -' . (int) $space_top / 2 . 'px;
		width: calc(100% + ' . (int) $space_top . 'px);
	}
	#sp-testimonial-free-wrapper-' . $post_id . ' .tfree-layout-grid .sp-testimonial-item {
		padding-right: ' . (int) $space_top / 2 . 'px;
		padding-left: ' . (int) $space_top / 2 . 'px;
		padding-bottom: ' . (int) $space_right . 'px;
	}';
	}
