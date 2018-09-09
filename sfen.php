<?php
require "./shogi.php";

$board = new Board($_GET['type'] == "on" ? True : False);
$board->loadSfen($_GET['sfen'], $_GET['lmv'], $_GET['eval']);
$board->View();
?>
