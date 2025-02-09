<?php
/**
 * Grid layout.
 *
 * This template can be overridden by copying it to yourtheme/testimonial-free/templates/grid.php
 *
 * @package    Testimonial_Free
 * @subpackage Testimonial_Free/Frontend
 */

?>
<div id="sp-testimonial-free-wrapper-<?php echo esc_attr( $post_id ); ?>" class="sp-testimonial-free-wrapper">
<?php
if ( $preloader ) {
	include self::sp_testimonial_locate_template( 'preloader.php' );
}
if ( $section_title ) {
	include self::sp_testimonial_locate_template( 'section-title.php' );
}

?>
<div id="sp-testimonial-free-<?php echo esc_attr( $post_id ); ?>" class="sp-testimonial-free-section tfree-layout-grid tfree-style-<?php echo esc_attr( $theme_style ); ?>" dir="<?php echo esc_attr( $slider_direction ); ?>" data-preloader="<?php echo esc_attr( $preloader ); ?>" <?php echo $the_rtl; ?>>
<div class="tfree-grid-items">
<?php
	echo $testimonial_items['output']; // phpcs:ignore
?>
</div>
<?php require self::sp_testimonial_locate_template( 'pagination.php' ); ?>
</div>
</div>
