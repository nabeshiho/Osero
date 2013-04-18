<?php
class setting{
protected $kaisuu;	//ターン
protected $stone;	//盤を構成する配列
protected $flag;	//ゲーム終了フラグ

public function __construct() {
	$this->flag =false;
    $this->kaisuu = 1;
    $this->stone = array();
}

//名前を入力
function name(){
	echo "\n".'1人目の名前を入力して下さい。';
	echo "\n".'名前：';
	$input_name1 = trim(fgets(STDIN));

	echo "\n".'2人目の名前を入力して下さい。';
	echo "\n".'名前：';
	$input_name2 = trim(fgets(STDIN));

	echo "\n".'先手(黒●):'.$input_name1."\n".'後手(白○):'.$input_name2;
	echo "\n\n".'ゲームを開始します。'."\n";
	
	$this->f();
}

//初期設定
function f(){
$this->flag =false;
$suu=0;
for($n_x = 0; $n_x <= 7; ++$n_x){
  for($n_y = 0; $n_y <= 7; ++$n_y){
	
	if     ($n_x===3 and $n_y===3){
		$this->stone[$suu]=0;
	
	}elseif($n_x===4 and $n_y===4){
		$this->stone[$suu]=0;
	
	}elseif($n_x===3 and $n_y===4){
		$this->stone[$suu]=1;
		
	}elseif($n_x===4 and $n_y===3){
		$this->stone[$suu]=1;
	
	}else{
		$this->stone[$suu]=2;
	}
	++$suu;
  	}
  }
   echo '0～7までの数字を「縦,横」の順に入力して下さい。'."\n";
   $this->set();
}

//盤に石をセットする
function set(){
	echo '0 1 2 3 4 5 6 7 '."\n";
	for($a=0; $a <= 63; ++$a){
		switch(true){
	 		case $this->stone[$a]===0:
        	echo '●';
        	break;

     		case $this->stone[$a]===1:
        	echo '○';
        	break;
        	
        	default:
        	echo '□';
   		}
    
    	if((($a+1)%8)===0){
        $b = ($a+1)/8 -1;
        echo ' '.$b."\n";
    	}
    }
    if($this->flag === false){
    	$this->play_game();
   	}
}

//ゲームをする
function play_game(){
	$p = ($this->kaisuu -1) % 2;
	if($p===0){
		$p_ms = '黒';
	}else{
		$p_ms = '白';
	}
	echo "\n".$this->kaisuu.' : '.$p_ms.'の番です。'."\n";
	
    echo "\n".'縦 : ';
	$input_x = trim(fgets(STDIN));
	
	echo "\n".'横 : ';
	$input_y = trim(fgets(STDIN));
	
	echo "\n";
	
	$answer = $input_x+($input_y*8);
	
	if($input_x<0 or $input_x>7 or $input_y<0 or $input_y>7 or is_numeric($input_x)===false or is_numeric($input_y)===false){
	echo '0以上7以下の数値を入力して下さい。'."\n";
	$this->set();
	
	}else{
		if($this->stone[$answer]!==2){
			echo '石のあるところには置くことが出来ません。'."\n";
			$this->set();
		}else{
			if($this->push($p,$input_x,$input_y)){
				echo '1枚以上相手の石を裏返せる場所に打ってください。'."\n";
				$this->set();
	        }else{
	        	// 裏返しの処理へ
		    	$this->turn($input_x,$input_y,$answer);
			}
		}
	}
	}
  
    //色判定
    function turn($x,$y,$ans){
    $player = ($this->kaisuu-1) % 2;

    $this->stone[$ans]=$player;
	$this->re_stone($x,$y,$player);
	}
	
	// 裏返し
	function re_stone($x,$y,$player){
	$this->kaisuu = $this->kaisuu + 1;
    for ($d = -1; $d <= 1; ++$d) {      // 上下方向
        for ($e = -1; $e <= 1; ++$e) {  // 左右方向
            if ($d == 0 && $e == 0) continue; 
            $count = $this->re_suu($player, $x, $y, $d, $e);
            for ($i = 1; $i <= $count; $i++) {
            	$n_x = $x+$i*$d;
            	$n_y = $y+$i*$e;
            	$n_s = $n_x+($n_y*8);
            	if($this->re_wall($n_x,$n_y)===true){
                $this->stone[$n_s] = $player; // 裏返す
                }
            }
        }
    }
		$this->r_fin();
	}
	
	// 裏返し 数を数える
	function re_suu($player, $x, $y, $d, $e){
	 if($player === 0){
	 	$aite = 1;
	 }else{
		$aite = 0;
	 }
	
	if($this->re_wall($x,$y)===true){
    for($k =1; $this->stone[($x+($k*$d))+(($y+($k*$e))*8)] === $aite; ++$k) {
    };
    
    if ($this->stone[($x+($k*$d))+(($y+($k*$e))*8)] === $player) {                             
        return $k-1;
    } else {
        return 0;
    }
    }
   	}
		
	// 裏返し 壁判定
	function re_wall($x,$y){
	$wall = false;
		if($x >= 0 and $x < 8 and $y >= 0 and $y < 8){
		$wall = true;
		}
	return $wall;
	}
	
	//石を打って良いかの判定
	function push($player, $x, $y){
      for ($d = -1; $d <= 1; ++$d){
        for ($e = -1; $e <= 1; ++$e) {
        	if($d===0 and $e===0){	continue;}
    		if ($this->re_suu($player, $x, $y, $d, $e)!==0){
    		return false;
    		}
    	}
      }
    return true;
	}
	
	//終了判定
	function r_fin(){
	if($this->kaisuu===61){
	$this->flag =true;
	echo '---終了---'."\n";
	
	$black_j =0;
	$white_j =0;
	
	for($i=0; $i <= 63; ++$i){
	if($this->stone[$i]===0){
        ++$black_j;
    }
    if($this->stone[$i]===1){
        ++$white_j;
    }
   }
	if($black_j>$white_j){
	$judge = '黒の勝利です！';
	}elseif($black_j<$white_j){
	$judge = '白の勝利です！';
	}else{
	$judge = '引き分けです！';
	}

	echo '黒：'.$black_j."\n";
	echo '白：'.$white_j."\n";
	echo "\n".$judge.''."\n\n";
	$this->set();
    
    }else{
    $this->set();
	}
	}
}

$next = new setting;
$next->name();
?>
