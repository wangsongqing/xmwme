/**
 * function：ajax 加载公共页面，方便文件组织结构
 * author：j+2
 * date：2014-10-30
 */

$.ajaxLoadPblic = function (option) {
    var opt = {
        rootPath: "",
        publicPath: "public",
        header: "header.html",
        subnav: "subnav.html",
        footer: "footer.html",
        asideFloat: "aside-float.html",
        subNavId: "main",
        debug : true
    }
    $.extend(opt, option);
    /**
	 * 判断是否为服务器环境
	 */

    if (window.location.hostname === "") {
        var debug = confirm("此网站页面使用了 Ajax 加载技术，必须在服务器环境下才能正常完整浏览，如果要继续浏览，请点确定！！！");
        if (!debug) {
            $("body").html("<div style='font:30px/80px Microsoft YaHei;color:#c00;text-align:center;padding-top:150px;'>\
						此网站页面使用了 <b style='font-family:Arial'>Ajax</b> 加载技术，必须在服务器环境下才能正常浏览!!!</div>");
            return false;
        }
    }
    var href = window.location.href;
    var reg = new RegExp("")
    if (opt.rootPath !== "") {
        var reg = new RegExp(".*" + opt.rootPath, "g");
        href = reg.exec(href);
    } else {
        var reg = new RegExp(window.location.pathname + ".*", "g");
        href = href.replace(reg, "");
    }

    href += "/" + opt.publicPath + "/";

    var headerUrl = href + opt.header;
    var subnavUrl = href + opt.subnav;
    var footerUrl = href + opt.footer;
    var asideFloatUrl = href + opt.asideFloat;

    function isOk(url) {
        if (~url.indexOf('.html') || ~url.indexOf('.html') || ~url.indexOf('.php') || ~url.indexOf('.aspx') || ~url.indexOf('.jsp')) {
            return true;
        } else {
            return false;
        }
    }

    function loadHtml(url, fn) {
        $.ajax({
            url: url,
            cache: false,
            statusCode: {
                404: function () {
                    if(opt.debug){
                        $("body").html("<div style='font:30px/50px Microsoft YaHei;color:#000;text-align:center;padding-top:150px;'>\
								<p style='color:#c00'>" + url + "</p><div>未找到，请检查各项配置是否正确</div></div>");
                    }
                }
            },
            dataType: "html",
            success: function (html) {
                var script = null;
                var searchHtml = /<body[\s\S]*.*>[\s\S]*(?=<\/body>)/g.exec(html);
                if (searchHtml === null) {
                    searchHtml = html;
                } else {
                    searchHtml = searchHtml.toString().replace(/^<body>*/g, "");
                }
                fn(searchHtml);
            },
            error: function () {
                //alert("error");
            }
        });
    }

    function loadFooter() {
        isOk(footerUrl) && loadHtml(footerUrl, function (html) { $(html).appendTo("body"); });
    }

    // load header
    isOk(headerUrl) && loadHtml(headerUrl, function (html) { $(html).prependTo("body"); })
    // load subnav start
    if (isOk(subnavUrl)) {
        loadHtml(subnavUrl, function (html) {
            $(html).appendTo(opt.subNavId);
            // load aside-float
            if (isOk(asideFloatUrl)) {
                loadHtml(asideFloatUrl, function (html) {
                    $(html).appendTo("body");
                    loadFooter();  // load footer
                });
                return false;
            } else {
                loadFooter();  // load footer
            }
        });
    } else {
        // load aside-float
        if (isOk(asideFloatUrl)) {
            loadHtml(asideFloatUrl, function (html) {
                $(html).appendTo("body");
                loadFooter();  // load footer
            });
            return false;
        } else {
            loadFooter();  // load footer
        }
    }
}
//$.ajaxLoadPblic({debug:false})