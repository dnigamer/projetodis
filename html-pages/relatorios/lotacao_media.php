<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Lotação Média</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Relatório da lotação média por paragem.">
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

        // Query the `lotacao_media` view to get the average lotação for each paragem
        $stmt = $pdo->query("
            SELECT paragem_id, media_lotacao
            FROM lotacao_media
            ORDER BY media_lotacao DESC
        ");

        // Check if there are results
        if ($stmt->rowCount() > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr><th>ID da Paragem</th><th>Lotação Média</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            // Display each row
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['paragem_id']) . '</td>';
                echo '<td>' . number_format($row['media_lotacao'], 2) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Nenhum dado de lotação média encontrado.</p>';
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Erro ao conectar ao banco de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>