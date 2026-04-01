<?php
session_start();

// Só atualiza a sessão se houver algo vindo pelo POST (vinda do login)
if (isset($_POST["username"])) $_SESSION["username"] = $_POST["username"];
if (isset($_POST["password"])) $_SESSION["password"] = $_POST["password"];
if (isset($_GET["tentativa"])) $_SESSION["tentativa"] = $_GET["tentativa"];

// Verifica se os dados básicos de sessão existem
if (!isset($_SESSION["username"]) || !isset($_SESSION["password"])) {
    header("Location: login.php?msgerro=Faça+login+para+acessar.");
    exit();
}

// Conexão com o banco
$conn = pg_connect("host=localhost dbname=atividade_rafael user=postgres password=123456");

if (!$conn) {
    die("Erro na conexão com o banco de dados.");
}

// Validação do usuário com query parametrizada (evita SQL Injection)
$username  = $_SESSION["username"];
$password  = $_SESSION["password"];
$resultado = pg_query_params($conn, "SELECT * FROM usuario WHERE username=$1 AND password=$2", [$username, $password]);

if (!$resultado || !$linha = pg_fetch_assoc($resultado)) {
    session_destroy();
    header("Location: login.php?msgerro=Usuário+ou+senha+inválidos!");
    exit();
}
?>