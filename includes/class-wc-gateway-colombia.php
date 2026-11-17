<?php
/**
 * Class WC_Gateway_Colombia_Hub
 * Extends WC_Payment_Gateway
 *
 * @package WooCommerce_Nequi_Daviplata_Hub
 * @author Gabriel Montiel Chamorro (@Gabmon24)
 */

defined('ABSPATH') || exit;

class WC_Gateway_Colombia_Hub extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'nequi_daviplata_hub';
        $this->icon               = apply_filters('woocommerce_nequi_daviplata_icon', '');
        $this->has_fields         = true;
        $this->method_title       = __('Nequi, Daviplata & Bancolombia Direct', 'wc-nequi-daviplata-hub');
        $this->method_description = __('Permite a tus clientes pagar sin comisiones por Nequi, Daviplata o Bancolombia y enviar el comprobante directamente por WhatsApp.', 'wc-nequi-daviplata-hub');

        // Cargar configuración
        $this->init_form_fields();
        $this->init_settings();

        $this->title        = $this->get_option('title', 'Transferencia Directa (Nequi / Daviplata / Bancolombia)');
        $this->description  = $this->get_option('description', 'Transfiere desde tu celular sin comisiones bancarias. Copia el número y envíanos el comprobante.');
        $this->instructions = $this->get_option('instructions', 'Una vez realizada la transferencia, haz clic en el botón de WhatsApp para confirmar tu pedido.');

        // Opciones de cuentas
        $this->nequi_phone        = $this->get_option('nequi_phone', '+573207132977');
        $this->nequi_name         = $this->get_option('nequi_name', 'Gabriel Montiel');
        $this->daviplata_phone    = $this->get_option('daviplata_phone', '+573207132977');
        $this->daviplata_name     = $this->get_option('daviplata_name', 'Gabriel Montiel');
        $this->bancolombia_type   = $this->get_option('bancolombia_type', 'Ahorros');
        $this->bancolombia_num    = $this->get_option('bancolombia_num', '123-456789-00');
        $this->bancolombia_name   = $this->get_option('bancolombia_name', 'Gabriel Montiel');
        $this->whatsapp_receipt   = $this->get_option('whatsapp_receipt', '+573207132977');

        // Guardar ajustes en admin
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);

        // Hooks para Thank You Page y Emails
        add_action('woocommerce_thankyou_' . $this->id, [$this, 'thankyou_page']);
        add_action('woocommerce_email_before_order_table', [$this, 'email_instructions'], 10, 3);
    }

    public function init_form_fields() {
        $this->form_fields = [
            'enabled' => [
                'title'   => __('Activar/Desactivar', 'wc-nequi-daviplata-hub'),
                'type'    => 'checkbox',
                'label'   => __('Activar pagos directos por Nequi, Daviplata y Bancolombia', 'wc-nequi-daviplata-hub'),
                'default' => 'yes',
            ],
            'title' => [
                'title'       => __('Título en el Checkout', 'wc-nequi-daviplata-hub'),
                'type'        => 'text',
                'description' => __('Texto que ve el cliente en la pantalla de pago.', 'wc-nequi-daviplata-hub'),
                'default'     => 'Transferencia Directa (Nequi / Daviplata / Bancolombia)',
                'desc_tip'    => true,
            ],
            'description' => [
                'title'       => __('Descripción', 'wc-nequi-daviplata-hub'),
                'type'        => 'textarea',
                'description' => __('Instrucción visible debajo del título en el checkout.', 'wc-nequi-daviplata-hub'),
                'default'     => 'Transfiere desde tu aplicación bancaria preferida con 0% de comisión. Tienes opciones de Nequi, Daviplata o Bancolombia.',
            ],
            'whatsapp_receipt' => [
                'title'       => __('WhatsApp para Recibir Comprobantes', 'wc-nequi-daviplata-hub'),
                'type'        => 'text',
                'description' => __('Número de WhatsApp de la tienda al que los clientes enviarán su foto de comprobante (con código de país, ej: +573207132977).', 'wc-nequi-daviplata-hub'),
                'default'     => '+573207132977',
            ],
            'nequi_title' => [
                'title'       => __('--- Datos de Nequi ---', 'wc-nequi-daviplata-hub'),
                'type'        => 'title',
            ],
            'nequi_phone' => [
                'title'       => __('Número de Nequi', 'wc-nequi-daviplata-hub'),
                'type'        => 'text',
                'default'     => '3207132977',
            ],
            'nequi_name' => [
                'title'       => __('Nombre del Titular Nequi', 'wc-nequi-daviplata-hub'),
                'type'        => 'text',
                'default'     => 'Gabriel Montiel',
            ],
            'daviplata_title' => [
                'title'       => __('--- Datos de Daviplata ---', 'wc-nequi-daviplata-hub'),
                'type'        => 'title',
            ],
            'daviplata_phone' => [
                'title'       => __('Número de Daviplata', 'wc-nequi-daviplata-hub'),
                'type'        => 'text',
                'default'     => '3207132977',
            ],
            'daviplata_name' => [
                'title'       => __('Nombre del Titular Daviplata', 'wc-nequi-daviplata-hub'),
                'type'        => 'text',
                'default'     => 'Gabriel Montiel',
            ],
            'bancolombia_title' => [
                'title'       => __('--- Datos de Bancolombia ---', 'wc-nequi-daviplata-hub'),
                'type'        => 'title',
            ],
            'bancolombia_type' => [
                'title'       => __('Tipo de Cuenta Bancolombia', 'wc-nequi-daviplata-hub'),
                'type'        => 'select',
                'options'     => [
                    'Ahorros'   => 'Cuenta de Ahorros',
                    'Corriente' => 'Cuenta Corriente',
                ],
                'default'     => 'Ahorros',
            ],
            'bancolombia_num' => [
                'title'       => __('Número de Cuenta Bancolombia', 'wc-nequi-daviplata-hub'),
                'type'        => 'text',
                'default'     => '123-456789-00',
            ],
            'bancolombia_name' => [
                'title'       => __('Nombre del Titular Bancolombia', 'wc-nequi-daviplata-hub'),
                'type'        => 'text',
                'default'     => 'Gabriel Montiel',
            ],
        ];
    }

    /**
     * Renderizado de las opciones de pago en la página de Checkout
     */
    public function payment_fields() {
        if ($this->description) {
            echo '<p class="wck-desc">' . wp_kses_post(wpautop(wptexturize($this->description))) . '</p>';
        }
        ?>
        <div class="wck-accounts-container">
            <!-- Tarjeta Nequi -->
            <?php if (!empty($this->nequi_phone)) : ?>
                <div class="wck-card wck-nequi">
                    <div class="wck-card-header">
                        <span class="wck-badge wck-badge-nequi">Nequi</span>
                        <span class="wck-titular"><?php echo esc_html($this->nequi_name); ?></span>
                    </div>
                    <div class="wck-card-body">
                        <div class="wck-number-box">
                            <span class="wck-num" id="wck-nequi-val"><?php echo esc_html($this->nequi_phone); ?></span>
                            <button type="button" class="wck-copy-btn" data-target="wck-nequi-val" title="<?php esc_attr_e('Copiar número', 'wc-nequi-daviplata-hub'); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                <span>Copiar</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tarjeta Daviplata -->
            <?php if (!empty($this->daviplata_phone)) : ?>
                <div class="wck-card wck-daviplata">
                    <div class="wck-card-header">
                        <span class="wck-badge wck-badge-daviplata">Daviplata</span>
                        <span class="wck-titular"><?php echo esc_html($this->daviplata_name); ?></span>
                    </div>
                    <div class="wck-card-body">
                        <div class="wck-number-box">
                            <span class="wck-num" id="wck-daviplata-val"><?php echo esc_html($this->daviplata_phone); ?></span>
                            <button type="button" class="wck-copy-btn" data-target="wck-daviplata-val" title="<?php esc_attr_e('Copiar número', 'wc-nequi-daviplata-hub'); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                <span>Copiar</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tarjeta Bancolombia -->
            <?php if (!empty($this->bancolombia_num)) : ?>
                <div class="wck-card wck-bancolombia">
                    <div class="wck-card-header">
                        <span class="wck-badge wck-badge-bancolombia">Bancolombia (<?php echo esc_html($this->bancolombia_type); ?>)</span>
                        <span class="wck-titular"><?php echo esc_html($this->bancolombia_name); ?></span>
                    </div>
                    <div class="wck-card-body">
                        <div class="wck-number-box">
                            <span class="wck-num" id="wck-bancolombia-val"><?php echo esc_html($this->bancolombia_num); ?></span>
                            <button type="button" class="wck-copy-btn" data-target="wck-bancolombia-val" title="<?php esc_attr_e('Copiar número', 'wc-nequi-daviplata-hub'); ?>">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                                <span>Copiar</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <p class="wck-help-text">💡 <em><?php esc_html_e('Al completar tu pedido podrás enviar el comprobante directamente a nuestro WhatsApp para despacho inmediato.', 'wc-nequi-daviplata-hub'); ?></em></p>
        <?php
    }

    /**
     * Procesar el pago: poner la orden en espera y vaciar carrito
     */
    public function process_payment($order_id) {
        $order = wc_get_order($order_id);

        // Estado "En espera" (on-hold) para verificar transferencia
        $order->update_status('on-hold', __('Esperando transferencia bancaria directa (Nequi / Daviplata / Bancolombia).', 'wc-nequi-daviplata-hub'));

        // Reducir stock
        wc_reduce_stock_levels($order_id);

        // Vaciar carrito
        WC()->cart->empty_cart();

        // Redireccionar a la página de Pedido Recibido
        return [
            'result'   => 'success',
            'redirect' => $this->get_return_url($order),
        ];
    }

    /**
     * Pantalla de Pedido Recibido (Thank You Page) con botón de WhatsApp
     */
    public function thankyou_page($order_id) {
        $order = wc_get_order($order_id);
        if (!$order) return;

        $total       = html_entity_decode(strip_tags($order->get_formatted_order_total()));
        $order_num   = $order->get_order_number();
        $client_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
        $admin_phone = preg_replace('/[^0-9]/', '', $this->whatsapp_receipt);

        // Resumen de productos comprados
        $items_str = '';
        foreach ($order->get_items() as $item) {
            $items_str .= '• ' . $item->get_name() . ' (x' . $item->get_quantity() . ")\n";
        }

        // Mensaje predeterminado de WhatsApp para el cliente
        $msg = "¡Hola! 👋 Acabo de realizar el pedido #{$order_num} en su tienda.\n\n";
        $msg .= "👤 Cliente: {$client_name}\n";
        $msg .= "💰 Total a pagar: {$total}\n";
        $msg .= "📦 Productos:\n{$items_str}\n";
        $msg .= "Adjunto aquí la captura de mi comprobante de pago para confirmación. ¡Muchas gracias!";

        $whatsapp_url = 'https://api.whatsapp.com/send?phone=' . $admin_phone . '&text=' . rawurlencode($msg);
        ?>
        <div class="wck-thankyou-box">
            <div class="wck-ty-header">
                <h3>📲 Confirma tu Pago para Despacho Inmediato</h3>
                <p>Tu pedido <strong>#<?php echo esc_html($order_num); ?></strong> ha sido registrado. Envíanos tu comprobante por WhatsApp para validar la transferencia y alistar tu envío.</p>
            </div>
            
            <a href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer" class="wck-whatsapp-cta">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2Z"/>
                </svg>
                <span>Enviar Comprobante por WhatsApp</span>
            </a>
        </div>
        <?php
    }

    /**
     * Instrucciones en el correo de confirmación de pedido
     */
    public function email_instructions($order, $sent_to_admin, $plain_text = false) {
        if ($order->get_payment_method() !== $this->id) {
            return;
        }

        echo "<h3>" . esc_html__('Datos de Transferencia Bancaria (Colombia)', 'wc-nequi-daviplata-hub') . "</h3>";
        echo "<ul>";
        if (!empty($this->nequi_phone)) {
            echo "<li><strong>Nequi:</strong> " . esc_html($this->nequi_phone) . " (" . esc_html($this->nequi_name) . ")</li>";
        }
        if (!empty($this->daviplata_phone)) {
            echo "<li><strong>Daviplata:</strong> " . esc_html($this->daviplata_phone) . " (" . esc_html($this->daviplata_name) . ")</li>";
        }
        if (!empty($this->bancolombia_num)) {
            echo "<li><strong>Bancolombia (" . esc_html($this->bancolombia_type) . "):</strong> " . esc_html($this->bancolombia_num) . " (" . esc_html($this->bancolombia_name) . ")</li>";
        }
        echo "</ul>";
        echo "<p>" . esc_html__('Recuerda enviar el comprobante a nuestro WhatsApp: ', 'wc-nequi-daviplata-hub') . "<strong>" . esc_html($this->whatsapp_receipt) . "</strong></p>";
    }
}
