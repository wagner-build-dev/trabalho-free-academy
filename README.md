# Free Academy — Versão XAMPP (PHP + MySQL)

## 📁 Estrutura de pastas

```
free-academy-xampp/
├── index.php               → Home
├── loja.php                → Loja de cursos
├── login.php               → Login e Cadastro
├── logout.php              → Logout
├── carrinho.php            → Carrinho
├── checkout.php            → Checkout
├── quem-somos.php          → Quem Somos
├── contato.php             → Contato
│
├── admin/
│   ├── index.php           → Painel administrativo completo
│   └── api/
│       ├── save_course.php     → Criar/Editar curso
│       ├── delete_course.php   → Excluir curso
│       └── update_order.php    → Atualizar status do pedido
│
├── api/
│   └── criar_pedido.php    → API AJAX para criar pedido
│
├── config/
│   └── conexao.php         → Configuração do banco de dados
│
├── includes/
│   ├── header.php          → Header (menu, logo, carrinho)
│   ├── footer.php          → Footer
│   └── proteger.php        → Proteção de páginas (exige login)
│
├── assets/
│   ├── css/style.css       → Todo o CSS do site
│   └── js/script.js        → JavaScript (carrinho, modais, toast)
│
└── database.sql            → Script SQL para criar o banco de dados
```

## 🚀 Como instalar no XAMPP

### 1. Instale o XAMPP
Baixe em: https://www.apachefriends.org/

### 2. Copie os arquivos
Coloque a pasta `free-academy-xampp` dentro de `C:\xampp\htdocs\` e renomeie para `free-academy`.

### 3. Crie o banco de dados
- Abra o XAMPP e inicie **Apache** e **MySQL**
- Acesse: http://localhost/phpmyadmin
- Clique em **Importar** → selecione `database.sql` → Execute

### 4. Configure a conexão (se necessário)
Abra `config/conexao.php` e ajuste `DB_USER` e `DB_PASS` conforme seu XAMPP.

### 5. Acesse o site
http://localhost/free-academy/

## 🔐 Credenciais de admin
- **Usuário:** `admin`
- **Senha:** `admin123`

Essas credenciais estão em `login.php` — você pode alterar diretamente no código.

## 📌 Observações
- O carrinho usa `localStorage` no navegador (JavaScript puro)
- O checkout requer login de cliente
- A sessão PHP controla login de clientes e admin separadamente
- Uploads de arquivos dos cursos: coloque os arquivos em `uploads/` e use o caminho como `course_file