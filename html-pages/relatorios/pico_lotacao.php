<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Pico de Lotação</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Relatório de pico de lotação por paragem.">
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

        $stmt = $pdo->query("
            SELECT p.id AS paragem_id, p.nome AS paragem_nome, lp.pico_lotacao
            FROM lotacao_pico lp
            JOIN paragens p ON lp.paragem_id = p.id
            ORDER BY lp.pico_lotacao DESC
        ");

        // Check if there are results
        if ($stmt->rowCount() > 0) {
            echo '<table>';
            echo '<thead>';
            echo '<tr><th>ID da Paragem</th><th>Nome da Paragem</th><th>Pico de Lotação</th></tr>';
            echo '</thead>';
            echo '<tbody>';

            // Display each row
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['paragem_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['paragem_nome']) . '</td>';
                echo '<td>' . htmlspecialchars($row['pico_lotacao']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>Nenhum dado de pico de lotação encontrado.</p>';
        }
    } catch (PDOException $e) {
        echo '<p style="color: red;">Erro ao conectar ao banco de dados: ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
    ?>
</body>
</html>