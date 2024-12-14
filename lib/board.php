<?php
require_once "game.php";
require_once "dbconnect.php";
//require_once('moves.php');
//move_piece(7,1,3,0);

function show_board() {
	global $mysqli;
	
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);
	
	$st->execute();
	$res = $st->get_result();
	
	// header ('Content-type: application/json');
	// print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}

function reset_board() {
	global $mysqli;
	$sql = 'call clean_board()';
	$mysqli->query($sql);
    show_board();

    $P_B = array_fill(1, 21, 1);  // Pieces for Blue
    $P_R = array_fill(1, 21, 1);  // Pieces for Red

    $P_B_json = json_encode($P_B);
    $P_R_json = json_encode($P_R);

    $sql = "UPDATE game_status SET Pieces_Blue = ?, Pieces_Red = ? WHERE g_status = 'started'";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $P_B_json, $P_R_json);
    $stmt->execute();
    $sql = "UPDATE game_status SET flag_Red_no_moves = '0'";
    $mysqli->query($sql);
    $sql = "UPDATE game_status SET flag_Blue_no_moves = '0'";
    $mysqli->query($sql);
    $sql = "UPDATE game_status SET bonus_points_red = '0'";
    $mysqli->query($sql);
    $sql = "UPDATE game_status SET bonus_points_blue = '0'";
    $mysqli->query($sql);
    $sql = "UPDATE game_status SET g_status = 'not active'";
    $mysqli->query($sql); 
    $sql = "UPDATE game_status SET p_turn = 'R'";
    $mysqli->query($sql);     
    $sql = "UPDATE game_status SET score_red = null";
    $mysqli->query($sql);
    $sql = "UPDATE game_status SET score_blue = null";
    $mysqli->query($sql);
    $sql = "UPDATE players SET username = null WHERE piece_color = 'R' ";
    $mysqli->query($sql);    
    $sql = "UPDATE players SET username = null WHERE piece_color = 'B' ";
    $mysqli->query($sql);    
    

    $stmt->execute();
}

function read_board() {
	global $mysqli;
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	return($res->fetch_all(MYSQLI_ASSOC));
}

function move_piece($x, $y, $x2, $y2, $token) {
    //echo 'In move peace function';
    global $mysqli;
    $sql = "SELECT Legal_Moves FROM game_status where g_status = 'started' ";
    $result = $mysqli->query($sql);

    if ($row = $result->fetch_assoc()) {

        // Decode JSON to arrays
        $moves = json_decode($row['Legal_Moves'], true);

        // Check for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "JSON Decode Error: " . json_last_error_msg() . "\n";
            exit;
        }

    } else {
        echo "No data found.\n";
    }

    // Iterate through each move in the decoded array
    foreach ($moves as $index => $move) {
        // Check if the current move matches the input coordinates (x, y, x2, y2)
        if (isset($move[0], $move[1], $move[2], $move[3])) {
            $move_x1 = $move[0];  // Piece value
            $move_y1 = $move[1];  // x-coordinate
            $move_x2 = $move[2];  // y-coordinate
            $move_y2 = $move[3];  // Rotation value

            // Compare the input values with the move's values
            if ($move_x1 == $x && $move_y1 == $y && $move_x2 == $x2 && $move_y2 == $y2) {
                // If they match, call the function to process the move


                $sql = "SELECT p_turn FROM game_status WHERE g_status = 'started'";
                $result = $mysqli->query($sql);    
                if ($result) {
                    // Check if there are any rows
                    if ($row = $result->fetch_assoc()) {
                        // Fetch the value of p_turn
                        $p_turn = $row['p_turn'];
                        //print_r($p_turn); // Display the value
                    } else {
                        echo "No rows found in the result.";
                    }
                } else {
                    // Query failed
                    echo "Query failed: " . $mysqli->error;
                }

                if ($p_turn == 'R') {
                    $sql = "SELECT Pieces_Red from game_status WHERE g_status = 'started'";
                    $Pieces_Red = $mysqli->query($sql);
                    if ($Pieces_Red && $row = $Pieces_Red->fetch_assoc()) {
                        //Decode the JSON string into an associative array
                        $Pieces_Red = json_decode($row['Pieces_Red'], true);
                        
                    if (isset($Pieces_Red[$x])) {
                        $Pieces_Red[$x] = 0;
                    }
                    // update Pieces_Red so that red cannot place the same piece again
                    $Pieces_Red = json_encode($Pieces_Red);
                    $sql = "UPDATE game_status SET Pieces_Red = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $Pieces_Red);
                    $stmt->execute();

                    //set bonus_points_red = 1 if last move was made with mononimo
                    if($x == 1){
                        $sql = "SELECT Pieces_Red from game_status WHERE g_status = 'started'";
                        $Pieces_Red = $mysqli->query($sql);
                        $counter_red = 0;              //counter to track if all pieces have been used to give bonus points
                        if ($Pieces_Red && $row = $Pieces_Red->fetch_assoc()){ 
                            //Decode the JSON string into an associative array
                            $Pieces_Red = json_decode($row['Pieces_Red'], true);
                            foreach ($Pieces_Red as $pls){
                                //$counter++;
                                if ($pls==1){
                                    $counter_red++;
                                }
                            }
                            if ($counter_red == 0){
                                $sql = "UPDATE game_status SET bonus_points_red = '1' WHERE g_status = 'started'";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->execute(); 
                            }
                        }
                    }                         
                    } else {
                        echo "Error fetching data: " . $mysqli->error;
                    }
                }else{                    
                    $sql = "SELECT Pieces_Blue from game_status WHERE g_status = 'started'";
                    $Pieces_Blue = $mysqli->query($sql);
                    if ($Pieces_Blue && $row = $Pieces_Blue->fetch_assoc()) {
                        //Decode the JSON string into an associative array
                        $Pieces_Blue = json_decode($row['Pieces_Blue'], true);
                        
                    if (isset($Pieces_Blue[$x])) {
                        $Pieces_Blue[$x] = 0;
                    }
                 
                    $Pieces_Blue = json_encode($Pieces_Blue);
                    $sql = "UPDATE game_status SET Pieces_Blue = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $Pieces_Blue);
                    $stmt->execute();     
                    } else {
                        echo "Error fetching data: " . $mysqli->error;
                    }
                    //set bonus_points_Blue = 1 if last move was made with mononimo
                    if($x == 1){
                        $sql = "SELECT Pieces_Blue from game_status WHERE g_status = 'started'";
                        $Pieces_Blue = $mysqli->query($sql);
                        $counter_blue = 0;              //counter to track if all pieces have been used to give bonus points
                        if ($Pieces_Blue && $row = $Pieces_Blue->fetch_assoc()){ 
                            //Decode the JSON string into an associative array
                            $Pieces_Blue = json_decode($row['Pieces_Blue'], true);
                            foreach ($Pieces_Blue as $pls){
                                $counter++;
                                if ($pls==1){
                                    $counter_blue++;
                                }
                            }
                            if ($counter_blue == 0){
                                $sql = "UPDATE game_status SET bonus_points_blue = '1' WHERE g_status = 'started'";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->execute(); 
                            }
                        }
                    }                       
                }
            do_move($x, $y, $x2, $y2);
            exit;  // Exit after making the move    
            }
        }
    }

    // If no valid move was found, return an error
    header("HTTP/1.1 400 Bad Request");
    print json_encode(['errormesg' => "This move is illegal."]);
    exit;
}


