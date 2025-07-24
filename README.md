# DeepSeek

## 写在最前

GPT大模型的横空出世真的改变了世界，用过的人都知道大模型完全可以作为生产力工具应用在很多领域。可以说大模型是最近几年又一个的巨大风口，目前大量投资机构和政府部门都在鼓励和支持相关行业的发展。尤其当DeepSeek-R1出现之后，国内大模型的需求进一步爆发。如果您也有使用AI大模型赚钱或创业的想法，欢迎免费进群讨论，二维码在本文最后。群里有很多志同道合的朋友一起分享资讯，分享知识，对接资源。另外请点下右上角的小星星，方便您随时找到本项目。

## 首次使用配置

请访问 http://你的域名/key.php 配置您的API_KEY列表，程序将全局自动循环调用。默认用户名：admin，默认密码：admin@2023。默认用户名密码可以在key.php文件中修改。如果需要调用第三方接口，请修改stream.php文件中第81行相关代码。

**本项目完全开源，是PHP版调用第三方兼容OpenAI规范的API接口进行问答的Demo，有以下特性和功能：**

1. 对PHP版本无要求，不需要数据库。核心代码只有几个文件，没用任何框架，修改调试很方便。
2. 采用stream流模式通信，一边生成一边输出，响应速度全网最快。
3. 支持OpenAI、DeepSeek的GPT-3.5-Turbo、GPT-4、DeepSeek-R1等多种模型（修改model名称和API接口地址）。
4. 支持Markdown格式文本显示，如表格、代码块。对代码进行了着色，提供了代码复制按钮，支持公式显示。
5. 支持多行输入，文本框高度自动调节，手机和PC端显示都已做适配。
6. 支持一些预设话术，支持上下文连续对话，AI回答途中可以随时打断。
7. 支持错误处理，接口返回错误时可以看到具体原因。
8. 可以实现区分内外网IP，内网直接访问，外网通过BASIC认证后可访问。
9. 可以实现页面输入自定义API_KEY使用，方便分享给网友或朋友使用。
10. 服务器自动记录所有访问者的对话日志和IP地址，方便管理员查询。
11. 支持API_KEY自动轮询，解决单个账户限制次数和出现错误的问题。
12. 支持调用画图模型，提问的第一个字是“画”即可生成图片。

**本项目定位是个人或朋友之间分享使用，轻量设计，不计划引入数据库等复杂功能。有需要的用户可以自行拿去修改，版权没有，改动不究。对于项目UI或其他功能有改进想法的朋友欢迎提交PR，或者在Issues或Discussions进行讨论。**

