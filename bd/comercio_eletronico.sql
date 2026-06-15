-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/06/2026 às 19:22
-- Versão do servidor: 8.0.46-0ubuntu0.22.04.2
-- Versão do PHP: 8.1.2-1ubuntu2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `comercio_eletronico`
--
CREATE DATABASE IF NOT EXISTS `comercio_eletronico` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `comercio_eletronico`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `email`, `senha`, `criado_em`, `username`) VALUES
(3, 'teste1', 'wagnermmonteiro.2025@gmail.com', '123456', '2026-06-10 10:45:26', 'teste'),
(4, 'alec', 'cordeiro.alec@gmail.com', '01020304', '2026-06-10 13:22:40', 'Alec Cordeiro');

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

DROP TABLE IF EXISTS `contatos`;
CREATE TABLE `contatos` (
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mensagem` text COLLATE utf8mb4_general_ci NOT NULL,
  `lido` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `contatos`
--

INSERT INTO `contatos` (`id`, `nome`, `email`, `mensagem`, `lido`, `created_at`) VALUES
(1, 'teste', 'wagnermmonteiro.2025@gmail.com', 'teste', 1, '2026-06-10 15:03:28'),
(2, 'Marisa', 'fdesonhos.ofc@gmail.com', 'Teste', 0, '2026-06-10 16:51:54');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

DROP TABLE IF EXISTS `itens_pedido`;
CREATE TABLE `itens_pedido` (
  `id` int NOT NULL,
  `pedido_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `quantidade` int NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unitario`) VALUES
(1, 1, 2, 1, '15.00'),
(2, 2, 3, 1, '9.90'),
(3, 2, 2, 1, '15.00'),
(4, 3, 4, 1, '5.99'),
(5, 3, 3, 1, '9.90'),
(6, 3, 2, 1, '15.00'),
(7, 3, 1, 1, '16.90'),
(8, 4, 2, 1, '15.00'),
(9, 5, 2, 1, '15.00'),
(10, 6, 3, 1, '9.90'),
(11, 6, 2, 1, '15.00'),
(12, 7, 4, 1, '5.99'),
(13, 8, 3, 1, '9.90'),
(14, 9, 4, 1, '5.99'),
(15, 10, 3, 1, '9.90'),
(16, 11, 3, 1, '9.90'),
(17, 12, 3, 1, '9.90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `id` int NOT NULL,
  `cliente_id` int NOT NULL,
  `data_pedido` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_id`, `data_pedido`, `total`) VALUES
(1, 3, '2026-06-10 11:13:05', '15.00'),
(2, 3, '2026-06-10 11:32:43', '24.90'),
(3, 3, '2026-06-10 11:34:07', '47.79'),
(4, 3, '2026-06-10 11:38:00', '15.00'),
(5, 3, '2026-06-10 11:45:42', '15.00'),
(6, 3, '2026-06-10 11:46:29', '24.90'),
(7, 3, '2026-06-10 12:42:57', '5.99'),
(8, 4, '2026-06-10 13:23:13', '9.90'),
(9, 4, '2026-06-10 13:24:16', '5.99'),
(10, 3, '2026-06-10 17:01:11', '9.90'),
(11, 3, '2026-06-10 17:07:05', '9.90'),
(12, 4, '2026-06-10 19:03:23', '9.90');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

DROP TABLE IF EXISTS `produtos`;
CREATE TABLE `produtos` (
  `id` int NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_general_ci NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int DEFAULT '999',
  `arquivo_curso` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `image_url` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `course_file` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `estoque`, `arquivo_curso`, `criado_em`, `image_url`, `course_file`) VALUES
(1, 'HTML e CSS do Zero', 'Aprenda a criar páginas modernas, responsivas e bem organizadas para seus primeiros projetos.', '16.90', 999, 'uploads/html-css.pdf', '2026-06-10 01:47:40', 'https://www.brasilcode.com.br/wp-content/uploads/2020/05/HTML-e-CSS-Guia-basico.png', 'https://drive.google.com/file/d/1hjSg8UpyNSqO5pClCqC6o9m27UJpF4Fv/view?usp=sharing'),
(2, 'JavaScript Básico', 'Entenda variáveis, funções, eventos e interações para deixar seu site mais dinâmico.', '15.00', 999, 'uploads/javascript.pdf', '2026-06-10 01:47:40', 'https://arquivo.devmedia.com.br/cursos/imagem/curso_388.jpg', 'https://drive.google.com/file/d/1SEyM1inCLTk_Gb5cZELRx_BfSDrEP8as/view?usp=sharing'),
(3, 'PHP com MySQL', 'Construa sistemas simples com cadastro, login, banco de dados e páginas dinâmicas.', '9.90', 999, 'uploads/php-mysql.pdf', '2026-06-10 01:47:40', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcReOPupSVWR05wz_YBuTLlP8t8vfij1qAv3yQ&s', 'https://drive.google.com/file/d/1QqtcfQej-ChlEbqvUKOqORiXgamBY_aJ/view?usp=sharing'),
(4, 'Marketing Digital Popular', 'Aprenda fundamentos de presença online, redes sociais e divulgação com baixo investimento.', '5.99', 999, 'uploads/marketing.pdf', '2026-06-10 01:47:40', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJYb12rlY26gY90KB8MNi6vxVhF9TmOnie9w&s', 'https://drive.google.com/file/d/1-ay6IEOzP1Pup9sNNXH1Y8B5Zz7OVEMt/view?usp=sharing');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

