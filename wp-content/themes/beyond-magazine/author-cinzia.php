
<?php
define( 'WP_USE_THEMES', false ); get_header();
add_action( 'pre_get_posts','so20175501_pre_get_posts' );
  function so20175501_pre_get_posts( $query ) {
    $query->set( 'post_type', 'recipes' );
    }
?>
    <div class="row" id="kt-main">
        <div class="col-md-12">
            <div id="kt-latest-title" class="h3">
                <p><span>Le ricette</span></p>
            </div>
        </div>

      </div>
      <div class="row">
        <div class="col-md-12">
          <h2>La cucina dei Girasoli di Cinzia Cezza :</h2>
        </div>
      </div>
      <div class="row">

        <div class="col-md-4">

          <?php
            $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
            $allmeta = get_user_meta($curauth->ID,'customer');
            echo '<img height="300" Width="300" src="'.$allmeta[0]['custom_pic'].'">';
            ?>
         </div>
        <div class="col-md-8"><?php echo $curauth->user_description; ?>
          <a href="https://wa.me/<?php echo $allmeta[0]['whatsapp']?>">
            Ricevi il listino via whatsapp
          </a>
        </div>
      </div>
      <div class="row">
        <!-- The Loop -->
        <?php

        $recipes = get_posts(array('author' => $author ));
        foreach ($recipes as $recipe) { ?>
          <div class="product-box col-md-3 col-xxs-12 col-xs-6">
              <div class="kt-article">
                  <a href="<?php echo get_permalink($recipe->ID); ?>">
                      <?php
                      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($recipe->ID), 'small_square' );
                      $url = $thumb['0'];
                      ?>
                      <div class="product-image" style="background-image: url(<?php echo $url; ?>)">
                          <div class="search-bg"><i class="fa fa-search fa-5x"></i>
                          </div>
                          <h3 class="h4 product-name"><?php  echo $recipe->post_title; ?></h3>

                      </div>
                  </a>
              </div>
          </div>
        <?php        }
        ?>


    </div>
<?php
get_footer();
?>
