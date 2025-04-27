<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Remover Alerta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Remover um alerta existente.">
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            // Step 2: Remove the alert from the database
            $id = $_POST['id'];

            // Validate the ID to ensure it's a number
            if (!is_numeric($id)) {
                echo '<p style="color: red;">ID inválido. Por favor, insira um número.</p>';
                exit;
            }

            // Check if the ID exists in the database
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM alertas WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();
            if ($count == 0) {
                echo '<p style="color: red;">Alerta com ID ' . htmlspecialchars($id) . ' não encontrado.</p>';
                exit;
            }

            $stmt = $pdo->prepare("DELETE FROM alertas WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo '<p style="color: green;">Alerta removido com sucesso!</p>';
            } else {
                echo '<p style="color: red;">Erro ao remover o alerta.</p>';
            }
        } else {
            // Step 1: Display the form to input the ID
            echo '<form action="/alertas/remover.php" method="POST">';
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
            echo '<input type="submit" value="Remover Alerta" class="styled-button">';
            echo '</div>';
            echo '</form>';
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Erro ao conectar ao banco de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>