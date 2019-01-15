<?php
require "./shogi.php";

session_start();

$board = new Board($_SESSION['type'] == "on" ? True : False);
$board->loadSfen($_SESSION['sfen'], $_SESSION['lmv'], $_SESSION['eval'], $SESSION['tsume']);
$board->View();
?>
