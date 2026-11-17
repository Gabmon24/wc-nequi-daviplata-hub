<?php
/**
 * Plugin Name:       WooCommerce Nequi & Daviplata Direct Hub (Colombia)
 * Plugin URI:        https://github.com/Gabmon24/wc-nequi-daviplata-hub
 * Description:       Pasarela de pago y confirmación directa para tiendas WooCommerce en Colombia. Permite cobros directos sin comisiones por Nequi, Daviplata y Bancolombia, con botones de copiar cuenta en un clic, cálculo exacto en COP y botón dinámico para enviar comprobante de pago por WhatsApp en la página de pedido recibido.
 * Version:           1.0.0
 * Author:            Gabriel Montiel Chamorro (@Gabmon24)
 * Author URI:        https://github.com/Gabmon24
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wc-nequi-daviplata-hub
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * WC requires at least: 6.0
 * WC tested up to:   9.5
 */

defined('ABSPATH') || exit;

define('WCK_VERSION', '1.0.0');
define('WCK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WCK_PLUGIN_URL', plugin_dir_url(__FILE__));

// Declarar compatibilidad con High-Performance Order Storage (HPOS) de WooCommerce
add_action('before_woocommerce_init', function() {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

// Comprobar que WooCommerce está activo
add_action('plugins_loaded', 'wck_init_gateway_class', 11);

function wck_init_gateway_class() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    require_once WCK_PLUGIN_DIR . 'includes/class-wc-gateway-colombia.php';

    // Registrar la pasarela en WooCommerce
    add_filter('woocommerce_payment_gateways', function($gateways) {
        $gateways[] = 'WC_Gateway_Colombia_Hub';
        return $gateways;
    });
}

// Cargar scripts y estilos en Checkout y Thank You page
add_action('wp_enqueue_scripts', function() {
    if (is_checkout() || is_order_received_page()) {
        wp_enqueue_style('wck-checkout-css', WCK_PLUGIN_URL . 'assets/css/checkout.css', [], WCK_VERSION);
        wp_enqueue_script('wck-checkout-js', WCK_PLUGIN_URL . 'assets/js/checkout.js', [], WCK_VERSION, true);
        wp_localize_script('wck-checkout-js', 'wckData', [
            'copiedText' => __('¡Copiado!', 'wc-nequi-daviplata-hub'),
        ]);
    }
});

// Enlace de configuración rápida en la lista de plugins
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=nequi_daviplata_hub') . '">' . __('Configuración', 'wc-nequi-daviplata-hub') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});
