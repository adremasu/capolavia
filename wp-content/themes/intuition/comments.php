<?php if(!(is_page() && !comments_open())): ?>
<div id="comments" class="comments">
    <?php if(post_password_required()): ?>
	<p class="comments-protected">
		<?php _e('This page is protected. Please insert the password to be able to read its contents.', 'cpotheme' ); ?>
	</p>

    <?php elseif(have_comments()): ?>
	<h3 id="commentlist-title" class="commentlist-title">
		<?php if(get_comments_number() == 1) _e('One comment', 'cpotheme');
		else printf(__('%1$s comments', 'cpotheme'), number_format_i18n(get_comments_number())); ?>
	</h3>
	<ol class="commentlist">
		<?php wp_list_comments('type=comment&callback=cpotheme_layout_comments'); ?>
	</ol>
	
	<?php if(pings_open()){ ?>
	<h3 id="pinglist-title" class="pinglist-title">
		<?php _e('Pingbacks', 'cpotheme'); ?>
	</h3>
	<ol class="pinglist">
		<?php wp_list_comments('type=pings&callback=cpotheme_layout_comments'); ?>
	</ol>
	<?php } ?>

	<?php if(get_comment_pages_count() > 1 && get_option('page_comments')){ ?>
        <div class="comment-navigation">
            <div class="nav-previous"><?php previous_comments_link(__('Older', 'cpotheme')); ?></div>
            <div class="nav-next"><?php next_comments_link(__('Newer', 'cpotheme')); ?></div>
        </div>
	<?php } ?>

    <?php endif; ?>
</div>

<div id="commentform" class="commentform">
    <?php comment_form(); ?>
	<?php if(!comments_open()){ ?>
	<p class="comments-closed">
		<?php _e('Comments are closed.', 'cpotheme' ); ?>
	</p>
	<?php } ?>
</div>

<?php endif; ?>