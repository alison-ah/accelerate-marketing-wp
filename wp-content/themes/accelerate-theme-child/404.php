<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<header class="page-header">
				<h1 class="page-title"><?php _e( 'Not Found', 'twentythirteen' ); ?></h1>
			</header>

			<div class="page-wrapper">
				<div class="page-content">
					<h2><?php _e( "Ope! It looks like you're a little lost.", 'twentythirteen' ); ?></h2>
					<p><?php _e( "Maybe I can point you in the right direction. Have you tried looking at our <a href="<?php echo site_url('/blog'); ?>">blog</a> or at our <a href="<?php echo site_url('/about'); ?>"> services </a>? Or maybe you're looking for our<a href="<?php echo site_url('/case-studies'); ?>"> featured work</a>. You can always<a href="<?php echo site_url('/contact'); ?>"> reach out</a> if you have a question.", 'twentythirteen' ); ?></p>
					<figure><img src="https://images.unsplash.com/photo-1464716821973-e1031cfa43dc?q=80&w=2145&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" /></figure>
					
					<?php get_search_form(); ?>
				</div><!-- .page-content -->
			</div><!-- .page-wrapper -->

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>