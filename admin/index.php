<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: /login.php');
    exit;
}
require_once '../config/conexao.php';

// Stats
$totalCursos   = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$totalPedidos  = $pdo->query("SELECT COUNT(*) FROM pedidos")->fetchColumn();
$completedPed  = $totalPedidos; // All orders are completed by default
$receita       = $pdo->query("SELECT COALESCE(SUM(total),0) FROM pedidos")->fetchColumn();
$recentOrders  = $pdo->query("SELECT p.id, p.cliente_id, c.nome as customer_name, c.email as customer_email, p.total, 'completed' as status FROM pedidos p LEFT JOIN clientes c ON p.cliente_id = c.id ORDER BY p.id DESC LIMIT 8")->fetchAll();

// Cursos
$cursos = $pdo->query("SELECT id, nome as name, descricao as description, preco as price, COALESCE(image_url, '') as image_url, COALESCE(course_file, '') as course_file FROM produtos ORDER BY id DESC")->fetchAll();

// Pedidos com itens
$stmt = $pdo->query("SELECT p.id, p.cliente_id, c.nome as customer_name, c.email as customer_email, p.total, 'completed' as status, '' as items, p.id as created_at FROM pedidos p LEFT JOIN clientes c ON p.cliente_id = c.id ORDER BY p.id DESC");
$pedidos = $stmt->fetchAll();

