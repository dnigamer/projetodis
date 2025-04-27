<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Obter Estado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Obter o estado de uma paragem.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            $stmt = $pdo->prepare("SELECT * FROM paragens WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            // Check if any records were found
            if ($stmt->rowCount() == 0) {
                echo "<p>Nenhum registo encontrado para a paragem com ID <strong>" . htmlspecialchars($id) . "</strong>.</p>";
                exit;
            }

            echo '<table>';
            echo '<thead>';
            echo '<tr><th>ID</th><th>Nome</th><th>Localização</th><th>Estado</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                echo "<td>" . htmlspecialchars($row['localizacao']) . "</td>";
                echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                echo "</tr>";
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            // Display the form to get the paragem ID
            echo '<form action="/paragens/estado.php" method="POST">';
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
            echo '<input type="submit" value="Obter Estado" class="styled-button">';
            echo '</div>';
            echo '</form>';
        }
    } catch (PDOException $e) {
        echo "<p>Erro ao conectar à base de dados: <strong>" . htmlspecialchars($e->getMessage()) . "</strong></p>";
    }
    ?>
</body>
