<?php
require_once 'includes/proteger.php';
require_once 'config/conexao.php';
require_once 'includes/header.php';

// O login salva o cliente inteiro na sessão — tenta pegar o id de diferentes formas
$clienteId = $_SESSION['cliente']['id'] ?? null;
if (!$clienteId) {
    // fallback: busca pelo email se o id não estiver na sessão
    $email = $_SESSION['cliente']['email'] ?? '';
    if ($email) {
        $stmtId = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
        $stmtId->execute([$email]);
        $clienteId = $stmtId->fetchColumn();
    }
}

$pedidos = [];
if ($clienteId) {
    $stmt = $pdo->prepare("SELECT id, total, data_pedido FROM pedidos WHERE cliente_id = ? ORDER BY id DESC");
    $stmt->execute([$clienteId]);
    $pedidos = $stmt->fetchAll();
}

// Para cada pedido, busca os itens
$itensPorPedido = [];
foreach ($pedidos as $ped) {
    $stmtItens = $pdo->prepare("
        SELECT pr.nome as course_name, ip.preco_unitario as price, COALESCE(pr.course_file,'') as course_file
        FROM itens_pedido ip
        JOIN produtos pr ON pr.id = ip.produto_id
        WHERE ip.pedido_id = ?
    ");
    $stmtItens->execute([$ped['id']]);
    $itensPorPedido[$ped['id']] = $stmtItens->fetchAll();
}

$nomeCliente = explode(' ', $_SESSION['cliente']['name'] ?? $_SESSION['cliente']['nome'] ?? 'Cliente')[0];
?>

<div class="page">
  <section class="section max-600" style="max-width:800px;">
    <div class="tag">Área do Cliente</div>
    <h1 class="section-title" style="font-size:40px;">Meus Pedidos</h1>
    <p style="color:#6b7280;margin-bottom:40px;">Olá, <strong><?= htmlspecialchars($nomeCliente) ?></strong>! Aqui estão todas as suas compras.</p>

    <?php if (empty($pedidos)): ?>
      <div style="background:#fff;border:1px solid rgba(15,17,21,0.1);border-radius:16px;padding:64px;text-align:center;">
        <div style="font-size:56px;margin-bottom:16px;">📦</div>
        <h2 style="font-weight:900;font-size:22px;margin-bottom:8px;">Nenhum pedido ainda</h2>
        <p style="color:#6b7280;margin-bottom:24px;">Você ainda não realizou nenhuma compra.</p>
        <a href="loja.php" class="btn-primary">Ver cursos disponíveis →</a>
      </div>
    <?php else: ?>
      <?php foreach ($pedidos as $ped): ?>
      <div style="background:#fff;border:1px solid rgba(15,17,21,0.1);border-radius:16px;padding:24px;margin-bottom:20px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid #f3f4f6;">
          <div>
            <div style="font-weight:900;font-size:16px;">Pedido #<?= $ped['id'] ?></div>
            <div style="font-size:13px;color:#9ca3af;margin-top:2px;">
              <?= !empty($ped['data_pedido']) ? date('d/m/Y \à\s H:i', strtotime($ped['data_pedido'])) : 'Data não disponível' ?>
            </div>
          </div>
          <div style="text-align:right;">
            <div style="font-weight:900;font-size:22px;">R$ <?= number_format($ped['total'], 2, ',', '.') ?></div>
            <span style="background:#dcfce7;color:#15803d;font-size:11px;font-weight:900;padding:2px 10px;border-radius:9999px;">Concluído</span>
          </div>
        </div>

        <?php foreach ($itensPorPedido[$ped['id']] as $item): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;font-size:14px;flex-wrap:wrap;gap:8px;">
          <span style="font-weight:700;">📚 <?= htmlspecialchars($item['course_name']) ?></span>
          <div style="display:flex;align-items:center;gap:12px;">
            <span style="font-weight:900;">R$ <?= number_format($item['price'], 2, ',', '.') ?></span>
            <?php if ($item['course_file']): ?>
              <a href="<?= htmlspecialchars($item['course_file']) ?>" target="_blank"
                 style="background:#00FF94;color:#000;font-weight:900;font-size:12px;padding:4px 12px;border-radius:8px;white-space:nowrap;">
                ⬇ Baixar
              </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</div>

<?php require_once 'includes/footer.php'; ?>
