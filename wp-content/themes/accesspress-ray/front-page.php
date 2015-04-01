<?php
/**
 * The front page file.
 * @package AccessPress Ray
 */

get_header(); ?>

	<?php 
		global $accesspress_ray_options;
		$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );

		if ( 'page' == get_option( 'show_on_front' ) ) {
		    include( get_page_template() );
		} else {
			get_template_part( 'index', 'one' ); 
		}
		
		
	?>
	
<?php get_footer(); ?>
