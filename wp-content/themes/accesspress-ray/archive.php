<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package AccessPress Ray
 */

get_header(); 
global $accesspress_ray_options;
$accesspress_ray_settings = get_option( 'accesspress_ray_options', $accesspress_ray_options );
?>

<div class="ak-container">
	<section id="primary" class="content-area">
		<main id="main" class="site-main clearfix">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'accesspress_ray' ), '<span class="vcard">' . get_the_author() . '</span>' );

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'accesspress_ray' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'accesspress_ray' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'accesspress_ray' ) ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'accesspress_ray' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'accesspress_ray' ) ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'accesspress_ray' );

						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'accesspress_ray');

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'accesspress_ray');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'accesspress_ray' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'accesspress_ray' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'accesspress_ray' );

						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'accesspress_ray' );

						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'accesspress_ray' );

						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'accesspress_ray' );

						else :
							_e( 'Archives', 'accesspress_ray' );

						endif;
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php 
			while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content-summary' );
				?>

			<?php endwhile; ?>
            <div class="clear"></div>
			<?php accesspress_ray_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar('right'); ?>
</div>
<?php get_footer(); ?>
