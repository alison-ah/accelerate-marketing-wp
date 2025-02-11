<?php
/**
 * The Helper class to manage all public-facing functionality of the plugin.
 *
 * @package    testimonial_free
 * @subpackage testimonial_free/Frontend
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\TestimonialFree\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Real Testimonials - helper class
 *
 * @since 2.0
 */
class Helper {
	/**
	 * Full Output show for frontend.
	 *
	 * @param array $post_id Shortcode ID.
	 * @param array $setting_options get all layout options.
	 * @param array $shortcode_data get all meta options.
	 * @param array $layout_data get all layout meta options.
	 * @param mixed $main_section_title section title.
	 * @return void
	 */
	public static function sp_testimonial_html_show( $post_id, $setting_options, $shortcode_data, $layout_data, $main_section_title ) {
		$layout      = isset( $layout_data['layout'] ) ? $layout_data['layout'] : 'slider';
		$theme_style = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme-one';
		$space       = isset( $shortcode_data['testimonial_margin']['top'] ) ? $shortcode_data['testimonial_margin'] : array(
			'top'   => '20',
			'right' => '20',
		);

		$columns            = isset( $shortcode_data['columns'] ) ? $shortcode_data['columns'] : array(
			'large_desktop' => '1',
			'desktop'       => '1',
			'laptop'        => '1',
			'tablet'        => '1',
			'mobile'        => '1',
		);
		$responsive_columns = array(
			'large_desktop' => $columns['large_desktop'],
			'desktop'       => $columns['desktop'],
			'laptop'        => $columns['laptop'],
			'tablet'        => $columns['tablet'],
			'mobile'        => $columns['mobile'],
		);
		// Slider Settings.
		$slider_auto_play = isset( $shortcode_data['slider_auto_play'] ) ? $shortcode_data['slider_auto_play'] : 'true';

		// Auto Play.
		$slider_auto_play_data = isset( $shortcode_data['carousel_autoplay']['slider_auto_play'] ) ? $shortcode_data['carousel_autoplay']['slider_auto_play'] : 'true';
		$slider_auto_play      = $slider_auto_play_data ? 'true' : 'false';

		$autoplay_disable_on_mobile = isset( $shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] ) ? $shortcode_data['carousel_autoplay']['autoplay_disable_on_mobile'] : 'false';
		$slider_auto_play_mobile    = ( ! $autoplay_disable_on_mobile && $slider_auto_play_data ) ? 'true' : 'false';

		$slider_auto_play_speed = isset( $shortcode_data['slider_auto_play_speed'] ) ? $shortcode_data['slider_auto_play_speed'] : '3000';
		$slider_scroll_speed    = isset( $shortcode_data['slider_scroll_speed'] ) ? (int) $shortcode_data['slider_scroll_speed'] : 600;
		$slider_pause_on_hover  = isset( $shortcode_data['slider_pause_on_hover'] ) && $shortcode_data['slider_pause_on_hover'] ? 'true' : 'false';
		$slider_infinite        = isset( $shortcode_data['slider_infinite'] ) && $shortcode_data['slider_infinite'] ? 'true' : 'false';

