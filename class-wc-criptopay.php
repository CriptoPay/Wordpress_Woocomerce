<?php
/**
 * class-wc-gateway-criptopay.php
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
 * @package woocriptopay
 * @since woocommerce 2.0.0
 *
 * Plugin Name:         CriptoPay Bitcoin and Altcoins Payments for Woocommerce
 * Plugin URI:          https://cripto-pay.com/desarrolladores
 * Description:         Bitcoin and Altcoins( Dogecoin, Litecoin, OkCash, more...) Payments for Woocommerce with auto change to your local currency.
 * Author:              Cripto-Pay
 * Author URI:          https://cripto-pay.com
 * Developer:           Carlos González, Víctor García
 * Text Domain:         woocriptopay
 * Domain Path:         /languages
 * Version:             3.2.0
 * License:             Copyright 2014-2016 CriptoPay S.L., MIT License
 * License URI:         https://github.com/criptopay/wordpress_woocomerce/blob/master/LICENSE
 * GitHub Plugin URI:   https://github.com/criptopay/wordpress_woocomerce
 */

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!function_exists("CriptoPayApiRest")) {
    require_once('CriptoPayApiPHP/src/bootstrap.php');
}

/**
 * Definimos el dominio
 */
load_plugin_textdomain( 'woocriptopay', null,'/languages' );
        
add_action('admin_notices', 'showAdminMessages');
function showAdminMessages() {
    $plugin_messages = array();
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        $plugin_messages[] = 'This plugin requires you to install Woocomerce plugin, <a href="http://wordpress.org/extend/plugins/woocommerce/">download it from here</a>.';
    }
    
    if (count($plugin_messages) > 0 ){
        echo '<div id="message" class="error">';
        echo '<h3>Cripto-Pay.com Gateway</h3>';
        foreach ($plugin_messages as $message) {
            echo '<p><strong>' . __('Required plugins','woocriptopay') . '</strong><br />'.$message.'</p>';
        }
        echo '</div>';
    }
}

add_action('plugins_loaded', 'init_wc_criptopay_payment_gateway', 0);

