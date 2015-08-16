=== Woocommerce CriptoPay ===
Contributors: CriptoPay
Donate link: https://cripto-pay.com/
Tags: woocommerce, criptopay, bitcoin, altcoin, divisa, fiat, cripto-moneda
Requires at least: 4.0.1
Tested up to: 4.3
Stable tag: 4.3
License: Copyright 2014-2015 CriptoPay S.L., MIT License
URI: https://github.com/criptopay/wordpress_woocomerce/blob/master/LICENSE
WC requires at least: 2.2
WC tested up to: 2.3

Integración para Woocommerce del sistema de pago con cripto-monedas con Cripto-Pay.

== Description ==

Añada la pasarela de pagos más segura a Woocommerce.
Permita a sus usuarios pagar con Bitcoins y Altcoins.

Web oficial: [criptopay](https://cripto-pay.com/)

== Installation ==

Puede instalarlo automáticamente desde Wordpress, o manualmente:

1. Descomprima el archivo, y copie la carpeta 'woocommerce-servired-integration-light' en su carpeta de plugins del servidor (/wp-content/plugins/).
1. Active el plugin desde el menú de Plugins.

== Preguntas frecuentes (FAQs) ==

= ¿ Donde consigo los datos de configuración ? =

Su entidad bancaria debe proporcionarle los datos de acceso. Una vez que los tenga, basta con añadirlos al plugin, y ya lo tendrá funcionando.

= ¿ Donde se configura el plugin ? =

En el menú de Woocommerce->Ajustes
En la pestaña de "Pasarelas de Pago"

== Screenshots ==

1. Admin - página de configuración.
2. Tienda - Formas de pago.


== Changelog ==
=2.0=
*Actualizado a la versión 2.0 API REST CriptoPay

= 1.5 =
* Compatible con Woocommerce 2.3.x
* Corregido error con https
* Añadidos filtros: wooservired_light_title, wooservired_light_description, wooservired_light_param_urlOK, and wooservired_light_param_KO

= 1.4 =
* Añadida selección entre url de Sermepa o RedSys.
* Comprobada compatibilidad con Wordpress hasta 3.9
* Comprobada compatibilidad con Woocommerce hasta 2.1.8
= 1.3.1 =
* Corregido error Pedido Duplicado
* Al terminar transacción correctamente muestra resumen del pedido.

= 1.3 =
* Corregido error específico La Caixa.

= 1.2 =
* Ampliado formato de firma y corregido error existente en ella.

= 1.1.1 =
* Soporte multilenguaje. Actualmente inglés y español.
* Corregido: fallo al activar el plugin sin tener instalado Woocommerce.

= 1.1.0 = 
* Corregido fallo nombre de plugin. No cargaba logotipo correctamente.

= 1.0.0 =
* Versión inicial.
