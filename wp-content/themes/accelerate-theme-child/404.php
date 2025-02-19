<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Accelerate Marketing
 * @since Accelerate Marketing 2.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<header class="page-header">
				<h1 class="page-title"><?php _e( 'Not Found', 'accelerate-marketing' ); ?></h1>
			</header>

			<div class="page-wrapper">
				<div class="page-content" id="not-found-page">
					<div class="text">
						<h2 class="not-found"><?php _e( "Ope!", 'accelerate-marketing' ); ?></h2>
							<h3 class="not-found"><?php _e( "It looks like you're a little lost.", 'accelerate-marketing' ); ?></h3>

								<p class="not-found highlight-links">
									<?php
										echo sprintf(
											"Maybe I can point you in the right direction. Have you tried looking at our 
											<a href=\"%s\">blog</a> 
											or at our <a href=\"%s\">services</a>
											? Or maybe you're looking for our 
											<a href=\"%s\">featured work</a>
											. You can always 
											<a href=\"%s\">reach out</a> 
											if you have a question.",
											site_url('/blog/'),
											site_url('/about/'),
											site_url('/case-studies/'),
											site_url('/contact/')
										);
									?>
								</p>

					</div><!-- .text -->
					<figure id="not-found-img">
						<img src="https://images.unsplash.com/photo-1464716821973-e1031cfa43dc?q=80&w=2145&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" style="max-width: 70%; height: auto;" />
					</figure>
					
				</div><!-- .page-content -->
			</div><!-- .page-wrapper -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>