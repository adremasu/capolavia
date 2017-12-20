<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
    <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">
    <!-- For first- and second-generation iPad: -->
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-precomposed_72x72.png">
    <!-- For iPhone with high-resolution Retina display: -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-precomposed_114x114.png">
    <!-- For third-generation iPad with high-resolution Retina display: -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-touch-icon-precomposed_144x144.png">
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title('|',true,'right'); ?></title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <?php
    if (is_home() || is_front_page()){
        $posts_page_id = get_option('page_for_posts');
        //grab the post object related to that id
        $posts_page = get_post($posts_page_id);
        $description = htmlspecialchars_decode(strip_tags(wpautop( $posts_page->post_excerpt )));
    } elseif(is_single() || is_page()){
        $description = htmlspecialchars_decode(get_the_excerpt());
    }

    $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
    $url = $thumb['0'];
    ?>
    <meta name="description" content="<?php echo $description; ?>">
    <link rel="alternate" href="<?php echo site_url(); ?>" hreflang="<?php echo get_bloginfo('language'); ?>" />
    <meta name="twitter:title" content="<?php wp_title('|',true,'right'); ?>" />
    <meta name="twitter:site" content="@capolavia">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="<?php echo site_url(); ?>">
    <meta name="twitter:description" content="<?php echo $description; ?>">
    <meta name="twitter:image" content="<?php echo $url; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <script id="mcjs">!function(c,h,i,m,p){m=c.createElement(h),p=c.getElementsByTagName(h)[0],m.async=1,m.src=i,p.parentNode.insertBefore(m,p)}(document,"script","https://chimpstatic.com/mcjs-connected/js/users/36c7b604eabfd0f01c09956bc/d345499b43b5ac1af79d4d689.js");</script>
</head>
<?php $products_archive=get_post_type_archive_link( 'products' );?>
<?php
$Path = $_SERVER['REQUEST_URI'];
$URI = 'https://'.$_SERVER[HTTP_HOST].$Path;
?>
<body <?php body_class(); ?> <?php if ($products_archive == $URI){?>data-ng-app="productsApp" <?php } ?>>
 <div class="kt-wrapper">
     <?php if ( is_active_sidebar( 'header-sidebar' ) ) : ?>
         <?php dynamic_sidebar( 'header-sidebar' ); ?>
     <?php endif; ?>
        <div class="container">

            <div class="row">
                <?php if (get_header_image() != ''){    ?>

                <div class="col-md-4">
                    <a href="<?php echo esc_url(home_url()); ?>">
                        <img class="logo img-responsive"  src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />
                    </a>
                </div>

                <?php } ?>
                <div class="col-md-8" id="kt-logo">
                    <h1>
                        <a class="blog-title" href="<?php echo esc_url(home_url()); ?>">
                            <?php echo get_bloginfo('name');?>
                        </a><br/>
                        <a class="hidden-xs kt-grey blog-description" href="<?php echo esc_url(home_url()); ?>">
                        <?php echo get_bloginfo('description');?>
                        </a>
                    </h1>
                </div>

            </div>
<!--            <div class="row">-->
<!--                <div class="col-md-2 col-md-offset-6" id="facebook-like">-->
<!--                    <div class="fb-like" data-href="https://www.facebook.com/saporidicapolavia" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>-->
<!--                </div>-->
<!--                <div class="col-md-2">-->
<!---->
<!--                    <div class="g-follow" data-annotation="bubble" data-height="20" data-href="https://plus.google.com/111014905723921272096" data-rel="publisher"></div>-->
<!--                    <script src="https://apis.google.com/js/platform.js" async defer>-->
<!--                        {lang: 'it'}-->
<!--                    </script>-->
<!--                </div>-->
<!--                <div class="col-md-2" id="twitter-follow">-->
<!---->
<!--                    <a href="https://twitter.com/capolavia" class="twitter-follow-button" data-show-count="false">Follow @capolavia</a>-->
<!--                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>-->
<!--                </div>-->
<!--            </div>-->
            <div class="row" id="kt-header-img">
                <div id="kt-main-nav">
                    <?php $beyond_menu_args =  array('location'=>'primary',
                        'menu_container'=>false,
                        'menu_class'=>'main-menu',
                        'menu_id'=>false);
                    wp_nav_menu($beyond_menu_args);
                    ?>
                </div>

            </div>