// Mensagens de contato
try {
    $contatos = $pdo->query("SELECT * FROM contatos ORDER BY created_at DESC")->fetchAll();
    $totalContatos = count($contatos);
    $naoLidos = $pdo->query("SELECT COUNT(*) FROM contatos WHERE lido = 0")->fetchColumn();
} catch (Exception $e) {
    $contatos = [];
    $totalContatos = 0;
    $naoLidos = 0;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin — Free Academy</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>

<div class="admin-wrapper">
  <!-- Sidebar -->
  <aside class="admin-sidebar">
    <div class="sidebar-logo">
      <img src="https://media.base44.com/images/public/6a28c8df52b234e765db2246/a1172fc07_Gemini_Generated_Image_kahyi2kahyi2kahy.png" alt="Free Academy" />
      <p>Painel Admin</p>
    </div>
    <nav class="sidebar-nav">
      <button class="sidebar-btn active" onclick="showTab('stats', this)">📊 Dashboard</button>
      <button class="sidebar-btn" onclick="showTab('courses', this)">📚 Cursos</button>
      <button class="sidebar-btn" onclick="showTab('orders', this)">🛍 Pedidos</button>
      <button class="sidebar-btn" onclick="showTab('messages', this)">✉ Mensagens <?php if ($naoLidos > 0): ?><span style="background:#00FF94;color:#000;border-radius:9999px;padding:1px 7px;font-size:11px;margin-left:4px;"><?= $naoLidos ?></span><?php endif; ?></button>
    </nav>
    <div class="sidebar-footer">
      <a href="../logout.php" class="sidebar-logout">↩ Sair</a>
    </div>
  </aside>

  <!-- Main -->
  <div class="admin-main">
    <header class="admin-topbar">
      <h1 id="topbar-title">Dashboard</h1>
      <span class="admin-badge">admin</span>
    </header>

    <main class="admin-content">

      <!-- STATS -->
      <div id="tab-stats" class="admin-section active">
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon indigo">📚</div>
            <div><div class="stat-value"><?= $totalCursos ?></div><div class="stat-label">Cursos publicados</div></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon black">🛍</div>
            <div><div class="stat-value"><?= $totalPedidos ?></div><div class="stat-label">Total de pedidos</div></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon green">📈</div>
            <div><div class="stat-value"><?= $completedPed ?></div><div class="stat-label">Pedidos concluídos</div></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon emerald">💰</div>
            <div><div class="stat-value">R$ <?= number_format($receita, 2, ',', '.') ?></div><div class="stat-label">Receita total</div></div>
          </div>
        </div>

        <div class="table-card">
          <h2>Últimos pedidos</h2>
          <?php if (empty($recentOrders)): ?>
            <p style="color:#9ca3af;font-size:14px;">Nenhum pedido ainda.</p>
          <?php else: ?>
          <table>
            <thead>
              <tr>
                <th>Cliente</th>
                <th>E-mail</th>
                <th>Total</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentOrders as $o): ?>
              <tr>
                <td style="font-weight:700;">Pedido #<?= $o['id'] ?></td>
                <td style="color:#6b7280;">-</td>
                <td style="font-weight:900;">R$ <?= number_format($o['total'], 2, ',', '.') ?></td>
                <td>
                  <span class="badge-completed">Concluído</span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php endif; ?>
        </div>
      </div>

      <!-- CURSOS -->
      <div id="tab-courses" class="admin-section">
        <div class="admin-section-header">
          <h2>Cursos (<?= count($cursos) ?>)</h2>
          <button class="btn-new" onclick="openCourseForm()">+ Novo curso</button>
        </div>

        <div class="courses-grid">
          <?php foreach ($cursos as $c): ?>
          <div class="admin-course-card">
            <?php if ($c['image_url']): ?>
              <img src="<?= htmlspecialchars($c['image_url']) ?>" alt="<?= htmlspecialchars($c['name']) ?>" />
            <?php endif; ?>
            <h3><?= htmlspecialchars($c['name']) ?></h3>
            <p><?= htmlspecialchars($c['description']) ?></p>
            <div class="price">R$ <?= number_format($c['price'], 2, ',', '.') ?></div>
            <div class="card-actions">
              <button class="btn-edit" onclick='openCourseForm(<?= htmlspecialchars(json_encode($c)) ?>)'>✏ Editar</button>
              <button class="btn-delete" onclick="confirmDelete(<?= $c['id'] ?>)">🗑 Excluir</button>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- PEDIDOS -->
      <div id="tab-orders" class="admin-section">
        <h2 style="font-size:20px;font-weight:900;margin-bottom:24px;">Histórico de pedidos (<?= count($pedidos) ?>)</h2>

        <?php if (empty($pedidos)): ?>
          <div style="background:#fff;border-radius:16px;padding:48px;text-align:center;color:#9ca3af;">
            <div style="font-size:48px;margin-bottom:16px;">🛍</div>
            <p style="font-weight:700;">Nenhum pedido registrado ainda.</p>
          </div>
        <?php else: ?>
          <?php foreach ($pedidos as $o): ?>
          <div class="order-card">
            <div class="order-head">
              <div>
                <div class="order-customer">Pedido #<?= $o['id'] ?></div>
                <div class="order-email">Cliente ID: <?= $o['cliente_id'] ?></div>
                <div class="order-date">-</div>
              </div>
              <div class="order-total">
                R$ <?= number_format($o['total'], 2, ',', '.') ?>
                <form method="POST" action="api/update_order.php" style="margin-top:4px;">
                  <input type="hidden" name="id" value="<?= $o['id'] ?>" />
                  <select name="status" class="order-select" onchange="this.form.submit()">
                    <option value="completed" selected>Concluído</option>
                    <option value="pending">Pendente</option>
                  </select>
                </form>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- MENSAGENS DE CONTATO -->
      <div id="tab-messages" class="admin-section">
        <h2 style="font-size:20px;font-weight:900;margin-bottom:24px;">Mensagens de Contato (<?= $totalContatos ?>)</h2>

        <?php if (empty($contatos)): ?>
          <div style="background:#fff;border-radius:16px;padding:48px;text-align:center;color:#9ca3af;">
            <div style="font-size:48px;margin-bottom:16px;">✉</div>
            <p style="font-weight:700;">Nenhuma mensagem recebida ainda.</p>
            <p style="font-size:13px;margin-top:8px;">Crie a tabela rodando o arquivo <code>migracao_contatos.sql</code> no phpMyAdmin.</p>
          </div>
        <?php else: ?>
          <?php foreach ($contatos as $msg): ?>
          <div class="order-card" style="<?= !$msg['lido'] ? 'border-left:4px solid #00FF94;' : '' ?>">
            <div class="order-head">
              <div>
                <div class="order-customer" style="display:flex;align-items:center;gap:8px;">
                  <?= htmlspecialchars($msg['nome']) ?>
                  <?php if (!$msg['lido']): ?>
                    <span style="background:#00FF94;color:#000;font-size:10px;font-weight:900;padding:2px 8px;border-radius:9999px;">NOVO</span>
                  <?php endif; ?>
                </div>
                <div class="order-email"><?= htmlspecialchars($msg['email']) ?></div>
                <div class="order-date"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></div>
              </div>
              <div style="display:flex;gap:8px;align-items:flex-start;">
                <?php if (!$msg['lido']): ?>
                <form method="POST" action="api/marcar_lido.php">
                  <input type="hidden" name="id" value="<?= $msg['id'] ?>" />
                  <button type="submit" style="background:#00FF94;color:#000;border:none;border-radius:8px;padding:6px 12px;font-size:12px;font-weight:900;cursor:pointer;">✔ Marcar lido</button>
                </form>
                <?php endif; ?>
              </div>
            </div>
            <div style="background:#f9fafb;border-radius:8px;padding:16px;font-size:14px;color:#374151;line-height:1.6;">
              <?= nl2br(htmlspecialchars($msg['mensagem'])) ?>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </main>
  </div>
