<?php
/**
 * CriptoPay-wordpress-woocomerce.php
 *
 * Copyright (c) Cripto-Pay cripto-pay.com
 * 
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Crito-Pay
 * @package wordpress_woocomerce
 * @since woocommerce 2.0.0
 *
 * Plugin Name: Woocommerce CriptoPay
 * Plugin URI:  https://cripto-pay.com/desarrolladores
 * Description: Pagos con bitcoins y altcoins para Woocommerce. Bitcoin and Altcoin gateway.
 * Author:      Cripto-Pay
 * Author URI:  https://cripto-pay.com
 * Developer:   Carlos González, Víctor García
 * Text Domain: woocommerce-criptopay
 * Version:             2.0
 * License:             Copyright 2014-2015 CriptoPay S.L., MIT License
 * License URI:         https://github.com/criptopay/wordpress_woocomerce/blob/master/LICENSE
 * GitHub Plugin URI:   https://github.com/criptopay/wordpress_woocomerce
 */

/**
 * Primero comprobamos que el plugin de WooCommerce esté activo
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    if ( ! defined( 'ABSPATH' ) ) { 
        exit; // Sale si tratan de acceder directamente
    }

    /** 
     * Pone el sistema a trabajar en modo desarrollo(true) o producción(false)
     **/
    if(!defined('CP_DEBUG')){
        define('CP_DEBUG',true);// En modo producción (false) no saltan excepciones que no sean graves.
    }
    
    /**
     * Si nuestra URL personal no está definida, la definimos con la ruta del URL
     */
    if ( !defined( 'WOOCOMMERCE_CRIPTOPAY_URL' ) ) {
            define( 'WOOCOMMERCE_CRIPTOPAY_URL', plugins_url() . '/CriptoPay_wordpress-woocomerce' ); // No usamos WP_PLUGIN_URL ya que no trabaja con protocolos de SSL
    }

    /**
     * Carga los ficheros necesarios y realiza comprobaciones
     */
    require_once(__DIR__.'/inc/CriptoPay_API_PHP/src/bootstrap.php');

    /**
     * Definimos el dominio
     */
    if(!defined('WOOCOMMERCE_CRIPTOPAY_DOMAIN')){
        define( 'WOOCOMMERCE_CRIPTOPAY_DOMAIN', 'woocriptopay' );
    }
    
    /**
     * incluimos las funciones definidas abajo
     */
    add_action( 'plugins_loaded', 'woocommerce_gateway_criptopay_init' );

    add_action( 'init', 'woocomerce_gateway_criptopay_cargardominio' );

    /**
     * Función para definir el lenguaje
     */
    function woocomerce_gateway_criptopay_cargardominio() {
            load_plugin_textdomain( WOOCOMMERCE_CRIPTOPAY_DOMAIN, null, __DIR__.'/languages' );
    }

    /**
     * Inicializamos nuestra clase incluyendo las funciones del IPN y el getaway del método
     * 
     * @return type
     */
    function woocommerce_gateway_criptopay_init() {

        if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

        include_once ('class-wc-gateway-criptopay.php');

        add_filter('woocommerce_payment_gateways', 'woocommerce_add_gateway_criptopay' );

    }

    /**
     * Función que nos devuelve la posición del array del método
     * 
     * @param array $methods
     * @return string
     */
    function woocommerce_add_gateway_criptopay($methods) {
            $methods[] = 'WC_Gateway_CriptoPay';
            return $methods;
    }

}