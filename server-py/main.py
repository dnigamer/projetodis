import asyncio
import websockets
import mysql.connector
import json
import time

# Save JSON data to the database
# Save JSON data to the database
def save_to_database(data):
    conn = mysql.connector.connect(
        host="192.168.1.4",
        user="uniuser",
        password="uL[*P87G.UkYY_X7",
        database="uniuser_sistema-niop"
    )
    cursor = conn.cursor()
    
    # get the paragem_id associated to the camera_id from the database
    camera_id = data.get("camera_id", "unknown")
    cursor.execute("SELECT paragem_id FROM camaras WHERE id = %s", (camera_id,))
    result = cursor.fetchone()
    if result is None:
        print(f"Camera ID {camera_id} not found in the database")
        return
    paragem_id = result[0]
            
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
    
    # Convert timestamp to MySQL datetime format
    data_registo = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(timestamp))
    lotacao = data.get("people_count", 0)
    
    # Insert data into the registo_lotacao table
    try:
        cursor.execute(
            "INSERT INTO registo_lotacao (paragem_id, camera_id, data_registo, lotacao) VALUES (%s, %s, %s, %s)",
            (paragem_id, camera_id, data_registo, lotacao)
        )
        print(f"Data saved to database: paragem_id={paragem_id}, camera_id={camera_id}, data_registo={data_registo}, lotacao={lotacao}")
        conn.commit()
    except mysql.connector.Error as e:
        print(f"Database error: {e}")
    finally:
        conn.close()

async def handle_connection(websocket):
    print("New websocket connection")
    try:
        async for message in websocket:
            try:
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
            except mysql.connector.Error as e:
                print(f"Database error: {e}")
                await websocket.send(json.dumps({"status": "error", "message": "Database error"}))
    except websockets.exceptions.ConnectionClosed as e:
        print(f"Connection closed: {e}")

async def main():
    server = await websockets.serve(handle_connection, "localhost", 8765)
    print("WebSocket server started on ws://localhost:8765")
    await server.wait_closed()

if __name__ == "__main__":
    asyncio.run(main())