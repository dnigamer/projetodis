<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remover Paragem</title>
    <meta name="description" content="Remover uma paragem do sistema.">
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

            $stmt = $pdo->prepare("SELECT * FROM paragens WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                echo "<p>Paragem com ID <strong>" . htmlspecialchars($id) . "</strong> não encontrada.</p>";
                exit;
            }

            // Prepare and execute the delete statement
            $stmt = $pdo->prepare("DELETE FROM paragens WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            echo "<p>Paragem com ID <strong>" . htmlspecialchars($id) . "</strong> removida com sucesso.</p>";
        } else {
            echo '<form action="/paragens/remover.php" method="POST">';
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
            echo '<input type="submit" value="Remover Paragem" class="styled-button">';
            echo '</div>';
            echo '</form>';
        }
    } catch (PDOException $e) {
        echo "<p>Erro ao conectar à base de dados: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
</body>
</html>