<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remover Câmara</title>
    <meta name="description" content="Remover uma câmara do sistema.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];

            if (!is_numeric($id)) {
                echo "<p>ID inválido. Por favor, insira um número.</p>";
                exit;
            }

            $stmt = $pdo->prepare("SELECT * FROM camaras WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo "<p>Câmara com ID <strong>" . htmlspecialchars($id) . "</strong> não encontrada.</p>";
                exit;
            }

            // Prepare and execute the delete statement
            $stmt = $pdo->prepare("DELETE FROM camaras WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            echo "<p>Câmara com ID <strong>" . htmlspecialchars($id) . "</strong> removida com sucesso.</p>";
        } else {
            echo '<form action="/camaras/remover.php" method="POST">';
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
            echo '<input type="submit" value="Remover Câmara" class="styled-button">';
            echo '</div>';
            echo '</form>';
        }
    } catch (PDOException $e) {
        echo "<p>Erro ao conectar à base de dados: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</body>
</html>