from fastapi import FastAPI, Request, Path, Body
from fastapi.responses import JSONResponse
import uvicorn

import mysql.connector
import time

app = FastAPI()
conn = mysql.connector.connect(
    host="192.168.1.4",
    user="uniuser",
    password="uL[*P87G.UkYY_X7",
    database="uniuser_sistema-niop"
)
cursor = conn.cursor()

"""
-/api/camaras (GET) - Obter dados de todas as câmaras
-/api/camaras (POST) - Adicionar nova camara
-/api/camaras/lotacao (GET) - Obter lotação de todas as câmaras
-/api/camaras/lotacao (POST) - Adicionar nova lotação
-/api/camaras/{camera_id} (GET) - Recebe dados de câmara com ID específico
-/api/camaras/{camera_id} (DELETE) - Remove dados de câmara com ID específico
-/api/camaras/{camera_id} (PUT) - Atualiza dados de câmara com ID específico
-/api/camaras/{camera_id}/lotacao (POST) - Adicionar nova lotação para câmara com ID específico
-/api/camaras/{camera_id}/lotacao/{limit} (GET) - Obter uma lista de lotação com limite especificado
"""

@app.get("/api/camaras/lotacao")
async def listar_lotacao():
    cursor.execute("""
        SELECT rl.camera_id, rl.data_registo, rl.lotacao
        FROM registo_lotacao rl
        INNER JOIN (
            SELECT camera_id, MAX(data_registo) AS max_data_registo
            FROM registo_lotacao
            GROUP BY camera_id
        ) latest
        ON rl.camera_id = latest.camera_id AND rl.data_registo = latest.max_data_registo
        ORDER BY rl.camera_id
    """)
    result = cursor.fetchall()
    if not result:
        return JSONResponse(status_code=404, content={"message": "Nenhuma lotação encontrada"})
    
    lotacao = []
    for row in result:
        lotacao.append({
            "camera_id": row[0],
            "data_registo": row[1].isoformat() if row[1] is not None else None,
            "lotacao": row[2],
        })
    return JSONResponse(status_code=200, content=lotacao)

@app.post("/api/camaras/lotacao")
async def camera_data(data: dict):
    required_fields = ["camera_id", "lotacao", "timestamp"]
    for field in required_fields:
        if field not in data:
            return JSONResponse(status_code=400, content={"message": f"Campo {field} é obrigatório"})
    
    camera_id = data.get("camera_id", "unknown")
    cursor.execute("SELECT paragem_id FROM camaras WHERE id = %s", (camera_id,))
    result = cursor.fetchone()
    if result is None:
        print(f"ID da câmara {camera_id} não encontrado na base de dados")
        return JSONResponse(status_code=404, content={"message": "ID da câmara não encontrado"})
    
    paragem_id = result[0]
    data_registo = time.strftime("%Y-%m-%d %H:%M:%S")
    lotacao = data.get("lotacao", 0)

    try:
        cursor.execute(
            "INSERT INTO registo_lotacao (paragem_id, camera_id, data_registo, lotacao) VALUES (%s, %s, %s, %s)",
            (paragem_id, camera_id, data_registo, lotacao)
        )
        conn.commit()
    except mysql.connector.Error as e:
        print(f"Database error: {e}")
        return JSONResponse(status_code=500, content={"message": "Erro ao salvar dados no banco de dados"})
    
    return JSONResponse(status_code=200, content={"message": "Dados recebidos com sucesso"})

