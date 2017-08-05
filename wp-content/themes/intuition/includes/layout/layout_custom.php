<?php
add_action('wp_head', 'cpotheme_styling_custom', 19);
function cpotheme_styling_custom(){
	
	//Background styling
	$primary_color = cpotheme_get_option('cpo_primary_color');
	
	$bg_texture = cpotheme_get_option('cpo_bg_texture');
	$texture_file = '';
	if($bg_texture != '' && $bg_texture != 'none')
		$texture_file = get_template_directory_uri().'/images/textures/texture_'.$bg_texture.'.png';
	?>
    
	<style type="text/css">
		<?php if($texture_file != ''): ?>
		.outer { background-image:url(<?php echo $texture_file; ?>); }
		<?php endif; ?>


		<?php if($primary_color != ''): ?>
		a:link, a:visited,
		.primary-color,
		.menu-main .current_page_ancestor > a,
		.menu-main .current-menu-item > a { color:<?php echo $primary_color; ?>; }
		.primary-color-bg,
		.menu-portfolio .current-cat a { background-color:<?php echo $primary_color; ?>; }
		
		<?php $faded_color = cpotheme_alter_brightness($primary_color, -50); ?>
		.button-default, .button-default:link, .button-default:visited, input[type=submit] { background:<?php echo $primary_color; ?>;
			background:-moz-linear-gradient(top, <?php echo $primary_color; ?> 0%, <?php echo $faded_color; ?> 100%);
			background:-webkit-linear-gradient(top, <?php echo $primary_color; ?> 0%,<?php echo $faded_color; ?> 100%); 
			background:linear-gradient(to bottom, <?php echo $primary_color; ?> 0%,<?php echo $faded_color; ?> 100%);
			filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $primary_color; ?>', endColorstr='<?php echo $faded_color; ?>',GradientType=0 );
			text-shadow:0 -1px 0 rgba(0, 0, 0, 0.5); border-color:<?php echo $faded_color; ?>; }
		.button-default:hover, input[type=submit]:hover { background:<?php echo $primary_color; ?>; }
		<?php endif; ?>		
    </style>
	<?php
}

add_action('wp_head', 'cpotheme_styling_dependencies', 20);
function cpotheme_styling_dependencies(){
	cpotheme_fonts('Average+Sans');
	cpotheme_fonts('Open+Sans');
}