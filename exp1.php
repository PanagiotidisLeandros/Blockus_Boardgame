<!DOCTYPE html>
<html>
<body>
<?<php>
$x;
$y=array([0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0],[0,0,0,0,0]);
$point=0;
$rot=0;
//checks to see every line in a 5x5 grid to determine the dimensions 
//of the shape
//After it checks one line, it calls the function next_line() to 
//start access the next line
//Currently uses a set value, need to find a way to feed it user input
function line_check($line){
switch($line){
case 1:
	$GLOBALS["y"][0][0]=1;
    break;
case 10:
	$GLOBALS["y"][1][1]=1;
    break;
case 11:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][1][1]=1;
    break;    
case 100:
	$GLOBALS["y"][2][2]=1;
	break;
case 101:
	$GLOBALS["y"][0][0]=1;
	$GLOBALS["y"][2][2]=1;
	break;
case 110:
	$GLOBALS["y"][1][1]=1;
	$GLOBALS["y"][2][2]=1;
	break;    
case 111:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][1][1]=1;
	$GLOBALS["y"][2][2]=1;
	break;
case 1000:
	$GLOBALS["y"][3][3]=1;
    break;
case 1001:
	$GLOBALS["y"][0][0]=1;
	$GLOBALS["y"][3][3]=1;
    break;
case 1010:
	$GLOBALS["y"][1][1]=1;
	$GLOBALS["y"][3][3]=1;
    break;    
case 1011:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][1][1]=1;
	$GLOBALS["y"][3][3]=1;
    break;
case 1100:
	$GLOBALS["y"][2][2]=1;
	$GLOBALS["y"][3][3]=1;
    break;
case 1101:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][2][2]=1;
	$GLOBALS["y"][3][3]=1;
    break;
case 1110:
	$GLOBALS["y"][1][1]=1;
    $GLOBALS["y"][2][2]=1;
	$GLOBALS["y"][3][3]=1;
    break;    
case 1111:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][1][1]=1;
    $GLOBALS["y"][2][2]=1;
	$GLOBALS["y"][3][3]=1;
    break;
case 10000:
	$GLOBALS["y"][4][4]=1;
    break;
case 10001:
	$GLOBALS["y"][0][0]=1;
	$GLOBALS["y"][4][4]=1;
    break;
case 10010:
	$GLOBALS["y"][1][1]=1;
	$GLOBALS["y"][4][4]=1;
    break;    
case 10011:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][1][1]=1;
	$GLOBALS["y"][4][4]=1;
    break;    
case 10100:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][2][2]=1;
	$GLOBALS["y"][4][4]=1;
    break;    
case 10101:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][2][2]=1;
	$GLOBALS["y"][4][4]=1;
    break;
case 10110:
	$GLOBALS["y"][1][1]=1;
    $GLOBALS["y"][2][2]=1;
    $GLOBALS["y"][4][4]=1;
    break;        
case 10111:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][1][1]=1;
    $GLOBALS["y"][2][2]=1;
	$GLOBALS["y"][4][4]=1;
    break;
case 11000:
	$GLOBALS["y"][3][3]=1;
	$GLOBALS["y"][4][4]=1;
    break;
case 11001:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][3][3]=1;
	$GLOBALS["y"][4][4]=1;
    break;
case 11010:
	$GLOBALS["y"][1][1]=1;
    $GLOBALS["y"][3][3]=1;
	$GLOBALS["y"][4][4]=1;
    break;    
case 11011:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][1][1]=1;
    $GLOBALS["y"][3][3]=1;
	$GLOBALS["y"][4][4]=1;
    break;
case 11100:
	$GLOBALS["y"][2][2]=1;
    $GLOBALS["y"][3][3]=1;
	$GLOBALS["y"][4][4]=1;
    break;    
case 11101:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][2][2]=1;
    $GLOBALS["y"][3][3]=1;
	$GLOBALS["y"][4][4]=1;
    break;        
case 11110:
	$GLOBALS["y"][1][1]=1;
    $GLOBALS["y"][2][2]=1;
    $GLOBALS["y"][3][3]=1;
	$GLOBALS["y"][4][4]=1;
    break;        
case 11111:
	$GLOBALS["y"][0][0]=1;
    $GLOBALS["y"][1][1]=1;
    $GLOBALS["y"][2][2]=1;
    $GLOBALS["y"][3][3]=1;
	$GLOBALS["y"][4][4]=1;
    break;    
default:
	echo $line." wrong number<br>";
    break;
}
global $point;
$point++;
if($point<5){
next_line($point);}
else{
$point=0;
}
}

//This function is purely for testing purposes, it's going to be
//changed almost entirely later.
//Currently just makes a set next line
//Need to find a way to link it with the user's selected piece
function next_line($counter){
$hyper=0;
$n=1;
while($hyper<$counter){
	$n=$n*10;
    $hyper++;
}
line_check($n);
}

function lastcheck(){
$i=0;
while ($i < 5) {
$j=0;
echo "(";
	while ($j<5){	
        echo $GLOBALS["y"][$i][$j];
        $j++;
        if($j<5){
        	echo ",";
		}
		}
echo ")<br>";
$i++;                    
}
}
function rotate(){
	$i=0;
	while ($i < 3) {
	$j=0;
		while ($j<4-$i){	
			$temp=$GLOBALS["y"][$i][$j];
			$GLOBALS["y"][$i][$j]=$GLOBALS["y"][4-$j][$i];
			$GLOBALS["y"][4-$j][$i]=$GLOBALS["y"][4-$i][4-$j];
			$GLOBALS["y"][4-$i][4-$j]=$GLOBALS["y"][$j][4-$i];
			$GLOBALS["y"][$j][4-$i]=$temp;
			$j++;
			}
	$i++;                    
	}	
}
line_check(1);
//lastcheck() is purely for test purposes, it shows you the final array
lastcheck();
</php>
</body>
</html>