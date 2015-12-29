<?php do_action('cpotheme_before_title'); ?>

<?php $image_url = cpotheme_header_image(); ?>
<?php if($image_url != false): ?>
<div id="banner" class="banner">
	<img class="banner-image" src="<?php echo $image_url; ?>">
</div>
<?php endif; ?>

<?php do_action('cpotheme_before_title'); ?>
<section id="pagetitle" class="pagetitle">
	<div class="container">
		<?php do_action('cpotheme_title'); ?>
	</div>
</section>
<?php do_action('cpotheme_after_title'); ?>

<?php do_action('cpotheme_after_title'); ?>