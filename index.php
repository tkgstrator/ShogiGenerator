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
              <form action="./" method="get">
                <input type="text" name="sfen" placeholder="局面情報入力"></input>
                <input type="text" name="lmv" placeholder="最終手"></input>
                <input type="text" name="eval" placeholder="評価値"></input>
                <p><label><input type="checkbox" name="type"><span>先後反転する</span></label></input></p>
                <input type="submit" class="btn" value="局面図作成"></input>
              </form>
            </div>
          </div>
        </div>
      </div>
<?php
    $array = array(
        'sfen' => $_GET['sfen'],
        'type' => $_GET['type'],
        'lmv' => $_GET['lmv'],
        'eval' => $_GET['eval']);
    $param = http_build_query($array);
    $url = "https://shogi.mydns.jp/sfen?".$param;
$str = "<img src=\"".$url."\">";
echo($str);
?>
    </div>
  </body>
</html>
