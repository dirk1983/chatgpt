# chatgpt
------
**PHP版调用OpenAI的API接口进行问答的Demo，代码已更新为调用最新的gpt-3.5-turbo模型。
采用Stream流模式通信，一边生成一边输出，响应速度超过官网。前端采用JS的EventSource，还将Markdown格式文本进行了排版，对代码进行了着色处理。**
很多人想要Demo网站中自己输入API-KEY的功能，已经把代码加上了，取消index.php的注释就行了。为了美观可以把上面的“连续对话”部分注释掉，要不然手机访问不是很友好。

在国内访问OpenAI的新接口会提示超时，如果你本地有HTTP-PROXY，可以把stream.php里面注释掉的“curl_setopt($ch, CURLOPT_PROXY, " http://127.0.0.1:1081 ");”修改一下，这样就可以通过你本地的代理访问openai的接口。如果你自己没代理，可以使用热心网友提供的反代地址，把“curl_setopt($ch, CURLOPT_URL, ' https://api.openai.com/v1/chat/completions ');”这行里面的网址改成' https://openai.1rmb.tk/v1/chat/completions '能用多久无法保证哦

*测试网址：http://mm1.ltd*

![微信图片_20230304200105](https://user-images.githubusercontent.com/5563148/222899925-c8cbdd67-2560-4853-af44-cf45fe7725d9.png)

------

核心代码只有几个文件，没有用任何框架，修改调试很方便，只需要修改stream.php中的API_KEY即可使用。

index.php前面的代码还可以实现区分内外网IP，内网直接访问，外网通过BASIC认证后可访问。可以根据需要删掉注释并进行修改。

适合放在公司内网，让同事们一起体验chatGPT的强大功能，或者自己用。


FAQ：

之前OpenAI官方API提供的最先进的模型是text-davinci-003，比官网的ChatGPT稍弱一些。最近OpenAI终于放出了gpt-3.5-turbo模型，理论上和官网的ChatGPT几乎没区别了。只是由于接口限制，问题和答案最多4096个tokens，实测1个汉字算2个tokens。

github上也有一些大神提供了基于官方web版chatgpt的代码（ https://github.com/acheong08/ChatGPT ）。原理就是把服务器模拟成一个客户端来和openai交互，用户所有请求通过服务器中转到openai。这个模式需要服务器IP是chatgpt支持的区域，并且稳定性差一些，问多了一段时间内可能会一直失败。好处是不限制问题和答案长度，不需要扣费。不过最新的模型放出来之后，这种方案就更加鸡肋了，好在之前没投入太多精力研究……

OpenAI官网的模型和接口调用介绍：

https://platform.openai.com/docs/models/moderation

https://platform.openai.com/docs/api-reference/chat/create

https://platform.openai.com/docs/guides/chat/introduction

https://platform.openai.com/docs/api-reference/models/list


对chatgpt感兴趣的同学们欢迎加群讨论。群里有很多大神，有问题可以互相帮助。如果需要在本项目基础上进行二次开发或者其他商务合作，可以加我微信沟通。

![微信截图_20230302202854](https://user-images.githubusercontent.com/5563148/222429139-f71c6bd3-8145-4038-9cd5-8654c7cf77c1.png)


