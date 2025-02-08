<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Accelerate Marketing
 * @since Accelerate Marketing 2.0
 */

get_header(); ?>

	<div id="primary" class="about-page hero-content">
		<div class="main-content-about" role="main">

		<?php while ( have_posts() ) : the_post();
			$hero_summary = get_field('hero_summary');
			$service_1 = get_field('service_1');
			$service_1_description = get_field('service_1_description');
			$service_1_image = get_field('service_1_image');
			$service_2 = get_field('service_2');
			$service_2_description = get_field('service_2_description');
			$service_2_image = get_field('service_2_image');
			$service_3 = get_field('service_3');
			$service_3_description = get_field('service_3_description');
			$service_3_image = get_field('service_3_image');
			$service_4 = get_field('service_4');
			$service_4_description = get_field('service_4_description');
			$service_4_image = get_field('service_4_image');
		
			$size = "full"; 
		?>
		
		<!-- Hero Section -->
		<div class="home-page hero-content" id="about-hero">
			<p id="hero-summary"><?php if($hero_summary) { echo $hero_summary; } ?></p>
		</div> <!-- .home-page.hero-content -->

		<article class="about">
			<aside class="about-services">
				<div class="intro">
					<h2><?php the_title(); ?></h2>
					<p><?php the_content(); ?></p>
				</div>
			
				<div class="services" id="service-1">
					<div class="service-image" id="service-img-1">
						<?php
							if ($service_1_image) { 
								echo wp_get_attachment_image($service_1_image, $size);
							}
						?>
					</div>

					<div class="service-text" id="service-1-text">
						<h3>
							<?php 
								if ($service_1) { 
									echo esc_html($service_1);
								}
							?>
						</h3>
						<p>
							<?php 
								if ($service_1_description) { 
									echo esc_html($service_1_description);
								}
							?>
						</p>
					</div>
				</div> <!-- .services -->

				<div class="services" id="service-2">
					<div class="service-image" id="service-img-2">
						<?php
							if ($service_2_image) { 
								echo wp_get_attachment_image($service_2_image, $size);
							}
						?>
					</div>

					<div class="service-text" id="service-2-text">
						<h3>
							<?php 
								if ($service_2) { 
									echo esc_html($service_2);
								}
							?>
						</h3>
						<p>
							<?php 
								if ($service_2_description) { 
									echo esc_html($service_2_description);
								}
							?>
						</p>
					</div>
				</div> <!-- .services -->

				<div class="services" id="service-3">
					<div class="service-image" id="service-img-3">
						<?php
							if ($service_3_image) { 
								echo wp_get_attachment_image($service_3_image, $size);
							}
						?>
					</div>

					<div class="service-text" id="service-3-text">
						<h3>
							<?php 
								if ($service_3) { 
									echo esc_html($service_3);
								}
							?>
						</h3>
						<p>
							<?php 
								if ($service_3_description) { 
									echo esc_html($service_3_description);
								}
							?>
						</p>
					</div>
				</div> <!-- .services -->

				<div class="services" id="service-4">
					<div class="service-image" id="service-img-4">
						<?php
							if ($service_4_image) { 
								echo wp_get_attachment_image($service_4_image, $size);
							}
						?>
					</div>

					<div class="service-text" id="service-4-text">
						<h3>
							<?php 
								if ($service_4) { 
									echo esc_html($service_4);
								}
							?>
						</h3>
						<p>
							<?php 
								if ($service_4_description) { 
									echo esc_html($service_4_description);
								}
							?>
						</p>
					</div>
				</div> <!-- .services -->

			</aside> <!-- .about-services -->
		</article> <!-- .about -->
		
		<?php endwhile; // end of the loop. ?>

	</div> <!-- .main-content -->
	</div> <!-- #primary.hero-content -->

	<div id="call-to-action">
		<h2 class="call-to-action">Interested in working with us?</h2>
		<a id="call-to-action-button" class="button" href="<?php echo site_url('/contact/') ?>">Contact Us</a>
	</div>

<?php get_footer(); ?>