		// Navigation.
		$show_navigation    = isset( $shortcode_data['spt_carousel_navigation']['navigation'] ) ? $shortcode_data['spt_carousel_navigation']['navigation'] : false;
		$nav_hide_on_mobile = isset( $shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] ) ? $shortcode_data['spt_carousel_navigation']['navigation_hide_on_mobile'] : false;
		$nav_on_mobile      = ! $nav_hide_on_mobile ? 'true' : 'false';

		// Pagination settings.
		$slider_pagination         = isset( $shortcode_data['spt_carousel_pagination']['pagination'] ) ? $shortcode_data['spt_carousel_pagination']['pagination'] : true;
		$pagination_hide_on_mobile = isset( $shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] ) ? $shortcode_data['spt_carousel_pagination']['pagination_hide_on_mobile'] : false;
		$pagination_on_mobile      = ! $pagination_hide_on_mobile ? 'true' : 'false';

		$adaptive_height  = isset( $shortcode_data['adaptive_height'] ) && $shortcode_data['adaptive_height'] ? 'true' : 'false';
		$slider_swipe     = isset( $shortcode_data['slider_swipe'] ) && $shortcode_data['slider_swipe'] ? 'true' : 'false';
		$swipe_to_slide   = isset( $shortcode_data['swipe_to_slide'] ) && $shortcode_data['swipe_to_slide'] ? 'true' : 'false';
		$slider_draggable = isset( $shortcode_data['slider_draggable'] ) && $shortcode_data['slider_draggable'] ? 'true' : 'false';
		$free_mode        = isset( $shortcode_data['free_mode'] ) && $shortcode_data['free_mode'] ? 'true' : 'false';
		$slider_direction = isset( $shortcode_data['slider_direction'] ) ? $shortcode_data['slider_direction'] : 'ltr';
		$rtl_mode         = ( 'rtl' === $slider_direction ) ? 'true' : 'false';
		$the_rtl          = 'true' === $rtl_mode ? 'dir="rtl"' : '';
		$section_title    = isset( $shortcode_data['section_title'] ) ? $shortcode_data['section_title'] : '';
		// Preloader.
		$preloader = isset( $shortcode_data['preloader'] ) ? $shortcode_data['preloader'] : false;
		// Testimonial Pagination.
		$grid_pagination = isset( $shortcode_data['grid_pagination'] ) ? $shortcode_data['grid_pagination'] : false;

		// Schema markup.
		if ( isset( $shortcode_data['schema_markup'] ) ) {
			$show_schema_markup = $shortcode_data['schema_markup'];
		} else {
			$show_schema_markup = isset( $setting_options['spt_enable_schema'] ) ? $setting_options['spt_enable_schema'] : false;
		}

		$post_query        = self::testimonial_query( $shortcode_data, $post_id, $layout, $grid_pagination );
		$testimonial_items = self::testimonial_items( $post_query, $shortcode_data, $layout_data, $post_id, $responsive_columns );
		$sc_title          = get_the_title( $post_id ) ? get_the_title( $post_id ) : 'Testimonial';
		wp_enqueue_script( 'sp-testimonial-scripts' );

		if ( 'slider' === $layout || 'carousel' === $layout ) {
			// Enqueue Swiper Script.
			$dequeue_swiper_js = isset( $setting_options['tf_dequeue_slick_js'] ) ? $setting_options['tf_dequeue_slick_js'] : true;
			if ( $dequeue_swiper_js ) {
				wp_enqueue_script( 'sp-testimonial-swiper-js' );
			}
			$slider_attr = '{"dots": ' . esc_attr( $slider_pagination ) . ', "spaceBetween": ' . esc_attr( (int) $space['top'] ) . ', "adaptiveHeight": ' . esc_attr( $adaptive_height ) . ', "pauseOnHover": ' . esc_attr( $slider_pause_on_hover ) . ', "slidesToShow": ' . esc_attr( $responsive_columns['large_desktop'] ) . ', "speed": ' . esc_attr( $slider_scroll_speed ) . ', "arrows": "' . esc_attr( $show_navigation ) . '", "autoplay": ' . esc_attr( $slider_auto_play ) . ', "autoplaySpeed": ' . esc_attr( $slider_auto_play_speed ) . ', "swipe": ' . esc_attr( $slider_swipe ) . ', "swipeToSlide": ' . esc_attr( $swipe_to_slide ) . ', "draggable": ' . esc_attr( $slider_draggable ) . ', "freeMode": ' . esc_attr( $free_mode ) . ', "rtl": ' . esc_attr( $rtl_mode ) . ', "infinite": ' . esc_attr( $slider_infinite ) . ',"slidesPerView": {"lg_desktop":' . $responsive_columns['large_desktop'] . ' , "desktop": ' . $responsive_columns['desktop'] . ', "laptop":' . $responsive_columns['laptop'] . ' , "tablet": ' . $responsive_columns['tablet'] . ', "mobile": ' . $responsive_columns['mobile'] . '},"navigation_mobile": ' . $nav_on_mobile . ', "pagination_mobile":' . $pagination_on_mobile . ', "autoplay_mobile":' . $slider_auto_play_mobile . '}';
			include self::sp_testimonial_locate_template( 'slider.php' );
		} else {
			include self::sp_testimonial_locate_template( 'grid.php' );
		}

		if ( $show_schema_markup ) {
			ob_start();
			self::testimonials_schema( $post_query, $sc_title, $testimonial_items['aggregate_rating'], $testimonial_items['schema_html'], $testimonial_items['total_testimonial'] );
			echo ob_get_clean(); // phpcs:ignore
		}
	}

	/**
	 * Testimonial Query
	 *
	 * @param  array $shortcode_data shortcode options.
	 * @param  int   $post_id shortcode id.
	 * @param  int   $layout Layout preset.
	 * @param  int   $grid_pagination Pagination switcher.
	 * @return object
	 */
	public static function testimonial_query( $shortcode_data, $post_id, $layout, $grid_pagination ) {
		$number_of_total_testimonials = ! empty( $shortcode_data['number_of_total_testimonials'] ) ? (int) $shortcode_data['number_of_total_testimonials'] : 1000;
		$order_by                     = isset( $shortcode_data['testimonial_order_by'] ) ? $shortcode_data['testimonial_order_by'] : 'date';
		$order                        = isset( $shortcode_data['testimonial_order'] ) ? $shortcode_data['testimonial_order'] : 'DESC';
		$testimonial_per_page         = ! empty( $shortcode_data['tp_per_page'] ) ? (int) $shortcode_data['tp_per_page'] : 8;

		if ( ( 'grid' === $layout ) && $grid_pagination && $number_of_total_testimonials >= $testimonial_per_page ) {
			$paged = 'paged' . $post_id;
			$paged = isset( $_GET[ "$paged" ] ) ? wp_unslash( absint( $_GET[ "$paged" ] ) ) : 1;
			$args  = array(
				'post_type'        => 'spt_testimonial',
				'orderby'          => $order_by,
				'order'            => $order,
				'paged'            => $paged,
				'posts_per_page'   => $testimonial_per_page,
				'suppress_filters' => apply_filters( 'spt_testimonial_free_suppress_filters', false, $post_id ),
			);
		} else {
			$args = array(
				'post_type'      => 'spt_testimonial',
				'orderby'        => $order_by,
				'order'          => $order,
				'posts_per_page' => $number_of_total_testimonials,
			);
		}
		$args                                     = apply_filters( 'spt_testimonial_pro_query_args', $args, $post_id );
		$post_query                               = new \WP_Query( $args );
		$post_query->number_of_total_testimonials = $number_of_total_testimonials;
		return $post_query;
	}

		/**
		 * The load google fonts function merge all fonts from shortcodes.
		 *
		 * @param  array $typography store the all shortcode typography.
		 * @return array
		 */
	public static function load_google_fonts( $typography ) {
		$enqueue_fonts = array();
		if ( ! empty( $typography ) ) {
			foreach ( $typography as $font ) {
				if ( isset( $font['type'] ) && 'google' === $font['type'] ) {
					$weight          = isset( $font['font-weight'] ) ? ( ( 'normal' !== $font['font-weight'] ) ? ':' . $font['font-weight'] : ':400' ) : ':400';
					$style           = isset( $font['font-style'] ) ? substr( $font['font-style'], 0, 1 ) : '';
					$enqueue_fonts[] = str_replace( ' ', '+', $font['font-family'] ) . $weight . $style;
				}
			}
		}
		$enqueue_fonts = array_unique( $enqueue_fonts );
		return $enqueue_fonts;
	}

	/**
	 * Load dynamic style of the existing shortcode id.
	 *
	 * @param  mixed $found_generator_id to push id option for getting how many shortcode in the page.
	 * @param  mixed $form_data to push all options.
	 * @param  mixed $setting_options gets option of the plugin.
	 * @return array dynamic style and typography use in the specific shortcode.
	 */
	public static function load_form_dynamic_style( $found_generator_id, $form_data = '', $setting_options = '' ) {
		$dequeue_google_fonts = isset( $setting_options['tpro_dequeue_google_fonts'] ) ? $setting_options['tpro_dequeue_google_fonts'] : true;
		$form_style           = '';
		$tpro_typography      = array();
		// If multiple shortcode found in the page .
		$form_id     = $found_generator_id;
		$form_fields = isset( $form_data['form_fields'] ) ? $form_data['form_fields'] : null;
		$testimonial = isset( $form_fields['testimonial'] ) ? $form_fields['testimonial'] : null;
		include SP_TFREE_PATH . 'Frontend/Views/partials/form-style.php';
		// Custom css merge with dynamic style.
		$custom_css = isset( $setting_options['custom_css'] ) ? trim( html_entity_decode( $setting_options['custom_css'] ) ) : '';
		if ( ! empty( $custom_css ) ) {
			$form_style .= $custom_css;
		}
		// Google font enqueue dequeue check.
		$tpro_typography = $dequeue_google_fonts ? $tpro_typography : array();
		$dynamic_style   = array(
			'dynamic_css' => self::minify_output( $form_style ),
			'typography'  => $tpro_typography,
		);
		return $dynamic_style;
	}

	/**
	 * Testimonial items
	 *
	 * @param object $post_query Query.
	 * @param array  $shortcode_data options.
	 * @param array  $layout_data layout options.
	 * @param array  $post_id post id.
	 * @param array  $responsive_columns grid columns.
	 * @return array
	 */
	public static function testimonial_items( $post_query, $shortcode_data, $layout_data, $post_id, $responsive_columns ) {
		$layout                = isset( $layout_data['layout'] ) ? $layout_data['layout'] : 'slider';
		$theme_style           = isset( $shortcode_data['theme_style'] ) ? $shortcode_data['theme_style'] : 'theme-one';
		$show_schema_markup    = isset( $shortcode_data['schema_markup'] ) ? $shortcode_data['schema_markup'] : false;
		$testimonial_title     = isset( $shortcode_data['testimonial_title'] ) ? $shortcode_data['testimonial_title'] : '';
		$testimonial_text      = isset( $shortcode_data['testimonial_text'] ) ? $shortcode_data['testimonial_text'] : '';
		$reviewer_name         = isset( $shortcode_data['testimonial_client_name'] ) ? $shortcode_data['testimonial_client_name'] : '';
		$star_rating           = isset( $shortcode_data['testimonial_client_rating'] ) ? $shortcode_data['testimonial_client_rating'] : '';
		$reviewer_position     = isset( $shortcode_data['client_designation'] ) ? $shortcode_data['client_designation'] : '';
		$testimonial_title_tag = isset( $shortcode_data['testimonial_title_tag'] ) ? $shortcode_data['testimonial_title_tag'] : 'h3';
		$reviewer_name_tag     = ( isset( $shortcode_data['testimonial_name_tag'] ) && $shortcode_data['testimonial_name_tag'] ) ? $shortcode_data['testimonial_name_tag'] : 'h4';
		// Image Settings.
		$client_image = isset( $shortcode_data['client_image'] ) ? $shortcode_data['client_image'] : true;
		$image_sizes  = isset( $shortcode_data['image_sizes'] ) ? $shortcode_data['image_sizes'] : 'tf-client-image-size';
		// Grid responsive column's classes.
		$grid_columns = sprintf( ( join( ' ', get_post_class( 'tfree-col-xl-%1$s tfree-col-lg-%2$s tfree-col-md-%3$s tfree-col-sm-%4$s tfree-col-xs-%5$s' ) ) ), $responsive_columns['large_desktop'], $responsive_columns['desktop'], $responsive_columns['laptop'], $responsive_columns['tablet'], $responsive_columns['mobile'] );
		$item_class   = ( 'slider' === $layout || 'carousel' === $layout ) ? 'swiper-slide' : $grid_columns;

		ob_start();
		$tpro_total_rating = 0;
		$testimonial_count = 0;
		$total_posts       = $post_query->found_posts;
		// If pagination is enabled and total testimonials are less than the total posts.
		if ( ! empty( $post_query->number_of_total_testimonials ) && $post_query->number_of_total_testimonials < $post_query->found_posts ) {
			$total_posts = $post_query->number_of_total_testimonials;
		}

		$schema_html = '';
		if ( $post_query->have_posts() ) {
			while ( $post_query->have_posts() ) :
				$post_query->the_post();
				$testimonial_data  = get_post_meta( get_the_ID(), 'sp_tpro_meta_options', true );
				$tfree_designation = ( isset( $testimonial_data['tpro_designation'] ) ? $testimonial_data['tpro_designation'] : '' );
				$tfree_name        = ( isset( $testimonial_data['tpro_name'] ) ? $testimonial_data['tpro_name'] : '' );
				$tfree_rating_star = ( isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : '' );
				if ( 'theme-one' === $theme_style ) {
					include self::sp_testimonial_locate_template( 'theme/theme-one.php' );
				}
				if ( $show_schema_markup ) {
					$testimonial_data  = get_post_meta( get_the_ID(), 'sp_tpro_meta_options', true );
					$tfree_name        = ( isset( $testimonial_data['tpro_name'] ) ? $testimonial_data['tpro_name'] : '' );
					$tfree_rating_star = ( isset( $testimonial_data['tpro_rating'] ) ? $testimonial_data['tpro_rating'] : 'five_star' );
					$rating_value      = '0';
					switch ( $tfree_rating_star ) {
						case 'five_star':
							$rating_value = '5';
							break;
						case 'four_star':
							$rating_value = '4';
							break;
						case 'three_star':
							$rating_value = '3';
							break;
						case 'two_star':
							$rating_value = '2';
							break;
						case 'one_star':
							$rating_value = '1';
							break;
						default:
							$rating_value = '5';
					}
					$tpro_total_rating += (int) $rating_value;
					$name               = get_the_title() ? esc_attr( wp_strip_all_tags( get_the_title() ) ) : '';
					$review_body        = get_the_content() ? esc_attr( wp_strip_all_tags( get_the_content() ) ) : '';
					$date               = get_the_date( 'Y-m-d' );
					$schema_html       .= '{
						"@type": "Review",
						"datePublished": "' . $date . '",
						"reviewBody": "' . $review_body . '",
						"reviewRating": {
							"@type": "Rating",
							"bestRating": "5",
							"ratingValue": "' . $rating_value . '",
							"worstRating": "1"
						},
						"author": {
							"@type": "Person",
							"name": "' . $tfree_name . '"
						}
					}';
					++$testimonial_count;
					if ( $testimonial_count < $total_posts ) {
						$schema_html .= ',';
					}
				}
				$aggregate_rating = 5;
				if ( $show_schema_markup ) {
					$aggregate_rating = round( ( $tpro_total_rating / $testimonial_count ), 2 );
				}
			endwhile;
		} else {
			echo '<h2 class="sp-not-testimonial-found">' . esc_html__( 'No testimonials found', 'testimonial-free' ) . '</h2>';
		}
		wp_reset_postdata();
		$outline = ob_get_clean();

		return array(
			'output'            => $outline,
			'aggregate_rating'  => $aggregate_rating,
			'schema_html'       => $schema_html,
			'total_testimonial' => $total_posts,
		);
	}

	/**
	 * Testimonial title and content text length counter.
	 *
	 * @param string $specific_id Specific ID.
	 * @param string $char_limit Character Limit.
	 * @param string $word_limit Word Limit.
	 * @param string $length_type Length Type (Character or Words).
	 * @return void
	 * @since 3.0.0
	 */
	public static function render_text_length_counter( $specific_id, $char_limit, $word_limit, $length_type ) {
		$data_attributes = wp_json_encode(
			array(
				'characters' => esc_attr( $char_limit ),
				'words'      => esc_attr( $word_limit ),
				'type'       => esc_attr( $length_type ),
			)
		);

		$display_text = ( 'characters' === $length_type ) ? '0 characters out of ' . esc_html( $char_limit ) : '0 words out of ' . esc_html( $word_limit );

		if ( ( 'characters' === $length_type && $char_limit > 0 ) || ( 'characters' !== $length_type && $word_limit > 0 ) ) {
			echo '<span class="sp-maximum_length" data-length_type=\'' . esc_attr( $data_attributes ) . '\' id="' . esc_attr( $specific_id ) . '">' . esc_html( $display_text ) . '</span>';
		}
	}

	/**
	 * Item schema markup
	 *
	 * @param  object $post_query query.
	 * @param  string $global_item_name Global item name.
	 * @param  string $aggregate_rating ratting.
	 * @param  string $schema_html schema HTML.
	 * @param  int    $total_posts  total post.
	 * @return void
	 */
	public static function testimonials_schema( $post_query, $global_item_name, $aggregate_rating, $schema_html, $total_posts ) {
		$outline = '';
		if ( $post_query->have_posts() ) {
			$outline .= '<script type="application/ld+json">
		{
			"@context": "http://schema.org",
			"@type": "Product",
			"name": "' . $global_item_name . '",
			"aggregateRating": {
				"@type": "AggregateRating",
				"bestRating": "5",
				"ratingValue": "' . $aggregate_rating . '",
				"worstRating": "1",
				"reviewCount": "' . $total_posts . '"
			},
			"review": [';
			$outline .= $schema_html;
			$outline .= ']
		}
		</script>';
		}
		echo $outline; // phpcs:ignore
	}

	/**
	 * Minify output
	 *
	 * @param  statement $html output.
	 * @return statement
	 */
	public static function minify_output( $html ) {
		$html = preg_replace( '/<!--(?!s*(?:[if [^]]+]|!|>))(?:(?!-->).)*-->/s', '', $html );
		$html = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $html );
		while ( stristr( $html, '  ' ) ) {
			$html = str_replace( '  ', ' ', $html );
		}
		return $html;
	}

	/**
	 * Custom Template locator.
	 *
	 * @param  mixed $template_name template name.
	 * @param  mixed $template_path template path.
	 * @param  mixed $default_path default path.
	 * @return string
	 */
	public static function sp_testimonial_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = 'testimonial-free/templates';
		}
		if ( ! $default_path ) {
			$default_path = SP_TFREE_PATH . 'Frontend/Views/templates/';
		}
		$template = locate_template( trailingslashit( $template_path ) . $template_name );
		// Get default template.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}
		// Return what we found.
		return $template;
	}

	/**
	 * Redirect function.
	 *
	 * @param string $url url.
	 * @param string $hash hash url.
	 * @return void
	 */
	public static function tpro_redirect( $url, $hash = false ) {
		if ( $hash ) {
			$string  = '<script type="text/javascript">';
			$string .= '
			var elmnt = document.getElementById("' . esc_url( $url ) . '");
			elmnt.scrollIntoView(true);
			// window.location.hash = "' . $url . '"';
			$string .= '</script>';
		} else {
			$string  = '<script type="text/javascript">';
			$string .= 'window.location = "' . esc_url( $url ) . '"';
			$string .= '</script>';
		}

		echo $string;
	}

	/**
	 * Frontend form html.
	 *
	 * @param  int   $form_id form id.
	 * @param  array $form_elements element.
	 * @param  array $form_data data.
	 * @return void
	 */
	public static function frontend_form_html( $form_id, $form_elements, $form_data ) {
		$form_element         = isset( $form_elements['form_elements'] ) ? $form_elements['form_elements'] : array();
		$ajax_form_submission = isset( $form_data['ajax_form_submission'] ) ? $form_data['ajax_form_submission'] : '';

		wp_enqueue_script( 'sp-testimonial-form-config' );
		wp_enqueue_style( 'tfree-form' );

		$form_fields                = $form_data['form_fields'];
		$full_name                  = $form_fields['full_name'];
		$full_name_required         = $full_name['required'] ? 'required' : '';
		$email_address              = $form_fields['email_address'];
		$email_address_required     = $email_address['required'] ? 'required' : '';
		$identity_position          = $form_fields['identity_position'];
		$identity_position_required = $identity_position['required'] ? 'required' : '';
		$testimonial_title          = $form_fields['testimonial_title'];
		$testimonial_title_required = $testimonial_title['required'] ? 'required' : '';
		$testimonial                = $form_fields['testimonial'];
		$testimonial_required       = $testimonial['required'] ? 'required' : '';
		$featured_image             = $form_fields['featured_image'];
		$featured_image_required    = $featured_image['required'] ? 'required' : '';

		$required_notice       = isset( $form_data['required_notice'] ) ? $form_data['required_notice'] : '';
		$required_notice_label = isset( $form_data['notice_label'] ) ? $form_data['notice_label'] : '';
		// $rating                     = $form_fields['rating'];
		$submit_btn = $form_fields['submit_btn'];
		// Testimonial submit form.
		include SP_TFREE_PATH . 'Frontend/Views/partials/submit-form.php';
		// END THE IF STATEMENT THAT STARTED THE WHOLE FORM.
		$form_style = '';
		include SP_TFREE_PATH . 'Frontend/Views/partials/form-style.php';
		echo '<style>' . wp_strip_all_tags( $form_style ) . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		include self::sp_testimonial_locate_template( 'form.php' );
	}
}
