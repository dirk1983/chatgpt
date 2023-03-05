var contextarray = [];

function getCookie(name) {
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(name + '=') === 0) {
            return cookie.substring(name.length + 1, cookie.length);
        }
    }
    return null;
}

$(document).ready(function () {

    $("#kw-target").on('keydown', function (event) {
        if (event.keyCode == 13) {
            send_post();
            return false;
        }
    });
    $("#ai-btn").click(function () {
        send_post();
        return false;
    });
    $("#clean").click(function () {
        $("#article-wrapper").html("");
        contextarray = [];
        layer.msg("清理完毕！");
        return false;
    });
    $("#showlog").click(function () {
        let btnArry = ['已阅'];
        layer.open({ type: 1, title: '全部对话日志', area: ['80%', '80%'], shade: 0.5, scrollbar: true, offset: [($(window).height() * 0.1), ($(window).width() * 0.1)], content: '<iframe src="chat.txt?' + new Date().getTime() + '" style="width: 100%; height: 100%;"></iframe>', btn: btnArry });
        return false;
    });

    function send_post() {
        if (($('#key').length) && ($('#key').val().length != 51)) {
            layer.msg("请输入正确的API-KEY", { icon: 5 });
            return;
        }

        var prompt = $("#kw-target").val();

        if (prompt == "") {
            layer.msg("请输入您的问题", { icon: 5 });
            return;
        }

        var loading = layer.msg('正在组织语言，请稍等片刻...', {
            icon: 16,
            shade: 0.4,
            time: false //取消自动关闭
        });

        function streaming() {
            var es = new EventSource("stream.php");
            var isstarted = true;
            var alltext = "";
            var isalltext = false;
            es.onerror = function (event) {
                layer.close(loading);
                var errcode = getCookie("errcode");
                switch (errcode) {
                    case "invalid_api_key":
                        layer.msg("API-KEY不合法");
                        break;
                    case "context_length_exceeded":
                        layer.msg("问题和上下文长度超限，请重新提问。");
                        break;
                    case "rate_limit_reached":
                        layer.msg("同时访问用户过多，请稍后再试。");
                        break;
                    case null:
                        layer.msg("OpenAI服务器访问超时。");
                        break;
                    default:
                        layer.msg("服务器出错了，错误类型：" + errcode);
                }
                es.close();
                return;
            }
            es.onmessage = function (event) {
                if (isstarted) {
                    layer.close(loading);
                    $("#kw-target").val("请耐心等待AI把话说完……");
                    $("#kw-target").attr("disabled", true);
                    layer.msg("处理成功！");
                    isstarted = false;
                    answer = randomString(16);
                    $("#article-wrapper").append('<li class="article-title" id="q' + answer + '"><pre></pre></li>');
                    for (var j = 0; j < prompt.length; j++) {
                        $("#q" + answer).children('pre').text($("#q" + answer).children('pre').text() + prompt[j]);
                    }
                    $("#article-wrapper").append('<li class="article-content" id="' + answer + '"></li>');
                    let str_ = '';
                    let i = 0;
                    let timer = setInterval(() => {
                        alltext = alltext.replace(/\\n/g, '\n');
                        if (str_.length < alltext.length) {
                            str_ += alltext[i++];
                            strforcode = str_ + "_";
                            if ((str_.split("```").length % 2) == 0) strforcode += "\n```\n";
                        } else {
                            if (isalltext) {
                                clearInterval(timer);
                                strforcode = str_;
                                $("#kw-target").val("");
                                $("#kw-target").attr("disabled", false);
                            }
                        }
                        var converter = new showdown.Converter();
                        $("#" + answer).html(converter.makeHtml(strforcode));
                        hljs.highlightAll();
                        document.getElementById("article-wrapper").scrollTop = 100000;
                    }, 30);
                }
                if (event.data == "[DONE]") {
                    isalltext = true;
                    contextarray.push([prompt, alltext]);
                    contextarray = contextarray.slice(-5); //只保留最近5次对话作为上下文，以免超过最大tokens限制
                    es.close();
                    return;
                }
                var json = eval("(" + event.data + ")");
                if (json.choices[0].delta.hasOwnProperty("content")) {
                    if (alltext == "") {
                        alltext = json.choices[0].delta.content.replace(/^\n+/, '');
                    } else {
                        alltext += json.choices[0].delta.content;
                    }
                }
            }
        }


        $.ajax({
            cache: true,
            type: "POST",
            url: "setsession.php",
            data: {
                message: prompt,
                context: (!($("#keep").length) || ($("#keep").prop("checked"))) ? JSON.stringify(contextarray) : '[]',
                key: ($("#key").length) ? ($("#key").val()) : '',
            },
            dataType: "json",
            success: function (results) {
                streaming();
            }
        });


    }

    function randomString(len) {
        len = len || 32;
        var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
        var maxPos = $chars.length;
        var pwd = '';
        for (i = 0; i < len; i++) {
            pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }
});
