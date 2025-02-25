<?php
/**
 * The template for displaying the about page
 *
 * @package WordPress
 * @subpackage Accelerate Marketing
 * @since Accelerate Marketing 2.0
 */

get_header(); ?>

<div id="primary" class="about-page hero-content">
    <div class="main-content-about" role="main" id="main-content-about">

        <?php while (have_posts()) : the_post();
            $hero_summary = get_field('hero_summary');
            $size = 'full'; 
            $services = [
                ['name' => get_field('service_1'), 'description' => get_field('service_1_description'), 'image' => get_field('service_1_image')],
                ['name' => get_field('service_2'), 'description' => get_field('service_2_description'), 'image' => get_field('service_2_image')],
                ['name' => get_field('service_3'), 'description' => get_field('service_3_description'), 'image' => get_field('service_3_image')],
                ['name' => get_field('service_4'), 'description' => get_field('service_4_description'), 'image' => get_field('service_4_image')]
            ];
        ?>

        <!-- Hero Section -->
        <div class="home-page hero-content" id="about-hero">
            <p id="hero-summary"><?php echo esc_html($hero_summary); ?></p>
        </div> <!-- .home-page.hero-content -->

        <article class="about" id="about-services">
            <aside class="about-services">
                <div class="intro">
                    <h1><?php the_title(); ?></h1>
                    <p><?php the_content(); ?></p>
                </div>

                <!-- Loop Through Services -->
                <?php foreach ($services as $index => $service) : ?>
                    <div class="services" id="service-<?php echo $index + 1; ?>">
                        <div class="service-image" id="service-img-<?php echo $index + 1; ?>">
                            <?php if ($service['image']) echo wp_get_attachment_image($service['image'], $size); ?>
                        </div>

                        <div class="service-text" id="service-<?php echo $index + 1; ?>-text">
                            <h2><?php echo esc_html($service['name']); ?></h2>
                            <p><?php echo esc_html($service['description']); ?></p>
                        </div>
                    </div> <!-- .services -->
                <?php endforeach; ?>

            </aside> <!-- .about-services -->
        </article> <!-- .about -->

        <?php endwhile; // end of the loop. ?>
		
		<div id="call-to-action">
    		<h2 class="call-to-action">Interested in working with us?</h2>
    		<a id="call-to-action-button" class="button" href="<?php echo site_url('/contact/') ?>">Contact Us</a>
		</div>

    </div> <!-- .main-content -->
</div> <!-- #primary.hero-content -->



<?php get_footer(); ?>
