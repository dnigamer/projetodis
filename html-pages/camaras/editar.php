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
    $host = '192.168.1.4';
    $dbname = 'uniuser_sistema-niop';
    $username = 'uniuser';
    $password = 'uL[*P87G.UkYY_X7';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_POST['id'])) {
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
                echo "<option value='ativo'" . ($row['estado'] == 'ativo' ? ' selected' : '') . ">Ativo</option>";
                echo "<option value='inativo'" . ($row['estado'] == 'inativo' ? ' selected' : '') . ">Inativo</option>";
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