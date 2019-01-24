<?php
require "./shogi.php";

session_start();

// SVG画像生成用
if(empty($_GET['sfen'])){
    $board = new Board($_SESSION['type'] == "on" ? True : False);
    $board->loadSfen($_SESSION['sfen'], $_SESSION['lmv'], $_SESSION['eval'], $_SESSION['tsume']);
    $board->View();
} else {
    $board = new Board($_GET['type'] == "on" ? True : False);
    $board->loadSfen($_GET['sfen'], $_GET['lmv'], $_GET['eval'], $_GET['tsume']);
    $board->View();
}
?>
