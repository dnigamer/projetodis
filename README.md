# Projeto de Desenvolvimento Integrado de Software - FleetVision
## Grupo 3 - 2024/25

## Introdução
Este projeto é desenvolvido no âmbito da unidade curricular de Desenvolvimento Integrado de Software do curso de Licenciatura em Engenharia de Tecnologias da Informação (LETI) na Universidade do Minho. O objetivo é aplicar os conhecimentos adquiridos ao longo da disciplina no desenvolvimento de uma solução tecnológica que atenda a uma necessidade real.

O projeto é realizado em parceria com os Transportes Urbanos de Braga (TUB), empresa responsável pelo transporte público da cidade. Atualmente, um dos desafios enfrentados pelos TUB é a falta de dados precisos sobre a lotação das paragens, dificultando a tomada de decisões para otimizar a operação da frota. Sem essas  informações, torna-se mais complexo ajustar a oferta de autocarros à procura real, o que pode resultar em superlotação ou desperdício de recursos.

Para resolver este problema, o sistema desenvolvido permitirá a análise automatizada da ocupação das paragens a partir de câmaras CCTV instaladas nos pontos de espera. Através do processamento de imagens em tempo real, será possível estimar o número de passageiros presentes em cada paragem e fornecer dados estatísticos detalhados para os TUB. Com estas informações, a empresa poderá tomar decisões mais
informadas sobre a gestão da frota, melhorando a eficiência do serviço e garantindo um transporte mais adequado às necessidades da cidade.

## Estrutura do Repositório

O repositório está organizado em múltiplos diretórios, cada um responsável por uma componente do sistema:

### [`camara-niop`](camara-niop/README.md)

Projeto desenvolvido na plataforma low-code NIOP. Responsável pela captura de imagens das câmaras instaladas nas paragens, integração com a interface gráfica (HMI) e envio dos dados de contagem para o backend via WebSocket. A configuração é feita através do ficheiro `info.json`.

### [`camara-py`](camara-py/README.md)

Implementação em Python para captura de imagens da webcam, deteção de pessoas utilizando o modelo YOLOv5 (Ultralytics), contagem de passageiros e geração de estatísticas. Utiliza as bibliotecas OpenCV, Ultralytics e NumPy. O código é modular, facilitando adaptações futuras.

### [`server-py`](server-py/README.md)

Backend desenvolvido em Python com FastAPI, responsável por receber os dados das câmaras, gerir a base de dados MySQL e disponibilizar uma API RESTful para consulta e gestão dos dados (câmaras, paragens, alertas, relatórios). Inclui endpoints para todas as operações principais e documentação automática via OpenAPI.

### [`html-pages`](html-pages/README.md)

Conjunto de páginas PHP organizadas em subdiretórios para gestão do sistema via interface web. Inclui páginas para gestão de alertas, câmaras, paragens, relatórios e recursos estáticos (CSS). Deve ser colocado na pasta `htdocs` do XAMPP ou servidor web equivalente.

### [`sistema-niop`](sistema-niop/README.md)

Projeto principal desenvolvido em NIOP, integrando todas as funcionalidades do sistema numa aplicação gráfica (HMI). Permite visualizar e gerir câmaras, paragens, alertas e relatórios, comunicando com o backend e apresentando os dados recolhidos.

### [`scriptDB.sql`](scriptDB.sql)

Script SQL para criação da base de dados MySQL necessária para o funcionamento do sistema. Contém as definições das tabelas, views e procedimentos armazenados utilizados pelo backend.

## Instalação e Execução

### Requisitos Gerais

- **niop Studio & HMI** (para componentes NIOP)
- **Python 3.8+** (para backend e camara-py)
- **MySQL Server** (base de dados)
- **XAMPP/Apache/Nginx** (para servir as páginas PHP)
- **Bibliotecas Python**: `fastapi`, `uvicorn`, `mysql-connector-python`, `opencv-python`, `ultralytics`, `numpy`

### Passos Gerais

1. **Base de Dados**: Execute o script [`scriptDB.sql`](scriptDB.sql) para criar as tabelas e views necessárias.
2. **Backend**: Configure e execute o servidor FastAPI em [`server-py`](server-py/README.md).
3. **Câmaras**: Configure e execute os projetos [`camara-niop`](camara-niop/README.md) e/ou [`camara-py`](camara-py/README.md) conforme necessário.
4. **Interface Web**: Copie o conteúdo de [`html-pages`](html-pages/README.md) para a pasta `htdocs` do XAMPP ou servidor web.
5. **Sistema NIOP**: Abra e execute o projeto em [`sistema-niop`](sistema-niop/README.md) através do niop Studio/HMI.

## Funcionalidades Principais

- **Contagem automática de passageiros em paragens**
- **Gestão de câmaras, paragens e alertas**
- **Relatórios estatísticos (lotação média, pico, fluxo, taxa de alertas)**
- **Interface gráfica intuitiva (web e HMI)**
- **API RESTful documentada para integração com outros sistemas**

## Licença

MIT License. Veja o ficheiro [LICENSE](LICENSE) para mais detalhes.