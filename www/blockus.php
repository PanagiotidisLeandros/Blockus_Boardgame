<?php

require_once "../lib/dbconnect.php";
require_once "../lib/board.php";
require_once "../lib/game.php";
require_once "../lib/users.php";

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
// $request = explode('/', trim($_SERVER['SCRIPT_NAME'],'/’));
// Σε περίπτωση που τρέχουμε php –S
$input = json_decode(file_get_contents('php://input'),true);
if($input==null) {
    $input=[];
}
if(isset($_SERVER['HTTP_X_TOKEN'])) {
    $input['token']=$_SERVER['HTTP_X_TOKEN'];
} else {
    $input['token']='';
}

//print "Path_info=".$_SERVER['PATH_INFO']."\n";

switch ($r=array_shift($request)) {
    case 'board' : 
        switch ($b=array_shift($request)) {
        	case '':
        	case null: handle_board($method);
                break;
             case 'piece': handle_piece($method, $request[0],$request[1],$input);
                   break;
			}
        break;
    case 'status': 
		handle_status($method);
		break;
	case 'players': handle_player($method, $request,$input);
		break;
	default:  header("HTTP/1.1 404 Not Found");
        exit;
}

function handle_board($method) {
    if($method=='GET') {
            show_board();
    } else if ($method=='POST') {
            reset_board();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
}

function handle_player($method, $p,$input) {
    switch ($b=array_shift($p)) {
			case '':
			case null: if($method=='GET') {show_users();}
					   else {header("HTTP/1.1 400 Bad Request"); 
							 print json_encode(['errormesg'=>"Method $method not allowed here."]);}
		                break;
			case 'R': 
            case 'Y': 
            case 'G':     
			case 'B': handle_user($method, $b,$input);
						break;
			default: header("HTTP/1.1 404 Not Found");
					 print json_encode(['errormesg'=>"Player $b not found."]);
					 break;
		}
}

function handle_status($method) {
    if($method=='GET') {
        show_status();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    ;
}	

function handle_piece($method, $piece_id,$cordinet_x,$input) {
    if($method=='GET') {
        //echo 'debug get handle piece';
        //show_piece($x,$y);
        //print json_encode(['errormesg'=>"Player not found."]);
    } else if ($method=='PUT') {
        move_piece($piece_id,$cordinet_x, $input['x'],$input['y'],$input['token']);
    }    


}

function handle_moves($method, $request, $input)
{
    if ($method == 'GET') {
        // Example: Fetch and display legal moves
        //show_legal_moves();
    } elseif ($method == 'POST') {
        // Example: Add a new move (or other operations defined in moves.php)
        //add_move($input);
    } else {
        header("HTTP/1.1 405 Method Not Allowed");
        print json_encode(['errormesg' => "Method $method not allowed for moves."]);
    }
}
?>