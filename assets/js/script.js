// ============================================
// FREE ACADEMY - JavaScript Global
// ============================================

// === CARRINHO LOCAL (localStorage) ===
function getCarrinho() {
  return JSON.parse(localStorage.getItem('fa_carrinho') || '[]');
}

function saveCarrinho(carrinho) {
  localStorage.setItem('fa_carrinho', JSON.stringify(carrinho));
}

function addToCarrinho(curso) {
  const carrinho = getCarrinho();
  const exists = carrinho.find(c => c.id === curso.id);
  if (!exists) {
    carrinho.push(curso);
    saveCarrinho(carrinho);
  }
  updateCartBadge();
  showToast('Curso adicionado ao carrinho!');
}

function removeFromCarrinho(id) {
  let carrinho = getCarrinho();
  carrinho = carrinho.filter(c => c.id !== id);
  saveCarrinho(carrinho);
  updateCartBadge();
}

function clearCarrinho() {
  localStorage.removeItem('fa_carrinho');
  updateCartBadge();
}

function updateCartBadge() {
  const count = getCarrinho().length;
  const badge = document.querySelector('.cart-badge');
  if (badge) {
    badge.textContent = count;
    badge.style.display = count > 0 ? 'flex' : 'none';
  } else if (count > 0) {
    const cartBtn = document.querySelector('.btn-cart');
    if (cartBtn) {
      const span = document.createElement('span');
      span.className = 'cart-badge';
      span.textContent = count;
      cartBtn.style.position = 'relative';
      cartBtn.appendChild(span);
    }
  }
}

// === TOAST ===
function showToast(msg) {
  const toast = document.createElement('div');
  toast.textContent = msg;
  toast.style.cssText = `
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    background: #00FF94; color: #000; padding: 12px 24px;
    font-weight: 900; font-size: 14px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.2);
    animation: slideUp 0.3s ease;
  `;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 2500);
}

// === COURSE MODAL ===
function openCourseModal(curso) {
  // Normaliza campos (banco retorna nome/descricao/preco)
  const nome = curso.name || curso.nome || '';
  const descricao = curso.description || curso.descricao || '';
  const preco = parseFloat(curso.price || curso.preco || 0);
  const imagem = curso.image_url || '';

  // Garante campos normalizados no objeto para reuso
  curso.name = nome;
  curso.description = descricao;
  curso.price = preco;

  const overlay = document.createElement('div');
  overlay.className = 'modal-overlay';
  overlay.id = 'course-modal';

  const imgHtml = imagem
    ? `<img src="${imagem}" alt="${nome}" style="width:100%;height:200px;object-fit:cover;display:block;">`
    : `<div class="course-modal-thumb"></div>`;

  overlay.innerHTML = `
    <div class="course-modal-box">
      <div class="course-modal-header">
        ${imgHtml}
        <button class="course-modal-close" onclick="closeCourseModal()">✕</button>
      </div>
      <div class="course-modal-body">
        <h2>${nome}</h2>
        <p>${descricao}</p>
        <div class="course-modal-price">R$ ${preco.toFixed(2).replace('.', ',')}</div>
        <div class="course-modal-actions">
          <button class="btn-cart-icon" onclick="addToCarrinho(${JSON.stringify(curso).replace(/"/g, '&quot;')}); closeCourseModal();" title="Adicionar ao carrinho">🛒</button>
          <button class="btn-buy" onclick="buyNow(${JSON.stringify(curso).replace(/"/g, '&quot;')})">Comprar agora</button>
        </div>
      </div>
    </div>
  `;

  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) closeCourseModal();
  });

  document.body.appendChild(overlay);
}

function closeCourseModal() {
  const modal = document.getElementById('course-modal');
  if (modal) modal.remove();
}

function buyNow(curso) {
  addToCarrinho(curso);
  closeCourseModal();
  window.location.href = 'checkout.php';
}

// === INIT ===
document.addEventListener('DOMContentLoaded', () => {
  updateCartBadge();
});

// CSS animation
const style = document.createElement('style');
style.textContent = `
  @keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to   { transform: translateY(0);    opacity: 1; }
  }
`;
document.head.appendChild(style);
