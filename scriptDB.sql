
-- Tabela de paragens
CREATE TABLE IF NOT EXISTS `paragens` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `localizacao` text NOT NULL,
  `estado` varchar(10) DEFAULT NULL,
  `lotacao` int(11) NOT NULL DEFAULT 0,
  `favorita` char(1) NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Tabela de camaras
CREATE TABLE IF NOT EXISTS `camaras` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `paragem_id` int(11) NOT NULL,
  `modelo` varchar(10) DEFAULT NULL,
  `fabricante` varchar(10) DEFAULT NULL,
  `data_instalacao` date DEFAULT NULL,
  `estado` varchar(10) DEFAULT NULL,
  FOREIGN KEY (`paragem_id`) REFERENCES `paragens`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Tabela de alertas
CREATE TABLE IF NOT EXISTS `alertas` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `paragem_id` int(11) NULL,
  `camera_id` int(11) NULL,
  `data_alerta` datetime DEFAULT NULL,
  `data_resolucao` datetime DEFAULT NULL,
  `tipo_alerta` varchar(10) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `gravidade` int(11) DEFAULT NULL,
  `estado` varchar(10) DEFAULT NULL,
  FOREIGN KEY (`paragem_id`) REFERENCES `paragens`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`camera_id`) REFERENCES `camaras`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Tabela de registo de lotação
CREATE TABLE IF NOT EXISTS `registo_lotacao` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `paragem_id` int(11) NOT NULL,
  `camera_id` int(11) NOT NULL,
  `data_registo` datetime DEFAULT NULL,
  `lotacao` int(11) DEFAULT NULL,
  FOREIGN KEY (`paragem_id`) REFERENCES `paragens`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`camera_id`) REFERENCES `camaras`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Create view for recent alerts
CREATE OR REPLACE VIEW `alertas_recentes` AS
SELECT a.id, a.paragem_id, a.camera_id, a.data_alerta, a.data_resolucao, a.tipo_alerta, a.descricao, a.gravidade, a.estado,
       p.nome AS paragem_nome, c.modelo AS camera_modelo
FROM alertas a
JOIN paragens p ON a.paragem_id = p.id
JOIN camaras c ON a.camera_id = c.id
WHERE a.data_alerta >= DATE_SUB(NOW(), INTERVAL 7 DAY)
ORDER BY a.data_alerta DESC;

-- Create view for average lotacao
CREATE OR REPLACE VIEW `lotacao_media` AS
SELECT r.paragem_id, AVG(r.lotacao) AS media_lotacao
FROM registo_lotacao r
JOIN paragens p ON r.paragem_id = p.id
GROUP BY r.paragem_id
HAVING media_lotacao > 0
ORDER BY media_lotacao DESC;

-- Create view for peak lotacao
CREATE OR REPLACE VIEW `lotacao_pico` AS
SELECT r.paragem_id, MAX(r.lotacao) AS pico_lotacao
FROM registo_lotacao r
JOIN paragens p ON r.paragem_id = p.id
GROUP BY r.paragem_id
HAVING pico_lotacao > 0
ORDER BY pico_lotacao DESC;

-- Create view for alertas rate
CREATE OR REPLACE VIEW `taxa_alertas` AS
SELECT p.id AS paragem_id, COUNT(a.id) AS total_alertas,
       SUM(CASE WHEN a.gravidade = 1 THEN 1 ELSE 0 END) AS alertas_baixa,
       SUM(CASE WHEN a.gravidade = 2 THEN 1 ELSE 0 END) AS alertas_media,
       SUM(CASE WHEN a.gravidade = 3 THEN 1 ELSE 0 END) AS alertas_alta
FROM paragens p
LEFT JOIN alertas a ON p.id = a.paragem_id
GROUP BY p.id
HAVING total_alertas > 0
ORDER BY total_alertas DESC;
