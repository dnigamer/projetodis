# Projeto de Desenvolvimento Integrado de Software
## Grupo 3 - 2024/25

## Introdução
Este projeto é desenvolvido no âmbito da unidade curricular de Desenvolvimento Integrado de Software do curso de Licenciatura em Engenharia de Tecnologias da Informação (LETI) na Universidade do Minho. O objetivo é aplicar os conhecimentos adquiridos ao longo da disciplina no desenvolvimento de uma solução tecnológica que atenda a uma necessidade real.

O projeto é realizado em parceria com os Transportes Urbanos de Braga (TUB), empresa responsável pelo transporte público da cidade. Atualmente, um dos desafios enfrentados pelos TUB é a falta de dados precisos sobre a lotação das paragens, dificultando a tomada de decisões para otimizar a operação da frota. Sem essas  informações, torna-se mais complexo ajustar a oferta de autocarros à procura real, o que pode resultar em superlotação ou desperdício de recursos.

Para resolver este problema, o sistema desenvolvido permitirá a análise automatizada da ocupação das paragens a partir de câmaras CCTV instaladas nos pontos de espera. Através do processamento de imagens em tempo real, será possível estimar o número de passageiros presentes em cada paragem e fornecer dados estatísticos detalhados para os TUB. Com estas informações, a empresa poderá tomar decisões mais
informadas sobre a gestão da frota, melhorando a eficiência do serviço e garantindo um transporte mais adequado às necessidades da cidade.

## Componentes deste repositório
### [camara-niop](camara-niop/README.md)

Este diretório contém o código-fonte do projeto desenvolvido no niop Studio. Esta parte do projeto é responsável pela execução do algoritmo de deteção de pessoas em imagens e pelo envio dos dados para o servidor. 

Feito inteiramente utilizando a plataforma niop Studio, não há qualquer código associado ao mesmo, uma vez que a plataforma baseia-se em "low-code" e "no-code".

### [camara-py](camara-py/README.md)

Este diretório contém o código-fonte do projeto desenvolvido fora do niop Studio. Esta parte do projeto é responsável pela captura de imagens da webcam, pela execução do algoritmo de deteção de pessoas, utilizando o modelo YOLOv5, e pela contagem de pessoas e geração de estatísticas finais. 

Feito inteiramente em Python, o código é dividido em várias funções, cada uma responsável por uma parte específica do processo. O código utiliza as bibliotecas OpenCV, Ultralytics e NumPy para a execução do algoritmo de deteção de pessoas e para a contagem de pessoas. O código é modular e fácil de entender, permitindo a sua adaptação a diferentes necessidades.

### [server-py](server-py/README.md)

Este diretório contém o código-fonte do servidor de backend desenvolvido em Python. Este servidor é responsável por receber os dados enviados pelo projeto camara-niop e por armazenar os dados na base de dados.

O servidor é desenvolvido em Python e utiliza as bibliotecas websockets, asyncio e sqlite3 para a execução do servidor WebSocket e para a comunicação com a base de dados. O código é modular e fácil de entender, permitindo a sua adaptação a diferentes necessidades.

Como ainda haverão alterações a serem feitas, o código ainda não está completamente documentado nem na sua melhor versão possível, sendo que a ideia final seria utilizar outras tecnologias de base de dados, como o MariaDB ou o MongoDB, para melhorar a performance e a escalabilidade do sistema. Neste momento, o servidor está a correr numa base de dados SQLite, que é uma base de dados leve e fácil de usar, mas que não é adequada para aplicações em produção.

## Licença

MIT License. Veja o ficheiro [LICENSE](LICENSE) para mais detalhes.