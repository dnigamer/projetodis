<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (empty($_POST['paragem_id']) && empty($_POST['camera_id'])) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: É necessário fornecer o ID da paragem ou da câmara.</h2>";
            exit;
        }

        // Check if the camera ID exists in the database
        if (!empty($_POST['camera_id'])) {
            $stmt_check = $pdo->prepare("SELECT * FROM camaras WHERE id = :camera_id");
            $stmt_check->bindParam(':camera_id', $_POST['camera_id'], PDO::PARAM_STR);
            $stmt_check->execute();
            if ($stmt_check->rowCount() == 0) {
                echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
                echo "<h2>Erro: ID da câmara não encontrado.</h2>";
                exit;
            }
        }

        // Check if the paragem ID exists in the database
        if (!empty($_POST['paragem_id'])) {
            $stmt_check = $pdo->prepare("SELECT * FROM paragens WHERE id = :paragem_id");
            $stmt_check->bindParam(':paragem_id', $_POST['paragem_id'], PDO::PARAM_STR);
            $stmt_check->execute();
            if ($stmt_check->rowCount() == 0) {
                echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
                echo "<h2>Erro: ID da paragem não encontrado.</h2>";
                exit;
            }
        }

        if (empty($_POST['tipo_alerta']) || empty($_POST['descricao']) || empty($_POST['gravidade'])) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: Todos os campos obrigatórios devem ser preenchidos.</h2>";
            exit;
        }

        // Prepare and execute the insert statement
        $stmt = $pdo->prepare("
            INSERT INTO alertas (paragem_id, camera_id, data_alerta, tipo_alerta, descricao, gravidade, estado)
            VALUES (:paragem_id, :camera_id, :data_alerta, :tipo_alerta, :descricao, :gravidade, :estado)
        ");

        // Bind parameters
        $paragem_id = !empty($_POST['paragem_id']) ? $_POST['paragem_id'] : null;
        $camera_id = !empty($_POST['camera_id']) ? $_POST['camera_id'] : null;
        $data_alerta = date('Y-m-d H:i:s'); // Current timestamp
        $tipo_alerta = $_POST['tipo_alerta'];
        $descricao = $_POST['descricao'];
        $gravidade = $_POST['gravidade'];
        $estado = !empty($_POST['estado']) ? $_POST['estado'] : 'Pendente';

        $stmt->bindParam(':paragem_id', $paragem_id, PDO::PARAM_INT);
        $stmt->bindParam(':camera_id', $camera_id, PDO::PARAM_INT);
        $stmt->bindParam(':data_alerta', $data_alerta, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_alerta', $tipo_alerta, PDO::PARAM_STR);
        $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
        $stmt->bindParam(':gravidade', $gravidade, PDO::PARAM_INT);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Alerta adicionado com sucesso!</h2>";
        } else {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro ao adicionar o alerta.</h2>";
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
    <title>Adicionar Alerta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Adicionar um novo alerta.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <form action="/alertas/adicionar.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="paragem_id">ID da Paragem</label></td>
                    <td><input type="text" id="paragem_id" name="paragem_id"></td>
                </tr>
                <tr>
                    <td><label for="camera_id">ID da Câmara</label></td>
                    <td><input type="text" id="camera_id" name="camera_id"></td>
                </tr>
                <tr>
                    <td><label for="tipo_alerta">Tipo de Alerta</label></td>
                    <td><input type="text" id="tipo_alerta" name="tipo_alerta" required></td>
                </tr>
                <tr>
                    <td><label for="descricao">Descrição</label></td>
                    <td><textarea id="descricao" name="descricao" rows="4" cols="50" required></textarea></td>
                </tr>
                <tr>
                    <td><label for="gravidade">Gravidade</label></td>
                    <td>
                        <select id="gravidade" name="gravidade" required>
                            <option value="1">Baixa</option>
                            <option value="2">Média</option>
                            <option value="3">Alta</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="estado">Estado</label></td>
                    <td><input type="text" id="estado" name="estado" placeholder="Pendente"></td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <input type="reset" value="Limpar Formulário" class="styled-button">
        <input type="submit" value="Adicionar Alerta" class="styled-button">
    </form>
</body>
</html>