@app.post("/api/camaras")
async def adicionar_camara(data: dict):
    required_fields = ["paragem_id", "modelo", "fabricante", "latitude", "longitude", "data_instalacao", "estado"]
    for field in required_fields:
        if field not in data:
            return JSONResponse(status_code=400, content={"message": f"Campo {field} é obrigatório"})
        
    cursor.execute("SELECT id FROM paragens WHERE id = %s", (data["paragem_id"],))
    result = cursor.fetchone()
    if result is None:
        return JSONResponse(status_code=404, content={"message": "ID da paragem não encontrado"})

    cursor.execute(
        "INSERT INTO camaras (paragem_id, modelo, fabricante, latitude, longitude, data_instalacao, estado) VALUES (%s, %s, %s, %s, %s, %s, %s)",
        (data["paragem_id"], data["modelo"], data["fabricante"], data["latitude"], data["longitude"], data["data_instalacao"], data["estado"])
    )
    conn.commit()
    return JSONResponse(status_code=201, content={"message": "Câmara adicionada com sucesso"})

@app.get("/api/camaras")
@app.get("/api/camaras/{camera_id}")
async def listar_camaras(camera_id: int = None):
    if camera_id is not None:
        cursor.execute("SELECT * FROM camaras WHERE id = %s", (camera_id,))
    else:
        cursor.execute("SELECT * FROM camaras")
        
    result = cursor.fetchall()
    if not result:
        return JSONResponse(status_code=404, content={"message": "Nenhuma câmara encontrada"})
    
    camaras = []
    for row in result:
        camara = {
            "id": row[0],
            "paragem_id": row[1],
            "modelo": row[2],
            "fabricante": row[3],
            "latitude": float(row[4]) if row[4] is not None else None,
            "longitude": float(row[5]) if row[5] is not None else None,
            "data_instalacao": row[6].isoformat() if row[6] is not None else None,
            "estado": row[7],
        }
        camaras.append(camara)
    
    return JSONResponse(status_code=200, content=camaras)

@app.get("/api/camaras/{camera_id}/lotacao")
async def lotacao_camara_specific_get(camera_id: int):
    cursor.execute("""
        SELECT rl.camera_id, rl.data_registo, rl.lotacao
        FROM registo_lotacao rl
        WHERE rl.camera_id = %s
        ORDER BY rl.data_registo DESC
    """, (camera_id,))
    result = cursor.fetchall()
    if not result:
        return JSONResponse(status_code=404, content={"message": "Nenhuma lotação encontrada"})
    
    lotacao = []
    for row in result:
        lotacao.append({
            "camera_id": row[0],
            "data_registo": row[1].isoformat() if row[1] is not None else None,
            "lotacao": row[2],
        })
    return JSONResponse(status_code=200, content=lotacao)

@app.post("/api/camaras/{camera_id}/lotacao/{limit}")
async def lotacao_camara_specific_post(camera_id: int, limit: int, request: Request):
    data = await request.json()
    required_fields = ["lotacao", "timestamp"]
    for field in required_fields:
        if field not in data:
            return JSONResponse(status_code=400, content={"message": f"Campo {field} é obrigatório"})
    
    lotacao = data.get("lotacao", 0)
    timestamp = data.get("timestamp", int(time.time()))
    data_registo = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(timestamp))
    
    cursor.execute(
        "INSERT INTO registo_lotacao (camera_id, data_registo, lotacao) VALUES (%s, %s, %s)",
        (camera_id, data_registo, lotacao)
    )
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Dados recebidos com sucesso"})

@app.delete("/api/camaras/{camera_id}")
async def remover_camara(camera_id: int):
    cursor.execute("DELETE FROM camaras WHERE id = %s", (camera_id,))
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Câmara removida com sucesso"})

@app.put("/api/camaras/{camera_id}")
async def atualizar_camara(camera_id: int, data: dict):
    allowed_fields = ["paragem_id", "modelo", "fabricante", "latitude", "longitude", "data_instalacao", "estado"]

    set_clauses = []
    values = []
    for field in allowed_fields:
        if field in data:
            if field == "paragem_id":
                cursor.execute("SELECT id FROM paragens WHERE id = %s", (data["paragem_id"],))
                result = cursor.fetchone()
                if result is None:
                    return JSONResponse(status_code=404, content={"message": "ID da paragem não encontrado"})
            set_clauses.append(f"{field} = %s")
            values.append(data[field])
    if not set_clauses:
        return JSONResponse(status_code=400, content={"message": "Nenhum campo válido para atualizar"})
    values.append(camera_id)
    cursor.execute(
        f"""
        UPDATE camaras
        SET {', '.join(set_clauses)}
        WHERE id = %s
        """,
        tuple(values)
    )
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Câmara atualizada com sucesso"})

