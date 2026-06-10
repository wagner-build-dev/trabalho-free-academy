<?php
include "config/conexao.php";
include "includes/header.php";

$mensagem = '';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $estoque = 999;
    $arquivoCurso = '';

    if (isset($_FILES['arquivo_curso']) && $_FILES['arquivo_curso']['error'] === 0) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        $nomeArquivo = time() . '-' . basename($_FILES['arquivo_curso']['name']);
        $destino = 'uploads/' . $nomeArquivo;
        move_uploaded_file($_FILES['arquivo_curso']['tmp_name'], $destino);
        $arquivoCurso = $destino;
    }

    $sql = $conexao->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, arquivo_curso) VALUES (?, ?, ?, ?, ?)");
    $sql->bind_param("ssdis", $nome, $descricao, $preco, $estoque, $arquivoCurso);

    if ($sql->execute()) {
        $mensagem = "Curso cadastrado com sucesso!";
    } else {
        $erro = "Erro ao cadastrar o curso.";
    }
}
?>

<section class="secao duas-colunas">
    <div>
        <span class="kicker">Cadastro de produtos</span>
        <h2>Cadastrar curso</h2>
        <p class="texto-grande">Esta página representa o cadastro de produtos do e-commerce. O arquivo enviado será usado como material entregue ao cliente após a compra.</p>
    </div>

    <form class="form-box" method="POST" enctype="multipart/form-data">
        <?php if ($mensagem): ?><div class="alerta"><?php echo $mensagem; ?></div><?php endif; ?>
        <?php if ($erro): ?><div class="alerta erro"><?php echo $erro; ?></div><?php endif; ?>

        <div class="campo">
            <label>Nome do curso</label>
            <input type="text" name="nome" required>
        </div>
        <div class="campo">
            <label>Descrição</label>
            <textarea name="descricao" required></textarea>
        </div>
        <div class="campo">
            <label>Preço</label>
            <input type="number" name="preco" step="0.01" min="0" required>
        </div>
        <div class="campo">
            <label>Arquivo do curso</label>
            <input type="file" name="arquivo_curso" required>
        </div>
        <button type="submit" style="margin-top: 18px;">Salvar curso</button>
    </form>
</section>

<?php include "includes/footer.php"; ?>