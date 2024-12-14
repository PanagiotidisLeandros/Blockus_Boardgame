$( function(){
    draw_empty_board();
    fill_board();
    $('#blockus_reset').click(reset_board);
    $('#blockus_login').click(login_to_game);
    $('#move_duv');
    //game_status_update();
    $('#do_move').click( do_move);
}
);

var me={};
var game_status={};

function draw_empty_board() {
    var t='<table id="blockus_table">';
    for(var i=6;i>0;i--) {
        t += '<tr>';
        for(var j=1;j<7;j++) {
            t += '<td class="blockus_square"id="square_'+j+'_'+i+'">' + j +','+i+'</td>';
        }
        t+='</tr>';
    }
    t+='</table>';
    $('#blockus_board').html(t);
}

function fill_board() {
    $.ajax(
    {url: "blockus.php/board/",
	headers: {"X-Token": me.token},
    success: fill_board_by_data
    }
    );
}

function fill_board_by_data(data) {
    for(var i=0;i<data.length;i++) {
    var o = data[i];
    var id = '#square_'+ o.x +'_' + o.y;
    var c = (o.cell_status!= null)? o.cell_status: '';
    $(id).addClass(o.cell_status+'_square').html(c);


    //var im = c;
    
    //var im = (o.piece!=null)?'<img class="piece"
    //src="images/'+c+'.png">':'';
    //$(id).addClass(o.b_color+'_square').html(c); 
    }
}


function reset_board() {
    $.ajax(
    {url: "blockus.php/board/",
		headers: {"X-Token": me.token},
        method: 'post',
    success: fill_board_by_data
    }
    );
}

function login_to_game() {
	if($('#username').val()=='') {
		alert('You have to set a username');
		return;
	}
	var p_color = $('#pcolor').val();
	//draw_empty_board(p_color);
	fill_board();
	
	$.ajax({url: "blockus.php/players/"+p_color, 
			method: 'PUT',
			dataType: "json",
			headers: {"X-Token": me.token},
			contentType: 'application/json',
			data: JSON.stringify( {username: $('#username').val(), piece_color: p_color}),
			success: login_result,
			error: login_error});
}

function login_result(data) {
	me = data[0];
	$('#game_initializer').hide();
	update_info();
	//game_status_update();
}

function login_error(data,y,z,c) {
	var x = data.responseJSON;
	alert(x.errormesg);
}

function update_info(){
	$('#game_info').html("I am Player: "+me.piece_color+", my name is "+me.username +'<br>Token='+me.token+'<br>Game state: '+game_status.g_status+', '+ game_status.p_turn+' must play now.');
	
	
}

function game_status_update() {
	
	// clearTimeout(timer);
	$.ajax({url: "chess.php/status/", success: update_status,headers: {"X-Token": me.token} });
}

function update_status(data) {
	last_update=new Date().getTime();
	//var game_stat_old = game_status;
	game_status=data[0];
	update_info();
	clearTimeout(timer);
	if(game_status.p_turn==me.piece_color &&  me.piece_color!=null) {
		x=0;
		// do play
		if(game_stat_old.p_turn!=game_status.p_turn) {
			fill_board();
		}
		$('#move_div').show(1000);
		timer=setTimeout(function() { game_status_update();}, 15000);
	} else {
		// must wait for something
		$('#move_div').hide(1000);
		timer=setTimeout(function() { game_status_update();}, 4000);
	}
 	
}


function illigal_move_error(xhr, status, error) {
    let errorMessage = "Move is not valid.";
    
    // Try to extract the error message from the server response
    if (xhr.responseJSON && xhr.responseJSON.errormesg) {
        errorMessage = xhr.responseJSON.errormesg;
    } else if (xhr.responseText) {
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.errormesg) {
                errorMessage = response.errormesg;
            }
        } catch (e) {
            // If JSON parsing fails, keep the default message
        }
    }
    
    // Display the error message in an alert
    alert(errorMessage);
}




function update_legal_moves() {
    
    $.ajax({
        url: 'moves.php', 
        method: 'get',
        success: function(data) {
            // Check if the data is received successfully
            if (data) {
                // Log the raw data (could be a JSON string or array)
                console.log(data); // Outputs raw data to the browser's console
                
                // Optionally, display the raw data in the div (as a string)
                $('#legal_moves').html('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
            } else {
                $('#legal_moves').html('<p>No legal moves available.</p>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error: ", textStatus, errorThrown); // Log the error details
            $('#legal_moves').html('<p>Error retrieving legal moves.</p>');
        }
    });
}








function game_status_update() {
	
	//clearTimeout(timer);
	$.ajax({url: "blockus.php/status/", success: update_status,headers: {"X-Token": me.token} });
}


function do_move() {
    var s = $('#the_move').val();
    
    var a = s.trim().split(/[ ]+/);
    if (a.length != 4) {
        alert('Must give 4 numbers');
        return;
    }
	$.ajax({url: "blockus.php/board/piece/"+a[0]+'/'+a[1], 
		method: 'PUT',
		dataType: "json",
		contentType: 'application/json',
		data: JSON.stringify( {x: a[2], y: a[3]}),
		headers: {"X-Token": me.token},
		success: move_result,
		error: illigal_move_error});
}

function move_result(data){
	fill_board_by_data(data);
	//$('#move_div').hide(1000);
}


function update_game_result() {
    
$.ajax({
    url: 'blockus.php/status/', // Update this to match the status route
    method: 'GET', // Ensure the method is correct (GET)
    success: function(data) {
        // Check if the data is received successfully
        if (data) {
            // Log the raw data (could be a JSON string or array)
            console.log(data); // Outputs raw data to the browser's console
            
            // Optionally, display the raw data in the div (as a string)
            $('#game_result').html('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
        } else {
            $('#game_result').html('<p>error</p>');
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log("AJAX Error: ", textStatus, errorThrown); // Log the error details
        $('#game_result').html('<p>Error retrieving game status .</p>');
    }
});
}