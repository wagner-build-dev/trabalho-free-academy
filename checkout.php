<?php require_once 'config/conexao.php'; ?>
<?php require_once 'includes/proteger.php'; // Redireciona para login se não estiver logado ?>
<?php
$cliente = $_SESSION['cliente'] ?? [];
// Normaliza campos do cliente (banco usa 'nome', sessão pode usar 'name')
if (empty($cliente['name']) && !empty($cliente['nome'])) {
    $cliente['name'] = $cliente['nome'];
}
if (empty($cliente['email']) && !empty($cliente['email'])) {
    $cliente['email'] = $cliente['email'];
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="page">
  <section class="section max-600">
    <!-- Steps -->
    <div class="steps" id="steps-bar">
      <div class="step-item">
        <div class="step-num active" id="step-num-1">1</div>
        <span class="step-label active">Dados</span>
      </div>
      <div class="step-sep"></div>
      <div class="step-item">
        <div class="step-num inactive" id="step-num-2">2</div>
        <span class="step-label inactive" id="step-lbl-2">Pagamento</span>
      </div>
      <div class="step-sep"></div>
      <div class="step-item">
        <div class="step-num inactive" id="step-num-3">3</div>
        <span class="step-label inactive" id="step-lbl-3">Concluído</span>
      </div>
    </div>

    <!-- Step 1: Dados -->
    <div id="step-1" class="checkout-box">
      <h2 style="font-size:24px;font-weight:900;margin-bottom:24px;">Confirme seus dados</h2>
      <div id="dados-container"></div>
      <button class="btn-full" style="margin-top:24px;" onclick="goStep2()">💳 Ir para Pagamento</button>
    </div>

    <!-- Step 2: Pagamento -->
    <div id="step-2" class="checkout-box" style="display:none;text-align:center;">
      <div style="width:64px;height:64px;background:#000;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:32px;">💳</div>
      <h2 style="font-size:24px;font-weight:900;margin-bottom:8px;">Confirmar Pagamento</h2>
      <p style="color:#6b7280;margin-bottom:32px;">Total a pagar: <strong id="total-display" style="font-size:18px;color:#000;"></strong></p>
      <button class="btn-full" id="btn-confirm" onclick="confirmarPagamento()">✔ Confirmar Pagamento</button>
    </div>

    <!-- Step 3: Sucesso -->
    <div id="step-3" class="checkout-box text-center" style="display:none;">
      <div class="success-icon">✔</div>
      <h2 style="font-size:30px;font-weight:900;margin-bottom:12px;">Compra Concluída!</h2>

      <div id="download-box"></div>
      <p style="color:#6b7280;font-size:14px;margin-bottom:32px;">Seu pedido foi registrado com sucesso.</p>
      <a href="loja.php" class="btn-full" style="display:flex;justify-content:center;">Continuar Comprando</a>
    </div>
  </section>
</div>

<script>
const cliente = <?= json_encode($cliente) ?>;

// Renderiza dados do cliente no step 1
document.addEventListener('DOMContentLoaded', () => {
  // Carrinho lido AQUI, depois que script.js já foi carregado
  const carrinhoRaw = JSON.parse(localStorage.getItem('fa_carrinho') || '[]');
  const carrinho = carrinhoRaw.map(c => ({
    ...c,
    name:  c.name  || c.nome  || '',
    price: parseFloat(c.price || c.preco || 0),
    description: c.description || c.descricao || ''
  }));

  window._carrinho = carrinho; // torna global para usar em confirmarPagamento

  if (!carrinho.length) {
    window.location.href = 'loja.php';
    return;
  }

  const total = carrinho.reduce((sum, c) => sum + parseFloat(c.price || c.preco || 0), 0);

  document.getElementById('dados-container').innerHTML = `
    <div class="data-row"><span>Nome</span><span>${cliente.name || cliente.nome || ''}</span></div>
    <div class="data-row"><span>E-mail</span><span>${cliente.email || ''}</span></div>
    <div class="data-row"><span>Cursos</span><span>${carrinho.length} curso(s)</span></div>
    <div class="data-row total-row" style="border:none;"><span>Total</span><span class="total-badge">R$ ${total.toFixed(2).replace('.', ',')}</span></div>
  `;

  document.getElementById('total-display').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;

});

function setStep(n) {
  [1,2,3].forEach(i => {
    document.getElementById(`step-${i}`).style.display = i === n ? 'block' : 'none';
    const num = document.getElementById(`step-num-${i}`);
    const lbl = document.getElementById(`step-lbl-${i}`);
    if (num) {
      num.className = 'step-num ' + (i < n ? 'done' : i === n ? 'active' : 'inactive');
      num.textContent = i < n ? '✓' : i;
    }
    if (lbl) lbl.className = 'step-label ' + (i === n ? 'active' : 'inactive');
  });
}

function goStep2() {
  setStep(2);
}

async function confirmarPagamento() {
  const carrinho = window._carrinho || [];
  const btn = document.getElementById('btn-confirm');
  btn.disabled = true;
  btn.textContent = '⏳ Processando...';

  const total = carrinho.reduce((sum, c) => sum + c.price, 0);
  const courses = carrinho.map(c => ({ course_id: c.id, course_name: c.name, price: c.price }));

  // Salva pedido via AJAX
  const resp = await fetch('api/criar_pedido.php', {
    method: 'POST',
    credentials: 'include',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      customer_name: cliente.name || cliente.nome,
      customer_email: cliente.email,
      courses,
      total
    })
  });

  const result = await resp.json().catch(() => null);

  if (resp.ok && result && result.sucesso) {
    const carrinho = window._carrinho || [];
    // Monta links de download
    let downloadHtml = '';
    if (carrinho.some(c => c.course_file)) {
      downloadHtml = `<div class="download-box">
        <p>Baixe seu curso aqui:</p>
        ${carrinho.map(c => `
          <div class="download-item">
            <small>${c.name}</small>
            ${c.course_file
              ? `<a href="${c.course_file.startsWith('http') ? c.course_file : 'https://' + c.course_file}" target="_blank" class="download-link">${c.course_file}</a>`
              : `<span class="no-link">Link não cadastrado para este curso.</span>`
            }
          </div>
        `).join('')}
      </div>`;
    }
    document.getElementById('download-box').innerHTML = downloadHtml;

    clearCarrinho();
    setStep(3);
  } else {
    btn.disabled = false;
    btn.textContent = '✔ Confirmar Pagamento';
    const msg = result ? (result.erro || JSON.stringify(result)) : 'Erro desconhecido';
    alert('Erro ao processar pedido: ' + msg);
  }
}
</script>

<?php require_once 'includes/footer.php'; ?>