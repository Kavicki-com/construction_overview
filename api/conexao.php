<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start(); // Inicia a sessão em todas as páginas
$host = 'localhost'; // ou o host do seu servidor de DB
$dbname = 'gestao_obras';
$user = 'root'; // seu usuário do MySQL
$pass = ''; // sua senha do MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Em produção, não exiba detalhes do erro. Apenas logue.
    die("Erro ao conectar com o banco de dados: " . $e->getMessage());
}
?>