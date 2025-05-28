# html-pages

Esta pasta contém as páginas PHP para a gestão do sistema, organizadas em diferentes subdiretórios com base na funcionalidade.

## Pasta `alertas`
Contém páginas relacionadas com a gestão de alertas no sistema:
- **adicionar.php**: Adicionar um novo alerta.
- **desativar.php**: Desativar um alerta existente.
- **editar.php**: Editar os detalhes de um alerta.
- **enviar.php**: Placeholder para envio de alertas (não implementado).
- **lista.php**: Listar todos os alertas com informações detalhadas.
- **recentes.php**: Mostrar alertas recentes.
- **remover.php**: Remover um alerta.

## Pasta `camaras`
Contém páginas para a gestão de câmaras:
- **adicionar.php**: Adicionar uma nova câmara.
- **contagem.php**: Mostrar a contagem de pessoas detetadas por uma câmara específica.
- **editar.php**: Editar os detalhes de uma câmara.
- **lista.php**: Listar todas as câmaras com informações detalhadas.
- **remover.php**: Remover uma câmara.

## Pasta `paragens`
Contém páginas para a gestão de paragens de autocarro:
- **adicionar.php**: Adicionar uma nova paragem.
- **editar.php**: Editar os detalhes de uma paragem.
- **estado.php**: Obter o estado de uma paragem.
- **favoritas.php**: Listar as paragens favoritas.
- **lista.php**: Listar todas as paragens com informações detalhadas.
- **lotacao.php**: Obter a lotação atual de uma paragem.
- **remover.php**: Remover uma paragem.

## Pasta `relatorios`
Contém páginas para a geração de relatórios:
- **fluxo_passageiros.php**: Relatório sobre o fluxo de passageiros ao longo do tempo.
- **geral.php**: Relatório geral sobre as paragens.
- **lotacao_media.php**: Relatório sobre a lotação média das paragens.
- **pico_lotacao.php**: Relatório sobre o pico de lotação das paragens.
- **taxa_alertas.php**: Relatório sobre a taxa de alertas por gravidade.

## Pasta `static`
Contém recursos estáticos, como ficheiros CSS:
- **css/style.css**: Folha de estilos para ecrãs maiores.
- **css/style-small.css**: Folha de estilos para ecrãs menores.

## Licença

MIT License. Veja o ficheiro [LICENSE](LICENSE) para mais detalhes.