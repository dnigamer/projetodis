<!DOCTYPE html>
<html>
<head>
    <title>Paragens</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Lista de paragens favoritas.">
    <link rel="stylesheet" href="/static/css/style-small.css">
</head>
<body>
    <h2>Paragens Favoritas</h2>
    <table>
        <thead>
            <tr>
                <th class="paragem-column">Paragem</th>
                <th>Nome</th>
                <th>Contagem</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once $_SERVER['DOCUMENT_ROOT'] . '/config.php';

            try {
                // Query to fetch data
                $stmt = $pdo->query("SELECT id, nome, lotacao FROM paragens WHERE favorita = 'S'");
                $count = $stmt->rowCount();
                if ($count == 0) {
                    echo "<tr><td colspan='3'>Não foram encontradas paragens favoritas</td></tr>";
                }

                // Fetch and display data
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lotacao']) . "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='3'>Error connecting to database: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>