<?php require_once 'config/conexao.php'; ?>
<?php require_once 'includes/header.php'; ?>

<?php
// Busca os 3 primeiros cursos
$stmt = $pdo->query("SELECT * FROM produtos LIMIT 3");
$cursos = $stmt->fetchAll();
?>

<div class="page">

  <!-- MARQUEE BAR -->
  <div class="marquee-bar">
    <div class="marquee-track">
      <?php
      $imgs = [
        ['url' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=300&h=160&fit=crop', 'label' => 'Programação'],
        ['url' => 'https://images.unsplash.com/photo-1504639725590-34d0984388bd?w=300&h=160&fit=crop', 'label' => 'Desenvolvimento Web'],
        ['url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=300&h=160&fit=crop', 'label' => 'Educação Digital'],
        ['url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=300&h=160&fit=crop', 'label' => 'Código'],
        ['url' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=300&h=160&fit=crop', 'label' => 'JavaScript'],
        ['url' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=300&h=160&fit=crop', 'label' => 'Tecnologia'],
        ['url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=300&h=160&fit=crop', 'label' => 'Aprendizado'],
        ['url' => 'https://images.unsplash.com/photo-1542831371-29b0f74f9713?w=300&h=160&fit=crop', 'label' => 'PHP & MySQL'],
      ];
      $allImgs = array_merge($imgs, $imgs);
      foreach ($allImgs as $img): ?>
        <div class="marquee-item">
          <img src="<?= $img['url'] ?>" alt="<?= htmlspecialchars($img['label']) ?>" loading="lazy" />
          <div class="marquee-label"><?= htmlspecialchars($img['label']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- HERO -->
  <section class="section grid-2 grid-2-center">
    <div>
      <div class="tag hero-tag">Cursos digitais com valor popular</div>
      <h2 class="hero-title">LIBERTE SUA MENTE.</h2>
      <p class="hero-desc">A Free Academy é uma loja de cursos 100% digital feita para democratizar o conhecimento com preços acessíveis e uma experiência simples.</p>
      <div class="hero-btns">
        <a href="loja.php" class="btn-primary">Ver cursos</a>
        <a href="login.php" class="btn-outline">Criar conta</a>
      </div>
    </div>
    <div class="hero-aside">
      <img src="https://media.base44.com/images/public/6a28c8df52b234e765db2246/a1172fc07_Gemini_Generated_Image_kahyi2kahyi2kahy.png" alt="Free Academy" class="hero-logo" />
      <div class="hero-card">
        <p>Educação digital com qualidade, acessibilidade e preços que cabem no seu bolso.</p>
        <small>Aprenda novas habilidades sem complicação.</small>
      </div>
    </div>
  </section>

  <!-- CURSOS EM DESTAQUE -->
  <section class="section">
    <div class="tag">Course Exchange</div>
    <h2 class="section-title">Cursos em destaque</h2>
    <p class="section-sub">Vitrine de cursos para quem quer aprender com preços populares.</p>

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
  </section>

</div>

<?php require_once 'includes/footer.php'; ?>