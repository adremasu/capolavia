<?php 

//Displays a description of the author of the current post
function cpotheme_post_authorbio(){ 
	 ?>
	<div id="author-info" class="block author-info">
		<div class="author-image">
			<?php echo get_avatar(get_the_author_meta('user_email'), 120); ?>
		</div>
		<div class="author-title"><?php _e('About The Author', 'cpotheme'); ?></div>
		<h4 class="author-name">
			<a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
				<?php echo get_the_author(); ?>
			</a>
		</h4>
		<div class="author-description"><?php the_author_meta('description'); ?></div>
	</div> <?php 
}


// Adds More Info links to previews
function cpotheme_post_readmore(){
	return '';
}


//Displays Read More link on excerpts with cutoff
add_filter('excerpt_more', 'cpotheme_auto_excerpt_more');
function cpotheme_auto_excerpt_more($more){
	return ' &hellip;'.cpotheme_post_readmore();
}


//Displays Read More link on excerpts
add_filter('get_the_excerpt', 'cpotheme_custom_excerpt_more');
function cpotheme_custom_excerpt_more($output){
	if(has_excerpt() && !is_attachment())
		$output .= cpotheme_post_readmore();
	return $output;
}


// Limits text preview lengths to a certain size
add_filter('excerpt_length', 'cpotheme_post_excerpt_length');
function cpotheme_post_excerpt_length($length){
	return 25;
}