<?php
require_once "../lib/board.php";
require_once "../lib/dbconnect.php";
require_once "../lib/game.php";

handle_get_moves();
check_and_update_player_turn();

function handle_get_moves() {

    $method = $_SERVER['REQUEST_METHOD'];
    if ($method !== 'GET') {
        // If the method is not GET, respond with an error
        header("HTTP/1.1 405 Method Not Allowed");
        echo json_encode(['errormesg' => "Method $method not allowed."]);
        exit;
    }
    // Read the current board state
    $orig_board = read_board();
    $board = convert_board($orig_board);


    $status = read_status();  // Get the game status

    $p_turn = isset($status[0]['p_turn']) ? $status[0]['p_turn'] : null;
    $g_status = isset($status[0]['g_status']) ? $status[0]['g_status'] : null;

    if ($g_status !== 'started') {                                                                      
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['errormesg' => "Game is not in a valid state to calculate moves."]);
        return;
    }

    if (!$p_turn) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode(['errormesg' => "Player turn not found."]);
        return;
    }



    $sql = "UPDATE game_status SET Legal_Moves = NULL WHERE g_status = 'started'";
     global $mysqli;
    // Prepare and execute the query
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();


    // Add valid moves for the current player to the board
    $status = read_status();  // Get the game status
    add_valid_moves_to_board($board, $p_turn);
    global $L_M;
    // Respond with the modified board including valid moves
    header('Content-Type: application/json');
    echo json_encode($L_M, JSON_PRETTY_PRINT);
    $sql = "SELECT Legal_Moves FROM game_status WHERE g_status = 'started'";
    $result = $mysqli->query($sql);

}


function convert_board(&$orig_board) {
	$board=[];
	foreach($orig_board as $i=>&$row) {
		$board[$row['x']][$row['y']] = &$row;
	} 
	return($board);
}



function add_valid_moves_to_board(&$board,$b) {
	//$number_of_moves=0;
    
	for($x=2;$x<=21;$x++) {
		for($y=2;$y<=21;$y++) {
			add_valid_moves_to_piece($board,$b,$x,$y);
		}
	}
	//return($number_of_moves);
}

