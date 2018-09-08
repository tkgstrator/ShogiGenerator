<?php
class Board{
    public function __construct(){
        $this->xml = new SimpleXMLElement('<svg xmlns="http://www.w3.org/2000/svg" width="600" height="550"></svg>');
        $board = $this->xml->addChild('g');
        $rect = $board->addChild('rect');
        $rect->addAttribute('x', '62');
        $rect->addAttribute('y', '37');
        $rect->addAttribute('width', '451');
        $rect->addAttribute('height', '451');
        $rect->addAttribute('fill', 'none');
        $rect->addAttribute('stroke-width', '3');
        $rect->addAttribute('stroke', '#000000');

        $text_n = array("１", "２", "３", "４", "５", "６", "７", "８", "９");
        $text_k = array("一", "二", "三", "四", "五", "六", "七", "八", "九");
        for($i=0; $i<9; $i++){
            $this->addLine(112.5+50*$i, 112.5+50*$i, 37.5, 487.5);
            $this->addLine(62.5, 512.5, 87.5+50*$i, 87.5+50*$i);
            $this->addText(487-50*$i, 28+2/3, $text_n[$i]);
            $this->addText(529.7, 70+50*$i, $text_k[$i]);
        }
    }
    public function View(){
        echo($this->xml->asXML());
    }

    public function addText($x, $y, $str){
        $board = $this->xml->addChild('text', $str);
        $board->addAttribute('x', $x);
        $board->addAttribute('y', $y);
        $board->addAttribute('font-size', '20');
        $board->addAttribute('font-family', '游明朝');
        $board->addAttribute('text-anchor', 'middle');
    }

    public function addLine($x1, $x2, $y1, $y2){
        $board = $this->xml->addChild('line');
        $board->addAttribute('x1', $x1);
        $board->addAttribute('y1', $y1);
        $board->addAttribute('x2', $x2);
        $board->addAttribute('y2', $y2);
        $board->addAttribute('stroke-width', '2');
        $board->addAttribute('stroke', '#000000');
    }

    public function addCapturePiece($pieces, $player){
        $b = $this->xml->addChild('g');

        if($player == 1){
            $trans = "translate(567.5, 485)";
        }else{
            $trans = "translate(32.5, 40) scale(-1, -1)";
        }

        $b->addAttribute('transform', $trans);

        foreach(array_reverse($pieces) as $key => $p){
            switch($p){
            case "p":
                $text = "歩";
                break;
            case "l":
                $text = "香";
                break;
            case "n":
                $text = "桂";
                break;
            case "r":
                $text = "飛";
                break;
            case "b":
                $text = "角";
                break;
            case "g":
                $text = "金";
                break;
            case "s":
                $text = "銀";
                break;
            case "1":
                $text = "一";
                break;
            case "2":
                $text = "二";
                break;
            case "3":
                $text = "三";
                break;
            case "4":
                $text = "四";
                break;
            case "5":
                $text = "五";
                break;
            case "6":
                $text = "六";
                break;
            case "7":
                $text = "七";
                break;
            case "8":
                $text = "八";
                break;
            case "9":
                $text = "九";
                break;
            case "後":
                $text = "後";
                break;
            case "先":
                $text = "先";
                break;
            case "手":
                $text = "手";
                break;
            case " ":
                $text = " ";
                break;
            }
            // var_dump($this->black);
            $black = $b->addChild('text', $text);
            $black->addAttribute('x', '0');
            $black->addAttribute('y', ($key-strlen($this->black))*(32+1/7)*(-1));
            $black->addAttribute('font-size', 32+1/7);
            $black->addAttribute('font-family', '游明朝');
            $black->addAttribute('text-anchor', 'middle');
        }
        if($player == 1){
            $pos = ($key-strlen($this->black))*(32+1/7)*(-1);
            $poly = $b->addChild('polygon');
            $point = "0,".($pos-65)." 11.5,".($pos-60)." 15,".($pos-35)." -15,".($pos-35)." -11.5,".($pos-60);
            $poly->addAttribute('points', $point);
        }else{
            $pos = ($key-strlen($this->white))*(32+1/7)*(-1);
            $poly = $b->addChild('polygon');
            $point = "0,".($pos-65)." 11.5,".($pos-60)." 15,".($pos-35)." -15,".($pos-35)." -11.5,".($pos-60);
            $poly->addAttribute('points', $point);
            $poly->addAttribute('fill', 'none');
            $poly->addAttribute('stroke', '#000000');
            $poly->addAttribute('stroke-width', 2);
        }
    }

    public function addInfo(){
        $b = $this->xml->addChild('');
        // $b->addAttribute('transform', $trans)
    }

