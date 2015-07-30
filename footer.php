<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package CriptoPay_v2
 * @since CriptoPay_v2 2.0
 */
?>

</div><!-- #main .site-main -->

<!-- PAGE FOOTER -->
        <section id="page-footer" class="parallax-background imagenMonedas">
            <article class="box-contact">
                <div class="pattern-overlay"></div>
                <div class="footersize speacing-box ">
                    <div class="container">
                        
                        <div class="row speacing-box-mini contacto">
                            <div class="col-sm-3">
                                <div id="text-3" class="footer-wa-container widget_text">
                                    <div class="textwidget">
                                        <?php wp_nav_menu( array('menu' => '', 'container' => 'nav', 'container_class' => 'pin-contact-footer', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '',
                                        'echo' => true, 'fallback_cb' => 'wp_page_menu', 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="menu-menu-1" class="menu-top real">%3$s</ul>',
                                        'depth' => 0, 'walker' => '', 'theme_location' => 'footer1')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div id="text-4" class="footer-wa-container widget_text">
                                    <div class="textwidget">
                                        <?php wp_nav_menu( array('menu' => '', 'container' => 'nav', 'container_class' => 'pin-contact-footer', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '',
                                        'echo' => true, 'fallback_cb' => 'wp_page_menu', 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="menu-menu-1" class="menu-top real">%3$s</ul>',
                                        'depth' => 0, 'walker' => '', 'theme_location' => 'footer2')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div id="text-5" class="footer-wa-container widget_text">
                                    <div class="textwidget">
                                        <?php wp_nav_menu( array('menu' => '', 'container' => 'nav', 'container_class' => 'pin-contact-footer', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '',
                                        'echo' => true, 'fallback_cb' => 'wp_page_menu', 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="menu-menu-1" class="menu-top real">%3$s</ul>',
                                        'depth' => 0, 'walker' => '', 'theme_location' => 'footer3')); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div id="text-6" class="footer-wa-container widget_text">
                                    <div class="textwidget">
                                        <ul class="menu-footer">
                                            <li>
                                                <a class="contactoFooter" href="http://dev.callebitcoin.org/contacto/">Contacto</a>
                                            </li>
                                            <li>
                                                <p style="color:#fff">
                                                    Calle General Arrando, 9<br>
                                                    28010 Madrid | España
                                                </p>                                    
                                            </li>
                                            <li>
                                                <p style="color:#fff">
                                                    (+34) 914 669 448<br>
                                                    info@cripto-pay.com
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                         if (is_front_page()){ ?>
                            <div class="social-contact text-center">
                            <a href="https://www.facebook.com/CriptoPay" target="_blank"> 
                                <img src="<?php echo get_template_directory_uri(); ?>/images/social-icon/facebook.png" alt="social">
                            </a>
                            <a href="https://plus.google.com/106847515370459588343/posts" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/social-icon/google.png" alt="social">
                            </a>
                            <a href="https://twitter.com/CriptoPay" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/social-icon/twitter.png" alt="social"> 
                            </a>
                            <a href="https://www.linkedin.com/company/cripto-pay/" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/social-icon/linkedin.png" alt="social">
                            </a>
                            <a href="https://www.youtube.com/channel/UCmW5kPBTkG8LsOJrXte7-cA" target="_blank">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/social-icon/youtube.png" alt="social">
                            </a>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </article>
           
            <article class="copyright">
                <div class="close-contact">
                    <div class="arrow">
                        <a href="#menusflotantes"><span class="fa fa-angle-double-up font-color">
                        </span></a>
                    </div>
                </div>
                <div class="container">
                    <div class="pie">
                        <div class="col-xs-12 font-raleway">
                            <?php printf( __('Theme by %2$s.', 'cripto-pay'), 'Cripto-Pay', '<a href="http://cripto-pay.com/" rel="designer"><span>Cripto-Pay</span></a>' ); ?>
                            <?php echo mi_copyright(); ?> | <?php _e('All Rights Reserved'); ?>
                            <div id="flags_language_selector" class="pieabajo"><?php language_selector_flags(); ?></div>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </div>

<!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/libs/html5-shiv.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/libs/respond.min.js"></script>
<![endif]-->
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script><!-- libreria de google-->
          <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
          <script src="<?php echo get_template_directory_uri(); ?>/js/comun.min.js"></script>

        <!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
	<![endif]-->

<?php CriptoPay_v2_scripts();?>    
    <?php wp_footer(); ?>
        <script>
$(function() {
                $( "#tabs" ).tabs();
            });
            </script>
</body>
</html>