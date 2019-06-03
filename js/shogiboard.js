$(document).ready(function () {
  // URLパラメータ取得し、手番情報だけを抜き出す
  var param = location.search.substr(1).split("&");
  var turn = 0;
  Array.prototype.forEach.call(param, function (s) {
    if (s.split("=")[0] == "t") {
      turn = s.split("=")[1];
    }
  });

  $.getJSON('https://shogi.mydns.jp/sfen', function (sfen) {
    moves = sfen["board"];
    sfen = moves[turn].split(" ")[0];
    lmv = moves[turn].split(" ")[3];

    line = new Array();

    // 一文字ずつ出力
    Array.prototype.forEach.call(sfen, function (s) {
      if (s == "+") {
        isProm = true;
      } else {
        isProm = false;
      }

      // 正規表現を使った小文字判定
      if (s.match(/[a-z]/) || s.match(/[1-9]/) || s.match(/[A-Z]/)) {
        if (s.match(/[1-9]/)) {
          for (var i = 0; i < s; i++) {
            line.push("");
          }
        } else {
          if (isProm) {
            line.push("+" + s);
          } else {
            line.push(s);
          }
        }
      } else {
        if (s == " ") {
          return;
        }
      }
    });

    // 盤面に駒を表示する
    var board = Snap("#board").g();

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

      black = board.g().transform(`translate(${x}, ${y})`);
      white = board.g().transform(`translate(${x}, ${y}) scale(-1, -1)`);
      
      // もし最終手なら
      if (id == lmv) {
        if (s == s.toLowerCase()) {
          white.text(0, 0, text).attr({
            textAnchor: "middle",
            fill: "#000000",
            dy: 16,
            fontSize: 41,
            fontWeight: "bold",
            fontFamily: "Yu Gothic",
          });
        } else {
          black.text(0, 0, text).attr({
            textAnchor: "middle",
            fill: "#000000",
            dy: 16,
            fontSize: 41,
            fontWeight: "bold",
            fontFamily: "Yu Gothic",
          });
        }
      } else {
        if (s == s.toLowerCase()) {
          white.text(0, 0, text).attr({
            textAnchor: "middle",
            fill: "#000000",
            dy: 16,
            fontSize: 41,
            fontFamily: "Yu Mincho",
          });
        } else {
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
  });
});

$(function () {
  var board = Snap("#board");

  board.rect(62, 37, 451, 451).attr({
    fill: "none",
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

  black = {
    capture: new Array("先", "手"),
  };

  white = {
    capture: new Array("後", "手"),
  };

  piece = {
    black: board.g().transform("translate(567.5, 485)"),
    white: board.g().transform("translate(32.5, 40) scale(-1, -1)"),
  };

  // 駒の図形の描画
  black.pos = ((black.capture.length)) * (32 + 1 / 7) * (-1);
  white.pos = ((white.capture.length)) * (32 + 1 / 7) * (-1);
  black.point = new Array(0, black.pos - 65, 11.5, black.pos - 60, 15, black.pos - 35, -15, black.pos - 35, -11.5, black.pos - 60, 0, black.pos - 65);
  white.point = new Array(0, white.pos - 65, 11.5, white.pos - 60, 15, white.pos - 35, -15, white.pos - 35, -11.5, white.pos - 60, 0, white.pos - 65);

  piece.black.polyline(black.point);
  piece.white.polyline(white.point).attr({
    fill: "none",
    stroke: "#000000",
    strokeWidth: 2,
  });

  // 持ち駒情報表示
  for (var i = 0; i < black.capture.length; i++) {
    pos = (i - (black.capture.length)) * (32 + 1 / 7);

    piece.white.text(0, pos, white.capture[i]).attr({
      fontSize: 32 + 1 / 7,
      fontFamily: "Yu Mincho",
      textAnchor: "middle",
    });

    piece.black.text(0, pos, black.capture[i]).attr({
      fontSize: 32 + 1 / 7,
      fontFamily: "Yu Mincho",
      textAnchor: "middle",
    });
  }
});

function first() {
  location.href = location.pathname + "?t=0";
}

function next() {
  var param = location.search.substr(1).split("&");
  var turn = 0;
  Array.prototype.forEach.call(param, function (s) {
    if (s.split("=")[0] == "t") {
      turn = s.split("=")[1].replace(/[^0-9]/, "");
      next = parseInt(turn) + 1;
    }
  });

  location.href = location.pathname + "?t=" + next;
}

function last() {
  location.href = location.pathname + "?t=0";
}