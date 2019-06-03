// ロードしたJSONを保存しておくためのグローバル変数
var bsfen;
var turn = 0;
var max = 0;

function addPiece() {
  var piece = Snap("#board").g();
  moves = bsfen["board"];

  // 範囲外を参照しないための処理
  turn < 0 ? turn = 0 : turn;
  turn > max ? turn = max : turn;

  sfen = moves[turn].split(" ")[0];
  capture = moves[turn].split(" ")[2];

  player = {
    black: {
      koma: piece.g().transform("translate(567.5, 485)"),
      capture: new Array("先", "手", " "),
    },
    white: {
      koma: piece.g().transform("translate(32.5, 40) scale(-1, -1)"),
      capture: new Array("後", "手", " "),
    },
  };

  var tmp = "";
  Array.prototype.forEach.call(capture, function (p, id) {
    // もし数字なら
    if (p.match(/[0-9]/)) {
      tmp += p;
      return;
    }

    switch (p.toLowerCase()) {
      case "p":
        text = "歩";
        break;
      case "l":
        text = "香";
        break;
      case "n":
        text = "桂";
        break;
      case "r":
        text = "飛";
        break;
      case "b":
        text = "角";
        break;
      case "g":
        text = "金";
        break;
      case "s":
        text = "銀";
        break;
      default:
        break;
    }
    // 小文字にしても同じなら相手の駒
    if (p == p.toLowerCase()) {
      player.white.capture.push(text);
    } else {
      player.black.capture.push(text);
    }

    // 枚数チェック
    if (tmp.length > 0) {
      switch (tmp) {
        case "2":
          num = "二";
          break;
        case "3":
          num = "三";
          break;
        case "4":
          num = "四";
          break;
        case "5":
          num = "五";
          break;
        case "6":
          num = "六";
          break;
        case "7":
          num = "七";
          break;
        case "8":
          num = "八";
          break;
        case "9":
          num = "九";
          break;
        case "10":
          num = ["十"];
          break;
        case "11":
          num = ["十", "一"];
          break;
        case "12":
          num = ["十", "二"];
          break;
        case "13":
          num = ["十", "三"];
          break;
        case "14":
          num = ["十", "四"];
          break;
        case "15":
          num = ["十", "五"];
          break;
        case "16":
          num = ["十", "六"];
          break;
        case "17":
          num = ["十", "七"];
          break;
        case "18":
          num = ["十", "八"];
          break;
        default:
          break;
      }
      if (p == p.toLowerCase()) {
        player.white.capture.push(num);
      } else {
        player.black.capture.push(num);
      }
      tmp = "";
    }
  });

  // 駒の図形の描画
  player.black.pos = ((player.black.capture.length)) * (32 + 1 / 7) * (-1);
  player.white.pos = ((player.white.capture.length)) * (32 + 1 / 7) * (-1);
  player.black.point = new Array(0, player.black.pos - 65, 11.5, player.black.pos - 60, 15, player.black.pos - 35, -15, player.black.pos - 35, -11.5, player.black.pos - 60, 0, player.black.pos - 65);
  player.white.point = new Array(0, player.white.pos - 65, 11.5, player.white.pos - 60, 15, player.white.pos - 35, -15, player.white.pos - 35, -11.5, player.white.pos - 60, 0, player.white.pos - 65);

  player.black.koma.polyline(player.black.point);
  player.white.koma.polyline(player.white.point).attr({
    fill: "none",
    stroke: "#000000",
    strokeWidth: 2,
  });

  // 持ち駒情報表示
  for (var i = 0; i < player.black.capture.length; i++) {
    // 持ち駒の数から位置を計算
    player.black.dai = (i - (player.black.capture.length)) * (32 + 1 / 7);
    player.black.koma.text(0, player.black.dai, player.black.capture[i]).attr({
      fontSize: 32 + 1 / 7,
      fontFamily: "Yu Mincho",
      textAnchor: "middle",
    });
  }
  for (var i = 0; i < player.white.capture.length; i++) {
    // 持ち駒の数から位置を計算
    player.white.dai = (i - (player.white.capture.length)) * (32 + 1 / 7);

    player.white.koma.text(0, player.white.dai, player.white.capture[i]).attr({
      fontSize: 32 + 1 / 7,
      fontFamily: "Yu Mincho",
      textAnchor: "middle",
    });
  }

  lmv = moves[turn].split(" ")[3];

  var isProm = false;

  line = new Array();

  // 一文字ずつ出力
  Array.prototype.forEach.call(sfen, function (s, id) {
    // 正規表現を使った小文字判定
    if (s.match(/[a-z]/) || s.match(/[1-9]/) || s.match(/[A-Z]/)) {
      if (s.match(/[1-9]/)) {
        for (var i = 0; i < s; i++) {
          line.push("");
        }
      } else {
        sfen[id - 1] == "+" ? line.push("+" + s) : line.push(s);
      }
    }
  });

  // 盤面に駒を表示する
  Array.prototype.forEach.call(line, function (s, id) {
    switch (s.toLowerCase()) {
      case "p":
        text = "歩";
        break;
      case "l":
        text = "香";
        break;
      case "n":
        text = "桂";
        break;
      case "r":
        text = "飛";
        break;
      case "b":
        text = "角";
        break;
      case "g":
        text = "金";
        break;
      case "s":
        text = "銀";
        break;
      case "k":
        text = "玉";
        break;
      case "+p":
        text = "と";
        break;
      case "+l":
        text = "杏";
        break;
      case "+n":
        text = "圭";
        break;
      case "+r":
        text = "龍";
        break;
      case "+b":
        text = "馬";
        break;
      case "+s":
        text = "全";
        break;
      default:
        text = "";
        break;
    }

    // idを座標に変換
    m = id % 9;
    n = parseInt(id / 9);

    x = 50 * m + 87.5;
    y = 50 * n + 62.5;

    // もし最終手なら
    if (id == lmv) {
      back = piece.g().rect(x - 25, y - 25, 50, 50).attr({
        fill: "#111111",
      });

      if (s == s.toLowerCase()) {
        white = piece.g().transform(`translate(${x}, ${y}) scale(-1, -1)`);
        white.text(0, 0, text).attr({
          textAnchor: "middle",
          fill: "#FFFFFF",
          dy: 16,
          fontSize: 41,
          fontFamily: "Yu Mincho",
        });
      } else {
        black = piece.g().transform(`translate(${x}, ${y})`);
        black.text(0, 0, text).attr({
          textAnchor: "middle",
          fill: "#FFFFFF",
          dy: 16,
          fontSize: 41,
          fontFamily: "Yu Mincho",
        });
      }
    } else {
      if (s == s.toLowerCase()) {
        white = piece.g().transform(`translate(${x}, ${y}) scale(-1, -1)`);
        white.text(0, 0, text).attr({
          textAnchor: "middle",
          fill: "#000000",
          dy: 16,
          fontSize: 41,
          fontFamily: "Yu Mincho",
        });
      } else {
        black = piece.g().transform(`translate(${x}, ${y})`);
        black.text(0, 0, text).attr({
          textAnchor: "middle",
          fill: "#000000",
          dy: 16,
          fontSize: 41,
          fontFamily: "Yu Mincho",
        });
      }
    }
  });
}