"""
/api/paragens (GET) - Recebe dados de todas as paragens
/api/paragens (POST) - Adiciona nova paragem
/api/paragens/favoritas (GET) - Obter paragens favoritas
/api/paragens/{paragem_id} (GET) - Obter dados de paragem com ID específico
/api/paragens/{paragem_id}/lotacao (GET) - Obter lotação de paragem com ID específico
/api/paragens/{paragem_id}/favoritas (PUT) - Adiciona ou remove paragem de favoritas
/api/paragens/{paragem_id}/lotacao (GET) - Obter lotação de paragem com ID específico
/api/paragens/{paragem_id} (DELETE) - Remove dados de paragem com ID específico
/api/paragens/{paragem_id} (PUT) - Atualiza dados de paragem com ID específico
"""

@app.get("/api/paragens")
async def listar_paragens():
    cursor.execute("SELECT * FROM paragens")
    result = cursor.fetchall()
    if not result:
        return JSONResponse(status_code=404, content={"message": "Nenhuma paragem encontrada"})
    paragens = []
    for row in result:
        paragens.append({
            "id": row[0],
            "nome": row[1],
            "localizacao": row[2],
            "estado": row[3],
            "lotacao": row[4],
            "favorita": row[5]
        })
    return JSONResponse(status_code=200, content=paragens)

@app.post("/api/paragens")
async def adicionar_paragem(data: dict = Body(...)):
    required_fields = ["nome", "localizacao"]
    for field in required_fields:
        if field not in data:
            return JSONResponse(status_code=400, content={"message": f"Campo {field} é obrigatório"})
    estado = data.get("estado", "Ativo")
    lotacao = data.get("lotacao", 0)
    favorita = data.get("favorita", "N")
    cursor.execute(
        "INSERT INTO paragens (nome, localizacao, estado, lotacao, favorita) VALUES (%s, %s, %s, %s, %s)",
        (data["nome"], data["localizacao"], estado, lotacao, favorita)
    )
    conn.commit()
    return JSONResponse(status_code=201, content={"message": "Paragem adicionada com sucesso"})

@app.get("/api/paragens/favoritas")
async def listar_paragens_favoritas():
    cursor.execute("SELECT * FROM paragens WHERE favorita = 'S'")
    result = cursor.fetchall()
    favoritas = []
    for row in result:
        favoritas.append({
            "id": row[0],
            "nome": row[1],
            "localizacao": row[2],
            "estado": row[3],
            "lotacao": row[4],
            "favorita": row[5]
        })
    return JSONResponse(status_code=200, content=favoritas)

@app.get("/api/paragens/{paragem_id}")
async def obter_paragem(paragem_id: int = Path(...)):
    cursor.execute("SELECT * FROM paragens WHERE id = %s", (paragem_id,))
    row = cursor.fetchone()
    if not row:
        return JSONResponse(status_code=404, content={"message": "Paragem não encontrada"})
    return JSONResponse(status_code=200, content={
        "id": row[0],
        "nome": row[1],
        "localizacao": row[2],
        "estado": row[3],
        "lotacao": row[4],
        "favorita": row[5]
    })

@app.get("/api/paragens/{paragem_id}/lotacao")
async def obter_lotacao_paragem(paragem_id: int = Path(...)):
    cursor.execute("SELECT lotacao FROM paragens WHERE id = %s", (paragem_id,))
    row = cursor.fetchone()
    if not row:
        return JSONResponse(status_code=404, content={"message": "Paragem não encontrada"})
    return JSONResponse(status_code=200, content={"paragem_id": paragem_id, "lotacao": row[0]})

