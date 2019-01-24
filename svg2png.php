<?php
function isSfenValid($sfen){
    // スラッシュが8つない場合は不正
    if(substr_count($sfen, "/") !== 8){
        return False;
    }

    // 指定文字列
    $chars = "123456789plnrbgskw+-/";

    // それでない文字がきたら不正
    foreach(urldecode($sfen) as $key){
        if(strpos($char, mb_strtolower($key)) === False){
            return False;
        }
    }
    return True;
}

session_start();

if(!isSfenValid($_SESSION['sfen'])){
    $_SESSION['sfen'] = "lnsgkgsnl/1r5b1/ppppppppp/9/9/9/PPPPPPPPP/1B5R1/LNSGKGSNL b -1"; 
    $_SESSION['type'] = "";
    $_SESSION['lmv'] = "";
    $_SESSION['eval'] = "";
    $_SESSION['tsume'] = "";
}

$array = array(
    'sfen' => $_SESSION['sfen'],
    'type' => $_SESSION['type'],
    'lmv' => $_SESSION['lmv'],
    'eval' => $_SESSION['eval'],
    'tsume' => $_SESSION['tsume'],
);

// URLエンコード
$param = http_build_query($array);
$url = "https://shogi.mydns.jp/board?".$param;

// 一旦ローカルでファイルを作成してそれを読み込む
$name = urlencode($_SESSION['sfen']."type=".$_SESSION['type']."lmv=".$_SESSION['lmv']."eval=".$_SESSION['eval']);
$svg = dirname(__FILE__)."/svg/".$name.".svg";
$png = dirname(__FILE__)."/png/".$name.".png";


file_put_contents($svg, file_get_contents($url));
exec("convert -bordercolor '#000000' -border 8x8 -density 144 -resize 50% $svg $png");
header("content-type: image/png");
echo(file_get_contents($png));

// // 同一局面が存在する場合はそのまま画像を返す
// if(file_exists($svg)){
//     // file_put_contents($svg, file_get_contents($url));
//     header("Content-Type: image/png");
//     echo(file_get_contents($png));
//     // echo(file_get_contents($url));
//     // echo($_SESSION['sfen']);
// }else{
//     file_put_contents($svg, file_get_contents($url));
//     exec("convert -bordercolor '#000000' -border 8x8 -density 144 -resize 50% $svg $png");
//     header("content-type: image/png");
//     // echo($_SESSION['sfen']);
//     echo(file_get_contents($png));
// }
