# chatgpt
GPT-4已经发布，快进群参与讨论吧…… 免费加群，即将达到500人上限，欲加从速。
------
**2023-03-16更新版本日志：**

1. 支持表格和公式的显示
2. 优化了代码显示逻辑

------
**2023-03-11更新版本日志：**

1. 支持多行输入，文本框高度自动调节
3. AI回答途中可以随时打断
4. 增加了API_KEY被封禁和未提供API_KEY错误的提示
5. 增加了一些预设话术
6. 对手机浏览器进行了适配优化
7. 修复了AI回复内容包含某些代码时，显示效果异常的bug
8. 增加了代码复制按钮

**PHP版调用OpenAI的API接口进行问答的Demo，代码已更新为调用最新的gpt-3.5-turbo模型。
采用Stream流模式通信，一边生成一边输出，响应速度超过官网。前端采用JS的EventSource，还将Markdown格式文本进行了排版，对代码进行了着色处理。服务器记录所有访问者的对话日志。**

很多人想要Demo网站中自己输入API-KEY的功能，已经把代码加上了，取消index.php的注释就行了。为了美观可以把上面的“连续对话”部分注释掉，要不然手机访问不是很友好。

在国内访问OpenAI的新接口会提示超时，如果你本地有HTTP-PROXY，可以把stream.php里面注释掉的“curl_setopt($ch, CURLOPT_PROXY, " http://127.0.0.1:1081 ");”修改一下，这样就可以通过你本地的代理访问openai的接口。

如果你自己没代理，可以使用热心网友提供的反代地址，把“curl_setopt($ch, CURLOPT_URL, ' https://api.openai.com/v1/chat/completions ');”这行里面的网址改成' https://openai.1rmb.tk/v1/chat/completions '，不确定那个什么时候会失效，也可以进群再找其他群友求一个。不过反代的方式访问速度比较慢，最好还是自己买个海外服务器吧，每个月不到20元的有的是。

如果你实在不会买海外服务器，那你有自己的域名吗？有的话还可以用cf worker自建反代，具体可以参考这篇文章：https://github.com/noobnooc/noobnooc/discussions/9

*测试网址：http://mm1.ltd* 

![微信截图_20230312112146](https://user-images.githubusercontent.com/5563148/224522389-f60e3047-c0e6-49cd-bee7-80feaf2c86a4.png)


------

核心代码只有几个文件，没有用任何框架，修改调试很方便，只需要修改stream.php中的API_KEY即可使用。

index.php前面的代码还可以实现区分内外网IP，内网直接访问，外网通过BASIC认证后可访问。可以根据需要删掉注释并进行修改。

部署好了可以放在公司内网，让同事们一起体验chatGPT的强大功能。也可以发到朋友圈分享，互联网技术大牛的形象直接拉满。


FAQ：

之前OpenAI官方API提供的最先进的模型是text-davinci-003，比官网的ChatGPT稍弱一些。最近OpenAI终于放出了gpt-3.5-turbo模型，理论上和官网的ChatGPT几乎没区别了。只是由于接口限制，问题和答案最多4096个tokens，实测1个汉字算2个tokens。

github上也有一些大神提供了基于官方web版chatgpt的代码（ https://github.com/acheong08/ChatGPT ）。原理就是把服务器模拟成一个客户端来和openai交互，用户所有请求通过服务器中转到openai。这个模式需要服务器IP是chatgpt支持的区域，并且稳定性差一些，问多了一段时间内可能会一直失败。好处是不限制问题和答案长度，不需要扣费。不过最新的模型放出来之后，这种方案就更加鸡肋了，好在之前没投入太多精力研究……

有网友提出想使用docker方式运行本项目，其实随便找一个nginx+php环境的docker，把path指向本项目所在的目录就行了。这里提供热心网友提供的docker镜像：gindex/nginx-php。使用方式如下：

```
docker pull gindex/nginx-php
docker run -itd -v /root/chatgpt(本地目录):/usr/share/nginx/html --name nginx-php -p 8080(主机端口):80 --restart=always gindex/nginx-php
```

还有另一位热心网友基于本项目在github上的docker版chatgpt，网址：https://github.com/hsmbs/chatgpt-php ，也可以用。

喜欢使用独立Windows桌面应用的朋友可以下载Release里面的exe文件运行，其实就是一个指向我演示网站的浏览器套个壳。

OpenAI官网的模型和接口调用介绍：

https://platform.openai.com/docs/models/moderation

https://platform.openai.com/docs/api-reference/chat/create

https://platform.openai.com/docs/guides/chat/introduction

https://platform.openai.com/docs/api-reference/models/list


对chatgpt感兴趣的同学们欢迎加群讨论。群里有很多大神，有问题可以互相帮助。如果需要在本项目基础上进行二次开发或者其他商务合作，可以加我微信沟通。

由于群里人数已超过200，无法直接扫码进群，想进群的朋友可以加热心网友小号，由他帮忙拉进群。有自动拉人进群方案的朋友欢迎随时联系我，方便大家讨论。

![微信截图_20230306154434](https://user-images.githubusercontent.com/5563148/223048985-4cac05cb-acf0-4f04-aad5-1c3dcec609d0.png)


有热心网友建议我放个打赏码，各位如果真的想表达感谢，小额即可。

![打赏码](https://user-images.githubusercontent.com/5563148/222968018-9def451a-bbce-4a7e-bde6-edecc7ced40f.jpg)

最后，我还做了个在微信个人订阅号中通过调用OpenAI最新接口和gpt-3.5-turbo模型实现ChatGPT聊天机器人的功能，已开源，需要的朋友也可以拿去。
https://github.com/dirk1983/chatgpt-wechat-personal
