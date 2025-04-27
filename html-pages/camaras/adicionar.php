<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = '192.168.1.4';
    $dbname = 'uniuser_sistema-niop';
    $username = 'uniuser';
    $password = 'uL[*P87G.UkYY_X7';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the insert statement
        $stmt = $pdo->prepare("INSERT INTO camaras (id, paragem_id, modelo, fabricante, latitude, longitude, data_instalacao, estado) VALUES (:id, :paragem_id, :modelo, :fabricante, :latitude, :longitude, :data_instalacao, :estado)");
        
        // Ver se o id da câmara já existe
        $stmt_check = $pdo->prepare("SELECT * FROM camaras WHERE id = :id");
        $stmt_check->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
        $stmt_check->execute();
        if ($stmt_check->rowCount() > 0) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: ID da câmara já existe. Por favor, escolha um ID diferente.</h2>";
            exit;
        }

        // Validar campos
        if (empty($_POST['id']) || empty($_POST['modelo']) || empty($_POST['fabricante']) || empty($_POST['latitude']) || empty($_POST['longitude']) || empty($_POST['data_instalacao'])) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: Todos os campos são obrigatórios.</h2>";
            exit;
        }

        // Bind parameters
        $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
        $stmt->bindParam(':paragem_id', $_POST['paragem'], PDO::PARAM_STR);
        $stmt->bindParam(':modelo', $_POST['modelo'], PDO::PARAM_STR);
        $stmt->bindParam(':fabricante', $_POST['fabricante'], PDO::PARAM_STR);
        if (!is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: Latitude e Longitude devem ser números.</h2>";
            exit;
        }
        if ($_POST['latitude'] < -90 || $_POST['latitude'] > 90 || $_POST['longitude'] < -180 || $_POST['longitude'] > 180) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: Latitude deve estar entre -90 e 90 e Longitude entre -180 e 180.</h2>";
            exit;
        }
        $stmt->bindParam(':latitude', $_POST['latitude'], PDO::PARAM_STR);
        $stmt->bindParam(':longitude', $_POST['longitude'], PDO::PARAM_STR);
        $data_instalacao = DateTime::createFromFormat('d/m/Y', $_POST['data_instalacao']);
        if ($data_instalacao) {
            $formatted_date = $data_instalacao->format('Y-m-d');
            $stmt->bindParam(':data_instalacao', $formatted_date, PDO::PARAM_STR);
        } else {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Erro: Data de instalação inválida. O formato correto é DD/MM/AAAA.</h2>";
            exit;
        }
        $stmt->bindParam(':estado', $_POST['estado'], PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Câmara adicionada com sucesso!</h2>";
        } else {
            echo "<head><link rel='stylesheet' href='/static/css/style.css'></head>";
            echo "<h2>Ocorreu um erro ao adicionar a câmara.</h2>";
        }
    } catch (PDOException $e) {
        echo "<p>Erro ao adicionar registo à base de dados: <strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <title>Adicionar Câmara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Adicionar uma nova câmara ao sistema.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>

<body>
    <form action="/camaras/adicionar.php" method="POST">
        <table>
            <thead style="width: 50%; border-collapse: collapse; margin-top: 20px;">
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="id">ID da câmara</label></td>
                    <td><input type="text" id="id" name="id" required></td>
                </tr>
                <tr>
                    <td><label for="paragem">Paragem associada</label></td>
                    <td><input type="text" id="paragem" name="paragem"></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Informação da Câmara</strong></td>
                </tr>
                <tr>
                    <td><label for="modelo">Modelo</label></td>
                    <td><input type="text" id="modelo" name="modelo" required></td>
                </tr>
                <tr>
                    <td><label for="fabricante">Fabricante</label></td>
                    <td><input type="text" id="fabricante" name="fabricante" required></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Localização</strong></td>
                </tr>
                <tr>
                    <td><label for="latitude">Latitude</label></td>
                    <td><input type="text" id="latitude" name="latitude" required></td>
                </tr>
                <tr>
                    <td><label for="longitude">Longitude</label></td>
                    <td><input type="text" id="longitude" name="longitude" required></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Outras informações</strong></td>
                </tr>
                <tr>
                    <td><label for="data_instalacao">Data de Instalação (DD/MM/AAAA)</label></td>
                    <td><input type="text" id="data_instalacao" name="data_instalacao" placeholder="DD/MM/AAAA" required></td>
                </tr>
                <tr>
                    <td><label for="estado">Estado</label></td>
                    <td>
                        <select id="estado" name="estado">
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <br><br>

        <input type="reset" value="Limpar Formulário" class="styled-button">
        <input type="submit" value="Adicionar Câmara" class="styled-button">
    </form>
</body>
</html>