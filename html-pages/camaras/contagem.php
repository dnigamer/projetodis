<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contagem da Câmara</title>
    <meta name="description" content="Contagem de pessoas na câmara.">
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $stmt = $pdo->prepare("SELECT * FROM registo_lotacao WHERE camera_id = :id ORDER BY data_registo DESC LIMIT 10");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            // Check if any records were found
            if ($stmt->rowCount() == 0) {
                echo "<p>Nenhum registo encontrado para a câmara com ID <strong>" . htmlspecialchars($id) . "</strong>.</p>";
                exit;
            }

            echo '<table>';
            echo '<thead>';
            echo '<tr><th>ID</th><th>Data</th><th>Hora</th><th>Contagem</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                $datetime = new DateTime($row['data_registo']);
                echo "<td>" . htmlspecialchars($datetime->format('Y-m-d')) . "</td>";
                echo "<td>" . htmlspecialchars($datetime->format('H:i:s')) . "</td>";
                echo "<td>" . htmlspecialchars($row['lotacao']) . "</td>";
                echo "</tr>";
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            // Display the form to get the camera ID
            echo '<form action="/camaras/contagem.php" method="POST">';
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
            echo '<input type="submit" value="Obter Contagem" class="styled-button">';
            echo '</div>';
            echo '</form>';
        }
    } catch (PDOException $e) {
        echo "<p>Erro ao conectar à base de dados: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</body>