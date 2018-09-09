<?php
class Board{
    public function __construct($type=False){
        $this->type = $type;
        $this->xml = new SimpleXMLElement('<svg xmlns="http://www.w3.org/2000/svg" width="600" height="550"></svg>');
        $this->addBoard();
    }


    public function addBoard(){
        $this->board = $this->xml->addChild('g');
        $rect = $this->board->addChild('rect');
        $rect->addAttribute('x', '62');
        $rect->addAttribute('y', '37');
        $rect->addAttribute('width', '451');
        $rect->addAttribute('height', '451');
        $rect->addAttribute('fill', 'none');
        $rect->addAttribute('stroke-width', '3');
        $rect->addAttribute('stroke', '#000000');

        $text_n = array("１", "２", "３", "４", "５", "６", "７", "８", "９");
        !$this->type ? $text_n = $text_n : $text_n = array_reverse($text_n);
        $text_k = array("一", "二", "三", "四", "五", "六", "七", "八", "九");
        !$this->type ? noap : $text_k = array_reverse($text_k);

        // 盤の文字とかを書く
        for($i=0; $i<9; $i++){
            $this->addLine(112.5+50*$i, 112.5+50*$i, 37.5, 487.5);
            $this->addLine(62.5, 512.5, 87.5+50*$i, 87.5+50*$i);
            $this->addText(487-50*$i, 28+2/3, $text_n[$i]);
            $this->addText(529.7, 70+50*$i, $text_k[$i]);
        }
    }

    
    // 将棋盤の文字を書く関数
    public function addText($x, $y, $str){
        $board = $this->board->addChild('text', $str);
        $board->addAttribute('x', $x);
        $board->addAttribute('y', $y);
        $board->addAttribute('font-size', '20');
        $board->addAttribute('font-family', '游明朝');
        $board->addAttribute('text-anchor', 'middle');
    }
    
    
    // 将棋盤のラインを書く関数
    public function addLine($x1, $x2, $y1, $y2){
        $board = $this->board->addChild('line');
        $board->addAttribute('x1', $x1);
        $board->addAttribute('y1', $y1);
        $board->addAttribute('x2', $x2);
        $board->addAttribute('y2', $y2);
        $board->addAttribute('stroke-width', '2');
        $board->addAttribute('stroke', '#000000');
    }
    
    
    // 持ち駒を書く関数なのだが、ダサいのでなんとかしたい...
    public function addCapturePiece($pieces, $player){
        $b = $this->xml->addChild('g');
        if($player == 1){
            $trans = "translate(567.5, 485)";
        }else{
            $trans = "translate(32.5, 40) scale(-1, -1)";
        }

        $b->addAttribute('transform', $trans);
        // 持ち駒情報は反転しないと違和感がある並びになってしまう
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
            $black = $b->addChild('text', $text);
            $black->addAttribute('x', '0');
            $black->addAttribute('y', ($key-strlen($this->black))*(32+1/7)*(-1));
            $black->addAttribute('font-size', 32+1/7);
            $black->addAttribute('font-family', '游明朝');
            $black->addAttribute('text-anchor', 'middle');
        }
        if($player == True){
            $pos = ($key-strlen($this->black))*(32+1/7)*(-1);
            $poly = $b->addChild('polygon');
            $point = "0,".($pos-65)." 11.5,".($pos-60)." 15,".($pos-35)." -15,".($pos-35)." -11.5,".($pos-60);
            $poly->addAttribute('points', $point);
            if($this->type){
                $poly->addAttribute('fill', 'none');
                $poly->addAttribute('stroke', '#000000');
                $poly->addAttribute('stroke-width', 2);
            }
        }else{
            $pos = ($key-strlen($this->white))*(32+1/7)*(-1);
            $poly = $b->addChild('polygon');
            $point = "0,".($pos-65)." 11.5,".($pos-60)." 15,".($pos-35)." -15,".($pos-35)." -11.5,".($pos-60);
            $poly->addAttribute('points', $point);
            if(!$this->type){
                $poly->addAttribute('fill', 'none');
                $poly->addAttribute('stroke', '#000000');
                $poly->addAttribute('stroke-width', 2);
            }
        }
    }
    
    
    // 盤面に駒を表示する関数
    public function AddPiece($pieces){
        $pb = $this->xml->addChild('g');
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
                    $g = $pb->addChild('g');
                    // 小文字なら相手の駒なので反転させる
                    // 成り駒だと記号が入っていて正しく判定できないので最後の文字だけ見る処理を入れてある

                    if($this->type xor ctype_lower($p[strlen($p) - 1])){
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
                    if(10*(9-$n)+($m+1) == $this->lmv){
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
    public function View(){
        header('Content-type: image/svg+xml');
        echo($this->xml->asXML());
    }

    // 手数とか表示する関数なのだが未実装
    public function addInfo(){
        $b = $this->xml->addChild('');
    }

    // 評価値情報を載せている
    public function addEval($eval){
        $board = $this->xml->addChild('g');
        $text = "評価値 ".$eval;
        $eval = $board->addChild('text', $text);
        $eval->addattribute('x', '62');
        $eval->addattribute('y', 520);
        $eval->addattribute('font-size', 28);
        $eval->addAttribute('font-family', '游明朝');
    }

    // Sfen形式のデータを読み込む関数
    public function loadSfen($sfen, $lmv, $eval){
        
        // 盤面の駒情報を保存
        $board = array();
        $this->type ? $this->lmv = 110 - $lmv: $this->lmv = $lmv;

        $move = explode(" ", $sfen);
        
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

        // 盤面を9x9の配列情報として保存していく
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
                !$this->type ? array_push($board, $line) : array_unshift($board, array_reverse($line));
                $line = array();
            }
        }
        !$this->type ? array_push($board, $line) : array_unshift($board, array_reverse($line));

        $this->addPiece($board);
        $this->addCapturePiece($this->black, !$this->type);
        $this->addCapturePiece($this->white, $this->type);
        
        // 評価値情報があれば表示する
        !empty($eval) ? $this->addEval($eval) : nope;
    }
}
