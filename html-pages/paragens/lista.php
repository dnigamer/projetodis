<!-- ID, Localizacao, Estado, Lotacao atual-->
<!DOCTYPE html>
<html>
<head>
    <title>Lista de Paragens</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Lista de paragens com informações detalhadas.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>
<body>
    <h2>Lista de Paragens</h2>
    <table>
        <thead>
            <tr>
                <th class="ID-column">ID</th>
                <th>Nome</th>
                <th>Localização</th>
                <th>Estado</th>
                <th>Lotação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

            try {
                $stmt = $pdo->query("SELECT id, nome, localizacao, estado, lotacao FROM paragens");

                $count = $stmt->rowCount();
                if ($count == 0) {
                    echo "<tr><td colspan='5'>Não foram encontradas paragens</td></tr>";
                }

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['localizacao']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lotacao']) . "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='5'>Error connecting to database: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>