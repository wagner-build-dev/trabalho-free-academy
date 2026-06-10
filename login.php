<?php require_once 'config/conexao.php'; ?>
<?php require_once 'includes/header.php'; ?>

<?php
$loginError = '';
$regMessage = '';
$regError = '';
$showSuccessModal = false;

// === LOGIN ===
if (isset($_POST['action']) && $_POST['action'] === 'login') {
  $login = trim($_POST['login'] ?? '');
  $password = $_POST['password'] ?? '';

  // Admin hardcoded
  if ($login === 'admin' && $password === 'admin123') {
    $_SESSION['admin'] = true;
    $_SESSION['cliente'] = ['id' => 0, 'name' => 'Admin', 'email' => 'admin@free-academy.local'];
    header('Location: admin/index.php');
    exit;
  }

  // Cliente no banco (aceita email ou username)
  $stmt = $pdo->prepare("SELECT * FROM clientes WHERE (email = ? OR username = ?) AND senha = ?");
  $stmt->execute([$login, $login, $password]);
  $cliente = $stmt->fetch();

  if ($cliente) {
    $_SESSION['cliente'] = $cliente;
    header('Location: loja.php');
    exit;
  } else {
    $loginError = 'Login ou senha incorretos.';
  }
}

// === CADASTRO ===
if (isset($_POST['action']) && $_POST['action'] === 'register') {
  $name     = trim($_POST['name'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $email    = trim($_POST['email'] ?? '');
  $password = $_POST['reg_password'] ?? '';

  // Verifica duplicidade de e-mail
  $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
  $stmt->execute([$email]);
  if ($stmt->fetch()) { $regError = 'Este e-mail já está cadastrado.'; }

  if (!$regError) {
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) { $regError = 'Este nome de usuário já está em uso.'; }
  }

  if (!$regError) {
    $stmt = $pdo->prepare("INSERT INTO clientes (nome, username, email, senha) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $username, $email, $password]);
    $showSuccessModal = true;
  }
}
?>

<div class="page">
  <section class="section">
    <div style="margin-bottom:48px;">
      <div class="tag">Secure Entry</div>
      <h1 class="big-title">Login e Cadastro</h1>
    </div>

    <div class="login-grid">
      <!-- LOGIN -->
      <form method="POST" class="form-card">
        <input type="hidden" name="action" value="login" />
        <h3 class="form-title">🔑 Entrar</h3>
        <?php if ($loginError): ?>
          <div class="alert-error"><?= htmlspecialchars($loginError) ?></div>
        <?php endif; ?>
        <div class="form-group">
          <label>Usuário ou E-mail</label>
          <input type="text" name="login" required placeholder="Usuário ou e-mail" />
        </div>
        <div class="form-group">
          <label>Senha</label>
          <input type="password" name="password" required />
        </div>
        <button type="submit" class="btn-full">Fazer login</button>
      </form>

      <!-- CADASTRO -->
      <form method="POST" class="form-card">
        <input type="hidden" name="action" value="register" />
        <h3 class="form-title">👤 Criar conta</h3>
        <?php if ($regError): ?>
          <div class="alert-error"><?= htmlspecialchars($regError) ?></div>
        <?php endif; ?>
        <div class="form-group">
          <label>Nome</label>
          <input type="text" name="name" required />
        </div>
        <div class="form-group">
          <label>Nome de usuário</label>
          <input type="text" name="username" required />
        </div>
        <div class="form-group">
          <label>E-mail</label>
          <input type="email" name="email" required />
        </div>
        <div class="form-group">
          <label>Senha</label>
          <input type="password" name="reg_password" required />
        </div>
        <button type="submit" class="btn-full">Cadastrar</button>
      </form>
    </div>
  </section>
</div>

<!-- Modal sucesso cadastro -->
<?php if ($showSuccessModal): ?>
<div class="modal-overlay" id="success-modal">
  <div class="modal-box">
    <div class="modal-icon">👤</div>
    <h3>Cadastro realizado com sucesso!</h3>
    <p>Agora você já pode efetuar o Login com seu usuário e senha.</p>
    <button class="btn-full" onclick="document.getElementById('success-modal').remove()">OK, vou fazer Login</button>
  </div>
</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>