# Projeto de Backend REST API - Sistema de Contagem de Pessoas

## Introdução

Este projeto implementa uma API RESTful desenvolvida em Python com FastAPI, responsável por gerir e disponibilizar dados de um sistema de contagem de pessoas em paragens de autocarro. 

O backend integra-se com uma base de dados MySQL e permite a gestão de câmaras, paragens, alertas, registos de lotação e relatórios estatísticos.

A API serve como backend para aplicações de monitorização, gestão e análise de dados de ocupação e alertas em tempo real, permitindo a integração com sistemas de frontend, dashboards ou outras aplicações.

## Funcionalidades Principais

- **Gestão de Câmaras**: Adicionar, listar, atualizar, remover e consultar dados de câmaras e respetivos registos de lotação.
- **Gestão de Paragens**: Adicionar, listar, atualizar, remover, marcar/desmarcar como favorita e consultar dados de paragens.
- **Gestão de Alertas**: Adicionar, listar, atualizar, remover, ativar/desativar, enviar e consultar alertas, incluindo alertas recentes.
- **Relatórios**: Obter relatórios de fluxo de passageiros, lotação média, pico de lotação e taxa de alertas por paragem.

## Instalação

1. **Pré-requisitos**:
    - Python 3.8 ou superior
    - MySQL Server
    - Instalar dependências:
    ```bash
    pip install fastapi uvicorn mysql-connector-python
    ```

2. **Configuração da Base de Dados**:
    - Para configurar a base de dados, é necessário criar uma base de dados MySQL e executar o script SQL disponível no diretório raiz do projeto com o nome `scriptDB.sql`. Este script irá criar as tabelas necessárias para o funcionamento da API.

## Execução
Para iniciar o servidor FastAPI, execute o seguinte comando no terminal:

```bash
python main.py
```

O servidor estará disponível em `http://localhost:8000`, a não ser que tenha configurado uma porta diferente.

## Documentação da API
A documentação interativa da API pode ser acessada em `http://localhost:8000/docs` após iniciar o servidor. Esta documentação permite explorar os endpoints disponíveis, testar as requisições e visualizar os modelos de dados utilizados.

Imagem da documentação:
![Documentação da API](assets/openapi.png)

## Endpoints Disponíveis

### Câmaras

- `GET /api/camaras`  
  Lista todas as câmaras registadas.
- `GET /api/camaras/{camera_id}`  
  Consulta os dados de uma câmara específica.
- `POST /api/camaras`  
  Adiciona uma nova câmara.
- `PUT /api/camaras/{camera_id}`  
  Atualiza os dados de uma câmara.
- `DELETE /api/camaras/{camera_id}`  
  Remove uma câmara.
- `GET /api/camaras/lotacao`  
  Obtém a última lotação registada de todas as câmaras.
- `GET /api/camaras/{camera_id}/lotacao`  
  Lista o histórico de lotação de uma câmara.
- `POST /api/camaras/lotacao`  
  Regista uma nova lotação para uma câmara.
- `POST /api/camaras/{camera_id}/lotacao/{limit}`  
  Regista uma nova lotação para uma câmara com limite.

### Paragens

- `GET /api/paragens`  
  Lista todas as paragens.
- `GET /api/paragens/{paragem_id}`  
  Consulta os dados de uma paragem.
- `POST /api/paragens`  
  Adiciona uma nova paragem.
- `PUT /api/paragens/{paragem_id}`  
  Atualiza os dados de uma paragem.
- `DELETE /api/paragens/{paragem_id}`  
  Remove uma paragem.
- `GET /api/paragens/favoritas`  
  Lista as paragens marcadas como favoritas.
- `PUT /api/paragens/{paragem_id}/favoritas`  
  Marca ou desmarca uma paragem como favorita.
- `GET /api/paragens/{paragem_id}/lotacao`  
  Obtém a lotação atual de uma paragem.

### Alertas

- `GET /api/alertas`  
  Lista todos os alertas.
- `GET /api/alertas/{alerta_id}`  
  Consulta os dados de um alerta.
- `POST /api/alertas`  
  Adiciona um novo alerta.
- `PUT /api/alertas/{alerta_id}`  
  Atualiza os dados de um alerta.
- `DELETE /api/alertas/{alerta_id}`  
  Remove um alerta.
- `PUT /api/alertas/{alerta_id}/desativar`  
  Desativa (finaliza) um alerta.
- `PUT /api/alertas/{alerta_id}/ativar`  
  Ativa um alerta.
- `PUT /api/alertas/{alerta_id}/enviar`  
  (Placeholder) Envia um alerta.
- `GET /api/alerta/recentes`  
  Lista os alertas recentes.

### Relatórios

- `GET /api/relatorios/fluxopassageiros`  
  Retorna o fluxo de passageiros (lotação ao longo do tempo) para cada paragem.
- `GET /api/relatorios/lotacaomedia`  
  Retorna a lotação média para cada paragem.
- `GET /api/relatorios/picolotacao`  
  Retorna o pico de lotação para cada paragem.
- `GET /api/relatorios/taxaalertas`  
  Retorna a taxa de alertas por paragem.

## Exemplo de Utilização

### Adicionar uma nova câmara

```bash
curl -X POST http://localhost:8080/api/camaras \
  -H "Content-Type: application/json" \
  -d '{"paragem_id":1,"modelo":"Canon EOS","fabricante":"Canon","latitude":41.55,"longitude":-8.42,"data_instalacao":"2025-04-20","estado":"Ativo"}'
```

### Obter relatório de lotação média

```bash
curl http://localhost:8080/api/relatorios/lotacaomedia
```

## Estrutura da Base de Dados

A base de dados é composta por quatro tabelas principais: `paragens`, `camaras`, `alertas` e `registo_lotacao`. Também inclui várias views para relatórios (alertas recentes, lotação média, pico de lotação, taxa de alertas e fluxo de lotação) e uma trigger que mantém a lotação atualizada em cada paragem após novos registos.

**Resumo das tabelas:**
- **paragens**: Dados das paragens de autocarro (nome, localização, estado, lotação, favorita).
- **camaras**: Informações das câmaras instaladas em cada paragem.
- **alertas**: Registo de alertas gerados pelo sistema, associados a paragens e câmaras.
- **registo_lotacao**: Histórico de contagem de pessoas por câmara e paragem.

**Views e trigger:**
- Views para relatórios de alertas, lotação média/pico, taxa de alertas e fluxo de passageiros.
- Trigger para atualizar automaticamente a lotação da paragem após novo registo.

O script SQL completo encontra-se no ficheiro `scriptDB.sql` no diretório do projeto.

## Licença

MIT License. Consulte o ficheiro [LICENSE](LICENSE) para mais detalhes.
