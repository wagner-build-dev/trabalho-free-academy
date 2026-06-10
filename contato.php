<?php require_once 'config/conexao.php'; ?>
<?php require_once 'includes/header.php'; ?>

<div class="page">
  <section class="section grid-2">
    <div>
      <div class="tag">Contato</div>
      <h1 class="big-title">Fale com a Free Academy.</h1>
      <p class="about-intro">Use este formulário para enviar uma mensagem ou dúvida. Entraremos em contato em breve.</p>
    </div>

    <div class="form-card">
      <div id="success-msg" class="alert-success" style="display:none;">✅ Mensagem enviada com sucesso!</div>
      <div id="error-msg" class="alert-error" style="display:none;">Erro ao enviar. Tente novamente.</div>

      <form id="contact-form">
        <div class="form-group">
          <label>Nome</label>
          <input type="text" id="f-nome" required />
        </div>
        <div class="form-group">
          <label>E-mail</label>
          <input type="email" id="f-email" required />
        </div>
        <div class="form-group">
          <label>Mensagem</label>
          <textarea id="f-mensagem" required></textarea>
        </div>
        <button type="submit" class="btn-full" id="btn-enviar">✉ Enviar mensagem</button>
      </form>
    </div>
  </section>
</div>

<script>
document.getElementById('contact-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('btn-enviar');
  btn.disabled = true;
  btn.textContent = '⏳ Enviando...';

  const resp = await fetch('api/salvar_contato.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      nome: document.getElementById('f-nome').value,
      email: document.getElementById('f-email').value,
      mensagem: document.getElementById('f-mensagem').value
    })
  });

  const result = await resp.json().catch(() => null);

  if (resp.ok && result && result.sucesso) {
    document.getElementById('success-msg').style.display = 'block';
    document.getElementById('error-msg').style.display = 'none';
    this.reset();
  } else {
    document.getElementById('error-msg').style.display = 'block';
    document.getElementById('success-msg').style.display = 'none';
  }

  btn.disabled = false;
  btn.textContent = '✉ Enviar mensagem';
});
</script>

<?php require_once 'includes/footer.php'; ?>