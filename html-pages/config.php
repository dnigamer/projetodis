<?php
$host = '192.168.1.4';
$dbname = 'uniuser_sistema-niop';
$username = 'uniuser';
$password = 'uL[*P87G.UkYY_X7';

try {
    if (basename($_SERVER['PHP_SELF']) == 'config.php') {
        echo '<p style="color: red;">Acesso negado. Este arquivo não deve ser acessado diretamente.</p>';
        exit;
    }

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
} catch (PDOException $e) {
    die('<p style="color: red;">Erro ao conectar ao banco de dados: ' . htmlspecialchars($e->getMessage()) . '</p>');
}
?>