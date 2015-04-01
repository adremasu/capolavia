<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title('|',true,'right'); ?></title>    
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php if(beyond_get_favicon() != '') : ?>
        <link rel="icon" href="<?php echo esc_url(beyond_get_favicon()); ?>" type="image/x-icon">   
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

 <div class="kt-wrapper">
     <?php if ( is_active_sidebar( 'header-sidebar' ) ) : ?>
         <?php dynamic_sidebar( 'header-sidebar' ); ?>
     <?php endif; ?>
        <div class="container">
        
            <div class="row">
                <?php if (get_header_image() != ''){    ?>
                    <div class="col-md-4">
                        <img class="logo img-responsive"  src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
                    </div>
                <?php } ?>
                <div class="col-md-8" id="kt-logo">
                    <h1>
                        <a href="<?php echo esc_url(home_url()); ?>">
                            <span class="blog-title"><?php echo get_bloginfo('name');?></span>
                            <span class="kt-grey blog-description"><?php echo get_bloginfo('description');?></span>
                        </a>
                    </h1>
                </div>

            </div>
            <div class="row" id="kt-header-img">
                <div class="col-md-8" id="kt-main-nav">
                    <?php $beyond_menu_args =  array('location'=>'primary',
                        'menu_container'=>false,
                        'menu_class'=>'main-menu',
                        'menu_id'=>false);
                    wp_nav_menu($beyond_menu_args);
                    ?>
                </div>

            </div>
