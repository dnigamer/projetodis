import asyncio
import websockets
import sqlite3
import json
import time

# Initialize the database
def init_database():
    conn = sqlite3.connect("camera_data.db")
    cursor = conn.cursor()
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS camera_data (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            camera_id TEXT,
            timestamp TEXT,
            people_count INTEGER
        )
    """)
    conn.commit()
    conn.close()

# Save JSON data to the database
def save_to_database(data):
    conn = sqlite3.connect("camera_data.db")
    cursor = conn.cursor()
    
    camera_id = data.get("camera_id", "unknown")
    if camera_id is None:
        camera_id = "unknown"
        
    timestamp = data.get("timestamp", None)
    if timestamp is None:
        timestamp = int(time.time())
    else:
        if isinstance(timestamp, str):
            try:
                timestamp = int(timestamp)
            except ValueError:
                print(f"Invalid timestamp format: {timestamp}")
                return
        elif not isinstance(timestamp, int):
            print(f"Invalid timestamp type: {type(timestamp)}")
            return
        
        
    people_count = data.get("people_count", 0)
    
    cursor.execute("INSERT INTO camera_data (camera_id, timestamp, people_count) VALUES (?, ?, ?)", (camera_id, timestamp, people_count))
    print(f"Data saved to database: {camera_id}, {timestamp}, {people_count}")
    
    conn.commit()
    conn.close()

async def handle_connection(websocket):
    print("New connection established")
    try:
        async for message in websocket:
            try:
                # Parse the JSON message
                data = json.loads(message)
                destination = data.get("destination", "default")
                
                if destination == "camera_data":
                    save_to_database(data)
                    await websocket.send(json.dumps({"status": "success", "message": "Data received and saved"}))
                elif destination == "ping":
                    await websocket.send(json.dumps({"status": "success", "message": "Pong"}))
                elif destination == "get_status":
                    await websocket.send(json.dumps({"status": "success", "message": "Running"}))
                else:
                    await websocket.send(json.dumps({"status": "error", "message": f"Unknown destination: {destination}"}))
            except json.JSONDecodeError:
                print("Error: Received invalid JSON data")
                await websocket.send(json.dumps({"status": "error", "message": "Invalid JSON format"}))
            except sqlite3.Error as e:
                print(f"Database error: {e}")
                await websocket.send(json.dumps({"status": "error", "message": "Database error"}))
    except websockets.exceptions.ConnectionClosed as e:
        print(f"Connection closed: {e}")

async def main():
    init_database()
    server = await websockets.serve(handle_connection, "localhost", 8765)
    print("WebSocket server started on ws://localhost:8765")
    await server.wait_closed()

if __name__ == "__main__":
    asyncio.run(main())