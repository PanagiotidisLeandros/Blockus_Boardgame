<!DOCTYPE html>
<html>
<body>
<?<php>
//Score variable
$c=0;
//Array with every piece
$z=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21);
//To be called at the end of every move to remove the appropriate piece from the array and increase the score
function end_of_move($ex){
global $c;
global $z;
$c=$c+$ex;
$a=0;
if($ex>0){
$a=$ex-1;
}
if ($ex==1){
  $c++;
  }
  else if ($ex==2){
  $c=$c+2;
  }
  else if($ex<5){
  $c=$c+3;
  }
  else if($ex<10){
  $c=$c+4;
  }
  else{
  $c=$c+5;
  }
unset($z[$a]);
}
//To be called at the end of the game to remove points from score for every piece that wasn't played
function end_of_game(){
global $z;
global $c;
foreach ($z as $pls){
if ($pls==1){
$c--;
}
else if ($pls==2){
$c=$c-2;
}
else if($pls<5){
$c=$c-3;
}
else if($pls<10){
$c=$c-4;
}
else{
$c=$c-5;
}
}

}

/*  FOR TESTING ONLY
for ($i = 1; $i <= 11; $i++) {
  end_of_move($i);
}
end_of_game();
echo "Final score: $c";
*/
</php>
</body>
</html>