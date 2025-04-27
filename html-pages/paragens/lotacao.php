<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Obter Lotação</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Obter a lotação de uma paragem.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    try {
        if (!isset($_POST['id'])) {
            echo '<form action="/paragens/lotacao.php" method="POST">';
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
            echo '<input type="submit" value="Obter Lotação" class="styled-button">';
            echo '</div>';
            echo '</form>';
        } else {
            $id = $_POST['id'];

            $stmt = $pdo->prepare("
                SELECT p.id, p.nome, p.lotacao
                FROM paragens p
                WHERE p.id = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<table>';
                echo '<thead>';
                echo '<tr><th>ID</th><th>Nome</th><th>Lotação</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nome']) . '</td>';
                echo '<td>' . htmlspecialchars($row['lotacao']) . '</td>';
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
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