
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
  `modelo` varchar(30) DEFAULT NULL,
  `fabricante` varchar(30) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
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
-- Mostra alertas recentes (7 dias) ou ativos atualmente com detalhes como alerta e gravidade
CREATE OR REPLACE VIEW `alertas_recentes` AS
SELECT a.id, a.paragem_id, a.camera_id, a.data_alerta, a.data_resolucao,
       a.tipo_alerta, a.descricao, a.gravidade, a.estado,
       p.nome AS paragem_nome, c.modelo AS camera_modelo
FROM alertas a
JOIN paragens p ON a.paragem_id = p.id
JOIN camaras c ON a.camera_id = c.id
WHERE (a.data_alerta >= NOW() - INTERVAL 7 DAY OR a.estado = 'Ativo' OR a.estado = 'Pendente')
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

-- Creates a trigger to update the lotacao in paragens table from a last record in registo_lotacao
DELIMITER //

CREATE TRIGGER `atualiza_lotacao` AFTER INSERT ON `registo_lotacao`
FOR EACH ROW
BEGIN
  DECLARE v_lotacao INT;
  SELECT lotacao INTO v_lotacao FROM registo_lotacao WHERE id = NEW.id;
  UPDATE paragens SET lotacao = v_lotacao WHERE id = NEW.paragem_id;
END;
//

DELIMITER ;

--
-- Dados aleatórios para teste exemplo
--

-- Adicionar paragens de autocarro de teste em Braga da TUB
INSERT INTO `paragens` (`nome`, `localizacao`, `estado`, `lotacao`, `favorita`) VALUES
('Avenida I', 'Avenida da Liberdade, Braga', 'Ativo', 0, 'N'),
('Avenida II', 'Avenida da Liberdade, Braga', 'Ativo', 0, 'N'),
('Congregados', 'Praça dos Congregados, Braga', 'Ativo', 0, 'N'),
('Estação', 'Estação de Comboios, Braga', 'Ativo', 0, 'N'),
('Universidade', 'Universidade do Minho, Braga', 'Ativo', 0, 'N'),
('Hospital', 'Hospital de Braga, Braga', 'Ativo', 0, 'N'),
('Centro Comercial', 'Centro Comercial Braga Parque, Braga', 'Ativo', 0, 'N'),
('Parque da Cidade', 'Parque da Cidade, Braga', 'Ativo', 0, 'N');

-- Adicionar câmaras de teste
INSERT INTO `camaras` (`paragem_id`, `modelo`, `fabricante`, `latitude`, `longitude`, `data_instalacao`, `estado`) VALUES
(1, 'Canon EOS', 'Canon', 41.545678, -8.426123, '2025-04-20', 'Ativo'),
(2, 'Nikon D3500', 'Nikon', 41.546789, -8.427234, '2025-04-20', 'Ativo'),
(3, 'Sony Alpha', 'Sony', 41.547890, -8.428345, '2025-04-20', 'Ativo'),
(4, 'Fujifilm X-T4', 'Fujifilm', 41.548901, -8.429456, '2025-04-20', 'Ativo'),
(5, 'Panasonic Lumix', 'Panasonic', 41.549012, -8.430567, '2025-04-20', 'Ativo'),
(6, 'Olympus OM-D', 'Olympus', 41.550123, -8.431678, '2025-04-20', 'Ativo'),
(7, 'GoPro Hero9', 'GoPro', 41.551234, -8.432789, '2025-04-20', 'Ativo'),
(8, 'DJI Osmo Action', 'DJI', 41.552345, -8.433890, '2025-04-20', 'Ativo');

-- Adicionar alertas de teste
INSERT INTO `alertas` (`paragem_id`, `camera_id`, `data_alerta`, `data_resolucao`, `tipo_alerta`, `descricao`, `gravidade`, `estado`) VALUES
(1, 1, '2025-04-20 10:00:00', NULL, 'Serviço', 'Câmara fora de serviço', 2, 'Pendente'),
(2, 2, '2025-04-20 11:00:00', NULL, 'Serviço', 'Câmara fora de serviço', 2, 'Pendente');

-- Adicionar registo de lotação de teste
INSERT INTO `registo_lotacao` (`paragem_id`, `camera_id`, `data_registo`, `lotacao`) VALUES
(1, 1, '2025-04-20 08:00:00', 3),
(1, 1, '2025-04-20 09:00:00', 4),
(1, 1, '2025-04-20 10:00:00', 5),
(1, 1, '2025-04-20 11:00:00', 6),
(2, 2, '2025-04-20 08:30:00', 8),
(2, 2, '2025-04-20 09:30:00', 9),
(2, 2, '2025-04-20 10:30:00', 10),
(2, 2, '2025-04-20 11:30:00', 12),
(3, 3, '2025-04-20 12:00:00', 15),
(3, 3, '2025-04-20 13:00:00', 16),
(3, 3, '2025-04-20 14:00:00', 17),
(3, 3, '2025-04-20 15:00:00', 18),
(4, 4, '2025-04-20 13:00:00', 20),
(4, 4, '2025-04-20 14:00:00', 22),
(4, 4, '2025-04-20 15:00:00', 23),
(4, 4, '2025-04-20 16:00:00', 24),
(5, 5, '2025-04-20 14:00:00', 25),
(5, 5, '2025-04-20 15:00:00', 26),
(5, 5, '2025-04-20 16:00:00', 27),
(5, 5, '2025-04-20 17:00:00', 28),
(6, 6, '2025-04-20 15:00:00', 30),
(6, 6, '2025-04-20 16:00:00', 31),
(6, 6, '2025-04-20 17:00:00', 32),
(6, 6, '2025-04-20 18:00:00', 33),
(7, 7, '2025-04-20 16:00:00', 35),
(7, 7, '2025-04-20 17:00:00', 36),
(7, 7, '2025-04-20 18:00:00', 37),
(7, 7, '2025-04-20 19:00:00', 38),
(8, 8, '2025-04-20 17:00:00', 40),
(8, 8, '2025-04-20 18:00:00', 41),
(8, 8, '2025-04-20 19:00:00', 42),
(8, 8, '2025-04-20 20:00:00', 43);