CREATE DATABASE IF NOT EXISTS comercio_eletronico;
USE comercio_eletronico;

CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT DEFAULT 999,
    arquivo_curso VARCHAR(255),
    image_url VARCHAR(500),
    course_file VARCHAR(500),
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Migração: adicionar colunas se já existir a tabela sem elas
ALTER TABLE produtos ADD COLUMN IF NOT EXISTS image_url VARCHAR(500);
ALTER TABLE produtos ADD COLUMN IF NOT EXISTS course_file VARCHAR(500);

CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

INSERT INTO produtos (nome, descricao, preco, estoque, arquivo_curso) VALUES
('HTML e CSS do Zero', 'Aprenda a criar páginas modernas, responsivas e bem organizadas para seus primeiros projetos.', 29.90, 999, 'uploads/html-css.pdf'),
('JavaScript Básico', 'Entenda variáveis, funções, eventos e interações para deixar seu site mais dinâmico.', 34.90, 999, 'uploads/javascript.pdf'),
('PHP com MySQL', 'Construa sistemas simples com cadastro, login, banco de dados e páginas dinâmicas.', 39.90, 999, 'uploads/php-mysql.pdf'),
('Marketing Digital Popular', 'Aprenda fundamentos de presença online, redes sociais e divulgação com baixo investimento.', 24.90, 999, 'uploads/marketing.pdf');