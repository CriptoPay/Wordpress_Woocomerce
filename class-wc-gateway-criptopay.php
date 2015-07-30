<?php

/**
 * class-wc-gateway-criptopay.php
 *
 * Copyright (c) Cripto-Pay www.cripto-pay.com
 *
 * 
 * 
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
 * @package woocommerce-criptopay
 * @since woocommerce 2.0.0
 */

/**
 * Para evitar fugas de datos por accesos directos 
 **/
if ( ! defined( 'ABSPATH' ) ) { 
   exit; // Sale si tratan de acceder directamente
}
    
class WC_Gateway_CriptoPay extends WC_Payment_Gateway {

    protected $CP_ApiId, $CP_ApiPassword;
    
    /**
     * Constructor para la entrada.
     *
     * @access public
     * @return void
     */
    public function __construct() {

        $this->id = 'criptopay';
        $this->icon = apply_filters('woocommerce_criptopay_icon', WOOCOMMERCE_CRIPTOPAY_URL . '/imagenes/logo.png');
        $this->has_fields = false;
        $this->method_title = __('CriptoPay (Bitcoin & Altcoins)', WOOCOMMERCE_CRIPTOPAY_DOMAIN);

        // Cargar las configuraciones
        $this->init_form_fields();
        $this->init_settings();

        // Definir las variables de usuario
        $this->title = apply_filters('woocriptopay_title', $this->get_option('title'));
        $this->description = apply_filters('woocriptopay_description', $this->get_option('description'));

        $this->CP_ApiId = $this->get_option('usuario');
        $this->CP_ApiPassword = $this->get_option('password');

        // Actions
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        add_action('woocommerce_receipt_criptopay', array($this, 'receipt_page'));
    }

