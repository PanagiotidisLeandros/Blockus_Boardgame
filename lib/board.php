<?php
require_once "game.php";
require_once "dbconnect.php";

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

    //currently only resets flag_no_moves, bonus_points and score
    $sql = "CALL Reset_game_status()";
    $mysqli->query($sql);

}

function read_board() {
	global $mysqli;
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	return($res->fetch_all(MYSQLI_ASSOC));
}

function move_piece($piece_id, $cordinet_x, $cordinet_y, $rotation, $token) {
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
        // Check if the current move matches the input coordinates ($piece_id, $cordinet_x, $cordinet_y, $rotation)
        if (isset($move[0], $move[1], $move[2], $move[3])) {
            $piece_id_in_legal_moves = $move[0];  // Piece value
            $cordinet_x_in_legal_moves = $move[1];  // x-coordinate
            $cordinet_y_in_legal_moves = $move[2];  // y-coordinate
            $rotation_in_legal_moves = $move[3];  // Rotation value

            // Compare the input values with the move's values
            if ($piece_id_in_legal_moves == $piece_id && $cordinet_x_in_legal_moves == $cordinet_x && $cordinet_y_in_legal_moves == $cordinet_y && $rotation_in_legal_moves == $rotation) {
                // If they match, call the function to process the move


                $sql = "SELECT p_turn FROM game_status WHERE g_status = 'started'";
                $result = $mysqli->query($sql);    
                if ($result) {
                    // Check if there are any rows
                    if ($row = $result->fetch_assoc()) {
                        // Fetch the value of p_turn
                        $p_turn = $row['p_turn'];
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
                        
                    if (isset($Pieces_Red[$piece_id])) {
                        $Pieces_Red[$piece_id] = 0;
                    }
                    // update Pieces_Red so that red cannot place the same piece again
                    $Pieces_Red = json_encode($Pieces_Red);
                    $sql = "UPDATE game_status SET Pieces_Red = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $Pieces_Red);
                    $stmt->execute();

                    //set bonus_points_red = 1 if last move was made with mononimo
                    if($piece_id == 1){
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
                                //old code
                                $sql = "UPDATE game_status SET bonus_points_red = '1' WHERE g_status = 'started'";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->execute(); 
                                //new code

                                $sql = "SELECT bonus_points FROM game_status WHERE g_status = 'started'";
                                $result = $mysqli->query($sql);
                                
                                if ($result && $row = $result->fetch_assoc()) {
                                    // Decode the JSON value of 'bonus_points' into an array
                                    $bonus_points_array = json_decode($row['bonus_points'], true);
                                    //from the array change appropriate value to 1
                                    $bonus_points_array[2] = 1;
                                    // update table game_status with the new array
                                    $bonus_points_array = json_encode($bonus_points_array);
                                    $sql = "UPDATE game_status SET bonus_points = ? WHERE g_status = 'started'";
                                    $stmt = $mysqli->prepare($sql);
                                    $stmt->bind_param("s", $bonus_points_array);
                                    $stmt->execute();  
                                } else {
                                    echo "No results or query failed.";
                                } 
                            }
                        }
                    }                         
                    } else {
                        echo "Error fetching data: " . $mysqli->error;
                    }
                }else if ($p_turn == 'B') {                    
                    $sql = "SELECT Pieces_Blue from game_status WHERE g_status = 'started'";
                    $Pieces_Blue = $mysqli->query($sql);
                    if ($Pieces_Blue && $row = $Pieces_Blue->fetch_assoc()) {
                        //Decode the JSON string into an associative array
                        $Pieces_Blue = json_decode($row['Pieces_Blue'], true);
                        
                    if (isset($Pieces_Blue[$piece_id])) {
                        $Pieces_Blue[$piece_id] = 0;
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
                    if($piece_id == 1){
                        $sql = "SELECT Pieces_Blue from game_status WHERE g_status = 'started'";
                        $Pieces_Blue = $mysqli->query($sql);
                        $counter_blue = 0;              //counter to track if all pieces have been used to give bonus points
                        if ($Pieces_Blue && $row = $Pieces_Blue->fetch_assoc()){ 
                            //Decode the JSON string into an associative array
                            $Pieces_Blue = json_decode($row['Pieces_Blue'], true);
                            foreach ($Pieces_Blue as $pls){
                                if ($pls==1){
                                    $counter_blue++;
                                }
                            }
                            if ($counter_blue == 0){
                                //old code
                                $sql = "UPDATE game_status SET bonus_points_blue = '1' WHERE g_status = 'started'";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->execute(); 

                                //new code

                                $sql = "SELECT bonus_points FROM game_status WHERE g_status = 'started'";
                                $result = $mysqli->query($sql);
                                
                                if ($result && $row = $result->fetch_assoc()) {
                                    // Decode the JSON value of 'bonus_points' into an array
                                    $bonus_points_array = json_decode($row['bonus_points'], true);
                                    //from the array change appropriate value to 1
                                    $bonus_points_array[0] = 1;
                                    // update table game_status with the new array
                                    $bonus_points_array = json_encode($bonus_points_array);
                                    $sql = "UPDATE game_status SET bonus_points = ? WHERE g_status = 'started'";
                                    $stmt = $mysqli->prepare($sql);
                                    $stmt->bind_param("s", $bonus_points_array);
                                    $stmt->execute();  
                                } else {
                                    echo "No results or query failed.";
                                } 
                            }
                        }
                    }
                }else if ($p_turn == 'Y') {
                    $sql = "SELECT Pieces_Yellow from game_status WHERE g_status = 'started'";
                    $Pieces_Yellow = $mysqli->query($sql);
                    if ($Pieces_Yellow && $row = $Pieces_Yellow->fetch_assoc()) {
                        //Decode the JSON string into an associative array
                        $Pieces_Yellow = json_decode($row['Pieces_Yellow'], true);
                        
                    if (isset($Pieces_Yellow[$piece_id])) {
                        $Pieces_Yellow[$piece_id] = 0;
                    }
                 
                    $Pieces_Yellow = json_encode($Pieces_Yellow);
                    $sql = "UPDATE game_status SET Pieces_Yellow = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $Pieces_Yellow);
                    $stmt->execute();     
                    } else {
                        echo "Error fetching data: " . $mysqli->error;
                    }
                    //set bonus_points_Yellow = 1 if last move was made with mononimo
                    if($piece_id == 1){
                        $sql = "SELECT Pieces_Yellow from game_status WHERE g_status = 'started'";
                        $Pieces_Yellow = $mysqli->query($sql);
                        $counter_yellow = 0;              //counter to track if all pieces have been used to give bonus points
                        if ($Pieces_Yellow && $row = $Pieces_Yellow->fetch_assoc()){ 
                            //Decode the JSON string into an associative array
                            $Pieces_Yellow = json_decode($row['Pieces_Yellow'], true);
                            foreach ($Pieces_Yellow as $pls){
                                if ($pls==1){
                                    $counter_yellow++;
                                }
                            }
                            if ($counter_yellow == 0){

                                $sql = "SELECT bonus_points FROM game_status WHERE g_status = 'started'";
                                $result = $mysqli->query($sql);
                                
                                if ($result && $row = $result->fetch_assoc()) {
                                    // Decode the JSON value of 'bonus_points' into an array
                                    $bonus_points_array = json_decode($row['bonus_points'], true);
                                    //from the array change appropriate value to 1
                                    $bonus_points_array[1] = 1;
                                    // update table game_status with the new array
                                    $bonus_points_array = json_encode($bonus_points_array);
                                    $sql = "UPDATE game_status SET bonus_points = ? WHERE g_status = 'started'";
                                    $stmt = $mysqli->prepare($sql);
                                    $stmt->bind_param("s", $bonus_points_array);
                                    $stmt->execute();  
                                } else {
                                    echo "No results or query failed.";
                                } 
                            }
                        }
                    }
                }elseif($p_turn == 'G'){
                    $sql = "SELECT Pieces_Green from game_status WHERE g_status = 'started'";
                    $Pieces_Green = $mysqli->query($sql);
                    if ($Pieces_Green && $row = $Pieces_Green->fetch_assoc()) {
                        //Decode the JSON string into an associative array
                        $Pieces_Green = json_decode($row['Pieces_Green'], true);
                        
                    if (isset($Pieces_Green[$piece_id])) {
                        $Pieces_Green[$piece_id] = 0;
                    }
                 
                    $Pieces_Green = json_encode($Pieces_Green);
                    $sql = "UPDATE game_status SET Pieces_Green = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $Pieces_Green);
                    $stmt->execute();     
                    } else {
                        echo "Error fetching data: " . $mysqli->error;
                    }
                    //set bonus_points_Green = 1 if last move was made with mononimo
                    if($piece_id == 1){
                        $sql = "SELECT Pieces_Green from game_status WHERE g_status = 'started'";
                        $Pieces_Green = $mysqli->query($sql);
                        $counter_green = 0;                               //counter to track if all pieces have been used to give bonus points
                        if ($Pieces_Green && $row = $Pieces_Green->fetch_assoc()){ 
                            //Decode the JSON string into an associative array
                            $Pieces_Green = json_decode($row['Pieces_Green'], true);
                            foreach ($Pieces_Green as $pls){
                                if ($pls==1){
                                    $counter_green++;
                                }
                            }
                            if ($counter_green == 0){

                                $sql = "SELECT bonus_points FROM game_status WHERE g_status = 'started'";
                                $result = $mysqli->query($sql);
                                
                                if ($result && $row = $result->fetch_assoc()) {
                                    // Decode the JSON value of 'bonus_points' into an array
                                    $bonus_points_array = json_decode($row['bonus_points'], true);
                                    //from the array change appropriate value to 1
                                    $bonus_points_array[3] = 1;
                                    // update table game_status with the new array
                                    $bonus_points_array = json_encode($bonus_points_array);
                                    $sql = "UPDATE game_status SET bonus_points = ? WHERE g_status = 'started'";
                                    $stmt = $mysqli->prepare($sql);
                                    $stmt->bind_param("s", $bonus_points_array);
                                    $stmt->execute();  
                                } else {
                                    echo "No results or query failed.";
                                } 
                            }
                        }
                    }
                } 
            do_move($piece_id, $cordinet_x, $cordinet_y, $rotation);
            exit; 
            }
        }
    }

    // If no valid move was found, return an error
    header("HTTP/1.1 400 Bad Request");
    print json_encode(['errormesg' => "This move is illegal."]);
    exit;
}


function do_move($piece_id,$cordinet_x,$cordinet_y,$rotation) {
	global $mysqli;
    
	$piece_id = $piece_id + ($rotation*21);                     
	$selected_piece = select_piece($piece_id);

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
    $st->bind_param('sii', $piece_json,$cordinet_x,$cordinet_y);
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