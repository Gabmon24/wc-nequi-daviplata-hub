# 🇨🇴 WooCommerce Nequi & Daviplata Direct Hub

<div align="center">

![WooCommerce](https://img.shields.io/badge/WooCommerce-6.0%20to%209.5%2B-96588A?style=for-the-badge&logo=woocommerce&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-5.8%2B-21759B?style=for-the-badge&logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-7.4%20|%208.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![HPOS Ready](https://img.shields.io/badge/WooCommerce-HPOS%20Compatible-success?style=for-the-badge)
![Commissions](https://img.shields.io/badge/Gateway%20Commissions-0%25-brightgreen?style=for-the-badge)
![License](https://img.shields.io/badge/License-GPLv2-blue?style=for-the-badge)

**A high-conversion WooCommerce payment gateway for Colombian e-commerce businesses that enables direct payments via Nequi, Daviplata, and Bancolombia with 0% gateway commissions and automated WhatsApp payment proof dispatching.**

[Ver Código en GitHub](https://github.com/Gabmon24/wc-nequi-daviplata-hub) · [Reportar Bug](https://github.com/Gabmon24/wc-nequi-daviplata-hub/issues)

</div>

---

## 💡 The Problem It Solves

Payment gateways in Colombia (such as Wompi, PayU, and Bold) take **2.9% to 3.5% + $900 COP** on every transaction. For micro-businesses, retail stores, and local Colombian e-commerce, this represents thousands of dollars lost monthly in transaction fees.

Furthermore, **over 70% of online shoppers in Colombia** prefer direct mobile transfers (Nequi, Daviplata, or Bancolombia App).

**WC Nequi & Daviplata Direct Hub** provides a seamless, friction-free checkout experience:
1. Customers pick direct transfer during checkout.
2. Distinct, branded cards display the store's **Nequi, Daviplata, or Bancolombia** account with a **1-click "Copiar" button**.
3. On order placement, the customer is presented with an automated **"Enviar Comprobante por WhatsApp"** button pre-populated with their **Order #, Customer Name, Total Amount in COP, and Purchased Products list**.

---

## ✨ Features

* 📱 **Branded Checkout Cards:** Clean, visual cards with official brand accents for Nequi (Magenta/Dark), Daviplata (Red), and Bancolombia (Yellow/Black).
* 📋 **1-Click Copy to Clipboard:** Fast copy interaction with visual checkmark feedback so customers never mistype the phone number or account.
* 📲 **Automated WhatsApp Confirmation:** Generates a pre-formatted WhatsApp chat link for the store administrator containing the order ID, itemized list, customer name, and total.
* ⚡ **High-Performance Order Storage (HPOS) Ready:** Declares native compatibility with WooCommerce's modern database engine (`custom_order_tables`).
* ✉️ **Automated Email Instructions:** Injects transfer instructions and account numbers directly into WooCommerce customer order emails.
* 🛡️ **Zero Bloat & Secure:** Built extending the native `WC_Payment_Gateway` class with data sanitization, nonces, and zero jQuery dependencies.

---

## 📸 Workflow Preview

```text
[Cliente en Carrito] 
       ⬇
[Pantalla de Checkout] ➔ Selecciona "Transferencia Directa (Nequi / Daviplata / Bancolombia)"
       ⬇                  ➔ Tarjetas con botón de 1-Clic para Copiar Número
[Clic en "Realizar Pedido"]
       ⬇
[Pantalla de Pedido Recibido (Thank You)]
       ⬇ 
[Botón Verde WhatsApp: "Enviar Comprobante"]
       ⬇ 
Abre WhatsApp con mensaje pre-redactado:
"¡Hola! 👋 Acabo de realizar el pedido #1042 en su tienda.
👤 Cliente: Carlos Pérez
💰 Total a pagar: $125.000 COP
📦 Productos:
• Proteína Whey 2lb (x1)
• Creatina Monohidratada 300g (x1)
Adjunto aquí la captura de mi comprobante de pago."
```

---

## 🏗️ Structure

```text
wc-nequi-daviplata-hub/
├── assets/
│   ├── css/
│   │   └── checkout.css          # Responsive checkout & thank you styles
│   └── js/
│       └── checkout.js           # Clipboard interaction & feedback
├── includes/
│   ├── class-wc-gateway-colombia.php # WC_Payment_Gateway implementation
│   └── index.php                 # Security guard
├── index.php                     # Security guard
├── LICENSE                       # GPLv2
├── README.md                     # Documentation
└── wc-nequi-daviplata-hub.php    # Plugin bootstrap & HPOS declaration
```

---

## 🚀 Installation & Setup

1. Clone or upload the folder to your WordPress plugins directory:
   ```bash
   cd wp-content/plugins/
   git clone https://github.com/Gabmon24/wc-nequi-daviplata-hub.git
   ```
2. Navigate to **Plugins** in the WordPress Dashboard and activate **WooCommerce Nequi & Daviplata Direct Hub**.
3. Go to **WooCommerce ➔ Ajustes ➔ Pagos**.
4. Enable **Nequi, Daviplata & Bancolombia Direct** and click **Gestionar**.
5. Enter your Nequi phone, Daviplata phone, Bancolombia account details, and WhatsApp store phone number. Click **Guardar cambios**.

---

## 👨‍💻 Author

**Gabriel Montiel Chamorro**
* GitHub: [@Gabmon24](https://github.com/Gabmon24)
* LinkedIn: [linkedin.com/in/gabriel-montielc](https://www.linkedin.com/in/gabriel-montielc/)
* Location: Montería, Córdoba, Colombia 🇨🇴

---

## 📄 License

GPL-2.0 or later.
