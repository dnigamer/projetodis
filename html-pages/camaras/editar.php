<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Câmara</title>
    <meta name="description" content="Editar informações de uma câmara existente.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['paragem'], $_POST['modelo'], $_POST['fabricante'], $_POST['latitude'], $_POST['longitude'], $_POST['data_instalacao'], $_POST['estado'])) {
            $id = $_POST['id'];
            $paragem = $_POST['paragem'];
            $modelo = $_POST['modelo'];
            $fabricante = $_POST['fabricante'];
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $data_instalacao = DateTime::createFromFormat('d/m/Y', $_POST['data_instalacao'])->format('Y-m-d');
            $estado = $_POST['estado'];

            $stmt = $pdo->prepare("UPDATE camaras SET paragem_id = :paragem, modelo = :modelo, fabricante = :fabricante, latitude = :latitude, longitude = :longitude, data_instalacao = :data_instalacao, estado = :estado WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':paragem', $paragem, PDO::PARAM_STR);
            $stmt->bindParam(':modelo', $modelo, PDO::PARAM_STR);
            $stmt->bindParam(':fabricante', $fabricante, PDO::PARAM_STR);
            $stmt->bindParam(':latitude', $latitude, PDO::PARAM_STR);
            $stmt->bindParam(':longitude', $longitude, PDO::PARAM_STR);
            $stmt->bindParam(':data_instalacao', $data_instalacao, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo '<p style="color: green;">Câmara atualizada com sucesso!</p>';
            } else {
                echo '<p style="color: red;">Erro ao atualizar a câmara.</p>';
            }
        } elseif (!isset($_POST['id'])) {
            echo '<form action="/camaras/editar.php" method="POST">';
            echo '<table>';
            echo '<thead>';
            echo '<tr><th>Campo</th><th>Valor</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td><label for="id">ID da Câmara</label></td>';
            echo '<td><input type="text" id="id" name="id" required></td>';
            echo '</tr>';
            echo '</table>';
            echo '<div style="margin-top: 20px;">';
            echo '<input type="submit" value="Procurar Câmara" class="styled-button">';
            echo '</div>';
            echo '</form>';
        } else {
            $id = $_POST['id'];

            $stmt = $pdo->prepare("SELECT * FROM camaras WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<form action="/camaras/editar.php" method="POST">';
                echo '<table>';
                echo '<thead>';
                echo '<tr><th>Campo</th><th>Valor</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                echo "<tr><td><label for='id'>ID da câmara</label></td>";
                echo "<td><input type='text' id='id' name='id' value='" . htmlspecialchars($row['id']) . "' readonly></td></tr>";
                echo "<tr><td><label for='paragem'>Paragem associada</label></td>";
                echo "<td><input type='text' id='paragem' name='paragem' value='" . htmlspecialchars($row['paragem_id']) . "'></td></tr>";
                echo "<tr><td colspan='2'><strong>Informação da Câmara</strong></td></tr>";
                echo "<tr><td><label for='modelo'>Modelo</label></td>";
                echo "<td><input type='text' id='modelo' name='modelo' value='" . htmlspecialchars($row['modelo']) . "' required></td></tr>";
                echo "<tr><td><label for='fabricante'>Fabricante</label></td>";
                echo "<td><input type='text' id='fabricante' name='fabricante' value='" . htmlspecialchars($row['fabricante']) . "' required></td></tr>";
                echo "<tr><td colspan='2'><strong>Localização</strong></td></tr>";
                echo "<tr><td><label for='latitude'>Latitude</label></td>";
                echo "<td><input type='text' id='latitude' name='latitude' value='" . htmlspecialchars($row['latitude']) . "' required></td></tr>";
                echo "<tr><td><label for='longitude'>Longitude</label></td>";
                echo "<td><input type='text' id='longitude' name='longitude' value='" . htmlspecialchars($row['longitude']) . "' required></td></tr>";
                echo "<tr><td colspan='2'><strong>Outras informações</strong></td></tr>";
                echo "<tr><td><label for='data_instalacao'>Data de Instalação</label></td>";
                echo "<td><input type='text' id='data_instalacao' name='data_instalacao' value='" . htmlspecialchars($row['data_instalacao']) . "' placeholder='DD/MM/AAAA' required></td></tr>";
                echo "<tr><td><label for='estado'>Estado</label></td>";
                echo "<td><select id='estado' name='estado'>";
                echo "<option value='Ativo'" . ($row['estado'] == 'Ativo' ? ' selected' : '') . ">Ativo</option>";
                echo "<option value='Inativo'" . ($row['estado'] == 'Inativo' ? ' selected' : '') . ">Inativo</option>";
                echo "</select></td></tr>";
                echo '</tbody>';
                echo '</table>';
                echo '<div style="margin-top: 20px;">';
                echo '<input type="submit" value="Atualizar Câmara" class="styled-button">';
                echo '</div>';
                echo '</form>';
            } else {
                echo '<p style="color: red;">Câmara não encontrada para o ID fornecido.</p>';
            }
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Erro ao conectar à base de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>