<?php if(is_active_sidebar('footer-sidebar-1') && is_active_sidebar('footer-sidebar-2')): ?>
   <footer class="kt-footer">
   <div class="container">
        <div class="row">
        <?php
            $the_sidebars = wp_get_sidebars_widgets();
            $widgetsQty = count( $the_sidebars['footer-sidebar-1'] );
            $widgetWidth = (12%$widgetsQty == 0) ? 12/$widgetsQty : '12';
            $beyond_fsn = esc_html(beyond_footer_sidebars());
            if($beyond_fsn == 1):
        ?>
               <div class="col-md-12 kt-sidebar">

                    <?php if (!dynamic_sidebar( 'footer-sidebar-1')): ?>
                        <div class="pre-widget">
                            <h3><?php _e('Widgetized Sidebar', 'beyondmagazine'); ?></h3>
                            <p><?php _e('This panel is active and ready for you to add
                            some widgets via the WP Admin', 'beyondmagazine'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
        <?php elseif($beyond_fsn == 2): ?>
                <div class="col-md-6 kt-sidebar">
                    <?php if (!dynamic_sidebar( 'footer-sidebar-1')): ?>
                        <div class="pre-widget">
                            <h3><?php _e('Widgetized Sidebar', 'beyondmagazine'); ?></h3>
                            <p><?php _e('This panel is active and ready for you to add
                            some widgets via the WP Admin', 'beyondmagazine'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 kt-sidebar">
                    <?php if (!dynamic_sidebar( 'footer-sidebar-2')): ?>
                        <div class="pre-widget">
                            <h3><?php _e('Widgetized Sidebar', 'beyondmagazine'); ?></h3>
                            <p><?php _e('This panel is active and ready for you to add
                            some widgets via the WP Admin', 'beyondmagazine'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
        <?php else: ?>
               <div class="col-md-12 kt-sidebar">
                    <?php if (!dynamic_sidebar( 'footer-sidebar-1')): ?>
                        <div class="pre-widget">
                            <h3><?php _e('Widgetized Sidebar', 'beyondmagazine'); ?></h3>
                            <p><?php _e('This panel is active and ready for you to add
                            some widgets via the WP Admin', 'beyondmagazine'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
        <?php endif ;?>
        </div>
    </div>
   </footer>
<?php endif; ?>
            <div id="kt-copyright">
                <div class="row">
                    <div class="col-md-12">
                        <div class="kt-copyright-column">
                            <p>
                                <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">
                                    <img alt="Licenza Creative Commons" width="80" height="15" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/80x15.png" />
                                </a>
                                <br />
                                I contenuti di questo sito sono distribuiti con Licenza <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Internazionale  4.0</a>. Condividi allo stesso modo.
                                <br/>
                                <span class="impressum">Capolavia Azienda Agricola di Marchetto Andrea, via Rodolfo Rossi 66, Rovigo (RO) - C.F. MRCNDR84B11H620W - P.I. 01523680294</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

    </div>
   </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "LocalBusiness",
      "name" : "Sapori di Capolavia",
      "alternateName" : "Capolavia",
      "url": "<?php echo home_url();?>",
      "logo": "<?php header_image(); ?>",
      "contactPoint" : [
        { "@type" : "ContactPoint",
          "telephone" : "+393703398586",
          "contactType" : "customer support",
          "areaServed" : ["IT", "DE", "CH", "AT"]
        } ] },
      "sameAs": ["https://www.facebook.com/saporidicapolavia", "https://twitter.com/capolavia", "https://plus.google.com/111014905723921272096"],
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Rovigo",
        "addressRegion": "Italy"
      }
    }
    </script>

<?php wp_footer();?>

  </body>

</html>