@app.put("/api/paragens/{paragem_id}/favoritas")
async def atualizar_favorita(paragem_id: int = Path(...), data: dict = Body(...)):
    favorita = data.get("favorita", "N")
    cursor.execute("UPDATE paragens SET favorita = %s WHERE id = %s", (favorita, paragem_id))
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Favorita atualizada com sucesso"})

@app.delete("/api/paragens/{paragem_id}")
async def remover_paragem(paragem_id: int = Path(...)):
    cursor.execute("DELETE FROM paragens WHERE id = %s", (paragem_id,))
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Paragem removida com sucesso"})

@app.put("/api/paragens/{paragem_id}")
async def atualizar_paragem(paragem_id: int = Path(...), data: dict = Body(...)):
    allowed_fields = ["nome", "localizacao", "estado", "lotacao", "favorita"]
    set_clauses = []
    values = []
    for field in allowed_fields:
        if field in data:
            set_clauses.append(f"{field} = %s")
            values.append(data[field])
    if not set_clauses:
        return JSONResponse(status_code=400, content={"message": "Nenhum campo válido para atualizar"})
    values.append(paragem_id)
    cursor.execute(
        f"UPDATE paragens SET {', '.join(set_clauses)} WHERE id = %s",
        tuple(values)
    )
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Paragem atualizada com sucesso"})

"""
/api/alertas (GET) - Recebe dados de todos os alertas
/api/alertas (POST) - Adiciona novo alerta
/api/alertas/{alerta_id} (GET) - Recebe dados de alerta com ID específico
/api/alertas/{alerta_id} (DELETE) - Remove dados de alerta com ID específico
/api/alertas/{alerta_id} (PUT) - Atualiza dados de alerta com ID específico
/api/alertas/{alerta_id}/desativar (PUT) - Desativa alerta com ID específico
/api/alertas/{alerta_id}/ativar (PUT) - Ativa alerta com ID específico
/api/alertas/{alerta_id}/enviar (PUT) - Envia alerta com ID específico
/api/alertas/recentes (GET) - Recebe dados de alertas recentes
"""

@app.get("/api/alertas")
async def listar_alertas():
    cursor.execute("SELECT * FROM alertas")
    result = cursor.fetchall()
    alertas = []
    for row in result:
        alertas.append({
            "id": row[0],
            "paragem_id": row[1],
            "camera_id": row[2],
            "data_alerta": row[3].isoformat() if row[3] else None,
            "data_resolucao": row[4].isoformat() if row[4] else None,
            "tipo_alerta": row[5],
            "descricao": row[6],
            "gravidade": row[7],
            "estado": row[8]
        })
    return JSONResponse(status_code=200, content=alertas)

@app.post("/api/alertas")
async def adicionar_alerta(data: dict = Body(...)):
    required_fields = ["paragem_id", "camera_id", "tipo_alerta", "descricao", "gravidade", "estado"]
    for field in required_fields:
        if field not in data:
            return JSONResponse(status_code=400, content={"message": f"Campo {field} é obrigatório"})
    data_alerta = data.get("data_alerta", time.strftime("%Y-%m-%d %H:%M:%S"))
    cursor.execute(
        "INSERT INTO alertas (paragem_id, camera_id, data_alerta, tipo_alerta, descricao, gravidade, estado) VALUES (%s, %s, %s, %s, %s, %s, %s)",
        (data["paragem_id"], data["camera_id"], data_alerta, data["tipo_alerta"], data["descricao"], data["gravidade"], data["estado"])
    )
    conn.commit()
    return JSONResponse(status_code=201, content={"message": "Alerta adicionado com sucesso"})

