"""
Detect people in an image or from a camera feed using YOLOv5.
Developed in use for subject Desenvolvimento Integrado de Software in the course of 
Engenharia de Telecomunicações e Informática at Universidade do Minho.

License: MIT License

Copyright (c) 2025 Gonçalo Miranda (@dnigamer at GitHub)
"""

import uvicorn
from fastapi import FastAPI
from fastapi.responses import JSONResponse

import argparse
import sys
import os
import time

from ultralytics import YOLO
import cv2
import contextlib

app = FastAPI()
model = YOLO("yolov5su.pt", verbose=False)
args = None
os.environ["OPENCV_LOG_LEVEL"] = "SILENT"

def register_arguments() -> argparse.Namespace:
    """
    Register command line arguments.
    
    Returns:
        argparse.Namespace: The parsed command line arguments.
    """
    parser = argparse.ArgumentParser(description="Detetar pessoas em imagens ou de uma câmera usando YOLOv5.")
    parser.add_argument(
        "--image", "-i",
        type=str,
        help="Caminho para a imagem de entrada. Se não for fornecido, a câmera 0 do sistema será usada."
    )
    parser.add_argument(
        "--verbose", "-v",
        action="store_true",
        help="Ativar saída detalhada."
    )
    parser.add_argument(
        "--output", "-o",
        type=str,
        help="Caminho para salvar a imagem de saída."
    )
    parser.add_argument(
        "--model", "-m",
        type=str,
        default="yolov5su.pt",
        help="Caminho para o arquivo de pesos do modelo YOLOv5."
    )
    parser.add_argument(
        "--server", "-s",
        action="store_true",
        help="Ativar modo de servidor. Permite executar o script de deteção como um servidor REST API."
    )
    parser.add_argument(
        "--camera", "-c",
        type=int,
        default=0,
        help="ID da câmera a utilizar. O padrão é 0."
    )
    return parser.parse_args()

def load_model(args: argparse.Namespace, model_path: str) -> YOLO:
    """
    Load the YOLOv5 model.
    
    Args:
        args (argparse.Namespace): The command line arguments.
        model_path (str): The path to the YOLOv5 model weights file.
    Returns:
        YOLO: The loaded YOLOv5 model.
    Raises:
        ValueError: If the model could not be loaded.
    """
    try:
        model = YOLO(model_path, verbose=False)
        if args.verbose:
            print(f"Modelo '{model_path}' carregado com sucesso.")
        if model is None:
            raise ValueError("Modelo não pôde ser carregado.")
        return model
    except Exception as e:
        if args.verbose:
            print(f"Erro ao carregar modelo: {e}")
        return None

def image_checking(args: argparse.Namespace) -> str:
    """
    Checks if we have a valid image path or if we should use the camera.
    
    Args:
        args (argparse.Namespace): The command line arguments.
    Returns:
        str: The path to the image.
    Raises:
        SystemExit: If the image path does not exist and camera cannot be opened.
    """
    if args.image and not args.server:
        image_path = args.image
        if not os.path.exists(image_path):
            if args.verbose:
                print(f"Error: O caminho da imagem '{image_path}' não existe.")
            return None
    else:
        try:
            if args.camera:
                cap = cv2.VideoCapture(args.camera)
            else:
                cap = cv2.VideoCapture(0)
        except Exception as e:
            if args.verbose:
                print(f"Erro ao abrir a câmera: {e}")
            return None
        
        if not cap.isOpened():
            if args.verbose:
                print("Error: Não foi possível abrir a câmera.")
            return None
        
        try:  
            ret, frame = cap.read()
            if not ret:
                if args.verbose:
                    print("Error: Não foi possível ler o frame da câmera.")
                return None
        except Exception as e:
            if args.verbose:
                print(f"Erro ao capturar imagem da câmera: {e}")
            return None
                
        image_path = "frame.jpg"
        cv2.imwrite(image_path, frame)
        cap.release()
        if args.verbose:
            print(f"Imagem capturada da câmera e guardada como '{image_path}'.")
    return image_path

