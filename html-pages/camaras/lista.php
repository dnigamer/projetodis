<!-- ID, Localizacao, Estado -->
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <title>Lista de Câmaras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Lista de câmaras com informações detalhadas.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <h2>Lista de Câmaras</h2>
    <table>
        <thead>
            <tr>
                <th class="ID-column">ID</th>
                <th>Paragem</th>
                <th>Modelo</th>
                <th>Fabricante</th>
                <th>Data de Instalação</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php
            require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

            try {
                $stmt = $pdo->query("SELECT id, paragem_id, modelo, fabricante, data_instalacao, estado FROM camaras");

                $count = $stmt->rowCount();
                if ($count == 0) {
                    echo "<tr><td colspan='6'>Não foram encontradas câmaras</td></tr>";
                }

                // Fetch and display data
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['paragem_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['modelo']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['fabricante']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['data_instalacao']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='6'>Error connecting to database: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>