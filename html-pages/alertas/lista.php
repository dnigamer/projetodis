<!-- ID Data Paragem Tipo Descrição Gravidade Estado -->
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Alertas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Lista de alertas com informações detalhadas.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <h2>Lista de Alertas</h2>
    <table>
        <thead>
            <tr>
                <th class="id-column">ID</th>
                <th>Paragem</th>
                <th>Câmara</th>
                <th>Data Alerta</th>
                <th>Data Resolução</th>
                <th>Tipo Alerta</th>
                <th>Descrição</th>
                <th>Gravidade</th>
                <th>Estado</th>
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
                    $stmt = $pdo->query("SELECT id, paragem_id, camera_id, data_alerta, data_resolucao, tipo_alerta, descricao, gravidade, estado FROM alertas");

                    $count = $stmt->rowCount();
                    if ($count == 0) {
                        echo "<tr><td colspan='9'>Não foram encontrados alertas</td></tr>";
                    }

                    // Fetch and display data
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['paragem_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['camera_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['data_alerta']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['data_resolucao']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tipo_alerta']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gravidade']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='9'>Error connecting to database: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>