<?php
/*
  This template is used to display the heading and banner of most pages.
 */
?>
<?php if(!is_home() && !is_front_page()){ ?>
<section id="pagetitle" class="pagetitle">
	<div class="container">
		<?php cpotheme_breadcrumb(); ?>
		<h1 class="pagetitle-title"><?php the_title(); ?></h1>
	</div>
</section>
<?php } ?>