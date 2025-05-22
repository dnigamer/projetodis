# Implementação do Sistema de Contagem de Passageiros em Paragens

Este documento descreve a estrutura do projeto, incluindo uma visão geral de cada pasta e arquivo, com base nas funcionalidades descritas nos arquivos README.md e outros contextos fornecidos.

Resumidamente, o projeto é dividido em cinco partes principais:
- **camara-py**: Captura e processa imagens.
- **camara-niop**: Gerencia a captura feita pela camara-py e envia dados para o servidor de forma contínua.
- **server-py**: Recebe e processa os dados enviados pelas câmaras guardando-os numa base de dados.
- **sistema-niop**: Sistema principal desenvolvido na plataforma NIOP.
- **html-pages**: Páginas PHP que compõem a interface de browser wrapper do sistema-niop.


---

## Estrutura Geral

```
facedetect/
├── camara-py/
├── camara-niop/
├── html-pages/
├── sistema-niop/
└── server-py/
```

---

## Pasta camara-py

Esta pasta contém o código Python responsável por capturar e processar imagens ou streams de vídeo para contar o número de passageiros.

O projeto pode ser compilado em um executável para facilitar sua execução. Neste caso, o arquivo `main.spec` é utilizado para configurar o PyInstaller para gerar o executável.


### Estrutura

```
camara-py/
├── .gitignore
├── exemplos/
├── assets/
├── main.py
├── main.spec
├── README.md
├── requirements.txt
└── LICENSE
```

### Arquivos Principais

- **`main.py`**: Código principal que captura e processa imagens.
- **`main.spec`**: Arquivo de configuração para compilar o projeto em um executável usando PyInstaller.
- **`requirements.txt`**: Lista de dependências necessárias para executar o projeto.
- **`README.md`**: Explica como instalar, executar e compilar o projeto.

---

## Pasta camara-niop

Esta pasta contém o projeto desenvolvido na plataforma de low-code NIOP, que serve para executar o código Python da pasta `camara-py`, processar os resultados e enviar os dados para o servidor `server-py`.

### Estrutura

```
camara-niop/
├── camara-niop.npp
├── LICENSE
├── README.md
├── assets/
│   └── projeto.png
├── Pages/
│   └── Resources/
└── Workflows/
    └── main.niw
```

### Arquivos Principais

- **`camara-niop.npp`**: Arquivo de projeto da plataforma NIOP.
- **`main.niw`**: Workflow principal que define a lógica do projeto.
- **`README.md`**: Explica o funcionamento do projeto, incluindo a captura de imagens e envio de dados via WebSocket.

---

## Pasta html-pages

Esta pasta contém as páginas PHP responsáveis pela interface web do sistema, organizadas por funcionalidade.

### Estrutura

```
html-pages/
├── config.php
├── README.md
├── alertas/
│   ├── adicionar.php
│   ├── desativar.php
│   ├── editar.php
│   ├── enviar.php
│   ├── lista.php
│   ├── recentes.php
│   └── remover.php
├── camaras/
│   ├── adicionar.php
│   ├── contagem.php
│   ├── editar.php
│   ├── lista.php
│   └── remover.php
├── paragens/
│   ├── adicionar.php
│   ├── editar.php
│   ├── estado.php
│   ├── favoritas.php
│   ├── lista.php
│   ├── lotacao.php
│   └── remover.php
├── relatorios/
│   ├── fluxo_passageiros.php
│   ├── geral.php
│   ├── lotacao_media.php
│   ├── pico_lotacao.php
│   └── taxa_alertas.php
└── static/
    ├── css/
        ├── style.css
        └── style-small.css
```

### Subpastas

- **`alertas/`**: Contém páginas para gerenciar alertas, como adicionar, editar, desativar e listar alertas.
- **`camaras/`**: Contém páginas para gerenciar câmaras, como adicionar, editar, remover e consultar contagens.
- **`paragens/`**: Contém páginas para gerenciar paragens, como adicionar, editar, consultar estado e listar paragens favoritas.
- **`relatorios/`**: Contém páginas para gerar relatórios, como lotação média, pico de lotação e fluxo de passageiros.
- **`static/`**: Contém recursos estáticos, como arquivos CSS.

---

## Pasta sistema-niop

Esta pasta contém o sistema principal desenvolvido na plataforma NIOP, que integra todas as funcionalidades do projeto.

### Estrutura

```
sistema-niop/
├── bindings.txt
├── README.md
├── sistema-niop.npp
├── assets/
│   ├── alertas.png
│   ├── camaras.png
│   ├── homepage.png
│   ├── paragens.png
│   └── relatorios.png
├── Pages/
└── Workflows/
```

### Arquivos Principais

- **`sistema-niop.npp`**: Arquivo de projeto da plataforma NIOP.
- **`bindings.txt`**: Configurações de bindings do projeto.
- **`README.md`**: Explica as funcionalidades do sistema, incluindo câmaras, paragens, alertas e relatórios.

---

## Pasta server-py

Esta pasta contém o código Python para o servidor que armazena e processa os dados enviados pelas câmaras.

### Estrutura

```
server-py/
├── .gitignore
├── main.py
└── requirements.txt
```

### Arquivos Principais

- **`main.py`**: Código principal do servidor que processa os dados recebidos.
- **`requirements.txt`**: Lista de dependências necessárias para executar o servidor.

---
