<!DOCTYPE html>
<html>
<head>
    <title>Alertas Recentes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Lista de alertas recentes.">
    <link rel="stylesheet" href="/static/css/style-small.css">
</head>
<body>
    <h2>Alertas Recentes</h2>
    <table>
        <thead>
            <tr>
                <th class="id-column">ID</th>
                <th>Data Alerta</th>
                <th>Alerta</th>
                <th>Gravidade</th>
            </tr>
        </thead>
        <tbody>
        <?php
                // Database connection
                $host = '192.168.1.4';
                $dbname = 'uniuser_sistema-niop';
                $username = 'uniuser';
                $password = 'uL[*P87G.UkYY_X7';

                try {
                    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Query to fetch data
                    $stmt = $pdo->query("SELECT id, data_alerta, descricao, gravidade FROM alertas_recentes ORDER BY data_alerta DESC LIMIT 10");

                    $count = $stmt->rowCount();
                    if ($count == 0) {
                        echo "<tr><td colspan='4'>Não foram encontrados alertas</td></tr>";
                    }

                    // Fetch and display data
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['data_alerta']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gravidade']) . "</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='4'>Error connecting to database: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>