# Projeto de Desenvolvimento Integrado de Software
## Grupo 3 - 2024/25

## Introdução
O projeto consiste na implementação de um sistema de deteção de pessoas em imagens, utilizando técnicas de visão computacional e machine learning para contar o número de pessoas presentes numa determinada paragem de autocarro. A partir ou de imagens ou de uma stream de webcam, o sistema deve ser capaz de identificar e contar o número de pessoas presentes na imagem ou stream.

A utilização deste sistema será feita por parte de um operador de transportes públicos, que poderá utilizar a informação obtida para otimizar a gestão de recursos e melhorar a eficiência do serviço prestado.

Este projeto feito em Python, utiliza certas bibliotecas e modelos de machine learning para realizar a deteção de pessoas que não estão disponíveis no programa indicado para o desenvolvimento deste projeto (niop Studio). Assim, parte do projeto foi desenvolvido fora do niop Studio, utilizando Python e as bibliotecas OpenCV, Ultralytics e NumPy para integração com um algoritmo de deteção de objetos. 

O modelo YOLOv5 foi utilizado para a deteção de pessoas, e o algoritmo de contagem foi implementado utilizando técnicas de processamento de imagem e machine learning.

## Instalação
Devido ao facto de o projeto ter sido desenvolvido fora do niop Studio, é necessário instalar as bibliotecas necessárias para a execução do projeto. Para tal, é necessário ter o Python 3.8 ou superior instalado no seu sistema para poder fazer ou a compilação do projeto ou a execução do mesmo. 

Para instalar as bibliotecas necessárias, execute o seguinte comando no terminal:
```bash
pip install -r requirements.txt
```

## Execução
Após a instalação das bibliotecas necessárias, é possível executar o projeto. Para tal, execute o seguinte comando no terminal:
```bash
python main.py
```

Sem qualquer argumento passado, o programa vai automaticamente tirar uma imagem da webcam e executar a deteção de pessoas nessa imagem capturada utilizando um modelo predefinido do YOLOv5. Se não houver webcam disponível, o programa irá falhar por não ter nenhuma imagem para processar.

### Argumentos
#### --image, -i
O argumento `--image` ou `-i` permite especificar o caminho para uma imagem de entrada. Se não for fornecido, o programa irá utilizar a webcam como fonte de imagem.
#### --verbose, -v
O argumento `--verbose` ou `-v` ativa a saída detalhada do programa. Isso pode incluir informações adicionais sobre o processamento da imagem e os resultados da deteção.
#### --output, -o
O argumento `--output` ou `-o` permite especificar o caminho para salvar a imagem de saída. Se não for fornecido, a imagem de saída não será salva.
#### --model, -m
O argumento `--model` ou `-m` permite especificar o caminho para o arquivo de pesos do modelo YOLOv5. O valor padrão é `yolov5su.pt`, que é um modelo pré-treinado. Se quiser utilizar outro modelo, deve especificar o caminho para o arquivo de pesos correspondente.

**NOTA:** Reforçando, se não for fornecido nenhum argumento, o programa irá utilizar o modelo pré-treinado `yolov5su.pt` como padrão e imprimirá apenas os resultados na consola sem qualquer outra informação ou output.

## Compilação para um ficheiro executável
Para compilar o projeto para um ficheiro executável, é necessário ter o PyInstaller instalado. Para tal, execute o seguinte comando no terminal:
```bash
pip install pyinstaller
```

Após a instalação do PyInstaller, execute o seguinte comando no terminal para compilar o projeto:
```bash
pyinstaller --onefile main.py
```

Isto irá gerar um ficheiro executável na pasta `dist` com o nome `main.exe` (ou `main` no Linux). O ficheiro executável pode ser executado diretamente sem a necessidade de ter o Python ou as bibliotecas instaladas.

O executável irá funcionar da mesma forma que o script Python, aceitando os mesmos argumentos e opções. No entanto, é importante notar que o executável pode ser maior em tamanho devido à inclusão das bibliotecas necessárias.

Outro ponto a ter em conta é que o executável não irá funcionar fora do sistema operativo para o qual foi compilado. Ou seja, se compilar o projeto no Windows, o executável só irá funcionar no Windows. Para compilar o projeto para outro sistema operativo, deve-se utilizar o PyInstaller nesse sistema operativo.

### Considerações acerca do executável
O programa de saída pode demorar algum tempo a ser gerado, cerca de 2 a 3 minutos, dependendo do tamanho do projeto e da velocidade do computador usado. 

O tempo de execução do programa de saída é **relativamente mais elevado** comparando com a execução direta do código utilizando o comando `python main.py`, devido à inclusão de todas as bibliotecas necessárias para a execução do projeto. O tempo de execução do programa de saída pode variar consoante o sistema operativo e o computador utilizado.

É altamente recomendável executar o projeto antes de o compilar para um executável, para garantir que tudo está a funcionar corretamente. Caso contrário, o executável pode não funcionar como esperado.

## Licença
MIT License. Veja o ficheiro LICENSE para mais detalhes.