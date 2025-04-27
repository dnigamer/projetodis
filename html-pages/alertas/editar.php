<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Editar Alerta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Editar informações de um alerta existente.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <?php
    $host = '192.168.1.4';
    $dbname = 'uniuser_sistema-niop';
    $username = 'uniuser';
    $password = 'uL[*P87G.UkYY_X7';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['tipo_alerta'], $_POST['descricao'], $_POST['gravidade'], $_POST['estado'])) {
            // Step 3: Update the alert in the database
            $id = $_POST['id'];
            $tipo_alerta = $_POST['tipo_alerta'];
            $descricao = $_POST['descricao'];
            $gravidade = $_POST['gravidade'];
            $estado = $_POST['estado'];

            $stmt = $pdo->prepare("
                UPDATE alertas 
                SET tipo_alerta = :tipo_alerta, descricao = :descricao, gravidade = :gravidade, estado = :estado 
                WHERE id = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_alerta', $tipo_alerta, PDO::PARAM_STR);
            $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $stmt->bindParam(':gravidade', $gravidade, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo '<p style="color: green;">Alerta atualizado com sucesso!</p>';
            } else {
                echo '<p style="color: red;">Erro ao atualizar o alerta.</p>';
            }
        } elseif (!isset($_POST['id'])) {
            // Step 1: Display the form to input the ID
            echo '<form action="/alertas/editar.php" method="POST">';
            echo '<table>';
            echo '<thead>';
            echo '<tr><th>Campo</th><th>Valor</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td><label for="id">ID do Alerta:</label></td>';
            echo '<td><input type="text" id="id" name="id" required></td>';
            echo '</tr>';
            echo '</table>';
            echo '<div style="margin-top: 20px;">';
            echo '<input type="submit" value="Buscar Alerta" class="styled-button">';
            echo '</div>';
            echo '</form>';
        } else {
            // Step 2: Fetch and display the form to edit the alert
            $id = $_POST['id'];

            $stmt = $pdo->prepare("SELECT * FROM alertas WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<form action="/alertas/editar.php" method="POST">';
                echo '<table>';
                echo '<thead>';
                echo '<tr><th>Campo</th><th>Valor</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                echo "<tr><td><label for='id'>ID do Alerta</label></td>";
                echo "<td><input type='text' id='id' name='id' value='" . htmlspecialchars($row['id']) . "' readonly></td></tr>";
                echo "<tr><td><label for='tipo_alerta'>Tipo de Alerta</label></td>";
                echo "<td><input type='text' id='tipo_alerta' name='tipo_alerta' value='" . htmlspecialchars($row['tipo_alerta']) . "' required></td></tr>";
                echo "<tr><td><label for='descricao'>Descrição</label></td>";
                echo "<td><textarea id='descricao' name='descricao' rows='4' cols='50' required>" . htmlspecialchars($row['descricao']) . "</textarea></td></tr>";
                echo "<tr><td><label for='gravidade'>Gravidade</label></td>";
                echo "<td><select id='gravidade' name='gravidade'>";
                echo "<option value='1'" . ($row['gravidade'] == 1 ? ' selected' : '') . ">Baixa</option>";
                echo "<option value='2'" . ($row['gravidade'] == 2 ? ' selected' : '') . ">Média</option>";
                echo "<option value='3'" . ($row['gravidade'] == 3 ? ' selected' : '') . ">Alta</option>";
                echo "</select></td></tr>";
                echo "<tr><td><label for='estado'>Estado</label></td>";
                echo "<td><input type='text' id='estado' name='estado' value='" . htmlspecialchars($row['estado']) . "' required></td></tr>";
                echo '</tbody>';
                echo '</table>';
                echo '<div style="margin-top: 20px;">';
                echo '<input type="submit" value="Atualizar Alerta" class="styled-button">';
                echo '</div>';
                echo '</form>';
            } else {
                echo '<p style="color: red;">Alerta não encontrado para o ID fornecido.</p>';
            }
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Erro ao conectar ao banco de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>