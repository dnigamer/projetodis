<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Fluxo de Passageiros</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Relatório do fluxo de passageiros ao longo do dia.">
    <link rel="stylesheet" href="/static/css/style-small.css">
</head>
<body>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    try {
        if (!isset($_POST['paragem_id'])) {
            echo '<form action="/relatorios/fluxo_passageiros.php" method="POST">';
            echo '<table>';
            echo '<thead>';
            echo '<tr><th>Campo</th><th>Valor</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td><label for="paragem_id">ID da Paragem</label></td>';
            echo '<td><input type="text" id="paragem_id" name="paragem_id" required></td>';
            echo '</tr>';
            echo '</table>';
            echo '<div style="margin-top: 20px;">';
            echo '<input type="submit" value="Gerar Relatório" class="styled-button">';
            echo '</div>';
            echo '</form>';
        } else {
            $paragem_id = $_POST['paragem_id'];

            // Validate the paragem ID
            if (!is_numeric($paragem_id)) {
                echo '<p style="color: red;">ID da Paragem inválido. Por favor, insira um número válido.</p>';
                exit;
            }

            $stmt = $pdo->prepare("
                SELECT r.data_registo, r.lotacao
                FROM registo_lotacao r
                WHERE r.paragem_id = :paragem_id
                ORDER BY r.data_registo ASC
            ");
            $stmt->bindParam(':paragem_id', $paragem_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo '<table>';
                echo '<thead>';
                echo '<tr><th>Data e Hora</th><th>Lotação</th></tr>';
                echo '</thead>';
                echo '<tbody>';

                // Display each row
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['data_registo']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['lotacao']) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>Nenhum dado de lotação encontrado para a paragem com ID ' . htmlspecialchars($paragem_id) . '.</p>';
            }
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Erro ao conectar ao banco de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>