function add_valid_moves_to_piece(&$board, $b, $x, $y) {
    global $L_M; 

    global $mysqli;
    $sql = "SELECT Pieces_Blue, Pieces_Red, Pieces_Yellow, Pieces_Green FROM game_status";
    $result = $mysqli->query($sql);

    if ($row = $result->fetch_assoc()) {

        // Decode JSON to arrays
        $P_B = json_decode($row['Pieces_Blue'], true);
        $P_R = json_decode($row['Pieces_Red'], true);
        $P_Y = json_decode($row['Pieces_Yellow'], true);
        $P_G = json_decode($row['Pieces_Green'], true);

    } else {
            echo "No data found.\n";
    }
    // Only process if the starting cell is empty
    if ($board[$x][$y]['cell_status'] == 'E') {

        // Loop through all pieces (from 1 to 21 and all possible rotations)
        for ($c = 1; $c <= 84; $c++) {
            $selected_piece = select_piece($c);

            if ($selected_piece) {
                $all_within_bounds = true;

                // Check if all coordinates of the piece are within bounds
                foreach ($selected_piece as $coord) {
                    $actual_x = $x + $coord[0];
                    $actual_y = $y + $coord[1];
                    if ($actual_x < 2 || $actual_x > 21 || $actual_y < 2 || $actual_y > 21) {
                        $all_within_bounds = false;
                        break; // No need to check further, exit loop early
                    }
                    if (!isset($board[$actual_x][$actual_y]) || $board[$actual_x][$actual_y]['cell_status'] != 'E') {
                        $all_within_bounds = false; // Cell is not empty
                        break; // No need to check further
                    }
                }


                // If all coordinates are within bounds
                if ($all_within_bounds) {
                    $no_cross_owned_cell = true;

                    // Check if adjacent cells are not owned by the current player
                    foreach ($selected_piece as $coord) {
                        $actual_x = $x + $coord[0];
                        $actual_y = $y + $coord[1];

                        $adjacent_cells = [
                            [$actual_x + 1, $actual_y],
                            [$actual_x - 1, $actual_y],
                            [$actual_x, $actual_y + 1],
                            [$actual_x, $actual_y - 1]
                        ];

                        foreach ($adjacent_cells as $adj) {
                            $adj_x = $adj[0];
                            $adj_y = $adj[1];

                            // Ensure we are within bounds
                            if ($adj_x > 0 && $adj_x <= 22 && $adj_y > 0 && $adj_y <= 22) {
                                if ($board[$adj_x][$adj_y]['cell_status'] == $b) {
                                    $no_cross_owned_cell = false;
                                    break 2; // Exit both loops, no need to check further
                                }
                            }
                        }
                    }

                    // Check if the no adjacent cells are owned by the current player
                    if ($no_cross_owned_cell && $all_within_bounds) {
                        $has_diagonal_owned_cell = false;

                        // Check if there are diagonal cells owned by the current player
                        foreach ($selected_piece as $coord) {
                            $actual_x = $x + $coord[0];
                            $actual_y = $y + $coord[1];

                            $diagonal_cells = [
                                [$actual_x + 1, $actual_y + 1],
                                [$actual_x + 1, $actual_y - 1],
                                [$actual_x - 1, $actual_y + 1],
                                [$actual_x - 1, $actual_y - 1]
                            ];

                            foreach ($diagonal_cells as $diag) {
                                $diag_x = $diag[0];
                                $diag_y = $diag[1];

                                // Ensure diagonal cell is within bounds
                                if ($diag_x > 0 && $diag_x <= 22 && $diag_y > 0 && $diag_y <= 22) {
                                    if ($board[$diag_x][$diag_y]['cell_status'] == $b) {
                                        $has_diagonal_owned_cell = true;
                                        break; // No need to check further diagonal cells
                                    }
                                }
                            }
                        }

                        // If all conditions are met, add the move to the legal moves list
                        if ($has_diagonal_owned_cell && $all_within_bounds) {
                            if ($c >= 1 && $c <= 21) {
                                $rotation = 0;
                                $piece = $c;
                            } elseif ($c >= 22 && $c <= 42) {
                                $rotation = 1;
                                $piece = $c - 21; 
                            } elseif ($c >= 43 && $c <= 63) {
                                $rotation = 2;
                                $piece = $c - 42;
                            } elseif ($c >= 64 && $c <= 84) {
                                $rotation = 3;
                                $piece = $c - 63; 
                            } else {
                                throw new Exception("Invalid piece index: $piece");
                            }

                            if ($b == 'R' && $P_R[$piece] == 1){
                                $L_M[] = [$piece, $x, $y, $rotation];
                                $L_M_json = json_encode($L_M);

                                $sql = "UPDATE game_status SET Legal_Moves = ? WHERE g_status = 'started'";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->bind_param("s", $L_M_json);
    
                                $stmt->execute();
                            }elseif($b == 'B' && $P_B[$piece] == 1)
                            {
                                $L_M[] = [$piece, $x, $y, $rotation];
                                $L_M_json = json_encode($L_M);

                                $sql = "UPDATE game_status SET Legal_Moves = ? WHERE g_status = 'started'";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->bind_param("s", $L_M_json);
                                $stmt->execute();   
                            }elseif($b == 'Y' && $P_Y[$piece] == 1)
                            {
                                $L_M[] = [$piece, $x, $y, $rotation];
                                $L_M_json = json_encode($L_M);

                                $sql = "UPDATE game_status SET Legal_Moves = ? WHERE g_status = 'started'";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->bind_param("s", $L_M_json);
                                $stmt->execute();   
                            }elseif($b == 'G' && $P_G[$piece] == 1)
                            {
                                $L_M[] = [$piece, $x, $y, $rotation];
                                $L_M_json = json_encode($L_M);

                                $sql = "UPDATE game_status SET Legal_Moves = ? WHERE g_status = 'started'";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->bind_param("s", $L_M_json);
                                $stmt->execute();   
                            }
                        }
                    }
                }
            }
        }
    }
}

