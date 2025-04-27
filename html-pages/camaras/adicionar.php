<?php
// detect POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Detetado POST!<br>";
}
?>

<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <title>Adicionar Câmara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <meta name="description" content="Adicionar uma nova câmara ao sistema.">
    <link rel="stylesheet" href="/static/css/style.css">
</head>

<body>
    <form action="/camaras/adicionar.php" method="POST">
        <table>
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="id">ID da câmara</label></td>
                    <td><input type="text" id="id" name="id" required></td>
                </tr>
                <tr>
                    <td><label for="paragem">Paragem associada</label></td>
                    <td><input type="text" id="paragem" name="paragem"></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Informação da Câmara</strong></td>
                </tr>
                <tr>
                    <td><label for="modelo">Modelo</label></td>
                    <td><input type="text" id="modelo" name="modelo" required></td>
                </tr>
                <tr>
                    <td><label for="fabricante">Fabricante</label></td>
                    <td><input type="text" id="fabricante" name="fabricante" required></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Localização</strong></td>
                </tr>
                <tr>
                    <td><label for="latitude">Latitude</label></td>
                    <td><input type="text" id="latitude" name="latitude" required></td>
                </tr>
                <tr>
                    <td><label for="longitude">Longitude</label></td>
                    <td><input type="text" id="longitude" name="longitude" required></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Outras informações</strong></td>
                </tr>
                <tr>
                    <td><label for="data_instalacao">Data de Instalação</label></td>
                    <td><input type="text" id="data_instalacao" name="data_instalacao" placeholder="DD/MM/AAAA" required></td>
                </tr>
                <tr>
                    <td><label for="estado">Estado</label></td>
                    <td>
                        <select id="estado" name="estado">
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        <br><br>

        <input type="reset" value="Limpar Formulário" class="styled-button">
        <input type="submit" value="Adicionar Câmara" class="styled-button">
    </form>
</body>
</html>