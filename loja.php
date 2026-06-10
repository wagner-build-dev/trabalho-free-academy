<?php require_once 'config/conexao.php'; ?>
<?php require_once 'includes/header.php'; ?>

<?php
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY id DESC");
$cursos = $stmt->fetchAll();
?>

<div class="page">
  <section class="section">
    <div class="tag">Course Exchange</div>
    <h1 class="section-title">Loja de cursos</h1>
    <p class="section-sub">Escolha um curso e finalize a compra para receber o acesso imediatamente.</p>

    <?php if (empty($cursos)): ?>
      <p style="color:#9ca3af;font-size:18px;">Nenhum curso disponível no momento.</p>
    <?php else: ?>
    <div class="grid-3">
      <?php foreach ($cursos as $curso): ?>
      <article class="course-card" onclick="openCourseModal(<?= htmlspecialchars(json_encode($curso)) ?>)">
        <div class="course-thumb">
          <?php if (!empty($curso['image_url'])): ?>
            <img src="<?= htmlspecialchars($curso['image_url']) ?>" alt="<?= htmlspecialchars($curso['nome']) ?>" />
          <?php endif; ?>
        </div>
        <div class="course-body">
          <h3 class="course-title"><?= htmlspecialchars($curso['nome']) ?></h3>
          <p class="course-desc"><?= htmlspecialchars($curso['descricao']) ?></p>
          <p class="course-price">R$ <?= number_format($curso['preco'], 2, ',', '.') ?></p>
          <div class="course-actions" onclick="event.stopPropagation()">
            <button class="btn-cart-icon" onclick="addToCarrinho(<?= htmlspecialchars(json_encode($curso)) ?>)" title="Adicionar ao carrinho">🛒</button>
            <button class="btn-buy" onclick="openCourseModal(<?= htmlspecialchars(json_encode($curso)) ?>)">Comprar</button>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </section>
</div>

<?php require_once 'includes/footer.php'; ?>