function check_and_update_player_turn(){
    global $mysqli;
$sql = "SELECT p_turn FROM game_status WHERE g_status = 'started'";
$result = $mysqli->query($sql);    
if ($result) {
    // Check if there are any rows
    if ($row = $result->fetch_assoc()) {
        // Fetch the value of p_turn
        $b = $row['p_turn'];
        $sql = "SELECT Legal_Moves 
        FROM game_status 
        WHERE JSON_LENGTH(Legal_Moves) = 0 OR Legal_Moves IS NULL";
        $result = $mysqli->query($sql);
        // Check if current player has no legal moves if he does not skip to the next player and raise falg_no_moves
        if ($result && $result->num_rows > 0) {
            // Legal_Moves is empty
            if ($b == 'R') {
                $mysqli->query('CALL Next_Player()');

                $sql = "SELECT flag_no_moves FROM game_status WHERE g_status = 'started'";
                $result = $mysqli->query($sql);
                
                if ($result && $row = $result->fetch_assoc()) {
                    // Decode the JSON value of 'flag_no_moves' into an array
                    $flag_no_moves_array = json_decode($row['flag_no_moves'], true);
                    //from the array change appropriate value to 1
                    $flag_no_moves_array[2] = 1;
                    // update table game_status with the new array
                    $flag_no_moves_array = json_encode($flag_no_moves_array);
                    $sql = "UPDATE game_status SET flag_no_moves = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $flag_no_moves_array);
                    $stmt->execute();  
                } else {
                    echo "No results or query failed.";
                }        


            }
            if ($b == 'B') {
                $mysqli->query('CALL Next_Player()');

                $sql = "SELECT flag_no_moves FROM game_status WHERE g_status = 'started'";
                $result = $mysqli->query($sql);
                
                if ($result && $row = $result->fetch_assoc()) {
                    // Decode the JSON value of 'flag_no_moves' into an array
                    $flag_no_moves_array = json_decode($row['flag_no_moves'], true);
                    //from the array change appropriate value to 1
                    $flag_no_moves_array[0] = 1;
                    // update table game_status with the new array
                    $flag_no_moves_array = json_encode($flag_no_moves_array);
                    $sql = "UPDATE game_status SET flag_no_moves = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $flag_no_moves_array);
                    $stmt->execute();  
                } else {
                    echo "No results or query failed.";
                }   
            }
            if ($b == 'Y') {
                $mysqli->query('CALL Next_Player()');
                $sql = "SELECT flag_no_moves FROM game_status WHERE g_status = 'started'";
                $result = $mysqli->query($sql);
                
                if ($result && $row = $result->fetch_assoc()) {
                    // Decode the JSON value of 'flag_no_moves' into an array
                    $flag_no_moves_array = json_decode($row['flag_no_moves'], true);
                    //from the array change appropriate value to 1
                    $flag_no_moves_array[1] = 1;
                    // update table game_status with the new array
                    $flag_no_moves_array = json_encode($flag_no_moves_array);
                    $sql = "UPDATE game_status SET flag_no_moves = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $flag_no_moves_array);
                    $stmt->execute();  
                } else {
                    echo "No results or query failed.";
                }   
            }
            if ($b == 'G') {
                $mysqli->query('CALL Next_Player()');

                $sql = "SELECT flag_no_moves FROM game_status WHERE g_status = 'started'";
                $result = $mysqli->query($sql);
                
                if ($result && $row = $result->fetch_assoc()) {
                    // Decode the JSON value of 'flag_no_moves' into an array
                    $flag_no_moves_array = json_decode($row['flag_no_moves'], true);
                    //from the array change appropriate value to 1
                    $flag_no_moves_array[3] = 1;
                    // update table game_status with the new array
                    $flag_no_moves_array = json_encode($flag_no_moves_array);
                    $sql = "UPDATE game_status SET flag_no_moves = ? WHERE g_status = 'started'";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param("s", $flag_no_moves_array);
                    $stmt->execute();  
                } else {
                    echo "No results or query failed.";
                }   
            }
            // Query to get the values of flags 
            $sql = "SELECT flag_no_moves FROM game_status WHERE g_status = 'started'";
            $result = $mysqli->query($sql);
            
            if ($result && $row = $result->fetch_assoc()) {
                // Decode the JSON value of 'flag_no_moves' into an array
                $flag_no_moves_array = json_decode($row['flag_no_moves'], true);
                if ($flag_no_moves_array[0] == 1 && $flag_no_moves_array[1] == 1 && $flag_no_moves_array[2] == 1 && $flag_no_moves_array[3] == 1){
                    $sql = "UPDATE game_status SET g_status = 'ended'";
                    $mysqli->query($sql);
                    game_over();
                }
            } else {
                echo "No results or query failed.";
            }   
        }
    } else {
        echo "No rows found in the result.";
    }
} else {
    echo "Query failed: " . $mysqli->error;
}
}