------
# 测试网址：http://mm1.ltd
![t1](https://user-images.githubusercontent.com/5563148/232330560-1b6a45f3-fcc1-4d3e-a2f7-b1c9878fe9cd.jpg)
![t2](https://user-images.githubusercontent.com/5563148/232330566-c6ea7fb3-474f-45e4-adda-37f3db27b92a.jpg)
![t3](https://github.com/dirk1983/chatgpt/assets/5563148/732b5bed-7e9c-4c07-9865-9b97957781a7)


------
## 常见问题

1. 在国内环境使用提示OpenAI连接超时

是的，OpenAI官方不支持中国（含港澳台地区）IP访问接口。有以下几种解决方案：

a. 使用境外服务器部署本项目，如美国、韩国、日本等，比如腾讯云日本就可以。

b. 如果本项目部署在电脑上，可以用电脑上的HTTP-PROXY代理，把stream.php里面注释掉的“curl_setopt($ch, CURLOPT_PROXY, " http://127.0.0.1:1081 ");”修改一下即可。

c. 使用反向代理服务，将OpenAI接口地址反代到某个网址，把“curl_setopt($ch, CURLOPT_URL, ' https://api.openai.com/v1/chat/completions ');”这行里面的网址改成反代后的网址即可。

使用后两种解决方案的时候可能会因为代理的缓存机制造成stream模式的实时性受影响，另外可能也增加了额外的访问延迟。

2. 关于反向代理的配置方式

如果你有海外服务器，使用nginx反代最简单，用宝塔搭建反代的方案可以参考这篇文章：https://blog.csdn.net/weixin_43227851/article/details/133440520

如果没有海外服务器，可以用cf worker免费建一个，前提是你要有一个域名，几块钱就能注册一个。搭建自己的cf worker教程在这里：https://github.com/noobnooc/noobnooc/discussions/9 。如果你连域名也不想注册，也可以用别人现成的反代地址，比如下面这个：https://openai.1rmb.tk/v1/chat/completions 。地址是群友提供的，不确定什么时候失效，用的人比较多时可能会有点卡，大家也可以进群求一个。

2023-11-16日OpenAI的API接口地址将很多IP屏蔽，包括一些香港IP和CloudFlare的IP，当天在国内服务器上使用cf worker搭建反代地址的方案不可用。一两天后OpenAI恢复了香港IP和CF访问OpenAI接口地址，目前用CF做反代的方案还是可行的。后续OpenAI也许还会偶尔抽风，大家可以进群第一时间了解类似的突发事件。

3. 关于Stream流模式的原理，为什么你部署的不像我的那么快

本项目前端使用的是Javascript的EventSource方式与后端进行通信，可以实现数据的流模式即时传输，而OpenAI接口也是支持数据实时生成实时传输的，因此才能实现问答的秒回。EventSource模式的缺点是不支持POST方式传递数据，GET方式对数据长度有限制，cookie也有限制，所以选择了分两步请求后端，采用SESSION传递数据。至于为什么你用我的代码部署的网站速度比较慢，主要原因除了服务器的问题，可能还有PHP环境的问题。PHP如果想实现流式输出需要关闭输出缓存，可能需要修改apache或nginx及php.ini的配置，具体修改方式可以自行搜索或者到群里问群友。

4. 如果想实现像Demo站一样输入API_KEY才能使用的功能，怎么修改代码

在index.php文件中取消掉相关的注释就行了，为了美观建议把上面的“连续对话”部分注释掉，要不然手机访问不是很友好。注释“连续对话”不影响网站运行，默认就是包含上下文的连续对话。

5. 是否支持docker？

有网友提出想使用docker方式运行本项目，其实随便找一个nginx+php环境的docker，把path指向本项目所在的目录就行了。这里提供热心网友提供的docker镜像：gindex/nginx-php。使用方式如下：

```
docker pull gindex/nginx-php
docker run -itd -v /root/chatgpt(本地目录):/usr/share/nginx/html --name nginx-php -p 8080(主机端口):80 --restart=always gindex/nginx-php
```

还有另一位热心网友基于本项目在github上的docker版AI大模型，网址：https://github.com/hsmbs/chatgpt-php ，也可以用。

6. 是否支持Windows客户端？

喜欢使用独立Windows桌面应用的朋友可以下载Release里面的exe文件运行，其实就是一个指向我演示网站的浏览器套个壳。

7. 有没有可以注册会员的商业运营版？

由于很多群友都有类似需求，我开发了一个款基于PHP+Mysql环境的商业版软件，已正式发布。有兴趣的话您可以访问这里查看详情：https://github.com/dirk1983/ai_commercial

------

附OpenAI官网的模型和接口调用介绍：

https://platform.openai.com/docs/models/moderation

https://platform.openai.com/docs/api-reference/chat/create

https://platform.openai.com/docs/guides/chat/introduction

https://platform.openai.com/docs/api-reference/models/list

------
**对AI大模型感兴趣的同学们欢迎加群讨论。我已建了10个微信群，群公告里有近5000群友中所有卖家提供的各种服务网址，有任何和AI有关的需求都可以找到相关的产品。群里也有很多大神，有问题可以互相帮助。**

由于目前最新的群里超过200人，请加我小号拉进群。

![微信截图_20230306154434](https://user-images.githubusercontent.com/5563148/223048985-4cac05cb-acf0-4f04-aad5-1c3dcec609d0.png)

我还做了个在微信个人订阅号中通过调用第三方接口实现AI大模型聊天机器人的功能，已开源，需要的朋友也可以拿去。
https://github.com/dirk1983/ai-wechat-personal


## 声明

1. 本项目遵循 <a href='https://github.com/dirk1983/deepseek/blob/main/LICENSE'>MIT开源协议</a>，仅用于技术研究和学习，使用本项目时需遵守所在地法律法规、相关政策以及企业章程，禁止用于任何违法或侵犯他人权益的行为。任何个人、团队和企业，无论以何种方式使用该项目、对何对象提供服务，所产生的一切后果，本项目均不承担任何责任。

2. 境内使用该项目时，建议使用国内厂商的大模型服务，并进行必要的内容安全审核及过滤。


## Star History

[![Star History Chart](https://api.star-history.com/svg?repos=dirk1983/chatgpt&type=Date)](https://star-history.com/#dirk1983/chatgpt&Date)

