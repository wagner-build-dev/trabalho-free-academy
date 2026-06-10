<?php require_once 'config/conexao.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="page">
  <section class="section">
    <div class="tag">Meu Carrinho</div>
    <h1 class="section-title">Carrinho de Compras</h1>

    <!-- Carrinho renderizado via JS (localStorage) -->
    <div id="cart-container"></div>
  </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const carrinho = getCarrinho();
  const container = document.getElementById('cart-container');
  const cliente = JSON.parse(sessionStorage.getItem('fa_cliente') || 'null');

  if (carrinho.length === 0) {
    container.innerHTML = `
      <div class="empty-cart">
        <div class="empty-cart-icon">🛒</div>
        <h2>Seu carrinho está vazio</h2>
        <a href="loja.php" class="btn-primary">Ver cursos</a>
      </div>
    `;
    return;
  }

  // Normaliza campos (banco usa nome/preco/descricao)
  carrinho.forEach(c => {
    c.name = c.name || c.nome || '';
    c.price = parseFloat(c.price || c.preco || 0);
    c.description = c.description || c.descricao || '';
  });

  const total = carrinho.reduce((sum, c) => sum + c.price, 0);

  let itemsHtml = carrinho.map(c => `
    <div class="cart-item">
      <div class="cart-thumb">
        ${c.image_url ? `<img src="${c.image_url}" alt="${c.name}" />` : ''}
      </div>
      <div class="cart-info">
        <h3>${c.name}</h3>
        <p>${c.description}</p>
      </div>
      <div>
        <div class="cart-price">R$ ${c.price.toFixed(2).replace('.', ',')}</div>
        <button class="btn-remove" onclick="removeItem(${c.id})">🗑 Remover</button>
      </div>
    </div>
  `).join('');

  let summaryItems = carrinho.map(c => `
    <div class="summary-item">
      <span>${c.name}</span>
      <span>R$ ${c.price.toFixed(2).replace('.', ',')}</span>
    </div>
  `).join('');

  const warningHtml = !<?= isset($_SESSION['cliente']) ? 'true' : 'false' ?> ? '<p class="alert-warning">⚠ Faça login para continuar</p>' : '';

  container.innerHTML = `
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:32px;align-items:start;">
      <div>${itemsHtml}</div>
      <div class="cart-summary">
        <h3>Resumo do pedido</h3>
        ${summaryItems}
        <div class="summary-total">
          <span>Total</span>
          <span>R$ ${total.toFixed(2).replace('.', ',')}</span>
        </div>
        ${warningHtml}
        <button class="btn-full" onclick="goCheckout()" style="font-size:18px;">
          Finalizar Compra →
        </button>
      </div>
    </div>
  `;
});

function removeItem(id) {
  removeFromCarrinho(id);
  location.reload();
}

function goCheckout() {
  const cliente = <?= isset($_SESSION['cliente']) ? 'true' : 'false' ?>;
  if (!cliente) {
    window.location.href = 'login.php';
  } else {
    window.location.href = 'checkout.php';
  }
}
</script>

<?php require_once 'includes/footer.php'; ?>