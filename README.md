# Programação Web em PHP 5

Este repositório contém os conceitos fundamentais e exemplos práticos desenvolvidos para o estudo de PHP 5 voltado ao desenvolvimento web.

---

## 🚀 Introdução

**Objetivo:** Apresentar conceitos fundamentais do PHP 5 para desenvolvimento web.

**Tópicos abordados:**
* Variáveis $_GET e $_POST
* Gerenciamento de Sessões ($_SESSION)
* Conexão com banco de dados PostgreSQL
* Uso de includes e require
* Criação de CRUD (Grid de produtos)

---

## 📥 Variáveis GET e POST

### Definições:
* $_GET: Captura dados enviados via URL (query string).
* $_POST: Captura dados enviados via corpo da requisição (formulários).

**Exemplo GET:**
<?php
echo "O nome enviado foi: " . $_GET['nome'];
?>

**Exemplo POST:**
<?php
echo "O nome enviado foi: " . $_POST['nome'];
?>

**Diferenças principais:**
* GET expõe os dados na URL; POST mantém os dados ocultos na requisição.
* GET possui limite de caracteres (URL); POST é flexível para grandes volumes de dados.

---

## 🔑 Sessões em PHP

As sessões permitem armazenar informações que persistem entre diferentes páginas durante a navegação do usuário.

* Iniciar sessão: session_start();
* Armazenar valor: $_SESSION['usuario'] = "João";
* Recuperar valor: echo $_SESSION['usuario'];
* Finalizar sessão: session_destroy();

---

## 🐘 Conexão com PostgreSQL

### 1. Configuração (php.ini)
Certifique-se de habilitar a extensão no seu servidor:
extension=php_pgsql.dll

### 2. Estrutura do Banco de Dados
Crie as tabelas necessárias no PgAdmin dentro de um banco chamado produtos:

-- Tabela de Usuários
CREATE TABLE IF NOT EXISTS public.usuario (
    idusuario SERIAL NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(32) NOT NULL,
    status BOOLEAN DEFAULT true,
    CONSTRAINT usuario_pkey PRIMARY KEY (idusuario)
);

-- Inserir usuário padrão
INSERT INTO public.usuario (username, password, status)
VALUES ('admin', '123456', true);

-- Tabela de Produtos
CREATE TABLE IF NOT EXISTS public.produto (
    idproduto SERIAL NOT NULL,
    produtonome VARCHAR(100) NOT NULL,
    produtopreco REAL NOT NULL DEFAULT 0,
    produtofoto VARCHAR(150),
    produtostatus BOOLEAN DEFAULT false,
    CONSTRAINT produto_pkey PRIMARY KEY (idproduto)
);

### 3. Operações via PHP

**Estabelecendo Conexão:**
<?php
$conn = pg_connect("host=localhost dbname=produtos user=postgres password=123456");
if (!$conn) {
    echo "Erro na conexão com o banco de dados.";
}
?>

**Exemplo de Consulta (Select):**
<?php
$resultado = pg_query($conn, "SELECT * FROM usuarios");
while ($linha = pg_fetch_assoc($resultado)) {
    echo "Nome: " . $linha['nome'] . "<br>";
}
?>

---

## 🛠 Includes e Require

Utilizados para reutilizar componentes como menus, cabeçalhos e conexões.

* include 'arquivo.php';: Em caso de erro, exibe um aviso e continua a execução.
* require 'arquivo.php';: Em caso de erro, interrompe a execução do script imediatamente.

---

## 📦 Página de Produtos (Grid)

Exemplo de implementação de uma tabela dinâmica que lista os produtos do banco de dados:

<table border="1" align="center" width="100%" style="border-collapse: collapse; text-align: center;">
    <thead>
        <tr bgcolor="#CCCCCC">
            <th><input type="checkbox" name="todos"></th>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $resultado = pg_query($conn, "SELECT * FROM produto");
        while ($linha = pg_fetch_assoc($resultado)) {
        ?>
        <tr>
            <td><input type="checkbox" name="id[]" value="<?php echo $linha['idproduto']; ?>"></td>
            <td><?php echo $linha["idproduto"]; ?></td>
            <td><?php echo $linha["produtonome"]; ?></td>
            <td>R$ <?php echo number_format($linha["produtopreco"], 2, ',', '.'); ?></td>
            <td><?php echo ($linha["produtostatus"] == "t") ? "Ativo" : "Desativado"; ?></td>
        </tr>    
        <?php } ?>
    </tbody>
</table>

---
Documentação gerada para fins acadêmicos.
