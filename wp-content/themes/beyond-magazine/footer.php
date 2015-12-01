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
                            <p><a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Licenza Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by-sa/4.0/80x15.png" /></a><br />I contenuti di questo sito sono distribuiti con Licenza <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Internazionale  4.0</a>. Condividi allo stesso modo.
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
<!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
<script type="text/javascript">
    window.cookieconsent_options = {"message":"Questo sito usa cookie (sia cookie tecnici sia cookie analitici e di profilazione di terze parti), per fornirti una migliore esperienza di navigazione e per fornire pubblicità personalizzata. Continuando a navigare, chiudendo questo banner ne accetti l'utilizzo; per negare il consenso si rinvia all’informativa estesa.","dismiss":"Ho capito","learnMore":"Leggi tutto","link":"http:// capolavia.it/cookiepolicy","theme":"dark-bottom"};
</script>

<script type="text/javascript" src="//s3.amazonaws.com/cc.silktide.com/cookieconsent.latest.min.js"></script>
<!-- End Cookie Consent plugin -->
<?php wp_footer();?>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100862521); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100862521ns.gif" /></p></noscript>
  </body>

</html>