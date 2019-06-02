<?php
// mb_internal_encoding("UTF-8");
// mb_regex_encoding("UTF-8");

class Board{
    public function __construct(){
        $this->board = array_fill(0, 81, "-");
        $this->setPieces();
    }

    public function setPieces(){
        $sfen = str_split("lnsgkgsnl/1r5b1/ppppppppp/9/9/9/PPPPPPPPP/1B5R1/LNSGKGSNL");

        $offset = 0;
        foreach($sfen as $key => $value) {
            if($value == "/") {
                $offset -= 1;
                continue;
            }
            if(ctype_digit($value)){
                $offset += $value - 1;
                continue;
            }
            $this->board[$key + $offset] = $value;
        }
    }

    // KIFデータを整形する関数
    public function loadKif($url) {
        $kif = explode("\n", mb_convert_encoding(file_get_contents($url), "utf-8", "sjis"));
        foreach($kif as $value) {
            // コメントは無視
            if(strpos($value, '*') === false) {
                if(mb_substr_count($value, "/") == 1){
                    // 実際の指し手の部分だけを抽出する
                    $value = explode(" ", $value);
                    foreach($value as $move) {
                        if(mb_strlen($move) !== mb_strwidth($move)) {
                            $search = array_keys($this->table);
                            $replace = array_values($this->table);
                            $sfen = str_replace($search, $replace, $move);
                            if(strpos($sfen, "^") !== false) {
                                $sfen = str_replace("^", $prev, $sfen);
                            } else {
                                $prev = substr($sfen, 0, 2);
                            }
                            array_push($this->sfen, $sfen);
                        }
                    }
                }
            }
        }
    }

    public function PseudoSfen(){
        $json = array(
            "black" => "black",
            "white" => "white",
            "moves" => $this->sfen,
        );
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($json);
    }

    public function moveSfen($turn = 256){
        foreach($this->sfen as $key => $move) {
            // 数字が4つあれば駒台から打った駒ではない
            if(strlen(preg_replace("/[^1-9]+/u", "", $move)) == 4){
                $prom = strpos($move, "+");
                $move = preg_replace("/[^1-9]+/u", "", $move);

                // 座標変換
                $apos = (int)(substr($move, 1, 1))* 9 - (int)(substr($move, 0, 1));
                $bpos = (int)substr($move, 3, 1) * 9 - (int)substr($move, 2, 1);

                // 移動先の駒
                $piece = $this->board[$apos];

                if($piece !== "-") {
                    $piece = preg_replace("/[+]+/u", "", $piece);
                    // 先手が駒を取った
                    if(ctype_lower($piece)) {
                        $this->black[strtoupper($piece)] += 1;
                    }
                    // 後手が駒を取った
                    if(ctype_upper($piece)) {
                        $this->white[strtoupper($piece)] += 1;
                    }
                }
                // 成ったかどうか
                if($prom !== false) {
                    $this->board[$apos] = "+".$this->board[$bpos];
                } else {
                    $this->board[$apos] = $this->board[$bpos];
                }

                // 移動前は空にする
                $this->board[$bpos] = "-";

                // 駒台から駒を打ったとき
            } else {
                $piece = preg_replace("/[^A-Z]+/u", "", $move);
                $move = preg_replace("/[^1-9]+/u", "", $move);
                $apos = (int)(substr($move, 1, 1))* 9 - (int)(substr($move, 0, 1));
                $bpos = "**";
                if($key % 2 == 0){
                    $this->board[$apos] = $piece;
                    $this->black[$piece] -= 1;
                } else {
                    $this->board[$apos] = strtolower($piece);
                    $this->white[$piece] -= 1;
                }
            }
            // echo "Turn:".($key + 1).$bpos." to ".$apos."\n";
            if($key >= $turn - 1) break;
        }
    }

    public function export($turn) {
        // 指定手数まで動かす
        $this->moveSfen($turn);

        $sfen = "";
        $empty = 0;
        foreach($this->board as $key => $value) {
            if($key% 9 == 0 && $key > 1) {
                if($empty > 0){
                    $sfen .= $empty;
                    $empty *= 0;
                }
                $sfen .= "/";
            }

            if($value !== "-") {
                if($empty !== 0){
                    $sfen .= $empty;
                    $empty *= 0;
                }
                $sfen .= $value;
            } else {
                $empty++;
            }
        }
        if(count($this->sfen) % 2 == 0) {
            $sfen .= " b ";
        } else {
            $sfen .= " w ";
        }
        $keys = array_keys($this->black);
        $value = array_values($this->black);
        foreach($value as $key => $val) {
            if($val != 0) {
                if($val >= 2){
                    $sfen .= $value[$key];
                }
                $sfen .= $keys[$key];
            }
        }
        $keys = array_keys($this->white);
        $value = array_values($this->white);
        foreach($value as $key => $val) {
            if($val != 0) {
                if($val >= 2){
                    $sfen .= $value[$key];
                }
                $sfen .= strtolower($keys[$key]);
            }
        }
        return $sfen;
    }

    private $table = array(
        "歩成" => "P+",
        "香成" => "L+",
        "桂成" => "N+",
        "銀成" => "S+",
        "角成" => "B+",
        "飛成" => "R+",
        "歩" => "P",
        "と" => "P",
        "香" => "L",
        "桂" => "N",
        "銀" => "S",
        "金" => "G",
        "角" => "B",
        "馬" => "B",
        "飛" => "R",
        "竜" => "R",
        "玉" => "K",
        "１" => "1",
        "２" => "2",
        "３" => "3",
        "４" => "4",
        "５" => "5",
        "６" => "6",
        "７" => "7",
        "８" => "8",
        "９" => "9",
        "一" => "1",
        "二" => "2",
        "三" => "3",
        "四" => "4",
        "五" => "5",
        "六" => "6",
        "七" => "7",
        "八" => "8",
        "九" => "9",
        "打" => "*",
        "成" => "",
        "同" => "^",
        "投了" => "resign",
        "　" => "",
        "(" => "",
        ")" => "",
    );

    private $black = array(
        "R" => 0,
        "B" => 0,
        "G" => 0,
        "S" => 0,
        "N" => 0,
        "L" => 0,
        "P" => 0,
    );

    private $white = array(
        "R" => 0,
        "B" => 0,
        "G" => 0,
        "S" => 0,
        "N" => 0,
        "L" => 0,
        "P" => 0,
    );
    
    private $sfen = array();
}
?>
