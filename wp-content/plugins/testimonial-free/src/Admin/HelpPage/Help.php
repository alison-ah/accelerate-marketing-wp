<?php
/**
 * The help page for the Testimonial Free
 *
 * @package Testimonial Free
 * @subpackage testimonial-free/admin
 */

namespace ShapedPlugin\TestimonialFree\Admin\HelpPage;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access.

/**
 * The help class for the Testimonial Free
 */
class Help {

	/**
	 * Single instance of the class
	 *
	 * @var null
	 */
	protected static $_instance = null;

	/**
	 * Plugins Path variable.
	 *
	 * @var array
	 */
	protected static $plugins = array(
		'woo-product-slider'             => 'main.php',
		'gallery-slider-for-woocommerce' => 'woo-gallery-slider.php',
		'post-carousel'                  => 'main.php',
		'easy-accordion-free'            => 'plugin-main.php',
		'testimonial-free'               => 'main.php',
		'location-weather'               => 'main.php',
		'woo-quickview'                  => 'woo-quick-view.php',
		'wp-expand-tabs-free'            => 'plugin-main.php',

	);

	/**
	 * Welcome pages
	 *
	 * @var array
	 */
	public $pages = array(
		'tfree_help',
	);

	/**
	 * Not show this plugin list.
	 *
	 * @var array
	 */
	protected static $not_show_plugin_list = array( 'testimonial-free', 'aitasi-coming-soon', 'latest-posts', 'widget-post-slider', 'easy-lightbox-wp' );

