"""
Detect people in an image or from a camera feed using YOLOv5.
Developed in use for subject Desenvolvimento Integrado de Software in the course of 
Engenharia de Telecomunicações e Informática at Universidade do Minho.

License: MIT License

Copyright (c) 2025 Gonçalo Miranda (@dnigamer at GitHub)
"""

import argparse
import sys
import os

from ultralytics import YOLO
import cv2

def register_arguments() -> argparse.Namespace:
    """
    Register command line arguments.
    
    Returns:
        argparse.Namespace: The parsed command line arguments.
    """
    parser = argparse.ArgumentParser(description="Detect people in an image or from a camera feed.")
    parser.add_argument(
        "--image", "-i",
        type=str,
        help="Path to the input image. If not provided, the camera will be used."
    )
    parser.add_argument(
        "--verbose", "-v",
        action="store_true",
        help="Enable verbose output."
    )
    parser.add_argument(
        "--output", "-o",
        type=str,
        help="Path to save the output image."
    )
    parser.add_argument(
        "--model", "-m",
        type=str,
        default="yolov5su.pt",
        help="Path to the YOLOv5 model weights file."
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
            print(f"Model loaded from {model_path}.")
        if model is None:
            raise ValueError("Model could not be loaded.")
        return model
    except Exception as e:
        if args.verbose:
            print(f"Error loading model: {e}")
        sys.exit(-1)

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
    if args.image:
        image_path = args.image
        if not os.path.exists(image_path):
            if args.verbose:
                print(f"Image path '{image_path}' does not exist.")
            sys.exit(-1)
    else:
        # Use image from camera
        cap = cv2.VideoCapture(0)
        if not cap.isOpened():
            if args.verbose:
                print("Error: Could not open camera.")
            sys.exit(-1)
        ret, frame = cap.read()
        if not ret:
            if args.verbose:
                print("Error: Could not read frame from camera.")
            sys.exit(-1)
        image_path = "frame.jpg"
        cv2.imwrite(image_path, frame)
        cap.release()
        if args.verbose:
            print(f"Captured image from camera and saved as '{image_path}'.")
    return image_path

def main() -> None:
    """
    Main function to run the YOLOv5 model for person detection.
    """
    args = register_arguments()

    model_path = args.model
    image_path = image_checking(args)
    output_path = args.output if args.output else False
    if args.verbose:
        print(f"Using model: {model_path}")
        print(f"Input image: {image_path}")
        if output_path:
            print(f"Output image: {output_path}")
        else:
            print("Output image: Not specified, will not save.")

    model = load_model(args, model_path)    
    image = cv2.imread(image_path)
    if image is None:
        if args.verbose:
            print(f"Error: Could not read image '{image_path}'.")
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
        print(f"Number of people detected: {num_people}")
    else:
        print(num_people)
    
    if output_path:
        if args.verbose:
            print(f"Saving output image to '{output_path}'.")
        if not cv2.imwrite(output_path, image):
            if args.verbose:
                print(f"Error: Could not save image '{output_path}'.")
            sys.exit(-1)
    else:
        if args.verbose:
            print("Output image not specified, not saving.")
        sys.exit(num_people)
    
if __name__ == "__main__":
    main()
    