function game_over() {
    global $mysqli;
    $sql = "SELECT Pieces_Red from game_status WHERE g_status = 'ended'";
    $Pieces_Red = $mysqli->query($sql);

    $score_red = 0;
    $counter_red_pieces_unused = 0;              //counter to track if all pieces have been used to give +15 points
    $counter = 0;                  //counter to track number of loop in foreach 

    if ($Pieces_Red && $row = $Pieces_Red->fetch_assoc()){ 
        //Decode the JSON string into an associative array
        $Pieces_Red = json_decode($row['Pieces_Red'], true);
        foreach ($Pieces_Red as $pls){
            $counter++;
            // subtract points for every squere of every unused piece
            if ($pls==1 && $counter == 1){
            $score_red--;
            $counter_red_pieces_unused++;
            }
            else if ($pls==1 && $counter == 2){
            $score_red=$score_red-2;
            $counter_red_pieces_unused++;
            }
            else if($pls==1 && $counter < 5){
            $score_red=$score_red-3;
            $counter_red_pieces_unused++;
            }
            else if($pls==1 && $counter < 10){
            $score_red=$score_red-4;
            $counter_red_pieces_unused++;
            }
            else if($pls==1 && $counter <= 21){
            $score_red=$score_red-5;
            $counter_red_pieces_unused++;
            }
            // add points for every squere of every unused piece        
            if ($pls==0 && $counter == 1){
                $score_red++;
            }
            else if ($pls==0 && $counter == 2){
            $score_red=$score_red+2;
            }
            else if($pls==0 && $counter < 5){
            $score_red=$score_red+3;
            }
            else if($pls==0 && $counter < 10){
            $score_red=$score_red+4;
            }
            else if($pls==0 && $counter <= 21){
            $score_red=$score_red+5;
            }
        }
        $sql = "SELECT bonus_points FROM game_status WHERE g_status = 'ended'";
        $result = $mysqli->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            // Decode the JSON value of 'flag_no_moves' into an array
            $bonus_points = json_decode($row['bonus_points'], true);
            //Give +20 points if all pieces have been used and last piece used was mononimo OR +15 if all pieces have been used otherwise no bonus
            if ($counter_red_pieces_unused == 0 && $bonus_points[2] == '1'){
                $score_red = $score_red + 20;
            }else if($counter_red_pieces_unused == 0){
                $score_red = $score_red + 15;        
            }
        }
    }
    $sql = "SELECT score FROM game_status WHERE g_status = 'ended'";
    $result = $mysqli->query($sql);
    
    if ($result && $row = $result->fetch_assoc()) {
        // Decode the JSON value of 'bonus_points' into an array
        $score_array = json_decode($row['score'], true);
        //from the array change appropriate value to 1
        $score_array[2] = $score_red;
        // update table game_status with the new array
        $score_array = json_encode($score_array);
        $sql = "UPDATE game_status SET score = ? WHERE g_status = 'ended'";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $score_array);
        $stmt->execute();  
    } else {
        echo "No results or query failed.";
    } 
    
    //  Same code now for Player Βlue
    
    $sql = "SELECT Pieces_Blue from game_status WHERE g_status = 'ended'";
    $Pieces_Blue = $mysqli->query($sql);
    $score_blue = 0;
    $counter_blue_unused_pieces = 0;              //counter to track if all pieces have been used to give +15 points
    $counter = 0;                  //counter to track number of loop in foreach 
    if ($Pieces_Blue && $row = $Pieces_Blue->fetch_assoc()){ 
        //Decode the JSON string into an associative array
        $Pieces_Blue = json_decode($row['Pieces_Blue'], true);
        foreach ($Pieces_Blue as $pls){
            $counter++;
            if ($pls==1 && $counter == 1){
            $score_blue--;
            $counter_blue_unused_pieces++;
            }
            else if ($pls==1 && $counter == 2){
            $score_blue=$score_blue-2;
            $counter_blue_unused_pieces++;
            }
            else if($pls==1 && $counter < 5){
            $score_blue=$score_blue-3;
            $counter_blue_unused_pieces++;
            }
            else if($pls==1 && $counter < 10){
            $score_blue=$score_blue-4;
            $counter_blue_unused_pieces++;
            }
            else if($pls==1 && $counter <= 21){
            $score_blue=$score_blue-5;
            $counter_blue_unused_pieces++;
            }

            if ($pls==0 && $counter == 1){
            $score_blue++;
            }
            else if ($pls==0 && $counter == 2){
            $score_blue=$score_blue+2;
            }
            else if($pls==0 && $counter < 5){
            $score_blue=$score_blue+3;
            }
            else if($pls==0 && $counter < 10){
            $score_blue=$score_blue+4;
            }
            else if($pls==0 && $counter <= 21){
            $score_blue=$score_blue+5;
            }
        }

        $sql = "SELECT flag_no_moves FROM game_status WHERE g_status = 'ended'";
        $result = $mysqli->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            // Decode the JSON value of 'flag_no_moves' into an array
            $flag_no_moves_array = json_decode($row['flag_no_moves'], true);

        if ($counter_blue_unused_pieces == 0 && $flag_no_moves_array[0] == '1'){
            $score_blue = $score_blue + 20;
        }else if($counter_blue_unused_pieces == 0){
            $score_blue = $score_blue + 15;        
        }
    }
    }

    $sql = "SELECT score FROM game_status WHERE g_status = 'ended'";
    $result = $mysqli->query($sql);
    
    if ($result && $row = $result->fetch_assoc()) {
        // Decode the JSON value of 'bonus_points' into an array
        $score_array = json_decode($row['score'], true);
        //from the array change appropriate value to 1
        $score_array[0] = $score_blue;
        // update table game_status with the new array
        $score_array = json_encode($score_array);
        $sql = "UPDATE game_status SET score = ? WHERE g_status = 'ended'";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $score_array);
        $stmt->execute();  
    } else {
        echo "No results or query failed.";
    } 

    //  Player Yellow

    $sql = "SELECT Pieces_Yellow from game_status WHERE g_status = 'ended'";
    $Pieces_Yellow = $mysqli->query($sql);
    $score_yellow = 0;
    $counter_yellow_unused_pieces = 0;              //counter to track if all pieces have been used to give +15 points
    $counter = 0;                  //counter to track number of loop in foreach 
    if ($Pieces_Yellow && $row = $Pieces_Yellow->fetch_assoc()){ 
        //Decode the JSON string into an associative array
        $Pieces_Yellow = json_decode($row['Pieces_Yellow'], true);
        foreach ($Pieces_Yellow as $pls){
            $counter++;
            if ($pls==1 && $counter == 1){
            $score_yellow--;
            $counter_yellow_unused_pieces++;
            }
            else if ($pls==1 && $counter == 2){
            $score_yellow=$score_yellow-2;
            $counter_yellow_unused_pieces++;
            }
            else if($pls==1 && $counter < 5){
            $score_yellow=$score_yellow-3;
            $counter_yellow_unused_pieces++;
            }
            else if($pls==1 && $counter < 10){
            $score_yellow=$score_yellow-4;
            $counter_yellow_unused_pieces++;
            }
            else if($pls==1 && $counter <= 21){
            $score_yellow=$score_yellow-5;
            $counter_yellow_unused_pieces++;
            }

            if ($pls==0 && $counter == 1){
            $score_yellow++;
            }
            else if ($pls==0 && $counter == 2){
            $score_yellow=$score_yellow+2;
            }
            else if($pls==0 && $counter < 5){
            $score_yellow=$score_yellow+3;
            }
            else if($pls==0 && $counter < 10){
            $score_yellow=$score_yellow+4;
            }
            else if($pls==0 && $counter <= 21){
            $score_yellow=$score_yellow+5;
            }
        }
        $sql = "SELECT flag_no_moves FROM game_status WHERE g_status = 'ended'";
        $result = $mysqli->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            // Decode the JSON value of 'flag_no_moves' into an array
            $flag_no_moves_array = json_decode($row['flag_no_moves'], true);

        if ($counter_yellow_unused_pieces == 0 && $flag_no_moves_array[1] == '1'){
            $score_yellow = $score_yellow + 20;
        }else if($counter_yellow_unused_pieces == 0){
            $score_yellow = $score_yellow + 15;        
        }
    }
    }

    $sql = "SELECT score FROM game_status WHERE g_status = 'ended'";
    $result = $mysqli->query($sql);
    
    if ($result && $row = $result->fetch_assoc()) {
        // Decode the JSON value of 'bonus_points' into an array
        $score_array = json_decode($row['score'], true);
        //from the array change appropriate value to 1
        $score_array[1] = $score_yellow;
        // update table game_status with the new array
        $score_array = json_encode($score_array);
        $sql = "UPDATE game_status SET score = ? WHERE g_status = 'ended'";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $score_array);
        $stmt->execute();  
    } else {
        echo "No results or query failed.";
    } 

    //  Player Green

    $sql = "SELECT Pieces_Green from game_status WHERE g_status = 'ended'";
    $Pieces_Green = $mysqli->query($sql);
    $score_green = 0;
    $counter_green_unused_pieces = 0;              //counter to track if all pieces have been used to give +15 points
    $counter = 0;                                  //counter to track number of loop in foreach 
    if ($Pieces_Green && $row = $Pieces_Green->fetch_assoc()){ 
        //Decode the JSON string into an associative array
        $Pieces_Green = json_decode($row['Pieces_Green'], true);
        foreach ($Pieces_Green as $pls){
            $counter++;
            if ($pls==1 && $counter == 1){
            $score_green--;
            $counter_green_unused_pieces++;
            }
            else if ($pls==1 && $counter == 2){
            $score_green=$score_green-2;
            $counter_green_unused_pieces++;
            }
            else if($pls==1 && $counter < 5){
            $score_green=$score_green-3;
            $counter_green_unused_pieces++;
            }
            else if($pls==1 && $counter < 10){
            $score_green=$score_green-4;
            $counter_green_unused_pieces++;
            }
            else if($pls==1 && $counter <= 21){
            $score_green=$score_green-5;
            $counter_green_unused_pieces++;
            }

            if ($pls==0 && $counter == 1){
            $score_green++;
            }
            else if ($pls==0 && $counter == 2){
            $score_green=$score_green+2;
            }
            else if($pls==0 && $counter < 5){
            $score_green=$score_green+3;
            }
            else if($pls==0 && $counter < 10){
            $score_green=$score_green+4;
            }
            else if($pls==0 && $counter <= 21){
            $score_green=$score_green+5;
            }
        }
        $sql = "SELECT flag_no_moves FROM game_status WHERE g_status = 'ended'";
        $result = $mysqli->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            // Decode the JSON value of 'flag_no_moves' into an array
            $flag_no_moves_array = json_decode($row['flag_no_moves'], true);

        if ($counter_green_unused_pieces == 0 && $flag_no_moves_array[3] == '1'){
            $score_green = $score_green + 20;
        }else if($counter_green_unused_pieces == 0){
            $score_green = $score_green + 15;        
        }
    }
    }

    $sql = "SELECT score FROM game_status WHERE g_status = 'ended'";
    $result = $mysqli->query($sql);
    
    if ($result && $row = $result->fetch_assoc()) {
        // Decode the JSON value of 'bonus_points' into an array
        $score_array = json_decode($row['score'], true);
        //from the array change appropriate value to 1
        $score_array[3] = $score_green;
        // update table game_status with the new array
        $score_array = json_encode($score_array);
        $sql = "UPDATE game_status SET score = ? WHERE g_status = 'ended'";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $score_array);
        $stmt->execute();  
    } else {
        echo "No results or query failed.";
    }

    // Update the game status with the winner
    if($score_red > $score_blue && $score_red > $score_yellow && $score_red > $score_green){
        $winner = "R";
    }elseif($score_blue > $score_red && $score_blue > $score_yellow && $score_blue > $score_green){
        $winner = "B";
    }elseif($score_yellow > $score_red && $score_yellow > $score_blue && $score_yellow > $score_green){
        $winner = "Y";
    }elseif($score_green > $score_red && $score_green > $score_blue && $score_green > $score_yellow){
        $winner = "G";
    }else{
        $winner = "D";
    }
    $winner_json = json_encode($winner);
    $sql = "UPDATE game_status SET result = ? WHERE g_status = 'ended'";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $winner_json);
    $stmt->execute();  
}

?>