	/**
	 * Help page construct function.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'help_admin_menu' ), 80 );

        $page   = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';// @codingStandardsIgnoreLine
		if ( 'tfree_help' !== $page ) {
			return;
		}
		add_action( 'admin_print_scripts', array( $this, 'disable_admin_notices' ) );
		add_action( 'spftestimonial_enqueue', array( $this, 'help_page_enqueue_scripts' ) );
	}

	/**
	 * Main Help page Instance
	 *
	 * @static
	 * @return self Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Help_page_enqueue_scripts function.
	 *
	 * @return void
	 */
	public function help_page_enqueue_scripts() {
		wp_enqueue_style( 'sp-real-testimonial-help', SP_TFREE_URL . 'Admin/HelpPage/css/help-page.min.css', array(), SP_TFREE_VERSION );
		wp_enqueue_style( 'sp-real-testimonial-help-fontello', SP_TFREE_URL . 'Admin/HelpPage/css/fontello.min.css', array(), SP_TFREE_VERSION );

		wp_enqueue_script( 'sp-real-testimonial-help', SP_TFREE_URL . 'Admin/HelpPage/js/help-page.min.js', array(), SP_TFREE_VERSION, true );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function help_admin_menu() {
		add_submenu_page(
			'edit.php?post_type=spt_testimonial',
			__( 'Real Testimonials', 'testimonial-free' ),
			__( 'Recommended', 'testimonial-free' ),
			'manage_options',
			'edit.php?post_type=spt_testimonial&page=tfree_help#recommended'
		);
		add_submenu_page(
			'edit.php?post_type=spt_testimonial',
			__( 'Real Testimonials', 'testimonial-free' ),
			__( 'Lite vs Pro', 'testimonial-free' ),
			'manage_options',
			'edit.php?post_type=spt_testimonial&page=tfree_help#lite-to-pro'
		);
		add_submenu_page(
			'edit.php?post_type=spt_testimonial',
			__( 'Testimonial Help', 'testimonial-free' ),
			__( 'Get Help', 'testimonial-free' ),
			'manage_options',
			'tfree_help',
			array(
				$this,
				'help_page_callback',
			)
		);
	}

	/**
	 * Sprtf_plugins_info_api_help_page function.
	 *
	 * @return void
	 */
	public function sprtf_plugins_info_api_help_page() {
		$plugins_arr = get_transient( 'sprtf_plugins' );
		if ( false === $plugins_arr ) {
			$args    = (object) array(
				'author'   => 'shapedplugin',
				'per_page' => '120',
				'page'     => '1',
				'fields'   => array(
					'slug',
					'name',
					'version',
					'downloaded',
					'active_installs',
					'last_updated',
					'rating',
					'num_ratings',
					'short_description',
					'author',
				),
			);
			$request = array(
				'action'  => 'query_plugins',
				'timeout' => 30,
				'request' => serialize( $args ),
			);
			// https://codex.wordpress.org/WordPress.org_API.
			$url      = 'http://api.wordpress.org/plugins/info/1.0/';
			$response = wp_remote_post( $url, array( 'body' => $request ) );

			if ( ! is_wp_error( $response ) ) {

				$plugins_arr = array();
				$plugins     = unserialize( $response['body'] );

				if ( isset( $plugins->plugins ) && ( count( $plugins->plugins ) > 0 ) ) {
					foreach ( $plugins->plugins as $pl ) {
						if ( ! in_array( $pl->slug, self::$not_show_plugin_list, true ) ) {
							$plugins_arr[] = array(
								'slug'              => $pl->slug,
								'name'              => $pl->name,
								'version'           => $pl->version,
								'downloaded'        => $pl->downloaded,
								'active_installs'   => $pl->active_installs,
								'last_updated'      => strtotime( $pl->last_updated ),
								'rating'            => $pl->rating,
								'num_ratings'       => $pl->num_ratings,
								'short_description' => $pl->short_description,
							);
						}
					}
				}

				set_transient( 'sprtf_plugins', $plugins_arr, 24 * HOUR_IN_SECONDS );
			}
		}

		if ( is_array( $plugins_arr ) && ( count( $plugins_arr ) > 0 ) ) {
			array_multisort( array_column( $plugins_arr, 'active_installs' ), SORT_DESC, $plugins_arr );

			foreach ( $plugins_arr as $plugin ) {
				$plugin_slug = $plugin['slug'];
				$image_type  = 'png';
				if ( isset( self::$plugins[ $plugin_slug ] ) ) {
					$plugin_file = self::$plugins[ $plugin_slug ];
				} else {
					$plugin_file = $plugin_slug . '.php';
				}

				switch ( $plugin_slug ) {
					case 'styble':
						$image_type = 'jpg';
						break;
					case 'location-weather':
					case 'logo-carousel-free':
					case 'easy-accordion-free':
					case 'gallery-slider-for-woocommerce':
						$image_type = 'gif';
						break;
				}

				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' );
				?>
				<div class="plugin-card <?php echo esc_attr( $plugin_slug ); ?>" id="<?php echo esc_attr( $plugin_slug ); ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3>
								<a class="thickbox" title="<?php echo esc_attr( $plugin['name'] ); ?>" href="<?php echo esc_url( $details_link ); ?>">
						<?php echo esc_html( $plugin['name'] ); ?>
									<img src="<?php echo esc_url( 'https://ps.w.org/' . $plugin_slug . '/assets/icon-256x256.' . $image_type ); ?>" class="plugin-icon"/>
								</a>
							</h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<li>
						<?php
						if ( $this->is_plugin_installed( $plugin_slug, $plugin_file ) ) {
							if ( $this->is_plugin_active( $plugin_slug, $plugin_file ) ) {
								?>
										<button type="button" class="button button-disabled" disabled="disabled">Active</button>
									<?php
							} else {
								?>
											<a href="<?php echo esc_url( $this->activate_plugin_link( $plugin_slug, $plugin_file ) ); ?>" class="button button-primary activate-now">
									<?php esc_html_e( 'Activate', 'testimonial-free' ); ?>
											</a>
									<?php
							}
						} else {
							?>
										<a href="<?php echo esc_url( $this->install_plugin_link( $plugin_slug ) ); ?>" class="button install-now">
								<?php esc_html_e( 'Install Now', 'testimonial-free' ); ?>
										</a>
								<?php } ?>
								</li>
								<li>
									<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal" aria-label="<?php echo esc_html( 'More information about ' . $plugin['name'] ); ?>" title="<?php echo esc_attr( $plugin['name'] ); ?>">
								<?php esc_html_e( 'More Details', 'testimonial-free' ); ?>
									</a>
								</li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( isset( $plugin['short_description'] ) ? $plugin['short_description'] : '' ); ?></p>
							<p class="authors"> <cite>By <a href="https://shapedplugin.com/">ShapedPlugin LLC</a></cite></p>
						</div>
					</div>
					<?php
					echo '<div class="plugin-card-bottom">';

					if ( isset( $plugin['rating'], $plugin['num_ratings'] ) ) {
						?>
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								array(
									'rating' => $plugin['rating'],
									'type'   => 'percent',
									'number' => $plugin['num_ratings'],
								)
							);
							?>
							<span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
						</div>
						<?php
					}
					if ( isset( $plugin['version'] ) ) {
						?>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Version:', 'testimonial-free' ); ?></strong>
							<span><?php echo esc_html( $plugin['version'] ); ?></span>
						</div>
							<?php
					}

					if ( isset( $plugin['active_installs'] ) ) {
						?>
						<div class="column-downloaded">
						<?php echo esc_html( number_format_i18n( $plugin['active_installs'] ) ) . esc_html__( '+ Active Installations', 'testimonial-free' ); ?>
						</div>
									<?php
					}

					if ( isset( $plugin['last_updated'] ) ) {
						?>
						<div class="column-compatibility">
							<strong><?php esc_html_e( 'Last Updated:', 'testimonial-free' ); ?></strong>
							<span><?php echo esc_html( human_time_diff( $plugin['last_updated'] ) ) . ' ' . esc_html__( 'ago', 'testimonial-free' ); ?></span>
						</div>
									<?php
					}

					echo '</div>';
					?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Check plugins installed function.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_installed( $plugin_slug, $plugin_file ) {
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Check active plugin function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return boolean
	 */
	public function is_plugin_active( $plugin_slug, $plugin_file ) {
		return is_plugin_active( $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Install plugin link.
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @return string
	 */
	public function install_plugin_link( $plugin_slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
	}

	/**
	 * Active Plugin Link function
	 *
	 * @param string $plugin_slug Plugin slug.
	 * @param string $plugin_file Plugin file.
	 * @return string
	 */
	public function activate_plugin_link( $plugin_slug, $plugin_file ) {
		return wp_nonce_url( admin_url( 'edit.php?post_type=spt_testimonial&page=tfree_help&action=activate&plugin=' . $plugin_slug . '/' . $plugin_file . '#recommended' ), 'activate-plugin_' . $plugin_slug . '/' . $plugin_file );
	}

	/**
	 * Making page as clean as possible
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'spt_testimonial' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * The Testimonial Help Callback.
	 *
	 * @return void
	 */
	public function help_page_callback() {
		add_thickbox();

		$action   = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : '';
		$plugin   = isset( $_GET['plugin'] ) ? sanitize_text_field( wp_unslash( $_GET['plugin'] ) ) : '';
		$_wpnonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( isset( $action, $plugin ) && ( 'activate' === $action ) && wp_verify_nonce( $_wpnonce, 'activate-plugin_' . $plugin ) ) {
			activate_plugin( $plugin, '', false, true );
		}

		if ( isset( $action, $plugin ) && ( 'deactivate' === $action ) && wp_verify_nonce( $_wpnonce, 'deactivate-plugin_' . $plugin ) ) {
			deactivate_plugins( $plugin, '', false, true );
		}

		?>
		<div class="sp-real-testimonial-help">
			<!-- Header section start -->
			<section class="sprtf__help header">
				<div class="sprtf-header-area-top">
					<p>You’re currently using <b>Real Testimonials Lite</b>. To access additional features, consider <a target="_blank" href="https://realtestimonials.io/pricing/?ref=1" ><b>upgrading to Pro!</b></a> 🚀</p>
				</div>
				<div class="sprtf-header-area">
					<div class="sprtf-container">
						<div class="sprtf-header-logo">
							<img src="<?php echo esc_url( SP_TFREE_URL . 'Admin/HelpPage/img/logo.svg' ); ?>" alt="">
							<span><?php echo esc_html( SP_TFREE_VERSION ); ?></span>
						</div>
					</div>
					<div class="sprtf-header-logo-shape">
						<img src="<?php echo esc_url( SP_TFREE_URL . 'Admin/HelpPage/img/logo-shape.svg' ); ?>" alt="">
					</div>
				</div>
				<div class="sprtf-header-nav">
					<div class="sprtf-container">
						<div class="sprtf-header-nav-menu">
							<ul>
								<li><a class="active" data-id="get-start-tab"  href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=spt_testimonial&page=tfree_help#get-start' ); ?>"><i class="sprtf-icon-play"></i> Get Started</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=spt_testimonial&page=tfree_help#recommended' ); ?>" data-id="recommended-tab"><i class="sprtf-icon-recommended"></i> Recommended</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=spt_testimonial&page=tfree_help#lite-to-pro' ); ?>" data-id="lite-to-pro-tab"><i class="sprtf-icon-lite-to-pro-icon"></i> Lite Vs Pro</a></li>
								<li><a href="<?php echo esc_url( home_url( '' ) . '/wp-admin/edit.php?post_type=spt_testimonial&page=tfree_help#about-us' ); ?>" data-id="about-us-tab"><i class="sprtf-icon-info-circled-alt"></i> About Us</a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			<!-- Header section end -->

			<!-- Start Page -->
			<section class="sprtf__help start-page" id="get-start-tab">
				<div class="sprtf-container">
					<div class="sprtf-start-page-wrap">
						<div class="sprtf-video-area">
							<h2 class='sprtf-section-title'>Welcome to Real Testimonials!</h2>
							<span class='sprtf-normal-paragraph'>Thank you for installing Real Testimonials! This video will help you get started with the plugin. Enjoy!</span>
							<iframe width="724" height="405" src="https://www.youtube.com/embed/4wtkBqZ4Urw?si=MFlR-StiiQcSDj3E" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
							<ul>
								<li><a class='sprtf-medium-btn' href="<?php echo esc_url( home_url( '/' ) . 'wp-admin/post-new.php?post_type=spt_shortcodes' ); ?>">Create a Testimonial View</a></li>
								<li><a target="_blank" class='sprtf-medium-btn' href="https://realtestimonials.io/demos/real-testimonials-lite-version-demo/">Live Demo</a></li>
								<li><a target="_blank" class='sprtf-medium-btn arrow-btn' href="https://realtestimonials.io/">Explore Real Testimonials <i class="sprtf-icon-button-arrow-icon"></i></a></li>
							</ul>
						</div>
						<div class="sprtf-start-page-sidebar">
							<div class="sprtf-start-page-sidebar-info-box">
								<div class="sprtf-info-box-title">
									<h4><i class="sprtf-icon-doc-icon"></i> Documentation</h4>
								</div>
								<span class='sprtf-normal-paragraph'>Explore Real Testimonials plugin capabilities in our enriched documentation.</span>
								<a target="_blank" class='sprtf-small-btn' href="https://docs.shapedplugin.com/docs/testimonial/overview/">Browse Now</a>
							</div>
							<div class="sprtf-start-page-sidebar-info-box">
								<div class="sprtf-info-box-title">
									<h4><i class="sprtf-icon-support"></i> Technical Support</h4>
								</div>
								<span class='sprtf-normal-paragraph'>For personalized assistance, reach out to our skilled support team for prompt help.</span>
								<a target="_blank" class='sprtf-small-btn' href="https://shapedplugin.com/create-new-ticket/">Ask Now</a>
							</div>
							<div class="sprtf-start-page-sidebar-info-box">
								<div class="sprtf-info-box-title">
									<h4><i class="sprtf-icon-team-icon"></i> Join The Community</h4>
								</div>
								<span class='sprtf-normal-paragraph'>Join the official ShapedPlugin Facebook group to share your experiences, thoughts, and ideas.</span>
								<a target="_blank" class='sprtf-small-btn' href="https://www.facebook.com/groups/ShapedPlugin/">Join Now</a>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Lite To Pro Page -->
			<section class="sprtf__help lite-to-pro-page" id="lite-to-pro-tab">
				<div class="sprtf-container">
					<div class="sprtf-call-to-action-top">
						<h2 class="sprtf-section-title">Lite vs Pro Comparison</h2>
						<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1" class='sprtf-big-btn'>Upgrade to Pro Now!</a>
					</div>
					<div class="sprtf-lite-to-pro-wrap">
						<div class="sprtf-features">
							<ul>
								<li class='sprtf-header'>
									<span class='sprtf-title'>FEATURES</span>
									<span class='sprtf-free'>Lite</span>
									<span class='sprtf-pro'><i class='sprtf-icon-pro'></i> PRO</span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>All Free Version Features</span>
									<span class='sprtf-free sprtf-check-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Amazing Testimonial Layouts (Slider, Carousel, Grid, Masonry, List, Isotope, etc.)  <i class="sprtf-hot">Hot</i></span>
									<span class='sprtf-free'><b>3</b></span>
									<span class='sprtf-pro'><b>6</b></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Testimonial Slider/Carousel Styles (Thumbnails Slider, Center, Ticker, Multi Rows, etc.)</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Customizable and Professionally Designed Testimonials Themes   <i class="sprtf-hot">Hot</i></span>
									<span class='sprtf-free'><b>1</b></span>
									<span class='sprtf-pro'><b>14+</b></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Add and Display Unlimited Testimonials and Groups</span>
									<span class='sprtf-free sprtf-check-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Add and Display Testimonials by Groups</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Reviewer Information Fields Including (Image, Company Logo, Video URL, 40+ Social Profiles, Rating, etc.)</span>
									<span class='sprtf-free'><b>6</b></span>
									<span class='sprtf-pro'><b>15+</b></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Add Unlimited Additional Custom Information Field</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Create Unlimited Testimonials Submission Form with 16 Selectable Fields <i class="sprtf-new">new</i> <i class="sprtf-hot">hot</i></span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Allow the Reviewer to Capture Video Testimonials with Recording UI and Submit Instantly <i class="sprtf-new">new</i> </span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Testimonial Form Layouts</span>
									<span class='sprtf-free'><b>1</b></span>
									<span class='sprtf-pro'><b>2</b></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Ajax Form Submission, Testimonial Form in Lightbox/Popup <i class="sprtf-new">new</i> <i class="sprtf-hot">hot</i></span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Testimonials are Pending in the Dashboard for Approval by the Admin</span>
									<span class='sprtf-free sprtf-check-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Admins can edit the Testimonials before Publishing</span>
									<span class='sprtf-free sprtf-check-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Auto Publish, Auto Publish based on Star Rating of Testimonial Form Submission <i class="sprtf-new">new</i> <i class="sprtf-hot">hot</i></span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Google reCAPTCHA (v2, v3) for Testimonial Form Spam Protection</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Email Notification to Admin, Awaiting Notification and Approval Notification  to Reviewer</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Filter Testimonials by Groups, Specific, Exclude, and Based on Star Rating</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Display Testimonials Randomly</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Enable/Disable Ajax Live Filters and Ajax Search Field <i class="sprtf-new">new</i></span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Rich Snippets/Structured Data Compatible (Schema supported)<i class="sprtf-hot">hot</i> </span>
									<span class='sprtf-free sprtf-check-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Show/Hide Average Rating and Set Margin</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Manage Testimonial Title and Content Limit, Read More Button, etc.</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Control Reviewer Information Fields with Advanced Customizations</span>
									<span class='sprtf-free sprtf-check-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Customize Reviewer Social Media Profiles (Alignment, Custom Color, Border, etc.)</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Reviewer Image Shape (Rounded, Circle, Custom)</span>
									<!-- <span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span> -->
									<span class='sprtf-free'><b>1</b></span>
									<span class='sprtf-pro'><b>3</b></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Reviewer Image Border, Box-shadow, Background, Padding Lightbox, Zoom and Grayscale Effects</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Reviewer Custom Image Dimension and Retina Ready Supported</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Video Testimonials with Lightbox Functionality <i class="sprtf-hot">hot</i></span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Stylize your Testimonial Typography with 1500+ Google Fonts</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Export or Import Testimonials (CSV) and Testimonials Views (Shortcodes)</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>All Premium Features, Security Enhancements, and Compatibility</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
								<li class='sprtf-body'>
									<span class='sprtf-title'>Priority Top-notch Support</span>
									<span class='sprtf-free sprtf-close-icon'></span>
									<span class='sprtf-pro sprtf-check-icon'></span>
								</li>
							</ul>
						</div>
						<div class="sprtf-upgrade-to-pro">
							<h2 class='sprtf-section-title'>Upgrade To PRO & Enjoy Advanced Features!</h2>
							<span class='sprtf-section-subtitle'>Already, <b>50000+</b> people are using Real Testimonials on their websites to create beautiful showcase, why won’t you!</span>
							<div class="sprtf-upgrade-to-pro-btn">
								<div class="sprtf-action-btn">
									<a target="_blank" href="https://realtestimonials.io/pricing/?ref=1" class='sprtf-big-btn'>Upgrade to Pro Now!</a>
									<span class='sprtf-small-paragraph'>14-Day No-Questions-Asked <a target="_blank" href="https://shapedplugin.com/refund-policy/">Refund Policy</a></span>
								</div>
								<a target="_blank" href="https://realtestimonials.io/" class='sprtf-big-btn-border'>See All Features</a>
								<a target="_blank" class="sprtf-big-btn-border sprtf-pro-live-btn" href="https://realtestimonials.io/demos/slider/">Pro Live Demo</a>
							</div>
						</div>
					</div>
					<div class="sprtf-testimonial">
						<div class="sprtf-testimonial-title-section">
							<span class='sprtf-testimonial-subtitle'>NO NEED TO TAKE OUR WORD FOR IT</span>
							<h2 class="sprtf-section-title">Our Users Love Real Testimonials Pro!</h2>
						</div>
						<div class="sprtf-testimonial-wrap">
							<div class="sprtf-testimonial-area">
								<div class="sprtf-testimonial-content">
									<p>We have the plugin pro version in use on two project sites in various setups and pages and I can only say it is a superb plugin and really easy to set up with so many options to display testimonials. All...</p>
								</div>
								<div class="sprtf-testimonial-info">
									<div class="sprtf-img">
										<img src="<?php echo esc_url( SP_TFREE_URL . 'Admin/HelpPage/img/sirpa.png' ); ?>" alt="">
									</div>
									<div class="sprtf-info">
										<h3>Sirpa</h3>
										<div class="sprtf-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="sprtf-testimonial-area">
								<div class="sprtf-testimonial-content">
									<p>This by far is the best testimonial plugin. Go for the pro version as it gives you all the different testimonial styles that you can think of. Very easy to use with lots of setting options for fonts, layouts and etc...</p>
								</div>
								<div class="sprtf-testimonial-info">
									<div class="sprtf-img">
										<img src="<?php echo esc_url( SP_TFREE_URL . 'Admin/HelpPage/img/ali.png' ); ?>" alt="">
									</div>
									<div class="sprtf-info">
										<h3>Ali Senejani</h3>
										<div class="sprtf-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
							<div class="sprtf-testimonial-area">
								<div class="sprtf-testimonial-content">
									<p>We had an issue getting the plugin to display the testimonial on mobile devices and the plugin support team logged in and changed the CSS on the plugin so it would work with our client’s theme! That...</p>
								</div>
								<div class="sprtf-testimonial-info">
									<div class="sprtf-img">
										<img src="<?php echo esc_url( SP_TFREE_URL . 'Admin/HelpPage/img/asiegle.png' ); ?>" alt="">
									</div>
									<div class="sprtf-info">
										<h3>Asiegle</h3>
										<div class="sprtf-star">
											<i>★★★★★</i>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Recommended Page -->
			<section id="recommended-tab" class="sprtf-recommended-page">
				<div class="sprtf-container">
					<h2 class="sprtf-section-title">Enhance your Website with our Free Robust Plugins</h2>
					<div class="sprtf-wp-list-table plugin-install-php">
						<div class="sprtf-recommended-plugins" id="the-list">
							<?php
								$this->sprtf_plugins_info_api_help_page();
							?>
						</div>
					</div>
				</div>
			</section>

			<!-- About Page -->
			<section id="about-us-tab" class="sprtf__help about-page">
				<div class="sprtf-container">
					<div class="sprtf-about-box">
						<div class="sprtf-about-info">
							<h3>All-in-One Testimonials Management Plugin for WordPress – by the Real Testimonials Team, ShapedPlugin, LLC</h3>
							<p>At <b>ShapedPlugin LLC</b>, we are dedicated to helping online businesses increase their conversion rates. However, we needed help finding a perfect testimonials plugin that could do so. Therefore, we created Real Testimonials, which are both easy to use and powerful.</p>
							<p>Real Testimonials is a comprehensive WordPress Testimonials Management plugin that helps online businesses easily collect, manage, and display testimonials on their website. Check it out now; you'll love the experience!</p>
							<div class="sprtf-about-btn">
								<a target="_blank" href="https://realtestimonials.io/" class='sprtf-medium-btn'>Explore Real Testimonials</a>
								<a target="_blank" href="https://shapedplugin.com/about-us/" class='sprtf-medium-btn sprtf-arrow-btn'>More About Us <i class="sprtf-icon-button-arrow-icon"></i></a>
							</div>
						</div>
						<div class="sprtf-about-img">
							<img src="https://shapedplugin.com/wp-content/uploads/2024/01/shapedplugin-team.jpg" alt="">
							<span>Team ShapedPlugin LLC at WordCamp Sylhet</span>
						</div>
					</div>
					<div class="sprtf-our-plugin-list">
						<h3 class="sprtf-section-title">Upgrade your Website with our High-quality Plugins!</h3>
						<div class="sprtf-our-plugin-list-wrap">
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://wpcarousel.io/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-carousel-free/assets/icon-256x256.png" alt="WP Carousel">
								<h4>WP Carousel</h4>
								<p>The most powerful and user-friendly multi-purpose carousel, slider, & gallery plugin for WordPress.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://realtestimonials.io/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/testimonial-free/assets/icon-256x256.gif" alt="Real Testimonials">
								<h4>Real Testimonials</h4>
								<p>Simply collect, manage, and display Testimonials on your website and boost conversions.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://smartpostshow.com/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/post-carousel/assets/icon-256x256.png" alt="Smart Post Show">
								<h4>Smart Post Show</h4>
								<p>Filter and display posts (any post types), pages, taxonomy, custom taxonomy, and custom field, in beautiful layouts.</p>
							</a>
							<a target="_blank" href="https://wooproductslider.io/" class="sprtf-our-plugin-list-box">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-product-slider/assets/icon-256x256.png" alt="Product Slider for WooCommerce">
								<h4>Product Slider for WooCommerce</h4>
								<p>Boost sales by interactive product Slider, Grid, and Table in your WooCommerce website or store.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://woogallery.io/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/gallery-slider-for-woocommerce/assets/icon-256x256.gif" alt="WooGallery">
								<h4>WooGallery</h4>
								<p>Product gallery slider and additional variation images gallery for WooCommerce and boost your sales.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://getwpteam.com/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/team-free/assets/icon-256x256.png" alt="WP Team">
								<h4>WP Team</h4>
								<p>Display your team members smartly who are at the heart of your company or organization!</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://logocarousel.com/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/logo-carousel-free/assets/icon-256x256.gif" alt="Logo Carousel">
								<h4>Logo Carousel</h4>
								<p>Showcase a group of logo images with Title, Description, Tooltips, Links, and Popup as a grid or in a carousel.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://easyaccordion.io/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/easy-accordion-free/assets/icon-256x256.gif" alt="Easy Accordion">
								<h4>Easy Accordion</h4>
								<p>Minimize customer support by offering comprehensive FAQs and increasing conversions.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://shapedplugin.com/woocategory/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-category-slider-grid/assets/icon-256x256.png" alt="WooCategory">
								<h4>WooCategory</h4>
								<p>Display by filtering the list of categories aesthetically and boosting sales.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://wptabs.com/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/wp-expand-tabs-free/assets/icon-256x256.png" alt="WP Tabs">
								<h4>WP Tabs</h4>
								<p>Display tabbed content smartly & quickly on your WordPress site without coding skills.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://shapedplugin.com/quick-view-for-woocommerce/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/woo-quickview/assets/icon-256x256.png" alt="Quick View for WooCommerce">
								<h4>Quick View for WooCommerce</h4>
								<p>Quickly view product information with smooth animation via AJAX in a nice Modal without opening the product page.</p>
							</a>
							<a target="_blank" class="sprtf-our-plugin-list-box" href="https://shapedplugin.com/smart-brands/">
								<i class="sprtf-icon-button-arrow-icon"></i>
								<img src="https://ps.w.org/smart-brands-for-woocommerce/assets/icon-256x256.png" alt="Smart Brands for WooCommerce">
								<h4>Smart Brands for WooCommerce</h4>
								<p>Smart Brands for WooCommerce Pro helps you display product brands in an attractive way on your online store.</p>
							</a>
						</div>
					</div>
				</div>
			</section>

			<!-- Footer Section -->
			<section class="sprtf-footer">
				<div class="sprtf-footer-top">
					<p><span>Made With <i class="sprtf-icon-heart"></i> </span> By the Team <a target="_blank" href="https://shapedplugin.com/">ShapedPlugin LLC</a></p>
					<p>Get connected with</p>
					<ul>
						<li><a target="_blank" href="https://www.facebook.com/ShapedPlugin/"><i class="sprtf-icon-fb"></i></a></li>
						<li><a target="_blank" href="https://twitter.com/intent/follow?screen_name=ShapedPlugin"><i class="sprtf-icon-x"></i></a></li>
						<li><a target="_blank" href="https://profiles.wordpress.org/shapedplugin/#content-plugins"><i class="sprtf-icon-wp-icon"></i></a></li>
						<li><a target="_blank" href="https://youtube.com/@ShapedPlugin?sub_confirmation=1"><i class="sprtf-icon-youtube-play"></i></a></li>
					</ul>
				</div>
			</section>
		</div>
		<?php
	}
}
