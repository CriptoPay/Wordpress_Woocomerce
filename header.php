<?php
/**
 * Cabecera de nuestro tema.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 * 
 * @package CriptoPay_v2
 * @since CriptoPay_v2 2.0
 */	
?>
<!DOCTYPE html>

<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html <?php language_attributes(); ?> class="no-js">
<!--<![endif]-->

    <head>
	<meta charset="<?php bloginfo('charset'); ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	
        <title><?php
            /**
             * Print the <title> tag based on what is being viewed.
             */
            global $page, $paged;

            wp_title('|',true,'right');

            // Add the blog name.
            //bloginfo('name');

            // Add the blog description for the home/front page.
            $site_description = get_bloginfo('description','display');
            if ( $site_description && (is_home() || is_front_page()))
            echo " | $site_description";

            // Add a page number if necessary:
            if ( $paged >= 2 || $page >= 2 )
            echo ' | ' . sprintf( __('Page %s','CriptoPay_v2'), max($paged,$page)); ?>
        </title>
        
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <?php wp_head(); ?>
	
	</head>
	  
	<body <?php body_class(); ?>>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-TTL5G8"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TTL5G8');</script>
<!-- End Google Tag Manager -->        
         <div class="body-wrapper">
            <!--<div id="page-top" class="clearfix"></div>-->
            <div class="top-navigation content-white">
                <div class="top-navigation-inner">
                    
                    <!-- MENU MEDIO Y GRANDE -->
                    <div class="hidden-xs">
                        <header class="container">
                            <div class="logo left">
                                <a href="<?php echo home_url('/'); ?>" title="<?php echo esc_attr(get_bloginfo('name','display')); ?>" rel="home">
                                    <img class="principal logonuevo" alt="<?php bloginfo('name'); ?>" src="<?php bloginfo('template_url'); ?>/imagenes/logo_dossier.png">
                                </a>
                            </div>
                            <div id="header-share" class="right">
                                <a class="google" href="https://plus.google.com/106847515370459588343/posts" target="_blank"></a>
                                <a class="twitter" href="https://twitter.com/CriptoPay" target="_blank"></a>
                                <a class="facebook" href="https://www.facebook.com/CriptoPay" target="_blank"></a>
                                <a class="linkedin" href="https://www.linkedin.com/company/cripto-pay/" target="_blank"></a>  
                                <a class="youtube" href="https://www.youtube.com/channel/UCmW5kPBTkG8LsOJrXte7-cA" target="_blank"></a> 
                            </div>
                                    <?php wp_nav_menu( array('menu' => '', 'container' => 'nav', 'container_class' => 'menu right', 'container_id' => '', 'menu_class' => 'menu', 'menu_id' => '',
                                    'echo' => true, 'fallback_cb' => 'wp_page_menu', 'before' => '', 'after' => '', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="menu-menu-1" class="menu-top real">%3$s</ul>',
                                    'depth' => 0, 'walker' => '', 'theme_location' => 'big-med-menu')); ?>
                        </header><!-- #masthead .site-header -->
                    </div>
                    <div class="hidden-lg hidden-md hidden-sm">
                        <header class="navbar" role="banner">
                            <div class="container">
                                <div class="navbar-header">
                                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <div class="logo mobile left">
                                        <img src="<?php echo get_template_directory_uri(); ?>/imagenes/logo_dossier.png" alt="cripto-pay">
                                    </div>
                                </div>
                                                              
                            <nav class="collapse navbar-collapse bs-navbar-collapse menu right menu-top real">
                                    <?php wp_nav_menu( array('theme_location' => 'big-med-menu',)); ?>
                            </nav>
                            </div>
                        </header>
                    </div>
                </div>
            </div>
            
            <section id="menusflotantes">
                <!-- **************menu desplegable derecha -->
                <!--<div class="registroheader hidden-xs">
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1"><i class="fa fa-user 2x fa-2x highlight"><span>Regístrate</span></i></a></li>
                            <li><a href="#tabs-2"><i class="fa fa-users 1x fa-1x highlight"><span>Login</span></i></a></li>
                        </ul>
                        <div id="tabs-1">
                            
                            <form id="FrmRegistro" class="lateralheader" method='post' action='/panel/login'>
                                <div class="row registro_lateral">
                                    <div class="col-xs-12">
                                        <div class="speacing-box-mini">
                                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                                <input type="text" name="nombre" class="campoFormularioLateral" placeholder="Nombre" required="required">
                                            </div>
                                        </div>
                                        <div class="speacing-box-mini">
                                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                                <input type="email" name="email" class="campoFormularioLateral" placeholder="Email" required="required">
                                            </div>
                                        </div>
                                        <div class="speacing-box-mini">
                                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                                <input type="password" name="password" class="campoFormularioLateral" placeholder="Contraseña" required="required">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-contactCategoria" style="margin-left:148px">
                                            <input type="submit" value="REGISTRARSE">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                        </div> 
                        
                        <div id="tabs-2">
                            
                            <form id="FrmLogin" class="lateralheader" method='post' action='/panel/login' style="margin-top:150px;">
                                <div class="row registro_lateral">
                                    <div class="col-xs-12">
                                        <div class="speacing-box-mini">
                                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                                <input type="text" name="nombre" class="campoFormularioLateral" placeholder="Nombre" required="required">
                                            </div>
                                        </div>
                                        <div class="speacing-box-mini">
                                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                                <input type="password" name="password" class="campoFormularioLateral" placeholder="Contraseña" required="required">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-contactCategoria" style="margin-left:148px">
                                            <input type="submit" value="LOGIN">
                                        </div>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>-->

                <!-- **************banderas idiomas -->
                <div id="flags_language_selector" class="cabeceraarriba"><?php language_selector_flags_header(); ?></div>

            </section>

            <div id="main" class="site-main">
                