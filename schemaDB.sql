-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for adise24_blokus
CREATE DATABASE IF NOT EXISTS `adise24_blokus` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `adise24_blokus`;

-- Dumping structure for table adise24_blokus.board
CREATE TABLE IF NOT EXISTS `board` (
  `x` tinyint(5) NOT NULL,
  `y` tinyint(5) NOT NULL,
  `cell_status` enum('E','R','B') NOT NULL DEFAULT 'E',
  `legal_move` enum('Y','N') DEFAULT NULL,
  PRIMARY KEY (`x`,`y`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.board: ~36 rows (approximately)
INSERT INTO `board` (`x`, `y`, `cell_status`, `legal_move`) VALUES
	(1, 1, 'B', NULL),
	(1, 2, 'E', NULL),
	(1, 3, 'E', NULL),
	(1, 4, 'E', NULL),
	(1, 5, 'E', NULL),
	(1, 6, 'R', NULL),
	(2, 1, 'E', NULL),
	(2, 2, 'E', NULL),
	(2, 3, 'E', NULL),
	(2, 4, 'E', NULL),
	(2, 5, 'E', NULL),
	(2, 6, 'E', NULL),
	(3, 1, 'E', NULL),
	(3, 2, 'R', NULL),
	(3, 3, 'R', NULL),
	(3, 4, 'B', NULL),
	(3, 5, 'E', NULL),
	(3, 6, 'E', NULL),
	(4, 1, 'E', NULL),
	(4, 2, 'E', NULL),
	(4, 3, 'E', NULL),
	(4, 4, 'E', NULL),
	(4, 5, 'E', NULL),
	(4, 6, 'E', NULL),
	(5, 1, 'E', NULL),
	(5, 2, 'E', NULL),
	(5, 3, 'E', NULL),
	(5, 4, 'E', NULL),
	(5, 5, 'E', NULL),
	(5, 6, 'E', NULL),
	(6, 1, 'R', NULL),
	(6, 2, 'E', NULL),
	(6, 3, 'E', NULL),
	(6, 4, 'E', NULL),
	(6, 5, 'E', NULL),
	(6, 6, 'B', NULL);

-- Dumping structure for procedure adise24_blokus.clean_board
DELIMITER //
CREATE PROCEDURE `clean_board`()
BEGIN
	REPLACE INTO board SELECT * FROM clean_board;
END//
DELIMITER ;

-- Dumping structure for table adise24_blokus.clean_board
CREATE TABLE IF NOT EXISTS `clean_board` (
  `x` tinyint(5) NOT NULL,
  `y` tinyint(5) NOT NULL,
  `cell_status` enum('E','R','B') NOT NULL DEFAULT 'E',
  `legal_move` enum('Y','N') DEFAULT NULL,
  PRIMARY KEY (`x`,`y`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.clean_board: ~36 rows (approximately)
INSERT INTO `clean_board` (`x`, `y`, `cell_status`, `legal_move`) VALUES
	(1, 1, 'B', NULL),
	(1, 2, 'E', NULL),
	(1, 3, 'E', NULL),
	(1, 4, 'E', NULL),
	(1, 5, 'E', NULL),
	(1, 6, 'R', NULL),
	(2, 1, 'E', NULL),
	(2, 2, 'E', NULL),
	(2, 3, 'E', NULL),
	(2, 4, 'E', NULL),
	(2, 5, 'E', NULL),
	(2, 6, 'E', NULL),
	(3, 1, 'E', NULL),
	(3, 2, 'R', NULL),
	(3, 3, 'R', NULL),
	(3, 4, 'B', NULL),
	(3, 5, 'E', NULL),
	(3, 6, 'E', NULL),
	(4, 1, 'E', NULL),
	(4, 2, 'E', NULL),
	(4, 3, 'E', NULL),
	(4, 4, 'E', NULL),
	(4, 5, 'E', NULL),
	(4, 6, 'E', NULL),
	(5, 1, 'E', NULL),
	(5, 2, 'E', NULL),
	(5, 3, 'E', NULL),
	(5, 4, 'E', NULL),
	(5, 5, 'E', NULL),
	(5, 6, 'E', NULL),
	(6, 1, 'R', NULL),
	(6, 2, 'E', NULL),
	(6, 3, 'E', NULL),
	(6, 4, 'E', NULL),
	(6, 5, 'E', NULL),
	(6, 6, 'B', NULL);

-- Dumping structure for table adise24_blokus.game_status
CREATE TABLE IF NOT EXISTS `game_status` (
  `g_status` enum('not active','initialized','started','ended','aborded') NOT NULL DEFAULT 'not active',
  `p_turn` enum('R','B') DEFAULT NULL,
  `result` enum('R','B','D') DEFAULT NULL,
  `last_change` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.game_status: ~1 rows (approximately)
INSERT INTO `game_status` (`g_status`, `p_turn`, `result`, `last_change`) VALUES
	('not active', 'B', NULL, '2024-11-18 14:58:54');

-- Dumping structure for table adise24_blokus.players
CREATE TABLE IF NOT EXISTS `players` (
  `username` varchar(50) DEFAULT NULL,
  `color` enum('R','B') NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `last_action` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`color`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.players: ~2 rows (approximately)
INSERT INTO `players` (`username`, `color`, `token`, `last_action`) VALUES
	(NULL, 'R', NULL, NULL),
	(NULL, 'B', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
