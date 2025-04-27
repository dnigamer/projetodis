<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paragem</title>
    <meta name="description" content="Editar informações de uma paragem existente.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['nome'], $_POST['localizacao'], $_POST['estado'])) {
            $id = $_POST['id'];
            $nome = $_POST['nome'];
            $localizacao = $_POST['localizacao'];
            $estado = $_POST['estado'];
            $favorita = isset($_POST['favorita']) && $_POST['favorita'] === 'S' ? 'S' : 'N';

            $stmt = $pdo->prepare("UPDATE paragens SET nome = :nome, localizacao = :localizacao, estado = :estado, favorita = :favorita WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':localizacao', $localizacao, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
            $stmt->bindParam(':favorita', $favorita, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo '<p style="color: green;">Paragem atualizada com sucesso!</p>';
            } else {
                echo '<p style="color: red;">Erro ao atualizar a paragem.</p>';
            }
        } elseif (!isset($_POST['id'])) {
            // Display the form to input the ID as a table
            echo '<form action="/paragens/editar.php" method="POST">';
            echo '<table>';
            echo '<thead>';
            echo '<tr><th>Campo</th><th>Valor</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td><label for="id">ID da Paragem</label></td>';
            echo '<td><input type="text" id="id" name="id" required></td>';
            echo '</tr>';
            echo '</table>';
            echo '<div style="margin-top: 20px;">';
            echo '<input type="submit" value="Buscar Paragem" class="styled-button">';
            echo '</div>';
            echo '</form>';
        } else {
            $id = $_POST['id'];

            $stmt = $pdo->prepare("SELECT * FROM paragens WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            // Fetch the data
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<form action="/paragens/editar.php" method="POST">';
                echo '<table>';
                echo '<thead>';
                echo '<tr><th>Campo</th><th>Valor</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                echo "<tr><td><label for='id'>ID da Paragem</label></td>";
                echo "<td><input type='text' id='id' name='id' value='" . htmlspecialchars($row['id']) . "' readonly></td></tr>";
                echo "<tr><td><label for='nome'>Nome</label></td>";
                echo "<td><input type='text' id='nome' name='nome' value='" . htmlspecialchars($row['nome']) . "' required></td></tr>";
                echo "<tr><td><label for='localizacao'>Localização</label></td>";
                echo "<td><input type='text' id='localizacao' name='localizacao' value='" . htmlspecialchars($row['localizacao']) . "' required></td></tr>";
                echo "<tr><td><label for='estado'>Estado</label></td>";
                echo "<td><select id='estado' name='estado'>";
                echo "<option value='Ativo'" . ($row['estado'] == 'Ativo' ? ' selected' : '') . ">Ativo</option>";
                echo "<option value='Inativo'" . ($row['estado'] == 'Inativo' ? ' selected' : '') . ">Inativo</option>";
                echo "</select></td></tr>";
                echo "<tr><td><label for='favorita'>Favorita</label></td>";
                echo "<td><input type='checkbox' id='favorita' name='favorita' value='S'" . ($row['favorita'] == 'S' ? ' checked' : '') . "></td></tr>";
                echo '</tbody>';
                echo '</table>';
                echo '<div style="margin-top: 20px;">';
                echo '<input type="submit" value="Atualizar Paragem" class="styled-button">';
                echo '</div>';
                echo '</form>';
            } else {
                echo '<p style="color: red;">Paragem não encontrada para o ID fornecido.</p>';
            }
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Erro ao conectar à base de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>