function do_move($x,$y,$x2,$y2) {
	global $mysqli;
    //echo 'debug do_move';

	$x = $x + ($y2*21);                     
	$selected_piece = select_piece($x);

	if (!$selected_piece) {
        header('Content-type: application/json');
        echo json_encode(["error" => "Invalid piece selection"], JSON_PRETTY_PRINT);
        return;
    }
	
    // Convert the piece to a JSON string
    $piece_json = json_encode($selected_piece);

    // Prepare and execute the SQL query
    $sql = 'CALL `place_blokus_piece`(?,?,?);';
    $st = $mysqli->prepare($sql);

    if (!$st) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    // Bind the JSON string as a single parameter
    $st->bind_param('sii', $piece_json,$y,$x2);
    $st->execute();
	// change player turn
	//global $mysqli;
	$sql = 'call Next_Player()';
	$mysqli->query($sql);
    // Return the updated board as JSON
    header('Content-type: application/json');
    print json_encode(read_board(), JSON_PRETTY_PRINT);
}

function show_board_by_player($b) {

	global $mysqli;

	$orig_board=read_board();
	$board=convert_board($orig_board);
	$status = read_status();
	if($status['status']=='started' && $status['p_turn']==$b && $b!=null) {
		// It my turn !!!!
		add_valid_moves_to_board($board,$b);
		
		// Εάν n==0, τότε έχασα !!!!!
		// Θα πρέπει να ενημερωθεί το game_status.
	}
	header('Content-type: application/json');
	print json_encode($orig_board, JSON_PRETTY_PRINT);
}

function select_piece($x) {
    global $mysqli;
    $piece_id = ($x - 1) % 21 + 1;  // Maps 1–84 to 1–21 cyclically
    $rotation = floor(($x - 1) / 21);  // Determines the rotation (0–3)
    $stmt = $mysqli->prepare("SELECT piece FROM pieces WHERE piece_id = ? AND rotation = ?");

    if (!$stmt) {
        die("Failed to prepare statement: " . $mysqli->error);
    }

    // Bind the parameters and execute the query
    $stmt->bind_param("ii", $piece_id, $rotation);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row ? json_decode($row['piece'], true) : null;
}

?>