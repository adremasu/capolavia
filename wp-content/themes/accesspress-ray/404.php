<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package AccessPress Ray
 */

get_header(); ?>
<div class="ak-container">
		<main id="main" class="site-main">

			<section class="not-found">
				<header class="page-header">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'accesspress_ray' ); ?></h1>
				</header><!-- .page-header -->

				<div class="error-404">
                <span class="breeze-404">404</span> 
                <span class="breeze-error"><?php _e('error' , 'accesspress_ray' ); ?></span>   
                </div>

				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location.', 'accesspress_ray' ); ?></p>
				</div><!-- .page-content -->
           
			</section><!-- .error-404 -->

		</main><!-- #main -->
</div>
<?php get_footer(); ?>