<?php
function show_status() {
	global $mysqli;
	
	$sql = 'select * from game_status';
	$st = $mysqli->prepare($sql);
	
	$st->execute();
	$res = $st->get_result();
	
	// header ('Content-type: application/json');
	// print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
	header('Content-type: application/json');
	print json_encode(read_status(), JSON_PRETTY_PRINT);

    
}

function check_abort() {
	global $mysqli;
	
	$sql = "update game_status set status='aborded', result=if(p_turn='W','B','W'),p_turn=null where p_turn is not null and last_change<(now()-INTERVAL 5 MINUTE) and status='started'";
	$st = $mysqli->prepare($sql);
	$r = $st->execute();
}

function update_game_status() {
	global $mysqli;
	
	$status = read_status();
	
	
	$new_status=null;
	$new_turn=null;
	
	// $st3=$mysqli->prepare('select count(*) as aborted from players WHERE last_action< (NOW() - INTERVAL 5 MINUTE)');
	// $st3->execute();
	// $res3 = $st3->get_result();
	// $aborted = $res3->fetch_assoc()['aborted'];
	// if($aborted>0) {
	// 	$sql = "UPDATE players SET username=NULL, token=NULL WHERE last_action< (NOW() - INTERVAL 5 MINUTE)";
	// 	$st2 = $mysqli->prepare($sql);
	// 	$st2->execute();
	// 	if($status['status']=='started') {
	// 		$new_status='aborted';
	// 	}
	// }

	
	$sql = 'select count(*) as c from players where username is not null';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$active_players = $res->fetch_assoc()['c'];
	
	
	switch($active_players) {
		case 0: $new_status='not active'; break;
		case 1: $new_status='initialized one player connected'; break;
		case 2: $new_status='initialized two players connected'; break;
		case 3: $new_status='initialized three players connected'; break;			
		case 4: $new_status='started'; 
				if($status['p_turn']==null) {
					$new_turn='B'; // It was not started before...
				}
				break;
	}

	$sql = 'update game_status set g_status=?, p_turn=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ss',$new_status,$new_turn);
	$st->execute();
	
	
	
}

function read_status() {
	global $mysqli;
	$sql = 'select * from game_status';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	return($res->fetch_all(MYSQLI_ASSOC));
}

?>
