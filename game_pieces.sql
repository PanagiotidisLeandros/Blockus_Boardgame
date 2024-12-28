-- --------------------------------------------------------
-- Διακομιστής:                  127.0.0.1
-- Έκδοση διακομιστή:            10.4.32-MariaDB - mariadb.org binary distribution
-- Λειτ. σύστημα διακομιστή:     Win64
-- HeidiSQL Έκδοση:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pieces
CREATE DATABASE IF NOT EXISTS `pieces` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `pieces`;

-- Dumping structure for πίνακας pieces.anchors
CREATE TABLE IF NOT EXISTS `anchors` (
  `anchor_id` int(11) NOT NULL AUTO_INCREMENT,
  `anchor_y` int(11) DEFAULT NULL,
  `anchor_x` int(11) DEFAULT NULL,
  PRIMARY KEY (`anchor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pieces.anchors: ~22 rows (approximately)
DELETE FROM `anchors`;
INSERT INTO `anchors` (`anchor_id`, `anchor_y`, `anchor_x`) VALUES
	(1, 1, 1),
	(2, 1, 2),
	(3, 1, 3),
	(4, 1, 4),
	(5, 1, 5),
	(6, 2, 1),
	(7, 2, 2),
	(8, 2, 3),
	(9, 2, 4),
	(10, 2, 5),
	(11, 3, 1),
	(12, 3, 2),
	(13, 3, 3),
	(14, 3, 4),
	(15, 3, 5),
	(16, 4, 1),
	(17, 4, 2),
	(18, 4, 3),
	(19, 4, 4),
	(20, 4, 5),
	(21, 5, 1),
	(22, 5, 2),
	(23, 5, 3),
	(24, 5, 4),
	(25, 5, 5);

-- Dumping structure for πίνακας pieces.piece
CREATE TABLE IF NOT EXISTS `piece` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `line_1` varchar(50) DEFAULT NULL,
  `line_2` varchar(50) DEFAULT NULL,
  `line_3` varchar(50) DEFAULT NULL,
  `line_4` varchar(50) DEFAULT NULL,
  `line_5` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pieces.piece: ~21 rows (approximately)
DELETE FROM `piece`;
INSERT INTO `piece` (`p_id`, `line_1`, `line_2`, `line_3`, `line_4`, `line_5`) VALUES
	(1, '10000', '00000', '00000', '00000', '00000'),
	(2, '11000', '00000', '00000', '00000', '00000'),
	(3, '11100', '00000', '00000', '00000', '00000'),
	(4, '11110', '00000', '00000', '00000', '00000'),
	(5, '11111', '00000', '00000', '00000', '00000'),
	(6, '11000', '10000', '00000', '00000', '00000'),
	(7, '11100', '1000', '00000', '00000', '00000'),
	(8, '10000', '11000', '1000', '00000', '00000'),
	(9, '11100', '100', '00000', '00000', '00000'),
	(10, '11000', '11000', '00000', '00000', '00000'),
	(11, '10000', '11000', '10000', '10000', '00000'),
	(12, '10000', '11100', '100', '00000', '00000'),
	(13, '1000', '11100', '1000', '00000', '00000'),
	(14, '100', '11100', '100', '00000', '00000'),
	(15, '1100', '11000', '10000', '00000', '00000'),
	(16, '1100', '11000', '1000', '00000', '00000'),
	(17, '11110', '10', '00000', '00000', '00000'),
	(18, '11000', '11100', '00000', '00000', '00000'),
	(19, '11100', '100', '100', '00000', '00000'),
	(20, '11100', '10100', '00000', '00000', '00000'),
	(21, '11000', '1110', '00000', '00000', '00000');

-- Dumping structure for πίνακας pieces.relation
CREATE TABLE IF NOT EXISTS `relation` (
  `p_id` int(11) DEFAULT NULL,
  `anchor_id` int(11) DEFAULT NULL,
  KEY `FK_relation_piece` (`p_id`),
  KEY `FK_relation_anchors` (`anchor_id`),
  CONSTRAINT `FK_relation_anchors` FOREIGN KEY (`anchor_id`) REFERENCES `anchors` (`anchor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_relation_piece` FOREIGN KEY (`p_id`) REFERENCES `piece` (`p_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table pieces.relation: ~69 rows (approximately)
DELETE FROM `relation`;
INSERT INTO `relation` (`p_id`, `anchor_id`) VALUES
	(1, 1),
	(2, 2),
	(2, 1),
	(3, 1),
	(3, 3),
	(4, 1),
	(4, 4),
	(5, 1),
	(5, 5),
	(6, 1),
	(6, 2),
	(6, 6),
	(7, 1),
	(7, 3),
	(7, 7),
	(8, 1),
	(8, 6),
	(8, 7),
	(8, 11),
	(9, 1),
	(9, 3),
	(9, 8),
	(10, 1),
	(10, 2),
	(10, 6),
	(10, 7),
	(11, 1),
	(11, 6),
	(11, 7),
	(11, 11),
	(11, 16),
	(12, 1),
	(12, 6),
	(12, 8),
	(12, 13),
	(13, 2),
	(13, 6),
	(13, 8),
	(13, 12),
	(14, 3),
	(14, 6),
	(14, 13),
	(15, 2),
	(15, 3),
	(15, 6),
	(15, 7),
	(15, 11),
	(16, 2),
	(16, 3),
	(16, 6),
	(16, 12),
	(17, 1),
	(17, 4),
	(17, 9),
	(18, 1),
	(18, 2),
	(18, 6),
	(18, 8),
	(19, 1),
	(19, 3),
	(19, 13),
	(20, 1),
	(20, 3),
	(20, 6),
	(20, 8),
	(21, 1),
	(21, 2),
	(21, 7),
	(21, 9);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
