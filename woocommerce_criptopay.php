<?php
/**
 * woocommerce_criptopay.php
 *
 * Copyright (c) 2015 Cripto-Pay www.cripto-pay.com
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * Parts of this code are released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Cripto-Pay
 * @package woocommerce-criptopay
 * @since woocommerce 2.0.0
 *
 * Plugin Name: Woocommerce CriptoPay
 * Plugin URI: http://www.cripto-pay.com
 * Description: Pagos con bitcoins y altcoins para Woocommerce. Versión 1.0.
 * Author: Cripto-Pay
 * Author URI: http://www.cripto-pay.com
 * Developer: Carlos González
 * Text Domain: woocommerce-criptopay
 * Version: 1.0
 */

/**
 * Primero comprobamos que el plugin de WooCommerce esté activo
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    
    /**
     * Para evitar fugas de datos por accesos directos 
     **/
    if ( ! defined( 'ABSPATH' ) ) { 
        exit; // Sale si tratan de acceder directamente
    }

    /** 
     * Pone el sistema a trabajar en modo desarrollo(true) o producción(false)
     **/
    if(!defined('WP_DEBUG')){
        define('WP_DEBUG',true);// En modo producción (false) no saltan excepciones que no sean graves.
    }
    
    /**
     * Si nuestra URL personal no está definida, la definimos con la ruta del URL
     */
    if ( !defined( 'WOOCOMMERCE_CRIPTOPAY_URL' ) ) {
            define( 'WOOCOMMERCE_CRIPTOPAY_URL', plugins_url() . '/woocommerce-criptopay' ); // No usamos WP_PLUGIN_URL ya que no trabaja con protocolos de SSL
    }

    /**
     * Carga los ficheros necesarios y realiza comprobaciones
     */
    require_once(__DIR__.'/inc/CriptoPay_API_PHP/src/bootstrap.php');

    /**
     * 
     */
    define( 'WOOCOMMERCE_CRIPTOPAY_DOMAIN', 'woocriptopay' );

    add_action( 'plugins_loaded', 'woocommerce_gateway_criptopay_init' );

    add_action( 'init', 'miFuncion' );

    function miFuncion() {
            load_plugin_textdomain( WOOCOMMERCE_CRIPTOPAY_DOMAIN, null, __DIR__.'/languages' );
    }

    function woocommerce_gateway_criptopay_init() {

            if ( !class_exists( 'WC_Payment_Gateway' ) ) return;


            include_once ('class-wc-gateway-criptopay.php');


            add_filter('woocommerce_payment_gateways', 'woocommerce_add_gateway_criptopay_gateway' );

            add_action( 'woocommerce_api_wc_gateway_criptopay', 'wooomercer_ipn_response_criptopay' );
    }

    function wooomercer_ipn_response_criptopay () {

            $datos = $_POST;
            //RESPUESTA IPN CRIPTOPAY
            if(isset($datos['order'])){
                $order = new WC_Order($datos['order']);
            }
            if ( $order->status == 'completed' ) {
                    exit;
            }
                       
            
            if($datos['estado'] === "aceptado"){
                $order->add_order_note( sprintf( __( 'Operación con Cripto-Pay completada con éxito. TXID %s', WOOCOMMERCE_CRIPTOPAY_DOMAIN ), $datos['txid'] ) );
                $order->update_status('completed');
            }elseif($datos['estado']=="incompleto"){
                $order->add_order_note( sprintf( __( 'Operación con Cripto-Pay incompleta.Faltan %n %s . TXID parcial %s', WOOCOMMERCE_CRIPTOPAY_DOMAIN ), $datos['parcial'], $datos['divisa'], $datos['txid'] ) );
                $order->update_status('procesing');
            }elseif($datos['estado']==="pendiente"){
                exit;
            }else{
                throw new Exception("Hay un error en el plugin CriptoPay GW Woocomerce");
            }
            
            
            /*

            if ($datos['Ds_Response']=='0000') {  // Operacion correcta

                    $ds_order = ( $datos['Ds_Order'] );
                    $order_id = substr($ds_order,0,8);

                    $order = new WC_Order( $order_id );

                    // Comprobamos si el estado es 'completado'
                    if ( $order->status == 'completed' ) {
                            exit;
                    }

                    $order->add_order_note( sprintf( __( 'Operación con Cripto-Pay completada con éxito. Código %s', WOOCOMMERCE_CRIPTOPAY_DOMAIN ), $datos['Ds_AuthorisationCode'] ) );

            } else {
                    // Operación incorrecta
                    $order_id = ( $datos['Ds_Order'] );
                    $order = new WC_Order( $order_id );

                    $order->add_order_note( sprintf( __( 'ERROR en la peración. Código %s', WOOCOMMERCE_CRIPTOPAY_DOMAIN ), $datos['Ds_ErrorCode'] ) );

            }
*/
    }


    function woocommerce_add_gateway_criptopay_gateway($methods) {
            $methods[] = 'WC_Gateway_CriptoPay';
            return $methods;
    }

}