</div>

<!-- Modal: Criar/Editar Curso -->
<div class="modal-overlay" id="course-form-modal" style="display:none;">
  <div class="modal-form-box">
    <div class="modal-form-header">
      <h3 id="course-form-title">Novo curso</h3>
      <button class="btn-close-x" onclick="closeCourseForm()">✕</button>
    </div>
    <form method="POST" action="api/save_course.php" class="modal-form-body">
      <input type="hidden" name="id" id="course-id" value="" />
      <div class="form-group-sm">
        <label>Nome do curso *</label>
        <input type="text" name="name" id="course-name" required />
      </div>
      <div class="form-group-sm">
        <label>Preço (R$) *</label>
        <input type="number" step="0.01" name="price" id="course-price" required />
      </div>
      <div class="form-group-sm">
        <label>URL da imagem</label>
        <input type="text" name="image_url" id="course-image" />
      </div>
      <div class="form-group-sm">
        <label>Link do arquivo do curso</label>
        <input type="text" name="course_file" id="course-file" />
      </div>
      <div class="form-group-sm">
        <label>Descrição *</label>
        <textarea name="description" id="course-desc" required></textarea>
      </div>
      <div class="modal-form-footer">
        <button type="button" class="btn-cancel" onclick="closeCourseForm()">Cancelar</button>
        <button type="submit" class="btn-save">✔ Salvar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Confirmar exclusão -->
<div class="modal-overlay" id="delete-modal" style="display:none;">
  <div class="modal-box">
    <div class="delete-icon">🗑</div>
    <h3>Excluir curso?</h3>
    <p>Esta ação não pode ser desfeita.</p>
    <form method="POST" action="api/delete_course.php">
      <input type="hidden" name="id" id="delete-id" />
      <div style="display:flex;gap:12px;margin-top:8px;">
        <button type="button" class="btn-cancel" onclick="closeDeleteModal()">Cancelar</button>
        <button type="submit" class="btn-danger">Excluir</button>
      </div>
    </form>
  </div>
</div>

<script>
function showTab(tab, btn) {
  document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.sidebar-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  btn.classList.add('active');
  const labels = { stats: 'Dashboard', courses: 'Cursos', orders: 'Pedidos', messages: 'Mensagens' };
  document.getElementById('topbar-title').textContent = labels[tab];
}

function openCourseForm(curso) {
  const modal = document.getElementById('course-form-modal');
  if (curso) {
    document.getElementById('course-form-title').textContent = 'Editar curso';
    document.getElementById('course-id').value    = curso.id;
    document.getElementById('course-name').value  = curso.name;
    document.getElementById('course-price').value = curso.price;
    document.getElementById('course-image').value = curso.image_url || '';
    document.getElementById('course-file').value  = curso.course_file || '';
    document.getElementById('course-desc').value  = curso.description;
  } else {
    document.getElementById('course-form-title').textContent = 'Novo curso';
    document.getElementById('course-id').value    = '';
    document.getElementById('course-name').value  = '';
    document.getElementById('course-price').value = '';
    document.getElementById('course-image').value = '';
    document.getElementById('course-file').value  = '';
    document.getElementById('course-desc').value  = '';
  }
  modal.style.display = 'flex';
}

function closeCourseForm() {
  document.getElementById('course-form-modal').style.display = 'none';
}

function confirmDelete(id) {
  document.getElementById('delete-id').value = id;
  document.getElementById('delete-modal').style.display = 'flex';
}

function closeDeleteModal() {
  document.getElementById('delete-modal').style.display = 'none';
}

// Fecha modais ao clicar fora
document.getElementById('course-form-modal').addEventListener('click', e => {
  if (e.target === document.getElementById('course-form-modal')) closeCourseForm();
});
document.getElementById('delete-modal').addEventListener('click', e => {
  if (e.target === document.getElementById('delete-modal')) closeDeleteModal();
});
</script>

</body>
</html>
