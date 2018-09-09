<?php
// 将棋盤クラスを読み込み
require "shogi.php";

// インスタンスを生成
$board = new Board();

// Sfen値が空だったら適当に値を代入する
if(empty($_GET['sfen'])){
    $board->loadSfen("lnsgkgsnl/1r5b1/ppppppppp/9/9/9/PPPPPPPPP/1B5R1/LNSGKGSNL b - 1");
}else{
    $board->loadSfen($_GET['sfen']);
}
$board->View();
?>
