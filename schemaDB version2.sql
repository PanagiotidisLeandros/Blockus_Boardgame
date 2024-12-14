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
	(3, 2, 'E', NULL),
	(3, 3, 'E', NULL),
	(3, 4, 'E', NULL),
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
	(3, 2, 'E', NULL),
	(3, 3, 'E', NULL),
	(3, 4, 'E', NULL),
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
  `last_change` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Pieces_Red` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Pieces_Red`)),
  `Pieces_Blue` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Pieces_Blue`)),
  `Legal_Moves` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.game_status: ~1 rows (approximately)
INSERT INTO `game_status` (`g_status`, `p_turn`, `result`, `last_change`, `Pieces_Red`, `Pieces_Blue`, `Legal_Moves`) VALUES
	('started', 'R', 'D', '2024-12-04 15:11:59', '{"1":1,"2":1,"3":1,"4":1,"5":1,"6":1,"7":1,"8":1,"9":1,"10":1,"11":1,"12":1,"13":1,"14":1,"15":1,"16":1,"17":1,"18":1,"19":1,"20":1,"21":1}', '{"1":1,"2":1,"3":1,"4":1,"5":1,"6":1,"7":1,"8":1,"9":1,"10":1,"11":1,"12":1,"13":1,"14":1,"15":1,"16":1,"17":1,"18":1,"19":1,"20":1,"21":1}', '[[11,1,2,1],[3,1,4,0],[10,1,4,0],[18,1,4,0],[19,1,4,0],[20,1,4,0],[11,2,1,0],[13,2,1,0],[5,2,2,0],[12,2,2,0],[12,2,2,1],[5,2,2,3],[14,2,3,0],[4,2,3,3],[8,2,4,0],[16,2,4,0],[2,2,4,3],[1,2,5,0],[2,2,5,0],[3,2,5,0],[4,2,5,0],[5,2,5,0],[6,2,5,0],[7,2,5,0],[9,2,5,0],[10,2,5,0],[13,2,5,0],[15,2,5,0],[19,2,5,0],[20,2,5,0],[21,2,5,0],[1,2,5,1],[2,2,5,1],[3,2,5,1],[4,2,5,1],[5,2,5,1],[6,2,5,1],[7,2,5,1],[8,2,5,1],[9,2,5,1],[10,2,5,1],[11,2,5,1],[12,2,5,1],[13,2,5,1],[14,2,5,1],[15,2,5,1],[16,2,5,1],[17,2,5,1],[18,2,5,1],[19,2,5,1],[20,2,5,1],[21,2,5,1],[1,2,5,2],[11,2,5,2],[14,2,5,2],[1,2,5,3],[18,3,1,0],[20,3,1,3],[4,3,2,0],[6,3,2,0],[9,3,2,0],[13,3,2,0],[16,3,2,0],[17,3,2,0],[19,3,2,0],[13,3,2,3],[7,3,3,0],[12,3,3,1],[14,3,3,1],[15,3,3,1],[15,3,3,2],[20,3,3,2],[6,3,3,3],[7,3,3,3],[16,3,3,3],[21,3,4,0],[17,3,4,1],[18,3,4,1],[21,3,4,1],[9,3,4,2],[10,3,4,2],[19,3,4,2],[3,3,4,3],[8,3,4,3],[9,3,4,3],[10,3,4,3],[19,3,4,3],[20,3,4,3],[2,3,5,2],[3,3,5,2],[8,3,5,2],[12,3,5,2],[18,3,5,2],[7,3,6,1],[9,3,6,1],[10,3,6,1],[19,3,6,1],[14,3,6,2],[14,4,1,0],[10,4,1,3],[2,4,2,0],[3,4,2,0],[8,4,2,0],[12,4,2,0],[18,4,2,0],[9,4,2,3],[19,4,2,3],[9,4,3,0],[10,4,3,0],[19,4,3,0],[3,4,3,1],[8,4,3,1],[9,4,3,1],[19,4,3,1],[21,4,3,2],[17,4,3,3],[18,4,3,3],[21,4,3,3],[15,4,4,0],[20,4,4,0],[6,4,4,1],[10,4,4,1],[16,4,4,1],[7,4,4,2],[14,4,4,3],[15,4,4,3],[13,4,5,1],[20,4,5,1],[4,4,5,2],[6,4,5,2],[9,4,5,2],[13,4,5,2],[16,4,5,2],[17,4,5,2],[19,4,5,2],[18,4,6,2],[1,5,2,0],[11,5,2,0],[14,5,2,0],[1,5,2,1],[1,5,2,2],[2,5,2,2],[3,5,2,2],[4,5,2,2],[5,5,2,2],[6,5,2,2],[7,5,2,2],[9,5,2,2],[10,5,2,2],[13,5,2,2],[15,5,2,2],[19,5,2,2],[20,5,2,2],[21,5,2,2],[1,5,2,3],[2,5,2,3],[3,5,2,3],[4,5,2,3],[5,5,2,3],[6,5,2,3],[7,5,2,3],[8,5,2,3],[10,5,2,3],[11,5,2,3],[12,5,2,3],[13,5,2,3],[14,5,2,3],[15,5,2,3],[16,5,2,3],[17,5,2,3],[18,5,2,3],[21,5,2,3],[2,5,3,1],[7,5,3,1],[8,5,3,2],[16,5,3,2],[4,5,4,1],[19,5,4,1],[14,5,4,2],[5,5,5,1],[5,5,5,2],[12,5,5,2],[12,5,5,3],[11,5,6,2],[13,5,6,2],[3,6,3,2],[10,6,3,2],[18,6,3,2],[19,6,3,2],[20,6,3,2],[7,6,4,1],[11,6,5,3]]');

-- Dumping structure for procedure adise24_blokus.get_piece_coordinates
DELIMITER //
CREATE PROCEDURE `get_piece_coordinates`()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE p_id INT;
    DECLARE line_1 VARCHAR(5);
    DECLARE line_2 VARCHAR(5);
    DECLARE line_3 VARCHAR(5);
    DECLARE line_4 VARCHAR(5);
    DECLARE line_5 VARCHAR(5);
    DECLARE cur CURSOR FOR 
        SELECT p_id, line_1, line_2, line_3, line_4, line_5 FROM piece;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    -- Loop through all pieces in the piece table
    read_loop: LOOP
        FETCH cur INTO p_id, line_1, line_2, line_3, line_4, line_5;
        
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Create the piece's coordinates based on the filled cells in the grid
        SET @coordinates = '';
        
        -- Check each line (line_1 to line_5)
        SET @x = 0;
        WHILE @x < 5 DO
            SET @y = 0;
            WHILE @y < 5 DO
                IF SUBSTRING(line_1, @y + 1, 1) = '1' THEN
                    SET @coordinates = CONCAT(@coordinates, '[', @x, ',', @y, '],');
                END IF;
                SET @y = @y + 1;
            END WHILE;
            SET @x = @x + 1;
        END WHILE;

        -- Return the result as a list of coordinates for each p_id
        SELECT CONCAT('m', p_id) AS piece_name, TRIM(TRAILING ',' FROM @coordinates) AS coordinates;
    END LOOP;

    CLOSE cur;
END//
DELIMITER ;

-- Dumping structure for procedure adise24_blokus.Is_Cell_Empty
DELIMITER //
CREATE PROCEDURE `Is_Cell_Empty`(
	IN `x1` TINYINT,
	IN `y1` TINYINT,
	OUT `Is_Cell_Empty` BOOLEAN
)
BEGIN

DECLARE Status_Of_Selected_Cell CHAR(1);

SELECT cell_status
INTO Status_Of_Selected_Cell
FROM board
WHERE x=x1 AND Y=y1;

if Status_Of_Selected_Cell = 'E' then
	SET Is_Cell_Empty = TRUE;
	else
	SET Is_Cell_Empty = FALSE;
END if;	 
		 
END//
DELIMITER ;

-- Dumping structure for procedure adise24_blokus.Is_Cross_Cell_Owned_By_Player
DELIMITER //
CREATE PROCEDURE `Is_Cross_Cell_Owned_By_Player`(
	IN `x1` TINYINT,
	IN `y1` TINYINT,
	OUT `is_owned_by_player` BOOLEAN
)
BEGIN

DECLARE current_player CHAR(1);
DECLARE right_status CHAR(1);
DECLARE down_status CHAR(1);
DECLARE left_status CHAR(1);
DECLARE up_status CHAR(1);

SELECT p_turn
INTO current_player
FROM game_status;

SELECT cell_status
INTO right_status
FROM board
WHERE x = x1+1 AND y = y1;

SELECT cell_status
INTO down_status
FROM board
WHERE x = x1 AND y = y1-1;

SELECT cell_status
INTO left_status
FROM board
WHERE x = x1-1 AND y = y1;

SELECT cell_status
INTO up_status
FROM board
WHERE x = x1 AND y = y1+1;

if right_status     = current_player  OR
   down_status      = current_player  OR
   left_status      = current_player  OR
   up_status        = current_player  then
	SET is_owned_by_player = TRUE;
	else
	SET is_owned_by_player = FALSE;
	END if;
END//
DELIMITER ;

-- Dumping structure for procedure adise24_blokus.Is_Diagonal_Cell_Owned_By_Player
DELIMITER //
CREATE PROCEDURE `Is_Diagonal_Cell_Owned_By_Player`(
	IN `x1` TINYINT,
	IN `y1` TINYINT,
	OUT `is_owned_by_player` BOOLEAN
)
BEGIN

DECLARE current_player CHAR(1);
DECLARE top_left_status CHAR(1);
DECLARE top_right_status CHAR(1);
DECLARE bottom_left_status CHAR(1);
DECLARE bottom_right_status CHAR(1);

SELECT p_turn
INTO current_player
FROM game_status;

SELECT cell_status
INTO top_left_status
FROM board
WHERE x = x1-1 AND y = y1+1;

SELECT cell_status
INTO top_right_status
FROM board
WHERE x = x1+1 AND y = y1+1;

SELECT cell_status
INTO bottom_left_status
FROM board
WHERE x = x1-1 AND y = y1-1;

SELECT cell_status
INTO bottom_right_status
FROM board
WHERE x = x1+1 AND y = y1-1;

if top_left_status     = current_player  OR
   top_right_status    = current_player  OR
   bottom_left_status  = current_player  OR
   bottom_right_status = current_player  then
	SET is_owned_by_player = TRUE;
	else
	SET is_owned_by_player = FALSE;
	END if;
END//
DELIMITER ;

-- Dumping structure for procedure adise24_blokus.Next_Player
DELIMITER //
CREATE PROCEDURE `Next_Player`()
BEGIN

DECLARE Current_Player CHAR(1);
DECLARE Next_Player CHAR(1);

SELECT p_turn
INTO Current_Player
FROM game_status;

if Current_Player='R' THEN 
	SET Next_Player='B';
ELSE
	SET Next_Player='R';
END if; 			 

UPDATE game_status
SET p_turn = Next_Player;
END//
DELIMITER ;

-- Dumping structure for table adise24_blokus.piece
CREATE TABLE IF NOT EXISTS `piece` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `line_1` varchar(50) DEFAULT NULL,
  `line_2` varchar(50) DEFAULT NULL,
  `line_3` varchar(50) DEFAULT NULL,
  `line_4` varchar(50) DEFAULT NULL,
  `line_5` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.piece: ~21 rows (approximately)
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

-- Dumping structure for table adise24_blokus.pieces
CREATE TABLE IF NOT EXISTS `pieces` (
  `piece_id` int(11) DEFAULT NULL,
  `rotation` int(11) DEFAULT NULL,
  `piece` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`piece`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.pieces: ~84 rows (approximately)
INSERT INTO `pieces` (`piece_id`, `rotation`, `piece`) VALUES
	(1, 0, '[[0,0]]'),
	(2, 0, '[[0,0],[1,0]]'),
	(3, 0, '[[0,0],[1,0],[1,1]]'),
	(4, 0, '[[0,0],[1,0],[2,0]]'),
	(5, 0, '[[0,0],[1,0],[2,0],[3,0]]'),
	(6, 0, '[[0,0],[1,0],[2,0],[2,1]]'),
	(7, 0, '[[0,0],[1,0],[1,-1],[2,-1]]'),
	(8, 0, '[[0,0],[1,0],[0,1],[1,1]]'),
	(9, 0, '[[0,0],[1,0],[1,-1],[2,0]]'),
	(10, 0, '[[0,0],[1,0],[1,-1],[1,1],[2,1]]'),
	(11, 0, '[[0,0],[0,1],[0,2],[0,3],[0,4]]'),
	(12, 0, '[[0,0],[1,0],[0,1],[0,2],[0,3]]'),
	(13, 0, '[[0,0],[1,0],[2,0],[2,1],[3,1]]'),
	(14, 0, '[[0,0],[0,1],[1,1],[0,2],[1,2]]'),
	(15, 0, '[[0,0],[1,0],[2,0],[1,-1],[1,-2]]'),
	(16, 0, '[[0,0],[0,1],[1,0],[2,0],[2,1]]'),
	(17, 0, '[[0,0],[1,0],[2,0],[2,1],[2,2]]'),
	(18, 0, '[[0,0],[1,0],[1,1],[2,1],[2,2]]'),
	(19, 0, '[[0,0],[1,0],[1,1],[2,0],[1,-1]]'),
	(20, 0, '[[0,0],[1,0],[1,1],[1,-1],[1,-2]]'),
	(21, 0, '[[0,0],[1,0],[1,-1],[1,-2],[2,-2]]'),
	(1, 1, '[[0,0]]'),
	(2, 1, '[[0,0],[0,-1]]'),
	(3, 1, '[[0,0],[0,-1],[1,-1]]'),
	(4, 1, '[[0,0],[0,-1],[0,-2]]'),
	(5, 1, '[[0,0],[0,-1],[0,-2],[0,-3]]'),
	(6, 1, '[[0,0],[0,-1],[0,-2],[1,-2]]'),
	(7, 1, '[[0,0],[0,-1],[-1,-1],[-1,-2]]'),
	(8, 1, '[[0,0],[0,-1],[1,0],[1,-1]]'),
	(9, 1, '[[0,0],[0,-1],[-1,-1],[1,-1]]'),
	(10, 1, '[[0,0],[0,-1],[-1,-1],[1,-1],[1,-2]]'),
	(11, 1, '[[0,0],[1,0],[2,0],[3,0],[4,0]]'),
	(12, 1, '[[0,0],[1,0],[2,0],[2,-1],[3,0]]'),
	(13, 1, '[[0,0],[0,-1],[0,-2],[1,-2],[1,-3]]'),
	(14, 1, '[[0,0],[1,0],[1,-1],[2,0],[2,-1]]'),
	(15, 1, '[[0,0],[0,-1],[0,-2],[1,-1],[2,-1]]'),
	(16, 1, '[[0,0],[1,0],[0,-1],[0,-2],[1,-2]]'),
	(17, 1, '[[0,0],[0,-1],[0,-2],[1,-2],[2,-2]]'),
	(18, 1, '[[0,0],[0,-1],[1,-1],[1,-2],[2,-2]]'),
	(19, 1, '[[0,0],[0,-1],[1,-1],[0,-2],[-1,-1]]'),
	(20, 1, '[[0,0],[0,-1],[1,-1],[1,-2],[1,-3]]'),
	(21, 1, '[[0,0],[0,-1],[1,-1],[2,-1],[2,-2]]'),
	(1, 2, '[[0,0]]'),
	(2, 2, '[[0,0],[-1,0]]'),
	(3, 2, '[[0,0],[-1,0],[-1,-1]]'),
	(4, 2, '[[0,0],[-1,0],[-2,0]]'),
	(5, 2, '[[0,0],[-1,0],[-2,0],[-3,0]]'),
	(6, 2, '[[0,0],[-1,0],[-2,0],[-2,-1]]'),
	(7, 2, '[[0,0],[-1,0],[-1,1],[-2,1]]'),
	(8, 2, '[[0,0],[-1,0],[0,-1],[-1,-1]]'),
	(9, 2, '[[0,0],[-1,0],[-1,1],[-2,0]]'),
	(10, 2, '[[0,0],[-1,0],[-1,1],[-1,-1],[-2,-1]]'),
	(11, 2, '[[0,0],[0,-1],[0,-2],[0,-3],[0,-4]]'),
	(12, 2, '[[0,0],[-1,0],[0,-1],[0,-2],[0,-3]]'),
	(13, 2, '[[0,0],[-1,0],[-2,0],[-2,-1],[-3,-1]]'),
	(14, 2, '[[0,0],[0,-1],[-1,-1],[0,-2],[-1,-2]]'),
	(15, 2, '[[0,0],[-1,0],[-2,0],[-1,1],[-1,2]]'),
	(16, 2, '[[0,0],[0,-1],[-1,0],[-2,0],[-2,-1]]'),
	(17, 2, '[[0,0],[-1,0],[-2,0],[-2,-1],[-2,-2]]'),
	(18, 2, '[[0,0],[-1,0],[-1,-1],[-2,-1],[-2,-2]]'),
	(19, 2, '[[0,0],[-1,0],[-1,-1],[-2,0],[-1,1]]'),
	(20, 2, '[[0,0],[-1,0],[-1,-1],[-1,1],[-1,2]]'),
	(21, 2, '[[0,0],[-1,0],[-1,1],[-1,2],[-2,2]]'),
	(1, 3, '[[0,0]]'),
	(2, 3, '[[0,0],[0,1]]'),
	(3, 3, '[[0,0],[0,1],[-1,1]]'),
	(4, 3, '[[0,0],[0,1],[0,2]]'),
	(5, 3, '[[0,0],[0,1],[0,2],[0,3]]'),
	(6, 3, '[[0,0],[0,1],[0,2],[-1,2]]'),
	(7, 3, '[[0,0],[0,1],[-1,1],[-1,2]]'),
	(8, 3, '[[0,0],[0,1],[-1,0],[-1,1]]'),
	(9, 3, '[[0,0],[0,1],[-1,1],[1,0]]'),
	(10, 3, '[[0,0],[0,1],[-1,1],[1,1],[1,2]]'),
	(11, 3, '[[0,0],[-1,0],[-2,0],[-3,0],[-4,0]]'),
	(12, 3, '[[0,0],[0,1],[-1,0],[-2,0],[-3,0]]'),
	(13, 3, '[[0,0],[0,1],[0,2],[-1,2],[-1,3]]'),
	(14, 3, '[[0,0],[-1,0],[-1,1],[-2,0],[-2,1]]'),
	(15, 3, '[[0,0],[0,1],[0,2],[-1,1],[-2,1]]'),
	(16, 3, '[[0,0],[-1,0],[0,1],[0,2],[-1,2]]'),
	(17, 3, '[[0,0],[0,1],[0,2],[-1,2],[-2,2]]'),
	(18, 3, '[[0,0],[0,1],[-1,1],[-1,2],[-2,2]]'),
	(19, 3, '[[0,0],[0,1],[-1,1],[1,0],[-1,0]]'),
	(20, 3, '[[0,0],[0,1],[-1,1],[1,1],[2,1]]'),
	(21, 3, '[[0,0],[0,1],[-1,1],[-2,1],[-2,2]]');

-- Dumping structure for procedure adise24_blokus.place_blokus_piece
DELIMITER //
CREATE PROCEDURE `place_blokus_piece`(IN piece JSON, IN base_x INT, IN base_y INT)
BEGIN
    -- Declare variables
    DECLARE x_coord INT;
    DECLARE y_coord INT;
    DECLARE coord_count INT DEFAULT 0;
    DECLARE i INT DEFAULT 0;
    DECLARE current_player CHAR(1);

    -- Get the current player's symbol
    SELECT p_turn
    INTO current_player
    FROM game_status;

    -- Get the number of coordinates in the JSON array
    SET coord_count = JSON_LENGTH(piece);

    -- Loop through each coordinate in the JSON array
    WHILE i < coord_count DO
        -- Extract x and y coordinates from the JSON array
        SET x_coord = JSON_UNQUOTE(JSON_EXTRACT(piece, CONCAT('$[', i, '][0]')));
        SET y_coord = JSON_UNQUOTE(JSON_EXTRACT(piece, CONCAT('$[', i, '][1]')));

        -- Adjust the coordinates based on the base position
        SET x_coord = x_coord + base_x;
        SET y_coord = y_coord + base_y;

        -- Update the board
        UPDATE board
        SET cell_status = current_player
        WHERE x = x_coord AND y = y_coord;

        -- Increment the loop counter
        SET i = i + 1;
    END WHILE;
END//
DELIMITER ;

-- Dumping structure for table adise24_blokus.players
CREATE TABLE IF NOT EXISTS `players` (
  `username` varchar(50) DEFAULT NULL,
  `piece_color` enum('R','B') NOT NULL,
  `token` varchar(100) DEFAULT NULL,
  `last_action` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`piece_color`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.players: ~2 rows (approximately)
INSERT INTO `players` (`username`, `piece_color`, `token`, `last_action`) VALUES
	('antonis', 'R', '11b52615df33fc9e52a047bb06659da5', NULL),
	('nikos', 'B', '687a78ec35d4afca72e65fc700d6ae2b', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