@app.get("/api/alertas/{alerta_id}")
async def obter_alerta(alerta_id: int = Path(...)):
    cursor.execute("SELECT * FROM alertas WHERE id = %s", (alerta_id,))
    row = cursor.fetchone()
    if not row:
        return JSONResponse(status_code=404, content={"message": "Alerta não encontrado"})
    return JSONResponse(status_code=200, content={
        "id": row[0],
        "paragem_id": row[1],
        "camera_id": row[2],
        "data_alerta": row[3].isoformat() if row[3] else None,
        "data_resolucao": row[4].isoformat() if row[4] else None,
        "tipo_alerta": row[5],
        "descricao": row[6],
        "gravidade": row[7],
        "estado": row[8]
    })

@app.delete("/api/alertas/{alerta_id}")
async def remover_alerta(alerta_id: int = Path(...)):
    cursor.execute("DELETE FROM alertas WHERE id = %s", (alerta_id,))
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Alerta removido com sucesso"})

@app.put("/api/alertas/{alerta_id}")
async def atualizar_alerta(alerta_id: int = Path(...), data: dict = Body(...)):
    allowed_fields = ["paragem_id", "camera_id", "data_alerta", "data_resolucao", "tipo_alerta", "descricao", "gravidade", "estado"]
    set_clauses = []
    values = []
    for field in allowed_fields:
        if field in data:
            set_clauses.append(f"{field} = %s")
            values.append(data[field])
    if not set_clauses:
        return JSONResponse(status_code=400, content={"message": "Nenhum campo válido para atualizar"})
    values.append(alerta_id)
    cursor.execute(
        f"UPDATE alertas SET {', '.join(set_clauses)} WHERE id = %s",
        tuple(values)
    )
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Alerta atualizado com sucesso"})

@app.put("/api/alertas/{alerta_id}/desativar")
async def desativar_alerta(alerta_id: int = Path(...)):
    data_resolucao = time.strftime("%Y-%m-%d %H:%M:%S")
    cursor.execute(
        "UPDATE alertas SET estado = 'Finalizado', data_resolucao = %s WHERE id = %s",
        (data_resolucao, alerta_id)
    )
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Alerta desativado com sucesso"})

@app.put("/api/alertas/{alerta_id}/ativar")
async def ativar_alerta(alerta_id: int = Path(...)):
    cursor.execute(
        "UPDATE alertas SET estado = 'Ativo', data_resolucao = NULL WHERE id = %s",
        (alerta_id,)
    )
    conn.commit()
    return JSONResponse(status_code=200, content={"message": "Alerta ativado com sucesso"})

@app.put("/api/alertas/{alerta_id}/enviar")
async def enviar_alerta(alerta_id: int = Path(...)):
    # TODO: Implementar lógica para enviar alertas
    return JSONResponse(status_code=501, content={"message": "Enviar alerta não implementado"})

@app.get("/api/alerta/recentes")
async def alertas_recentes():
    cursor.execute("SELECT * FROM alertas_recentes")
    result = cursor.fetchall()
    alertas = []
    for row in result:
        alertas.append({
            "id": row[0],
            "paragem_id": row[1],
            "camera_id": row[2],
            "data_alerta": row[3].isoformat() if row[3] else None,
            "data_resolucao": row[4].isoformat() if row[4] else None,
            "tipo_alerta": row[5],
            "descricao": row[6],
            "gravidade": row[7],
            "estado": row[8],
            "paragem_nome": row[9],
            "camera_modelo": row[10]
        })
    return JSONResponse(status_code=200, content=alertas)

"""
/api/relatorios/fluxopassageiros (GET) - Obter dados de fluxo de passageiros
/api/relatorios/lotacaomedia (GET) - Obter dados de lotação média para cada paragem
/api/relatorios/picolotacao (GET) - Obter dados de pico de lotação para cada paragem
/api/relatorios/taxaalertas (GET) - Obter dados de taxa de alertas
"""

