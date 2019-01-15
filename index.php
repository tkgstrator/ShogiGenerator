<html>
  <title>局面ジェネレータ</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">
    <link rel="stylesheet" href="./css/wireframe.css">
    <link rel="stylesheet" href="./css/shogi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery&#45;3.3.1.js" integrity="sha256&#45;2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script> -->
    <script src="./js/clipboard.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </head>
  <body class="bg-light">
    <div class="pt-5 text-center">
      <div class="container">
        <div class="row">
          <div class="col-md-9 mx-auto">
            <h1 class="display-1">局面ジェネレータ
              <br>
              <br>
            </h1>
            <p class="lead">SFEN形式の局面データからSVG形式で盤面図を生成するウェブサービスです.
            <br>
            </p>
            <div style="width:100%">
              <form action="./" method="post">
                <input type="text" name="sfen" placeholder="局面情報入力"></input>
                <input type="text" name="lmv" placeholder="最終手"></input>
                <input type="text" name="eval" placeholder="評価値"></input>
                <p>
                    <label><input type="checkbox" name="type"><span>先後反転する</span></label></input>
                    <label><input type="checkbox" name="tsume"><span>詰将棋</span></label></input></p>
                <input type="submit" class="btn" value="局面図作成"></input>
              </form>
            </div>
          </div>
        </div>
      </div>
<?php
session_start();

$_SESSION['sfen'] = !empty($_POST['sfen']) ? $_POST['sfen'] : "lnsgkgsnl/1r5b1/ppppppppp/9/9/9/PPPPPPPPP/1B5R1/LNSGKGSNL b - 1";
$_SESSION['type'] = !empty($_POST['type']) ? $_POST['type'] : noap;
$_SESSION['lmv'] = !empty($_POST['lmv']) ? $_POST['lmv'] : noap;
$_SESSION['eval'] = !empty($_POST['eval']) ? $_POST['eval'] : "";
$_SESSION['tsume'] = !empty($_POST['tsume']) ? $_POST['tsume'] == "on" ? True : False : False;

$array = array(
    'sfen' => $_SESSION['sfen'],
    'type' => $_SESSION['type'],
    'lmv' => $_SESSION['lmv'],
    'eval' => $_SESSION['eval'],
    'tsume' => $_SESSION['tsume'],
);
$param = http_build_query($array);
$url = "https://shogi.mydns.jp/board?".$param;
$str = "<img src=\"".$url."\" width=\"40%\">";
echo($str);
?>
    </div>
  </body>
</html>
