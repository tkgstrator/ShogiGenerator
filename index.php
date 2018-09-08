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
            <p class="lead">SFEN形式の局面データからSVG形式で盤面図を生成するウェブサービスです.SVG形式はPNG形式とは違い,ベクター画像なのでどれだけ拡大縮小しても画質が荒くならない利点があります.
            <br>
            </p>
            <div style="width:100%">
              <form action="./" method="get">
                <input type="text" name="sfen" placeholder="sfen"></input>
                <input type="submit" class="btn" value="局面図作成"></input>
              </form>
            </div>
            <script>
            var clipboard = new ClipboardJS('.btn');
            clipboard.on('success', function(e) {
                console.log(e);
            });
            clipboard.on('error', function(e) {
                console.log(e);
            });
            </script>
            <button class="btn copy" data-clipboard-target="#urlcode">埋め込みコードをコピー</button>
          </div>
        </div>
        <div class="row">
          <input type="text" id="urlcode"></input>
<script>
$(document).ready(function() {
    document.getElementById("urlcode").value = '<img src="https://shogi.mydns.jp/sfen.php'+location.search+'"/>';
});
          </script>
        </div>
        <div class="ref">
          <p class="lead"><a href="http://www.geocities.jp/ookami_maasa/shogizumen/">shogizumen.js</a>
          <p class="lead"><a href="https://shogi.zukeran.org/shogi-draw/">将棋局面図の画像作成(SVG,PNG)</a>
        </div>
      </div>
<?php
if(is_Null($_GET["sfen"])){
    $url = "https://shogi.mydns.jp/sfen.php?sfen=lnsgkgsnl%2F1r5b1%2Fppppppppp%2F9%2F9%2F9%2FPPPPPPPPP%2F1B5R1%2FLNSGKGSNL+b+-+1";
}else{
    $url = "https://shogi.mydns.jp/sfen.php?sfen=".urlencode($_GET['sfen']);
}
echo("<img src=".$url."\" width=\"30%\">");
?>
    </div>
  </body>
</html>
