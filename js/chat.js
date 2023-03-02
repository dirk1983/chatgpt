
/*
//定时器*/
var contextarray = [];
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
        layer.open({<!-- -->
            type: 1
            ,title: '全部对话日志'
            ,area: ['80%', '80%']
            ,shade: 0.5
            ,scrollbar: true
            ,offset: [
                ($(window).height() * 0.1)
                ,($(window).width() * 0.1)
            ]
            ,content: '<iframe src="chat.txt?' + new Date().getTime()+ '" style="width: 100%; height: 100%;"></iframe>'
            ,btn: btnArry
        });
        return false;
    });
    
    function articlewrapper(question,answer,str){
        $("#article-wrapper").append('<li class="article-title" id="q'+answer+'"><pre></pre></li>');
        let str_ = ''
        let i = 0
        let timer = setInterval(()=>{
            if(str_.length<question.length){
                str_ += question[i++]
                $("#q"+answer).children('pre').text(str_+'_')//打印时加光标
            }else{
                clearInterval(timer)
                $("#q"+answer).children('pre').text(str_)//打印时加光标
            }
        },1)
        $("#article-wrapper").append('<li class="article-content" id="'+answer+'"><pre></pre></li>');
          if(str == null || str == ""){
              str="服务器响应超时，您可以更换词语再试试或过会儿再试。";
          }
        let str2_ = ''
        let i2 = 0
        let timer2 = setInterval(()=>{
            if(str2_.length<str.length){
                str2_ += str[i2++]
                $("#"+answer).children('pre').text(str2_+'_')//打印时加光标
            }else{
                clearInterval(timer2)
                $("#"+answer).children('pre').text(str2_)//打印时加光标
            }
        },5)
    }
    
    function send_post() {

        var prompt = $("#kw-target").val();
        if (prompt == "") {
            layer.msg("请输入您的问题", { icon: 5 });
            return;
        }

        var loading = layer.msg('正在组织语言，请稍等片刻...', {
            icon: 16,
            shade: 0.4,
            time:false //取消自动关闭
        });
        $.ajax({
            cache: true,
            type: "POST",
            url: "message.php",
            data: {
                message: prompt,
                context:$("#keep").prop("checked")?JSON.stringify(contextarray):'[]',
            },
            dataType: "json",
            success: function (results) {
                layer.close(loading);
                $("#kw-target").val("");
                layer.msg("处理成功！");
                contextarray.push([prompt, results.raw_message]);
                articlewrapper("问："+prompt,randomString(16),"答："+results.raw_message);
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
