<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {

        // Prepare and execute the insert statement
        $stmt = $pdo->prepare("INSERT INTO paragens (id, nome, localizacao, estado, favorita) VALUES (:id, :nome, :localizacao, :estado, :favorita)");

        // Ver se o id da paragem já existe
        $stmt_check = $pdo->prepare("SELECT * FROM paragens WHERE id = :id");
        $stmt_check->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
        $stmt_check->execute();
        if ($stmt_check->rowCount() > 0) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: ID da paragem já existe. Por favor, escolha um ID diferente.</h2>";
            exit;
        }

        // Validar campos
        if (empty($_POST['id']) || empty($_POST['nome']) || empty($_POST['localizacao'])) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: Todos os campos são obrigatórios.</h2>";
            exit;
        }

        // Bind parameters
        $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
        $stmt->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);
        $stmt->bindParam(':localizacao', $_POST['localizacao'], PDO::PARAM_STR);
        if (empty($_POST['estado'])) {
            $_POST['estado'] = 'Inativo';
        }
        $stmt->bindParam(':estado', $_POST['estado'], PDO::PARAM_STR);
        $favorita = isset($_POST['favorita']) ? 'S' : 'N';
        $stmt->bindParam(':favorita', $favorita, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Paragem adicionada com sucesso!</h2>";
        } else {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro ao adicionar a paragem.</h2>";
        }
    } catch (PDOException $e) {
        echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
        echo "<h2>Erro ao conectar ao banco de dados: " . htmlspecialchars($e->getMessage()) . "</h2>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Adicionar Paragem</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Adicionar uma nova paragem.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <form action="/paragens/adicionar.php" method="POST">
        <table>
            <thead style="width: 50%; border-collapse: collapse; margin-top: 20px;">
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="id">Número da paragem</label></td>
                    <td><input type="text" id="id" name="id" required></td>
                </tr>
                <tr>
                    <td><label for="nome">Nome</label></td>
                    <td><input type="text" id="nome" name="nome" required></td>
                </tr>
                <tr>
                    <td><label for="localizacao">Localização</label></td>
                    <td><input type="text" id="localizacao" name="localizacao" required></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Outras informações</strong></td>
                </tr>
                <tr>
                    <td><label for="lotacao">Estado</label></td>
                    <td>
                        <select id="estado" name="estado">
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="favorita">Favorita</label></td>
                    <td><input type="checkbox" id="favorita" name="favorita" value="S"></td>
                </tr>
            </tbody>
        </table>
        <br><br>

        <input type="reset" value="Limpar Formulário" class="styled-button">
        <input type="submit" value="Adicionar Paragem" class="styled-button">
    </form>
</body>
</html>
