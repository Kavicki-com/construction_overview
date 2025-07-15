<?php
// Este script deve ser incluído no topo das páginas protegidas.
require 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    // Se não houver sessão ativa, redireciona para a página de login
    header('Location: login.php');
    exit;
}
?>