    public function addEval($eval){
        $board = $this->xml->addChild('g');
        $text = "評価値 ".substr($eval,0, -1);
        $eval = $board->addChild('text', $text);
        $eval->addattribute('x', '62');
        $eval->addattribute('y', 520);
        $eval->addattribute('font-size', 28);
        $eval->addAttribute('font-family', '游明朝');
    }

    public function loadSfen($sfen){
        $board = array();
        $move = explode(" ", $sfen);

        // 対局情報を取得
        $this->turn = $move[1];
        $this->count = $move[3];
        $this->lastmove = $move[4];
        $this->eval = $move[5];

        // 持ち駒情報を保存
        $this->black = array("先", "手", " ");
        $this->white = array("後", "手", " ");

        // 持ち駒の数を数えるんだけど、関数がダサい
        foreach(str_split($move[2]) as $piece){
            if(is_numeric($piece)){
                $num = $piece;
                continue;
            }
            if(ctype_lower($piece)){
                array_push($this->white, $piece);
                if($num != 0){
                    array_push($this->white, $num);
                    $num = 0;
                }
            }else{
                array_push($this->black, mb_strtolower($piece));
                if($num != 0){
                    array_push($this->black, $num);
                    $num = 0;
                }
            }
        }

        $line = array();

        foreach(str_split($move[0]) as $val){
            if($val === "+"){
                $isPromotion = True;
                continue;
            }
            if(ctype_alnum($val)){
                if(is_numeric($val)){
                    for($i=0; $i<$val; $i++){
                        array_push($line, "");
                    }
                }else{
                    if($isPromotion){
                        array_push($line, "+".$val);
                        $isPromotion = False;
                    }else{
                        array_push($line, $val);
                    }
                }
            }
            if($val === "/"){
                array_push($board, $line);
                $line = array();
            }
        }
        array_push($board, $line);

        $this->addPiece($board);
        $this->addCapturePiece($this->black, 1);
        $this->addCapturePiece($this->white, 0);
        
        // 評価値情報があれば表示する
        if(!is_Null($this->eval)){
            $this->addEval($this->eval);
        }
    }

    public function AddPiece($pieces){
        foreach($pieces as $m => $line){
            foreach($line as $n => $p){
                if(!empty($p)){
                    $tmp = $p;
                    switch(mb_strtolower($tmp)){
                    case "p":
                        $text = "歩";
                        break;
                    case "l":
                        $text = "香";
                        break;
                    case "n":
                        $text = "桂";
                        break;
                    case "r":
                        $text = "飛";
                        break;
                    case "b":
                        $text = "角";
                        break;
                    case "g":
                        $text = "金";
                        break;
                    case "s":
                        $text = "銀";
                        break;
                    case "k":
                        $text = "玉";
                        break;
                    case "+p":
                        $text = "と";
                        break;
                    case "+l":
                        $text = "杏";
                        break;
                    case "+n":
                        $text = "圭";
                        break;
                    case "+r":
                        $text = "龍";
                        break;
                    case "+b":
                        $text = "馬";
                        break;
                    case "+s":
                        $text = "全";
                        break;
                    default:
                        $text = "あ";
                        break;
                    }
                    $g = $this->xml->addChild('g');
                    // 小文字なら相手の駒なので反転させる
                    // 成り駒だと記号が入っていて正しく判定できないので最後の文字だけ見る処理を入れてある
                    if(ctype_lower($p[strlen($p) - 1])){
                        // ここの書き方がダサいので誰か改善案求む
                        $trans = "translate(".(87.5+50*$n).",".(62.5+50*$m).") scale(-1, -1)";
                    }else{
                        $trans = "translate(".(87.5+50*$n).",".(62.5+50*$m).")";
                    }

                    // 小要素に設定を突っ込んでいく
                    // この辺はもっと柔軟に変えられるようにしてもいいかも
                    $g->addAttribute('transform',$trans);
                    $piece = $g->addChild('text', $text);
                    $piece->addAttribute('fill', '#000000');
                    $piece->addAttribute('dy', '16');
                    $piece->addAttribute('font-size', '41');
                    // 最終手の強調表示
                    if(10*(9-$n)+($m+1) == $this->lastmove){
                        $piece->addAttribute('font-family', 'YuGothicB');
                        $piece->addAttribute('font-weight', 'bold');
                    }else{
                        $piece->addAttribute('font-family', '游明朝');
                    }
                    $piece->addAttribute('text-anchor', 'middle');
                }
            }
        }
    }
}
$board = new Board();

if(empty($_GET['sfen'])){
    $board->loadSfen("lnsgkgsnl/1r5b1/ppppppppp/9/9/9/PPPPPPPPP/1B5R1/LNSGKGSNL b - 1");
}else{
    $board->loadSfen($_GET['sfen']);
}
header('Content-type: image/svg+xml');
$board->View();
?>
