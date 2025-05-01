# sistema-niop

# Introdução
Esta pasta contém o código total do sistema que apresenta os dados registados na base de dados do sistema de contagem de pessoas nas paragens. Esta aplicação foi desenvolvida utilizando a ferramenta de low-code niop. O sistema consiste numa aplicação contendo diferentes páginas, cada uma com um propósito específico. Abaixo, apresentamos uma breve descrição de cada página e suas funcionalidades.

## Página Inicial

A página inicial do sistema serve como um painel de controle central, oferecendo uma visão geral rápida do estado do sistema e acesso às principais funcionalidades. Ela contém:

- **Menu Lateral**: Localizado à esquerda, permite navegar entre as diferentes páginas do sistema, como:
  - **Câmaras**: Gerenciamento das câmaras conectadas.
  - **Paragens**: Informações sobre as paragens monitoradas.
  - **Alertas**: Exibição de alertas recentes.
  - **Relatórios**: Geração e visualização de relatórios.
  - **Configurações**: Ajustes e preferências do sistema.

- **Estado do Sistema**: Exibido no canto superior direito, indicando se o sistema está operacional (ONLINE) ou não (OFFLINE).

- **Alertas Recentes**: Uma tabela central que lista os alertas mais recentes, incluindo informações como ID, data, descrição do alerta e gravidade.

- **Paragens Favoritas**: Uma tabela que exibe as paragens marcadas como favoritas pelo usuário, com informações como nome e contagem de pessoas.

- **Mensagem de Boas-Vindas**: Um texto centralizado que dá as boas-vindas ao usuário.

Essa página foi projetada para ser intuitiva e fornecer acesso rápido às informações mais importantes do sistema.

Imagem:
![homepage](assets/homepage.png)

## Câmaras

A página de **Câmaras** permite gerenciar as câmaras conectadas ao sistema. Nela, o usuário pode realizar as seguintes ações:

- **Adicionar Câmara**: Inserir uma nova câmara no sistema, especificando informações como modelo, fabricante, e paragem associada.
- **Editar Câmara**: Atualizar os dados de uma câmara existente.
- **Remover Câmara**: Excluir uma câmara do sistema.
- **Obter Contagem**: Consultar a contagem de pessoas registrada por uma câmara específica.

Além disso, a página exibe uma tabela com a lista de câmaras registadas, contendo as seguintes informações:
- **ID**: Identificador único da câmara.
- **Paragem**: Paragem associada à câmara.
- **Modelo**: Modelo da câmara.
- **Fabricante**: Fabricante da câmara.
- **Data de Instalação**: Data em que a câmara foi instalada.
- **Estado**: Estado atual da câmara (Ativo ou Inativo).

Essa página foi projetada para facilitar o gerenciamento das câmaras e garantir que todas as informações estejam organizadas e acessíveis.

Imagem:
![camaras](assets/camaras.png)

## Paragens

A página de **Paragens** permite gerenciar as paragens monitoradas pelo sistema. Nela, o usuário pode realizar as seguintes ações:

- **Adicionar Paragem**: Inserir uma nova paragem no sistema, especificando informações como nome e localização.
- **Editar Paragem**: Atualizar os dados de uma paragem existente.
- **Remover Paragem**: Excluir uma paragem do sistema.
- **Obter Estado**: Consultar o estado atual de uma paragem (Ativo ou Inativo).
- **Atualizar Lotação**: Atualizar manualmente a lotação de uma paragem.

Além disso, a página exibe uma tabela com a lista de paragens cadastradas, contendo as seguintes informações:
- **ID**: Identificador único da paragem.
- **Nome**: Nome da paragem.
- **Localização**: Localização da paragem.
- **Estado**: Estado atual da paragem (Ativo ou Inativo).
- **Lotação**: Número atual de pessoas na paragem.

Essa página foi projetada para facilitar o gerenciamento das paragens e garantir que todas as informações estejam organizadas e acessíveis.

Imagem:
![paragens](assets/paragens.png)

## Alertas

A página de **Alertas** permite gerenciar os alertas gerados pelo sistema. Nela, o usuário pode realizar as seguintes ações:

- **Adicionar Alerta**: Criar um novo alerta no sistema, especificando informações como paragem, câmara, tipo e descrição.
- **Editar Alerta**: Atualizar os dados de um alerta existente.
- **Remover Alerta**: Excluir um alerta do sistema.
- **Enviar Alerta**: Notificar os responsáveis sobre um alerta específico.
- **Desativar Alerta**: Alterar o estado de um alerta para desativado.

Além disso, a página exibe uma tabela com a lista de alertas registrados, contendo as seguintes informações:
- **ID**: Identificador único do alerta.
- **Paragem**: Paragem associada ao alerta.
- **Câmara**: Câmara associada ao alerta.
- **Data Alerta**: Data em que o alerta foi gerado.
- **Data Resolução**: Data em que o alerta foi resolvido (se aplicável).
- **Tipo Alerta**: Tipo do alerta (e.g., Serviço, Segurança).
- **Descrição**: Descrição detalhada do alerta.
- **Gravidade**: Nível de gravidade do alerta.
- **Estado**: Estado atual do alerta (e.g., Pendente, Finalizado).

Essa página foi projetada para facilitar o acompanhamento e a resolução de problemas detectados pelo sistema, garantindo que os alertas sejam tratados de forma eficiente.

Imagem:
![alertas](assets/alertas.png)

## Relatórios

A página de **Relatórios** permite gerar e visualizar relatórios baseados nos dados coletados pelo sistema. Nela, o usuário pode realizar as seguintes ações:

- **Obter Lotação Média**: Calcular a lotação média das paragens monitoradas.
- **Obter Pico Lotação**: Identificar o pico de lotação em cada paragem.
- **Obter Fluxo Passageiros**: Analisar o fluxo de passageiros em diferentes paragens.
- **Obter Taxa de Alertas**: Calcular a taxa de alertas gerados em relação ao total de paragens ou câmaras.
- **Gerar Relatório**: Criar relatórios personalizados com base nos dados selecionados.

Além disso, a página exibe uma tabela com a lista de paragens, contendo as seguintes informações:
- **ID**: Identificador único da paragem.
- **Nome**: Nome da paragem.
- **Localização**: Localização da paragem.
- **Estado**: Estado atual da paragem (Ativo ou Inativo).
- **Lotação**: Número atual de pessoas na paragem.

Essa página foi projetada para fornecer relatórios detalhados sobre o sistema, ajudando na tomada de decisões e no acompanhamento do desempenho das paragens monitoradas.

Imagem:
![relatorios](assets/relatorios.png)