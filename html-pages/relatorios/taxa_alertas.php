<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Taxa de Alertas por Gravidade</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Relatório da taxa de alertas por gravidade.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

    try {
        // Query the `taxa_alertas` view to get the alert rates by severity
        $stmt = $pdo->query("
            SELECT paragem_id, total_alertas, alertas_baixa, alertas_media, alertas_alta
            FROM taxa_alertas
            ORDER BY total_alertas DESC
        ");

        // Check if there are results
        if ($stmt->rowCount() > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr><th>ID da Paragem</th><th>Total de Alertas</th><th>Baixa Gravidade</th><th>Média Gravidade</th><th>Alta Gravidade</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            // Display each row
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['paragem_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['total_alertas']) . '</td>';
                echo '<td>' . htmlspecialchars($row['alertas_baixa']) . " (" . round(($row['alertas_baixa'] / $row['total_alertas']) * 100, 2) . '%)</td>';
                echo '<td>' . htmlspecialchars($row['alertas_media']) . " (" . round(($row['alertas_media'] / $row['total_alertas']) * 100, 2) . '%)</td>';
                echo '<td>' . htmlspecialchars($row['alertas_alta']) . " (" . round(($row['alertas_alta'] / $row['total_alertas']) * 100, 2) . '%)</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Nenhum dado de alertas encontrado.</p>';
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Erro ao conectar ao banco de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>