function viewBoard() {
  var board = Snap("#board").g();

  board.rect(62, 37, 451, 451).attr({
    fill: "#FFC107",
    stroke: "#000000",
    strokeWidth: 3,
  });

  // 将棋盤を表示
  var text_n = new Array("１", "２", "３", "４", "５", "６", "７", "８", "９");
  var text_k = new Array("一", "二", "三", "四", "五", "六", "七", "八", "九");
  for (var i = 0; i < 9; i++) {
    board.line(112.5 + 50 * i, 37.5, 112.5 + 50 * i, 487.5).attr({
      strokeWidth: 2,
      stroke: "#000000"
    });
    board.line(62.5, 87.5 + 50 * i, 512.5, 87.5 + 50 * i).attr({
      strokeWidth: 2,
      stroke: "#000000"
    });
    board.text(487 - 50 * i, 28 + 2 / 3, text_n[i]).attr({
      fontSize: 20,
      fontFamily: "Yu Mincho",
      textAnchor: "middle",
    });
    board.text(529.7, 70 + 50 * i, text_k[i]).attr({
      fontSize: 20,
      fontFamily: "Yu Mincho",
      textAnchor: "middle",
    });
  }

}

$(document).ready(function () {
  // なんかすげーダサい
  $.getJSON('https://shogi.mydns.jp/sfen', function (json) {
    bsfen = json;
    max = bsfen["board"].length - 1;
    viewBoard();
    addPiece();
  });
});


function first() {
  turn *= 0;
  var paper = Snap("#board");
  paper.clear();
  viewBoard();
  addPiece();
}

function prev() {
  --turn;
  var paper = Snap("#board");
  paper.clear();
  viewBoard();
  addPiece();
}

function next() {
  ++turn;
  var paper = Snap("#board");
  paper.clear();
  viewBoard();
  addPiece();
}

function last() {
  turn = max;
  var paper = Snap("#board");
  paper.clear();
  viewBoard();
  addPiece();
}