# chatgpt

**写在最前：**

ChatGPT的横空出世真的改变了世界，用过的人都知道ChatGPT完全可以作为生产力工具应用在很多领域。可以说ChatGPT是最近几年又一个的巨大风口，目前大量投资机构和政府部门都在鼓励和支持相关行业的发展。如果您也有使用ChatGPT赚钱或创业的想法，欢迎免费进群讨论，二维码在本文最后。群里有很多志同道合的朋友一起分享资讯，分享知识，对接资源。另外请点下右上角的小星星，方便您随时找到本项目。

**本项目完全开源，是PHP版调用OpenAI的API接口进行问答的Demo，有以下特性和功能：**

1. 对PHP版本无要求，不需要数据库。核心代码只有几个文件，没用任何框架，修改调试很方便，只需要修改stream.php中的API_KEY即可使用。
2. 采用stream流模式通信，一边生成一边输出，响应速度全网最快。
3. 支持GPT-3.5-Turbo和GPT-4等各种模型（后者需要修改下默认model名称）。
4. 支持Markdown格式文本显示，如表格、代码块。对代码进行了着色，提供了代码复制按钮，支持公式显示。
5. 支持多行输入，文本框高度自动调节，手机和PC端显示都已做适配。
6. 支持一些预设话术，支持上下文连续对话，AI回答途中可以随时打断。
7. 支持错误处理，OpenAI接口返回错误时可以看到具体原因。
8. 可以实现区分内外网IP，内网直接访问，外网通过BASIC认证后可访问。
9. 可以实现输入API_KEY使用，方便分享给网友或朋友使用。
10. 服务器自动记录所有访问者的对话日志和IP地址，方便管理员查询。

**本项目定位是个人或朋友之间分享使用，轻量设计，不计划引入数据库等复杂功能，有需要的用户可以自行拿去修改，版权没有，改动不究。对于项目UI或其他功能有改进想法的朋友欢迎提交PR，或者在Issues或Discussions进行讨论。**

------
# 测试网址：http://mm1.ltd
![t1](https://user-images.githubusercontent.com/5563148/232330560-1b6a45f3-fcc1-4d3e-a2f7-b1c9878fe9cd.jpg)
![t2](https://user-images.githubusercontent.com/5563148/232330566-c6ea7fb3-474f-45e4-adda-37f3db27b92a.jpg)


------
**本项目常见问题：**

1. 在国内环境使用提示OpenAI连接超时

是的，OpenAI官方不支持中国（含港澳台地区）IP访问接口。有以下几种解决方案：

a. 使用境外服务器部署本项目，如美国、韩国、日本等，比如腾讯云日本就可以。

b. 如果本项目部署在电脑上，可以用电脑上的HTTP-PROXY代理，把stream.php里面注释掉的“curl_setopt($ch, CURLOPT_PROXY, " http://127.0.0.1:1081 ");”修改一下即可。

c. 使用反向代理服务，将OpenAI接口地址反代到某个网址，把“curl_setopt($ch, CURLOPT_URL, ' https://api.openai.com/v1/chat/completions ');”这行里面的网址改成反代后的网址即可。

使用后两种解决方案的时候可能会因为代理的缓存机制造成stream模式的实时性受影响，另外可能也增加了额外的访问延迟。

2. 关于反向代理的配置方式

如果你有海外服务器，使用nginx反代最简单，修改配置文件，增加一两行代码即可实现，具体方式自行搜索。如果没有海外服务器，可以用cf worker免费建一个，前提是你要有一个域名，几块钱就能注册一个。搭建自己的cf worker教程在这里：https://github.com/noobnooc/noobnooc/discussions/9 。如果你连域名也不想注册，也可以用别人现成的反代地址，比如下面这个：https://openai.1rmb.tk/v1/chat/completions 。地址是群友提供的，不确定什么时候失效，用的人比较多时可能会有点卡，大家也可以进群求一个。

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

还有另一位热心网友基于本项目在github上的docker版chatgpt，网址：https://github.com/hsmbs/chatgpt-php ，也可以用。

6. 是否支持Windows客户端？

喜欢使用独立Windows桌面应用的朋友可以下载Release里面的exe文件运行，其实就是一个指向我演示网站的浏览器套个壳。

7. 有没有可以注册会员的商业运营版？

由于很多群友都有类似需求，我开发了一个款基于PHP+Mysql环境的商业版软件，目前还在测试中，即将正式发布。有兴趣的话您可以访问这里查看详情：https://github.com/dirk1983/chatgpt_commercial

------

附OpenAI官网的模型和接口调用介绍：

https://platform.openai.com/docs/models/moderation

https://platform.openai.com/docs/api-reference/chat/create

https://platform.openai.com/docs/guides/chat/introduction

https://platform.openai.com/docs/api-reference/models/list

------
**对chatgpt感兴趣的同学们欢迎加群讨论。群里有很多大神，有问题可以互相帮助。如果需要在本项目基础上进行二次开发或者其他商务合作，可以加我微信沟通。**


旧群人已满，请加新群，预计一周左右人就能满。

![群二维码](https://user-images.githubusercontent.com/5563148/232360558-9f79606d-a16e-4810-8721-9ff82c858e41.png)


有热心网友建议我放个打赏码，各位如果真的想表达感谢，小额即可。

![打赏码](https://user-images.githubusercontent.com/5563148/222968018-9def451a-bbce-4a7e-bde6-edecc7ced40f.jpg)

最后，我还做了个在微信个人订阅号中通过调用OpenAI最新接口和gpt-3.5-turbo模型实现ChatGPT聊天机器人的功能，已开源，需要的朋友也可以拿去。
https://github.com/dirk1983/chatgpt-wechat-personal