@app.get("/api/relatorios/fluxopassageiros")
async def relatorio_fluxo_passageiros():
    """
    Retorna o fluxo de passageiros (lotação ao longo do tempo) para cada paragem.
    """
    cursor.execute("""
        SELECT p.id as paragem_id, p.nome, rl.data_registo, rl.lotacao
        FROM paragens p
        JOIN camaras c ON c.paragem_id = p.id
        JOIN registo_lotacao rl ON rl.camera_id = c.id
        ORDER BY p.id, rl.data_registo
    """)
    result = cursor.fetchall()
    fluxo = {}
    for row in result:
        paragem_id, nome, data_registo, lotacao = row
        if paragem_id not in fluxo:
            fluxo[paragem_id] = {
                "paragem_id": paragem_id,
                "nome": nome,
                "registos": []
            }
        fluxo[paragem_id]["registos"].append({
            "data_registo": data_registo.isoformat() if data_registo else None,
            "lotacao": lotacao
        })
    return JSONResponse(status_code=200, content=list(fluxo.values()))

@app.get("/api/relatorios/lotacaomedia")
async def relatorio_lotacao_media():
    """
    Retorna a lotação média para cada paragem.
    """
    cursor.execute("""
        SELECT p.id as paragem_id, p.nome, AVG(rl.lotacao) as lotacao_media
        FROM paragens p
        JOIN camaras c ON c.paragem_id = p.id
        JOIN registo_lotacao rl ON rl.camera_id = c.id
        GROUP BY p.id, p.nome
    """)
    result = cursor.fetchall()
    medias = []
    for row in result:
        medias.append({
            "paragem_id": row[0],
            "nome": row[1],
            "lotacao_media": float(row[2]) if row[2] is not None else 0.0
        })
    return JSONResponse(status_code=200, content=medias)

@app.get("/api/relatorios/picolotacao")
async def relatorio_pico_lotacao():
    """
    Retorna o pico de lotação para cada paragem.
    """
    cursor.execute("""
        SELECT p.id as paragem_id, p.nome, MAX(rl.lotacao) as pico_lotacao
        FROM paragens p
        JOIN camaras c ON c.paragem_id = p.id
        JOIN registo_lotacao rl ON rl.camera_id = c.id
        GROUP BY p.id, p.nome
    """)
    result = cursor.fetchall()
    picos = []
    for row in result:
        picos.append({
            "paragem_id": row[0],
            "nome": row[1],
            "pico_lotacao": int(row[2]) if row[2] is not None else 0
        })
    return JSONResponse(status_code=200, content=picos)

@app.get("/api/relatorios/taxaalertas")
async def relatorio_taxa_alertas():
    """
    Retorna a taxa de alertas por paragem (número de alertas / número de registos de lotação).
    """
    cursor.execute("""
        SELECT p.id as paragem_id, p.nome,
            COUNT(DISTINCT a.id) as total_alertas,
            COUNT(DISTINCT rl.id) as total_registos,
            (COUNT(DISTINCT a.id) / NULLIF(COUNT(DISTINCT rl.id), 0)) as taxa_alertas
        FROM paragens p
        LEFT JOIN camaras c ON c.paragem_id = p.id
        LEFT JOIN alertas a ON a.paragem_id = p.id
        LEFT JOIN registo_lotacao rl ON rl.camera_id = c.id
        GROUP BY p.id, p.nome
    """)
    result = cursor.fetchall()
    taxas = []
    for row in result:
        taxas.append({
            "paragem_id": row[0],
            "nome": row[1],
            "total_alertas": int(row[2]),
            "total_registos": int(row[3]),
            "taxa_alertas": float(row[4]) if row[4] is not None else 0.0
        })
    return JSONResponse(status_code=200, content=taxas)
        
        
if __name__ == "__main__":
    uvicorn.run(app, host="127.0.0.1", port=8080)