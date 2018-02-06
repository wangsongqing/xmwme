var LLKGame = function ($, win, doc) {
  var g_opts = null;
  var g_icons = [];
  var g_iconsAll = [];
  var g_temp = null;
  var dimenArr;
  var g_score = 0;
  var g_icon_total = 0;
  var g_reset_count = 2;
  var g_baby_count = 0;
  var g_red_count = 0;
  var g_bean_count = 0;
  var g_click_count = 0;
  var g_count = 0;
  var EV = "ontouchend" in document ? "touchstart" : "click";
  var isPlayGame = false;
  var isOGG = document.createElement('audio').canPlayType('audio/ogg;codecs="vorbis"') == "" ? 0 : 1;
  var lian_salt = ''; //并发参数
  var goods_id = $("#goods_id").val(); //商城购买游戏参数
  var goods_credit = $("#goods_credit").val(); //商城购买游戏参数
  var pathInfo = [{
    x: 0,
    y: 0
	}, {
    x: 0,
    y: 0
	}];
  var resource = {
    images: ["icons.png", "sprite.png", "over_bg.png", "sprite.png", "btns.png", "nums.png"],
    audios: {
      bgm: ["bgm.mp3", "bgm.ogg"],
      click: ["click.mp3", "click.ogg"],
      yes: ["yes.mp3", "yes.ogg"],
      yes2: ["yes2.mp3", "yes2.ogg"],
      over: ["over.mp3", "over.ogg"],
      no: ["no.mp3", "no.ogg"],
      reset: ["reset.mp3", "reset.ogg"],
      down: ["down.mp3", "down.ogg"]
    }
  };

  var Audio = {
    isOGG: function () {
      return document.createElement('audio').canPlayType('audio/ogg;codecs="vorbis"') == "" ? 0 : 1;
    },
    support: function () {
      return window.hasOwnProperty("AudioContext") || window.hasOwnProperty("webkitAudioContext");
    },
    status: "on",
    source: {},
    sound: function (source) {
      if (this.support() && this.status == "on") {
        return Audio.source[source].sound
      } else {
        return {
          play: function () {},
          stop: function () {},
          setVolume: function () {}
        }
      }
    },
    close: function () {
      if (this.support()) {
        for (var i in Audio.source) {
          Audio.source[i].sound.stop();
        }
        this.status == "off";
      }
    }
  }

  var loadingFiles = function (path, datas, callback) {
    var index, sum, images = new Array(),
      audios, loop = false,
      bust = "?mCjFfCkL";
    if (Audio.support()) {
      var engine = new jWebAudio.SoundEngine();
      for (var i in resource.audios) {
        loop = i === "bgm" || i === "down" ? true : false;
        Audio.source[i] = engine.addSoundSource({
          url: g_opts.path + "audios/" + resource.audios[i][Audio.isOGG()] + bust,
          'preLoad': true,
          'loop': loop
        })
      }
      audios = Object.keys(Audio.source);
    } else {
      audios = [];
    }
    index = sum = audios.length + datas.images.length;
    $(".game-loading").show();
    View.switch($("#loading .baby"), 2, "loading");
    var timer = setInterval(function () {
      clearInterval(timer);

      function loading() {
        index--;
        $("#progress i").css("width", ((sum - index) * 100 / sum) + "%");
        if (index == 0) {
          setTimeout(function () {
            clearInterval(View.s_timer["loading"]);
            callback && callback.call();
          }, 500)
        }
      }
      for (var i = 0; i < datas.images.length; i++) {
        images[i] = new Image();
        images[i].src = path + "images/" + datas.images[i]
      };
      if (Audio.support()) {
        for (var i in Audio.source) {
          Audio.source[i].load(loading())
        }
      }
      for (var i = 0; i < images.length; i++) {
        images[i].onload = loading;
      }
    }, 500)
  };

  var View = {
    s_timer: {},
    c_timer: null,
    createNum: function (obj, num) {
      obj.html(num);
    },
    create: function () {
      this.createNum($("#time"), g_opts.time / 1000);
      this.createNum($("#score"), 0);
      Control.create();
      Event.click();
    },
    switch: function (obj, n, name, loop) {
      var that = this,
        index = 0;
      that.s_timer[name] = setInterval(function () {
        if (index < n) {
          obj.attr({
            "data-frames": index
          })
          index++
        } else {
          if (loop != undefined && !loop) {
            clearInterval(that.s_timer[name])
            obj.removeAttr("data-frames");
            obj.hide();
          }
          index = 0
        }
      }, 200)
    },
    beginCountdown: function (callback) {
      var that = this;
      var timer = null;
      var index = 3;
      if (Audio.status == "on") {
        Audio.sound("begin").setVolume(100);
        Audio.sound("begin").stop();
        Audio.sound("begin").play();
      }

      function countdown() {
        if (index > 0) {
          $("#countdown").show().find("i").removeAttr("class").addClass("icon-" + index)
          index--;
          timer = setTimeout(function () {
            countdown();
          }, 1000)
        } else {
          $("#countdown").hide();
          clearTimeout(timer);
          callback && callback.call();
        }
      }
      countdown();
    },
    gameCountdown: function (time, callback) {
      var that = this,
        timer = null;
      Audio.sound("bgm").play();
      that.createNum($("#time"), --time);
      that.c_timer = setInterval(function () {
        if (time > 1) {
          if (time <= 6) {
            Audio.sound("down").play();
          }
          if (isPlayGame == false) {
            clearInterval(that.c_timer);
          } else {
            time--;
            that.createNum($("#time"), time);
          }
        } else {
          clearInterval(that.c_timer);
          callback && callback.call();
        }
      }, 1000)
    },
  }

  var Control = {
    randomsort: function (a, b) {
      return Math.random() > .5 ? -1 : 1
    },
    create: function () {
      var leng = g_opts.colNum * g_opts.rowNum;
      var arr = [];
      var html = "";
      for (var i = 0; i < g_opts.iconLen; i++) {
        g_icons.push("icon-" + i);
      }
      var temp = 0;
      for (var i = 0; i < leng / 2; i++) {
        if (temp > g_opts.iconLen - 1) {
          temp = 0
        }
        if (i >= 10 && i < 10 + g_opts.redEnvelopesNum) {
          temp = 0;
          g_iconsAll.push("icon-red");
          g_iconsAll.push("icon-red")
        } else if (i >= 10 + g_opts.redEnvelopesNum && i < 10 + g_opts.redEnvelopesNum + g_opts.beansNum) {
          temp = 0;
          g_iconsAll.push("icon-bean");
          g_iconsAll.push("icon-bean")
        } else {
          g_iconsAll.push(g_icons[temp]);
          g_iconsAll.push(g_icons[temp]);
          temp++
        }
      }
      g_iconsAll.sort(this.randomsort);
      var index = 0;
      for (var i = 0; i < g_opts.rowNum + 2; i++) {
        html += '<tr>';
        for (var j = 0; j < g_opts.colNum + 2; j++) {
          html += '<td data-x="' + i + '" data-y="' + j + '">';
          if (i != 0 && j < g_opts.colNum + 1 && j != 0 && i < g_opts.rowNum + 1) {
            html += '<i class="' + g_iconsAll[index] + '"></i>';
            dimenArr[i][j] = g_iconsAll[index];
            index++
          } else {
            dimenArr[i][j] = 0
          }
          html += '</td>'
        }
        html += '</tr>'
      }
      $("#icons_box").html(html)
    },
    reset: function () {
      var arr = [];
      $("#icons_box td").removeClass("active");
      g_temp = null;
      $("#icons_box td i").each(function (index) {
        arr.push($(this).attr("class"))
      });
      arr.sort(this.randomsort);
      $.each(arr, function (index) {
        $("#icons_box td i").eq(index).removeAttr("class").addClass(arr[index]);
        var i = Number($("#icons_box td i").eq(index).parent().attr("data-x"));
        var j = Number($("#icons_box td i").eq(index).parent().attr("data-y"))
        dimenArr[i][j] = arr[index];
      })
    },
    checkAdjacent: function (A, B) {
      var tempArr = [-1, 1];
      for (var i = 0; i < tempArr.length; i++) {
        if (A.y == B.y && A.x == B.x + tempArr[i]) {
          return true
        }
        if (A.x == B.x && A.y == B.y + tempArr[i]) {
          return true
        }
      }
    },
    getPaths: function (element) {
      var paths = [];
      for (var i = 0; i < g_opts.rowNum + 2; i++) {
        paths.push({
          x: i,
          y: element.y
        })
      }
      for (var j = 0; j < g_opts.colNum + 2; j++) {
        paths.push({
          x: element.x,
          y: j
        })
      }
      return paths
    },
    feasiblePath: function (A, B) {
      if (this.checkAdjacent(A, B)) {
        return true
      }
      var aPaths = this.getPaths(A);
      var bPaths = this.getPaths(B);
      var flag = false;
      var nearPath = [];
      for (var i = 0; i < aPaths.length; i++) {
        if (dimenArr[aPaths[i].x][aPaths[i].y]) {
          continue
        }
        if (!this.checkTwoPath(aPaths[i], A)) {
          continue
        }
        var bPositions = this.getSamePostions(bPaths, aPaths[i]);
        for (var j = 0; j < bPositions.length; j++) {
          if (dimenArr[bPositions[j].x][bPositions[j].y]) {
            continue
          }
          if (!this.checkTwoPath(bPositions[j], B)) {
            continue
          }
          if (this.checkTwoPath(aPaths[i], bPositions[j])) {
            flag = true;
            nearPath.push({
              "crossA": aPaths[i],
              "crossB": bPositions[j],
              "A": A,
              "B": B
            })
          }
        }
      }
      if (flag) {
        return nearPath
      } else {
        return false
      }
    },
    checkTwoPath: function (target, current) {
      if (current.y == target.y) {
        for (var i = target.x; i < current.x ? i < current.x : i > current.x; i < current.x ? i++ : i--) {
          if (dimenArr[i][current.y]) {
            return false
          }
        }
      } else {
        if (current.x == target.x) {
          for (var j = target.y; j < current.y ? j < current.y : j > current.y; j < current.y ? j++ : j--) {
            if (dimenArr[current.x][j]) {
              return false
            }
          }
        }
      }
      return true
    },
    getSamePostions: function (target, current) {
      var paths = [{
        x: 0,
        y: 0
			}, {
        x: 0,
        y: 0
			}]
      for (var i = 0; i < target.length; i++) {
        if (current.x == target[i].x) {
          paths[0].x = target[i].x;
          paths[0].y = target[i].y
        }
        if (current.y == target[i].y) {
          paths[1].x = target[i].x;
          paths[1].y = target[i].y
        }
      }
      return paths
    },
    getShortPath: function (nearPath) {
      var flag = true;
      var pathLength = [];
      var shortPath = null;
      if (nearPath.length == 1) {
        shortPath = nearPath[0]
      } else {
        for (var i = 0; i < nearPath.length; i++) {
          if (nearPath[i].crossA.x == nearPath[i].crossB.x && nearPath[i].crossA.y == nearPath[i].crossB.y) {
            flag = false;
            shortPath = nearPath[i];
            break
          }
        }
        if (flag) {
          for (var i = 0; i < nearPath.length; i++) {
            pathLength.push({
              'index': i,
              "value": this.getPathLength(nearPath[i].crossA, nearPath[i].A) + this.getPathLength(nearPath[i].crossB, nearPath[i].B) + this.getPathLength(nearPath[i].crossA, nearPath[i].crossB)
            })
          }
          pathLength.sort(function (obj1, obj2) {
            return obj1.value > obj2.value ? 1 : -1
          });
          shortPath = nearPath[pathLength[0].index]
        }
      }
      return shortPath
    },
    getPathLength: function (currentCoords, targetCoords) {
      if (currentCoords.x == targetCoords.x) {
        return Math.abs(currentCoords.y - targetCoords.y - 1)
      } else if (currentCoords.y == targetCoords.y) {
        return Math.abs(currentCoords.x - targetCoords.x - 1)
      }
    },
    direction: function (pathObj) {
      var directionA = '';
      var directionB = '';
      var pointAndCrossDirection = {
        "point": pathObj
      };
      if (pathObj.A.x == pathObj.crossA.x) {
        directionA += pathObj.A.y - pathObj.crossA.y > 0 ? "Right" : "Left"
      } else if (pathObj.A.y == pathObj.crossA.y) {
        directionA += pathObj.A.x - pathObj.crossA.x > 0 ? "bottom" : "top"
      }
      if (pathObj.B.x == pathObj.crossB.x) {
        directionB += pathObj.B.y - pathObj.crossB.y > 0 ? "Right" : "Left"
      } else if (pathObj.B.y == pathObj.crossB.y) {
        directionB += pathObj.B.x - pathObj.crossB.x > 0 ? "bottom" : "top"
      }
      if (pathObj.crossA.x == pathObj.crossB.x && pathObj.crossA.y == pathObj.crossB.y) {
        if ((pathObj.A.x == pathObj.crossA.x && pathObj.B.x == pathObj.crossB.x) || (pathObj.A.y == pathObj.crossA.y && pathObj.B.y == pathObj.crossB.y)) {} else {
          directionA = directionA + directionB;
          directionB = directionA;
          pointAndCrossDirection.crossDirection = {
            'A': this.formatStr(directionA),
            "B": this.formatStr(directionB)
          }
        }
      } else {
        if (pathObj.crossA.x == pathObj.crossB.x) {
          directionA += pathObj.crossA.y - pathObj.crossB.y > 0 ? "Left" : "Right";
          directionB += pathObj.crossA.y - pathObj.crossB.y > 0 ? "Right" : "Left"
        } else if (pathObj.crossA.y == pathObj.crossB.y) {
          directionA += pathObj.crossA.x - pathObj.crossB.x > 0 ? "top" : "bottom";
          directionB += pathObj.crossA.x - pathObj.crossB.x > 0 ? "bottom" : "top"
        }
        pointAndCrossDirection.crossDirection = {
          'A': this.formatStr(directionA),
          "B": this.formatStr(directionB)
        }
      }
      return pointAndCrossDirection
    },
    pathDirection: function (current, target) {
      if (current.x == target.x) {
        return "h"
      } else if (current.y == target.y) {
        return "v"
      }
    },
    formatStr: function (str) {
      if (str == "Righttop" || str == "topRight") {
        return "top-right"
      } else if (str == "Rightbottom" || str == "bottomRight") {
        return "bottom-right"
      } else if (str == "Lefttop" || str == "topLeft") {
        return "top-left"
      } else if (str == "Leftbottom" || str == "bottomLeft") {
        return "bottom-left"
      } else {
        return str
      }
    },
    pathCoordinate: function (pointAndCrossDirection) {
      var pathDirection = "";
      var coordinateArr = [];
      if (pointAndCrossDirection.crossDirection === undefined) {
        coordinateArr = this.getAllPoint(pointAndCrossDirection.point.A, pointAndCrossDirection.point.B);
        pathDirection = this.pathDirection(pointAndCrossDirection.point.A, pointAndCrossDirection.point.B);
        this.drawPathLine(coordinateArr, pathDirection)
      } else {
        this.drawPathLine(this.getAllPoint(pointAndCrossDirection.point.A, pointAndCrossDirection.point.crossA), this.pathDirection(pointAndCrossDirection.point.A, pointAndCrossDirection.point.crossA));
        this.drawPathLine(this.getAllPoint(pointAndCrossDirection.point.B, pointAndCrossDirection.point.crossB), this.pathDirection(pointAndCrossDirection.point.B, pointAndCrossDirection.point.crossB));
        this.drawPathLine(this.getAllPoint(pointAndCrossDirection.point.crossA, pointAndCrossDirection.point.crossB), this.pathDirection(pointAndCrossDirection.point.crossA, pointAndCrossDirection.point.crossB));
        this.drawCrossDirection(pointAndCrossDirection)
      }
    },
    getAllPoint: function (validPointA, validPointB) {
      var pointArr = [];
      if (validPointA.x == validPointB.x) {
        var i = validPointA.y < validPointB.y ? validPointA.y + 1 : validPointB.y + 1;
        var len = validPointA.y > validPointB.y ? validPointA.y : validPointB.y;
        for (; i < len; i++) {
          pointArr.push({
            x: validPointA.x,
            y: i
          })
        }
      } else if (validPointA.y == validPointB.y) {
        var i = validPointA.x < validPointB.x ? validPointA.x + 1 : validPointB.x + 1;
        var len = validPointA.x > validPointB.x ? validPointA.x : validPointB.x;
        for (; i < len; i++) {
          pointArr.push({
            x: i,
            y: validPointA.y
          })
        }
      }
      return pointArr
    },
    drawCrossDirection: function (path) {
      var trA = document.querySelectorAll('tr')[path.point.crossA.x];
      var tdA = trA.querySelectorAll('td')[path.point.crossA.y];
      tdA.innerHTML = "<div class='line line-" + path.crossDirection.A + "'></div>";
      var trB = document.querySelectorAll('tr')[path.point.crossB.x];
      var tdB = trB.querySelectorAll('td')[path.point.crossB.y];
      tdB.innerHTML = "<div class='line line-" + path.crossDirection.B + "'></div>"
    },
    drawPathLine: function (coordinateArr, direction) {
      var tr = document.querySelectorAll('tr');
      if (coordinateArr.length != 0) {
        for (var i = 0; i < coordinateArr.length; i++) {
          var td = tr[coordinateArr[i].x].querySelectorAll('td')[coordinateArr[i].y];
          td.innerHTML = "<div class='line-" + direction + "'></div>"
        }
      }
    },
    init: function () {}
  }
  var Event = {
    prevClass: "",
    reset: function () {
      $(".btn-reset").on("click", function ($event) {
        $event.preventDefault();
        if (g_reset_count <= 0) {
          return
        } else {
          g_reset_count--;
          $(this).attr("data-status", g_reset_count);
          Audio.sound("reset").stop();
          Audio.sound("reset").play();
          Control.reset();
        }
        return false;
      })
    },
    audio: function () {
      $(".btn-voice").on("click", function ($event) {
        $event.preventDefault();
        if ($(this).attr("data-status") == "on") {
          $(this).attr("data-status", "off")
          Audio.status = "off";
          Audio.close();
        } else {
          $(this).attr("data-status", "on")
          Audio.status = "on";
          Audio.sound("bgm").play();
        }
        return false;
      })
    },
    replay: function () {
      $(document).delegate("#btn_replay", "click", function ($event) {
        $event.preventDefault();
        if (Action.check()) {
          Run.reset();
          //重复玩游戏需要提示玩游戏需要消耗宝贝豆
          if (goods_id > 0) {
            layer.open({
              title: " ",
              className: "popup-custom",
              content: "重新开始需要消耗" + goods_credit + "宝贝豆，是否继续？",
              btn: ["确定", "取消"],
              yes: function () { //确定
                window.location.href = "/lian/index/?goods_id=" + goods_id;
              },
              no: function () {
                window.location.href = "/goods/index/";
              }
            })
            return false;
          }
          $("#icons_box").html("");
          $(".game-over").removeClass("show");
          setTimeout(function () {
            View.create();
            $(".game-over").hide();
            $(".game-main").show();
            View.beginCountdown(function () {
              isPlayGame = true;
              View.gameCountdown(g_opts.time / 1000, function () {
                isPlayGame = false;
                Run.over();
              });
            });
          }, 200)
        }
        return false;
      })
    },
    start: function () {
      $("#btn_start").on("click", function ($event) {
        $event.preventDefault();
        if (Action.check()) {
          $(".game-start").hide();
          //Audio.sound("begin").play();
          //Audio.sound("begin").setVolume(0);
          loadingFiles(g_opts.path, resource, function () {
            $("#loading").hide();
            $(".game-scene").show();
            View.create();
            View.beginCountdown(function () {
              isPlayGame = true;
              View.gameCountdown(g_opts.time / 1000, function () {
                isPlayGame = false;
                Run.over();
              });
            });
          })
        }
      })
      return false;
    },
    click: function () {
      var that = this;
      $("#icons_box td").on(EV, function ($event) {
        $event.preventDefault();
        if ($(this).html() == "") {
          return
        }
        Audio.sound("click").play();
        var firstImg = null;
        var secondImg = null;
        var allPath = null;
        var shortPath = null;
        var pathAndDirection = null;
        var x = $(this).attr("data-x");
        var y = $(this).attr("data-y");
        if (g_temp == null) {
          g_temp = $(this);
          $(this).addClass("active");
          pathInfo[0].x = Number(x);
          pathInfo[0].y = Number(y)
        } else if ($(this).attr("class") != "active") {
          $(this).addClass("active");
          pathInfo[1].x = Number(x);
          pathInfo[1].y = Number(y);
          if (dimenArr[pathInfo[0].x][pathInfo[0].y] == dimenArr[pathInfo[1].x][pathInfo[1].y]) {
            allPath = Control.feasiblePath(pathInfo[0], pathInfo[1]);
            if (allPath) {
              if ($(this).find("i").hasClass("icon-red")) {
                g_red_count++;
                Audio.sound("yes2").stop();
                Audio.sound("yes2").play();
              } else if ($(this).find("i").hasClass("icon-bean")) {
                g_bean_count++;
                Audio.sound("yes2").stop();
                Audio.sound("yes2").play();
              } else {
                g_baby_count++;
                Audio.sound("yes").stop();
                Audio.sound("yes").play();
              }
              g_count++;
              g_score = Math.floor(g_count * g_opts.price);
              View.createNum($("#score"), g_score);
              firstImg = g_temp;
              secondImg = $(this);
              if (typeof allPath != "boolean") {
                shortPath = Control.getShortPath(allPath);
                pathAndDirection = Control.direction(shortPath);
                Control.pathCoordinate(pathAndDirection);
                var divObj = $("#icons_box td div");
                setTimeout(function () {
                  divObj.remove();
                  firstImg.find("i").addClass("out");
                  secondImg.find("i").addClass("out");
                  setTimeout(function () {
                    $("#icons_box td").removeClass("active");
                    firstImg.empty();
                    secondImg.empty();
                  }, 150)
                }, 300)
              } else {
                setTimeout(function () {
                  firstImg.find("i").addClass("out");
                  secondImg.find("i").addClass("out");
                  setTimeout(function () {
                    $("#icons_box td").removeClass("active");
                    firstImg.empty();
                    secondImg.empty();
                  }, 150)
                }, 300)
              }
              dimenArr[pathInfo[0].x][pathInfo[0].y] = 0;
              dimenArr[pathInfo[1].x][pathInfo[1].y] = 0;
              g_click_count -= 2;
              if (g_click_count == 0) {
                Run.over();
              }
            } else {
              Audio.sound("no").stop();
              Audio.sound("no").play();
              $("#icons_box td").removeClass("active");
              g_temp = null
            }
          } else {
            Audio.sound("no").stop();
            Audio.sound("no").play();
            $("#icons_box td").removeClass("active");
            g_temp = null
          }
          g_temp = null
        } else {
          $("#icons_box td").removeClass("active");
          g_temp = null
        }
      })
      return false;
    },
    rule: function () {
      $(".link-rule").on("click", function ($event) {
        $event.preventDefault();
        layer.open({
          className: "popup-custom",
          content: $("#popup_rule").html(),
          btn: ["知道了"]
        })
        return false;
      });
    },
    exit: function () {
      window.onpopstate = function () {
        Audio.close();
        clearInterval(View.c_timer);
      }
    },
    init: function () {
      this.audio();
      this.start();
      this.rule();
      this.reset();
      this.replay();
      this.exit();
    }
  }

  var Action = {
    check: function () {
      var flag = true;
      return flag;
    },
    send: function () {
      BB.popup.loading.show();
      $.ajax({
        type: "post",
        url: '/lian/send/',
        data: {
          num: g_baby_count + g_red_count + g_bean_count,
        },
        async: false,
        cache: false,
        success: function (data) {
            /*layer.open({
              title: _msg,
              className: "popup-custom",
              content: _msg,
              btn: ["确定"],
              yes: function () {
                window.location.href = "/lian/index/";
              }
            })*/
            BB.popup.loading.hide();
            $(".game-over").html(data);
          
        }
      })
    }
  }

  var Run = {
    default: function () {
      dimenArr = new Array(g_opts.rowNum + 2);
      for (var i = 0; i < dimenArr.length; i++) {
        dimenArr[i] = new Array(g_opts.colNum + 2)
      };
      g_icon_total = g_opts.colNum * g_opts.rowNum;
      g_click_count = g_icon_total;
      var audioFile = Audio.isOGG() ? "begin.ogg" : "begin.mp3";
      if (Audio.support()) {
        Audio.source["begin"] = new jWebAudio.SoundEngine().addSoundSource({
          'url': g_opts.path + "audios/" + audioFile,
          'preLoad': true
        });
      } else {
        $(".btn-voice").hide();
      }
    },
    init: function (options) {
      var defaults = {
        path: "/Resource/activity/lianliankan/",
        price: 1,
        iconLen: 10,
        colNum: 6,
        rowNum: 9,
        redEnvelopesNum: 0,
        beansNum: 0
      };
      g_opts = $.extend({}, defaults, options);
      this.default();
      Event.init();
    },
    reset: function () {
      g_baby_count = 0;
      g_red_count = 0;
      g_bean_count = 0;
      g_reset_count = 2;
      g_score = 0;
      g_count = 0;
      g_click_count = g_icon_total;
      g_iconsAll = [];
      g_icons = [];
      $(".btn-reset").attr("data-status", g_reset_count);
    },
    over: function () {
      clearInterval(View.c_timer);
      Audio.sound("down").stop();
      Audio.sound("bgm").stop();
      Audio.sound("over").play();
      $(".game-main").hide();
      $(".game-over").show();
      setTimeout(function () {
        $(".game-over").addClass("show")
      }, 100)
      Action.send();
    }
  }
  return Run
}(Zepto, window, document);