function init_wc_criptopay_payment_gateway() {

    if (!class_exists('WC_Payment_Gateway')) {return;}

    class WC_criptopay extends WC_Payment_Gateway {

        protected $CP_ApiId, $CP_ApiPassword, $CP_Servidor,$notify_url,$log;

        /**
         * Constructor for the gateway.
         */
        public function __construct() {
            $this->id = 'criptopay';
            $this->icon = plugins_url( 'criptopay-wp/assets/images/CriptoPay_logo.png' );
            $this->method_title = __('CriptoPay Bitcoin and Altcoins Payments for Woocommerce', 'woocriptopay');
            $this->method_description = __('Bitcoin and Altcoins( Dogecoin, Litecoin, OkCash, more...) Payments for Woocommerce with auto change to your local currency.', 'woocriptopay');
            $this->notify_url = add_query_arg('wc-api', 'criptopay', home_url('/'));
            $this->log = new WC_Logger();

            $this->has_fields = false;

            // Load the settings
            $this->init_form_fields();
            $this->init_settings();

            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->CP_ApiId = $this->get_option('CP_ApiId');
            $this->CP_ApiPassword = $this->get_option('CP_ApiPassword');
            $this->CP_Servidor = $this->get_option('CP_Servidor');

            // Actions
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_api_criptopay', array($this, 'ipnCallback'));
            add_action('admin_notices', array($this, 'adminMessages'));
        }

        public function init_form_fields() {
            global $woocommerce;

            $this->form_fields = array(
                    'enabled' => array(
                    'title' => __('Status', 'woocriptopay'),
                    'type' => 'checkbox',
                    'label' => __('Enable CriptoPay Payments', 'woocriptopay'),
                    'description' => '',
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'woocriptopay'),
                    'type' => 'text',
                    'description' => __('This title will be displayed in the checkout process.', 'woocriptopay'),
                    'default' => __('CriptoPay', 'woocriptopay'),
                    'desc_tip' => true,
                ),
                'description' => array(
                    'title'       => __( 'Description', 'woocriptopay' ),
                    'type'        => 'text',
                    'desc_tip'    => true,
                    'description' => __('Description of the method of payment. Use it to tell the user that is a system of fast and safe payment.', 'woocriptopay'),
                    'default' => __('Secure payments through our servers. You will be redirected to the payment gateway Crypto-Pay.', 'woocriptopay')
                ),
                'CP_Servidor' => array(
                    'title' => __('Server', 'woocriptopay'),
                    'type' => 'select',
                    'description' => __('Enviroment to the gateway.', 'woocommerce'),
                    'default' => 'https://testnet.cripto-pay.com',
                    'desc_tip' => true,
                    'options' => array(
                        'https://testnet.cripto-pay.com' => __('Sandbox - https://testnet.cripto-pay.com', 'woocriptopay'),
                        'https://cripto-pay.com' => __('Production - https://cripto-pay.com', 'woocriptopay'),
                    )
                ),
                'CP_ApiId' => array(
                    'title' => __('API ID', 'woocriptopay'),
                    'type' => 'text',
                    'description' => __('API ID', 'woocriptopay'),
                    'default' => ''
                ),
                'CP_ApiPassword' => array(
                    'title' => __('API Password', 'woocriptopay'),
                    'type' => 'text',
                    'description' => __('API Password', 'woocriptopay'),
                    'default' => ''
                ),
                'Cert_Publi' => array(
                    'title' => __('Public certificate', 'woocriptopay'),
                    'type' => 'file',
                    'description' => __('Public certificate', 'woocriptopay'),
                    'default' => ''
                ),
                'Cert_Priv' => array(
                    'title' => __('Private certificate', 'woocriptopay'),
                    'type' => 'file',
                    'description' => __('Private certificate', 'woocriptopay'),
                    'default' => ''
                )
            );
        }
        
        /**
         * Procesamiento del pago.
         * @global type $woocommerce
         * @param type $order_id
         * @return type
         */
        function process_payment($order_id) {
            global $woocommerce;
            $order = new WC_Order($order_id);
            $this->log->add('woocriptopay', "Iniciado proceso de pago");

            //Instancia del Objeto para realizar la acciones
            $CRIPTOPAY = new CriptoPayApiRest\src\Comun\CriptoPayApiRest($this->CP_ApiId,$this->CP_ApiPassword,__DIR__.'/certificados/',$this->CP_Servidor);

            //Creamos los parametros para el pago a generar
            $pago = array(
                "total" => (float)$order->get_total(), // Obligatorio
                "divisa" => get_woocommerce_currency(),//, apply_filters( 'woocommerce_paypal_supported_currencies', array( 'BIT', 'DOG', 'SPA', 'ALT' ) ) ),      //Obligatorio
                "concepto" => "Pedido: ".$order->get_order_number(), //Obligatorio
                "URL_OK" => $this->get_return_url($order), //$this->get_return_url($order), //Opcionales
                "URL_KO" => get_permalink(woocommerce_get_page_id('checkout')), //Opcionales
                "IPN" => $this->notify_url,
                "IPN_POST" => json_encode(array("order"=>$order_id))//Opcionales
            );
           
            $CRIPTOPAY->Set($pago);
            $respuesta = $CRIPTOPAY->Get("PAGO","GENERAR");
            
            if(isset($respuesta->idpago)){
                return array(
                    'result' => 'success',
                    'redirect' => $this->CP_Servidor."/pago/".$respuesta->idpago//$order->get_checkout_payment_url(true)
                );
            }else{
                throw new Exception(__("CriptoPay payments is not configured correctly",'woocriptopay'));
            }

        }

        /**
         * Procesamos los datos enviados en el panel de configuraciÃ³n.
         * Conversión de los certificados en texto a fichero.
         * 
         */
        public function process_admin_options() {
            
            if (!is_dir(__DIR__.'/certificados')) {
                $res = mkdir(__DIR__.'/certificados', 0755, true);
                if (!$res) {
                    throw new Exception(__('The directory is not writeable, check permisions', 'woocriptopay'));
                }
            }
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            foreach ($_FILES as $key => $file) {
                if (!empty($file['name']) && substr($file['name'],0,10) == "CriptoPay_") {
                    if($finfo->file($file['tmp_name']) == "text/plain" &&
                    (substr($file['name'],-3) == "crt" || substr($file['name'],-3) == "key") ){
                        $move = move_uploaded_file($file['tmp_name'],__DIR__.'/certificados/'.$file['name']);
                    }else{
                        throw new Exception(__("File ".$file['name']." incorrect format ". $finfo->file($file['tmp_name'],'woocriptopay')));
                    }
                }
            }
            
            parent::process_admin_options();
        }
        
        function ipnCallback(){
            if(!isset($_POST['order']) || !isset($_POST['idpago'])){
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
                exit;
            }
            
            $idpago = $_POST['idpago'];
            $idorder = $_POST['order'];
            $this->log->add('woocriptopay', "Iniciada IPN Order ".$idorder." idpago ".$idpago);
            $CRIPTOPAY = new CriptoPayApiRest\src\Comun\CriptoPayApiRest($this->CP_ApiId,$this->CP_ApiPassword,__DIR__.'/certificados/',$this->CP_Servidor);
            $CRIPTOPAY->Set(array('idpago'=>$idpago));
            $respuesta = $CRIPTOPAY->Get("PAGO","DETALLE");
            $order = new WC_Order($idorder);
            
            if($respuesta->estado >= 30){
                $order->update_status('completed',__( 'Completed payment CriptoPay', 'woocriptopay' ));
                $order->reduce_order_stock();
                WC()->cart->empty_cart();
            }elseif($respuesta->estado >= 20){
                $order->update_status('processing',__( 'Awaiting validation payment CriptoPay', 'woocriptopay' ));
                $order->reduce_order_stock();
                WC()->cart->empty_cart();
            }elseif($respuesta->estado == 10){
                $order->update_status('processing',__( 'Incomplete payment', 'woocriptopay' ));
            }
            $this->log->add('woocriptopay', "FIN IPN en estado ".$respuesta->estado);
            exit;
        }
        function adminMessages() {
            $plugin_messages = array();
            $data_messages = array();
            include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                $plugin_messages[] = 'This plugin requires you to install Woocomerce plugin, <a href="http://wordpress.org/extend/plugins/woocommerce/">download it from here</a>.';
            }
            if($this->get_option('CP_ApiId') == null){
                $data_messages['API Id'] = __('API ID is required', 'woocriptopay');
            }
            if($this->get_option('CP_ApiPassword') == null){
                $data_messages['API Password'] = __('API password is required', 'woocriptopay');
            }
            if($this->get_option('CP_Servidor') == null){
                $data_messages['API Server'] = __('API server is required', 'woocriptopay');
            }

            if (count($plugin_messages) > 0 || count($data_messages) > 0) {
                echo '<div id="message" class="error">';
                echo '<h3>Cripto-Pay.com Gateway</h3>';
                if (count($plugin_messages) > 0) {
                    foreach ($plugin_messages as $message) {
                        echo '<p><strong>' . __('Required plugins','woocriptopay') . '</strong><br />'.$message.'</p>';
                    }
                }
                if (count($data_messages) > 0) {
                    echo '<p><strong>' . __('Required configuration data','woocriptopay') . '</strong><ul>';
                    foreach ($data_messages as $head=>$message) {
                        echo '<li><strong>' . $head . '</strong>'.$message.'</li>';
                    }
                    echo '</ul></p>';
                }
                echo '</div>';
            }

        }
        
    }

    /**
     * Add the gateway to WooCommerce
     */
    function add_criptopay_gateway($methods) {
        $methods[] = 'WC_criptopay';
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_criptopay_gateway');
}

?>