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

if ( ! defined( 'ABSPATH' ) ) { 
   exit; // Sale si tratan de acceder directamente
}


class WC_Gateway_CriptoPay extends \WC_Payment_Gateway {

    protected $CP_ApiId, $CP_ApiPassword, $CP_Sandbox;
        
    /**
     * Constructor del medio de pago.
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

        $this->CP_ApiId = $this->get_option('CP_ApiId');
        $this->CP_ApiPassword = $this->get_option('CP_ApiPassword');
        $this->CP_Sandbox = ($this->get_option('sandbox')=='yes')?true:false;

        // Actions
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));

        add_action('woocommerce_receipt_criptopay', array($this, 'receipt_page'));
        
        add_action( 'woocommerce_api_wc_gateway_criptopay', array($this, 'ipn_callback') );
    }
    
    /**
     * Procesamos los datos enviados en el panel de configuración.
     * Conversión de los certificados en texto a fichero.
     * 
     */
    public function process_admin_options(){
        
        $fcert = fopen(__DIR__."/certs/CriptoPay_ApiCert_".$_POST['woocommerce_criptopay_CP_ApiId'].".crt", "w");
        fwrite($fcert, $_POST['woocommerce_criptopay_Cert_Publi']);
        fclose($fcert);
        
        $fkey = fopen(__DIR__."/certs/CriptoPay_ApiKey_".$_POST['woocommerce_criptopay_CP_ApiId'].".key", "w");
        fwrite($fkey, $_POST['woocommerce_criptopay_Cert_Priv']);
        fclose($fkey);
        
        parent::process_admin_options();
    }
    
    /**
     * Inicializar los campos de los formularios de configuración del plugin
     *
     */
    function init_form_fields() {

        $this->form_fields = array(
            'enabled' => array(
                'title' => __('Habilitar/Deshabilitar', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'checkbox',
                'label' => __('Habilitar CriptoPay', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => 'yes'
            ),
            'sandbox' => array(
                'title' => __('Habilitar entorno de pruebas', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'checkbox',
                'label' => __('Desmarca esta casilla para funcionar en entorno de producción.', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
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
            'CP_ApiId' => array(
                'title' => __('Id API', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'text',
                'description' => __('ID de la API', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => ''
            ),
            'CP_ApiPassword' => array(
                'title' => __('Password API', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'text',
                'description' => __('Password para el ID de la API', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => ''
            ),
            'Cert_Publi' => array(
                'title' => __('Certificado Público', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'textarea',
                'description' => __('Certificado Público', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => ''
            ),
            'Cert_Priv' => array(
                'title' => __('Certificado Privado', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'type' => 'textarea',
                'description' => __('Certificado privado', WOOCOMMERCE_CRIPTOPAY_DOMAIN),
                'default' => ''
            ),
        );
    }

    /**
     * Opciones del panel de administración
     */
    public function admin_options() {
        echo '<h3>';
        _e('CriptoPay (Bitcoin & Altcoins)', WOOCOMMERCE_CRIPTOPAY_DOMAIN);
        echo '</h3><p>';
         _e('Gestión de pagos con Bitcoin & Altcoins.', WOOCOMMERCE_CRIPTOPAY_DOMAIN);
        echo '</p><table class="form-table">';
        $this->generate_settings_html();
        echo '</table>';
        // footer
        $this->printFooter();
    }

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
        if ($this->CP_Sandbox) {
            $criptopay_srv = 'https://sandbox.cripto-pay.com';
        } else {
            $criptopay_srv = 'https://api.cripto-pay.com';
        }

        require_once(__DIR__.'/inc/CriptoPay_API_PHP/src/bootstrap.php');

        CriptoPayApiRest\src\Comun\Log::Iniciar(LOG_DEBUG,LOG_INFO,"logCriptoPayApiRest.csv");

        //Instancia del Objeto para realizar la acciones
        $CRIPTOPAY = new CriptoPayApiRest\src\Comun\CriptoPayApiRest($this->CP_ApiId,$this->CP_ApiPassword,__DIR__.'/certs/');

        //Creamos los parametros para el pago a generar
        $pago = array(
            "total" => (float)$order->get_total(), // Obligatorio
            "divisa" => get_woocommerce_currency(),//, apply_filters( 'woocommerce_paypal_supported_currencies', array( 'BIT', 'DOG', 'SPA', 'ALT' ) ) ),      //Obligatorio
            "concepto" => "Pedido: ".$order->get_order_number(), //Obligatorio
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
            if($this->CP_Sandbox){
                $url='https://sandbox.cripto-pay.com/pago/'.$respuesta->idpago;
            } else {
                $url='https://cripto-pay.com/pago/'.$respuesta->idpago;
            }
        }else{
            throw new Exception("CriptoPay no está configurado correctamente");
        }

        return array(
            'result' => 'success',
            'redirect' => $url
        );
    }

    /**
     * Banner creación de cuenta
     */
    function printFooter() {
        $output = '<hr>';
        $output .= '<div style="background-color:#ccc; padding: 20px 10px;">';
        $output .= '<p>Puedes abrir tu cuenta ahora en <a href="https://cripto-pay.com" target="_blank">CriptoPay</a></p>';
        $output .= '<p>Si necesitas ayuda para configurar el plugin revisa <a href="https://cripto-pay.com/desarrolladores">la documentación en la web</a></p>';
        $output .= '</div>';
        echo $output;
    }
    
    /**
     * Procesador de la respuesta para el IPN de CriptoPay
     * 
     * @throws Exception
     */
    public function wooomercer_ipn_response_criptopay () {

        $datos = $_POST;

        if(isset($datos['order'])){
            $order = new WC_Order($datos['order']); //si existe algo, instanciamos nuestro objeto
        }

        if($order->status == 'completed'){
            exit;
        }
        
        require_once(__DIR__.'/inc/CriptoPay_API_PHP/src/bootstrap.php');

        CriptoPayApiRest\src\Comun\Log::Iniciar(LOG_DEBUG,LOG_INFO,"logCriptoPayApiRest.csv");

        //Instancia del Objeto para realizar la acciones
        $CRIPTOPAY = new CriptoPayApiRest\src\Comun\CriptoPayApiRest($this->CP_ApiId,$this->CP_ApiPassword,__DIR__.'/certs/');
        
        $CRIPTOPAY->Set(array("idpago"=>$datos['order']));
        $verificacion = $CRIPTOPAY->Get("PAGO", "ESTADO");
        
        if($verificacion['estado'] === "aceptado"){
            $order->add_order_note( sprintf( __( 'Operación con Cripto-Pay completada con éxito. TXID %s', WOOCOMMERCE_CRIPTOPAY_DOMAIN ), $datos['txid'] ) );
            $order->update_status('completed');
        }elseif($verificacion['estado']=="incompleto"){
            $order->add_order_note( sprintf( __( 'Operación con Cripto-Pay incompleta.Faltan %n %s . TXID parcial %s', WOOCOMMERCE_CRIPTOPAY_DOMAIN ), $datos['parcial'], $datos['divisa'], $datos['txid'] ) );
            $order->update_status('procesing');
        }elseif($verificacion['estado']==="pendiente"){
            exit;
        }else{
            throw new Exception("Hay un error en el plugin CriptoPay Gateway Woocomerce");
        }
    }

}
?>