def main() -> None:
    """
    Main function to run the YOLOv5 model for person detection.
    
    This function is called when the script is run directly.
    """

    model_path = args.model
    image_path = image_checking(args)
    output_path = args.output if args.output else False
    if args.verbose:
        print(f"Usando modelo: {model_path}")
        print(f"Usando imagem: {image_path}")
        if output_path:
            print(f"Saída da imagem: {output_path}")
        else:
            print("Saída da imagem: Não especificada, não será guardada.")

    model = load_model(args, model_path)    
    image = cv2.imread(image_path)
    if image is None:
        if args.verbose:
            print(f"Erro: Não foi possível ler a imagem '{image_path}'.")
        sys.exit(-1)
    
    results = model(image_path, verbose=False)

    num_people = 0
    for result in results:
        for box, cls in zip(result.boxes.xyxy, result.boxes.cls):
            if int(cls) == 0:
                num_people += 1
                x1, y1, x2, y2 = map(int, box)
                cv2.rectangle(image, (x1, y1), (x2, y2), (0, 255, 0), 2)

    if args.verbose:
        print(f"Número de pessoas detetadas: {num_people}")
    else:
        print(num_people)
    
    if output_path:
        if args.verbose:
            print(f"Guardando imagem de saída em '{output_path}'.")
        if not cv2.imwrite(output_path, image):
            if args.verbose:
                print(f"Erro: Não foi possível guardar a imagem '{output_path}'.")
            sys.exit(-1)
    else:
        if args.verbose:
            print("Saída da imagem: Não especificada, não será guardada.")
        sys.exit(num_people)
        
@app.get("/camaras")
async def get_cameras():
    """
    Endpoint to get the list of available cameras.

    Returns:
        JSONResponse: The list of available cameras.
    """

    @contextlib.contextmanager
    def suppress_stderr():
        with open(os.devnull, 'w') as devnull:
            old_stderr = sys.stderr
            sys.stderr = devnull
            try:
                yield
            finally:
                sys.stderr = old_stderr

    cameras = []
    for i in range(5):
        with suppress_stderr():
            cap = cv2.VideoCapture(i)
            if cap.isOpened():
                cameras.append(i)
                cap.release()
            else:
                cap.release()
    return JSONResponse(content={"cameras": cameras}, status_code=200)

@app.get("/detect")
async def detect_people():
    """
    Endpoint to detect people in an image.
    
    Returns:
        JSONResponse: The number of people detected.
    """
    start_time = time.time()
    
    image_path = image_checking(args)
    if not image_path:
        if args.verbose:
            return JSONResponse(content={"error": "Não foi possível capturar nenhuma imagem."}, status_code=400)
        
    image = cv2.imread(image_path)
    if image is None:
        if args.verbose:
            return JSONResponse(content={"error": "Não foi possível capturar nenhuma imagem."}, status_code=400)
        
    model_used = load_model(args, args.model)
    if model_used is None:
        if args.verbose:
            return JSONResponse(content={"error": "Modelo não pôde ser carregado."}, status_code=500)
    
    results = model_used(image_path, verbose=False)

    num_people = 0
    for result in results:
        for box, cls in zip(result.boxes.xyxy, result.boxes.cls):
            if int(cls) == 0:
                num_people += 1
                x1, y1, x2, y2 = map(int, box)
                cv2.rectangle(image, (x1, y1), (x2, y2), (0, 255, 0), 2)
                
    output_path = args.output if args.output else "output.jpg"
    if output_path:
        if not cv2.imwrite(output_path, image):
            if args.verbose:
                return JSONResponse(content={"error": "Não foi possivel guardar a imagem de saída."}, status_code=500)

    time_taken = round(time.time() - start_time, 3) * 1000
    
    output_path = os.path.abspath(output_path)
    
    return JSONResponse(content={"num_pessoas": num_people, "tempo": time_taken, "output_path": output_path}, status_code=200)
    
if __name__ == "__main__":
    args = register_arguments()
    if args.verbose:
        print("Argumentos recebidos:")
        for arg in vars(args):
            print(f"  {arg}: {getattr(args, arg)}")
            
    if args.server:
        if args.verbose:
            print("Executando em modo servidor.")
        uvicorn.run(app, host="127.0.0.1", port=8000)
    else:
        main()
