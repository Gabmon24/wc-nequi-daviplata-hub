/**
 * WooCommerce Nequi & Daviplata Direct Hub — Checkout JS
 */
document.addEventListener('DOMContentLoaded', () => {
  const data = window.wckData || {};
  const copiedText = data.copiedText || '¡Copiado!';

  // Event Delegation para botones de copiar
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.wck-copy-btn');
    if (!btn) return;

    const targetId = btn.getAttribute('data-target');
    const targetEl = document.getElementById(targetId);
    if (!targetEl) return;

    const textToCopy = targetEl.textContent.trim();

    navigator.clipboard.writeText(textToCopy).then(() => {
      const span = btn.querySelector('span');
      const originalText = span ? span.textContent : 'Copiar';

      btn.classList.add('copied');
      if (span) span.textContent = copiedText;

      setTimeout(() => {
        btn.classList.remove('copied');
        if (span) span.textContent = originalText;
      }, 2000);
    }).catch(err => {
      console.error('Error al copiar al portapapeles:', err);
    });
  });
});
