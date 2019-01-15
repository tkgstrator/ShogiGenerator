<?php
require "./shogi.php";

session_start();

if(empty($_GET['sfen'])){
    // SVG画像生成用
    $board = new Board($_SESSION['type'] == "on" ? True : False);
    $board->loadSfen($_SESSION['sfen'], $_SESSION['lmv'], $_SESSION['eval'], $_SESSION['tsume']);
    $board->View();
}else{
    // PNG画像生成用
    $board = new Board($_SESSION['type'] == "on" ? True : False);
    $board->loadSfen($_SESSION['sfen'], $_SESSION['lmv'], $_SESSION['eval'], $_SESSION['tsume']);
    $board->View();
}
?>