    /**
     * Inicializar los campos de los formularios de configuración de entrada
     * Estos son los que aparecerán en nuestro Woocommerce
     *
     * @access public
     * @return void
     */
    function init_form_fields() {

        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Habilitar/Deshabilitar', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'checkbox',
                'label' => __('Habilitar CriptoPay', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __('Título', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'text',
                'description' => __('Este título se mostrará en el proceso de checkout.', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => __('CriptoPay', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'desc_tip' => true,
            ),
            'description' => array(
                'title'       => __( 'Descripción', WOOCOMMERCE_CRIPTOPAY_DOMAIN ),
		'type'        => 'text',
		'desc_tip'    => true,
                'description' => __('Descripción del método de pago. Utilícelo para decirle al usuario que es un sistema de pago rápido y seguro.', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => __('Pagos seguros a través de nuestros servidores. Será redirigido a la pasarela de pago de Cripto-Pay.', WOOCOMMERCE_CRIPTOPAY_DOMAIN)
            ),
            'usuario' => array(
                'title' => __('Usuario', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'text',
                'description' => __('Nombre de usuario', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => ''
            ),
            'password' => array(
                'title' => __('Contraseña', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'text',
                'description' => __('Contraseña encriptada', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => ''
            ),
            'cert_publico' => array(
                'title' => __('Certificado Público', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'file',
                'description' => __('Certificado Público', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => ''
            ),
            'cert_privado' => array(
                'title' => __('Certificado Privado', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'file',
                'description' => __('Certificado privado', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => ''
            ),
            
            
        );
    }

    /**
     * Opciones del panel de administración
     * - Options for bits like 'title' and availability on a country-by-country basis
     *
     * @access public
     * @return void
     */
    public function admin_options() {
        ?>
        <h3>
        <?php _e('CriptoPay (Bitcoin & Altcoins)', WOOCOMMERCE_CRIPTOPAY_DOMAIN); ?>
        </h3>
        <p>
        <?php _e('Gestión de pagos con Bitcoin & Altcoins.', WOOCOMMERCE_CRIPTOPAY_DOMAIN); ?>
        </p>
        <table class="form-table">
            <?php
            // Genera el HTML de configuración.
            $this->generate_settings_html();
            ?>
        </table>
        <!--/.form-table-->
            <?php
            // footer
            //$this->printFooter();
        }

        /**
         * Salida cuando damos al botón de pago
         *
         * @access public
         * @return void
         */
        function receipt_page($order) {

            echo '<p>' . __('Thank you for your order, click on the button to pay for CriptoPay.', WOOCOMMERCE_CRIPTOPAY_DOMAIN) . '</p>';

            //echo $this->process_payment($order);
        }

        /**
         * Generamos el link de pago de Cripto-Pay
         *
         * @access public
         * @param mixed $order_id
         * @return string
         */
       //function generate_criptopay_form($order_id) {
            

            /*$criptopay_args = $this->get_criptopay_args($order);

            $criptopay_args_array = array();

            foreach ($criptopay_args as $key => $value) {
                $criptopay_args_array[] = '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" />';
            }*/

            /*if (method_exists($woocommerce, 'add_inline_js')) {
                $woocommerce->add_inline_js('windows.location.replace('.$url.')');
            } else {
                wc_enqueue_js('windows.location.replace('.$url.')');
            }

            return true;
        }*/// END Function

        /**
         * Get Servired Args for passing to PP
         *
         * @access public
         * @param mixed $order
         * @return array
         */
        /*function get_criptopay_args($order) {
            global $woocommerce;

            $order_id = $order->id;
            $ds_order = str_pad($order->id, 8, "0", STR_PAD_LEFT) . date('is');

            if ($this->signature == "completa") {
                //$message = $importe.$order.$code.$currency.$clave;
                $message = $order->get_total() * 100 .
                        $ds_order .
                        $this->commerce .
                        "978" .
                        $this->key;

                $signature = strtoupper(sha1($message));
            } else {
                // Ampliado
                //$amount.$order.$code.$currency.$transactionType.$urlMerchant.$clave;

                $message = $order->get_total() * 100 .
                        $ds_order .
                        $this->commerce .
                        "978" .
                        "0" .
                        add_query_arg('wc-api', 'WC_Gateway_CriptoPay', home_url('/')) .
                        $this->key;

                $signature = strtoupper(sha1($message));
            }

            $args = array(
                'Ds_Merchant_MerchantCode' => $this->commerce,
                'Ds_Merchant_Terminal' => $this->terminal,
                'Ds_Merchant_Currency' => 978,
                'Ds_Merchant_MerchantURL' => add_query_arg('wc-api', 'WC_Gateway_CriptoPay', home_url('/')),
                'Ds_Merchant_TransactionType' => 0,
                'Ds_Merchant_MerchantSignature' => $signature,
                'Ds_Merchant_UrlKO' => apply_filters('woocriptopay_param_urlKO', get_permalink(woocommerce_get_page_id('checkout'))),
                'Ds_Merchant_UrlOK' => apply_filters('woocriptopay_param_urlOK', $this->get_return_url($order)),
                'Ds_Merchant_Titular' => $this->titular,
                'Ds_Merchant_MerchantName' => $this->merchantName,
                'Ds_Merchant_Amount' => round($order->get_total() * 100),
                'Ds_Merchant_ProductDescription' => sprintf(__('Order %s', WOOCOMMERCE_CRIPTOPAY_DOMAIN), $order->get_order_number()),
                'Ds_Merchant_Order' => $ds_order,
            );


            return $args;
        }*/

        
        /**
         * Procesado del pago y retorno del resultado
         *
         * @access public
         * @param int $order_id
         * @return array
         */
        function process_payment($order_id) {

            global $woocommerce;

            $order = new WC_Order($order_id);

            // Diferenciamos si trabajamos con el servidor real del de pruebas
            if ($this->sandbox == 'yes') {
                $criptopay_srv = 'https://sandbox.cripto-pay.com';
            } else {
                $criptopay_srv = 'https://api.cripto-pay.com';
            }
            

            Comun\LOG::Iniciar(LOG_DEBUG,LOG_INFO,"logCriptoPayApiRest.csv");

            //Instancia del Objeto para realizar la acciones
            $CRIPTOPAY = new Comun\CriptoPayApiRest($this->CP_ApiId,$this->CP_ApiPassword,__DIR__.'/certs');

            //Creamos los parametros para el pago a generar
            $pago = array(
                "total" => (float)$order->GetTotal(), // Obligatorio
                "divisa" => get_woocommerce_currency(),//, apply_filters( 'woocommerce_paypal_supported_currencies', array( 'BIT', 'DOG', 'SPA', 'ALT' ) ) ),      //Obligatorio
                "concepto" => $this->merchantName.". Pedido: ".$order->get_order_number(), //Obligatorio
                "URL_OK" => $this->get_return_url($order), //Opcionales
                "URL_KO" => get_permalink(woocommerce_get_page_id('checkout')), //Opcionales
                "IPN" => str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'WC_Gateway_CriptoPay', home_url( '/' ) ) ),
                "IPN_POST" => array("order"=>$order_id)//Opcionales
            );
            //Agregamos los parámetros a la consulta
            $CRIPTOPAY->Set($pago);
            //Ejecutamos la función en sí.
            $respuesta = $CRIPTOPAY->Get("PAGO","GENERAR");
            if(isset($respuesta->idpago)){
                if ($this->sandbox == 'yes') {
                    $url='https://sandbox.cripto-pay.com/pago/'.$respuesta->idpago;
                } else {
                    $url='https://cripto-pay.com/pago/'.$respuesta->idpago;
                }
            }else{
                throw new Exception("CriptoPay no está configurado correctamente");
            }
            
            //return true;

            return array(
                'result' => 'success',
                /*'redirect' => add_query_arg('order', $order->id, add_query_arg('key', $order->order_key, get_permalink(woocommerce_get_page_id('pay'))))*/
                'redirect' => $url
            );
        }

        /**
         * Publicidad para el footer
         */
        /*function printFooter() {
            $output = '<hr>';
            $output .= '<div style="background-color:#ccc; padding: 20px 10px;">';
            $output .= '<p>Actualice los estados de los pedidos automáticamente y consiga soporte premium, usando <a href="http://plugintpv.com/plugins/servired-integracion-woocommerce/" target="_blank">Woocommerce CriptoPay</a>';
            $output .= '</div>';

            echo $output;
        }*/

    }
    ?>