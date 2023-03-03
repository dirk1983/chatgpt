# chatgpt

------

2023-03-02 23:50:00更新提示：目前从国内访问OpenAI的新接口会提示超时，国外正常。建议把代码部署到海外的服务器上即可正常使用。如果实在没有环境，可以访问我搭建的中转服务器。把message.php文件中的接口地址指向这里：http://mm1.ltd/chatgpt.php

即把原来的：curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
修改为：curl_setopt($ch, CURLOPT_URL, 'http://mm1.ltd/chatgpt.php');

这样在国内也可以正常调用接口了。

------


PHP版调用OpenAI的API接口进行问答的Demo，代码已更新为调用最新的gpt-3.5-turbo模型。接口格式有些变化，代码已适配，实测服务器响应更快，效果更好。

页面UI简洁，支持连续对话，支持保存查询日志。

核心代码只有一两个文件，没有用任何框架，修改调试很方便，只需要修改message.php中的API_KEY即可使用。

index.php前面的代码还可以实现区分内外网IP，内网直接访问，外网通过BASIC认证后可访问。可以根据需要删掉注释并进行修改。

适合放在公司内网，让同事们一起体验chatGPT的强大功能，或者自己用。

![微信截图_20230302172448](https://user-images.githubusercontent.com/5563148/222393529-f21d8db3-0079-4062-bd0f-677d5f40aadc.png)


FAQ：
部署调试时请注意两点，一个是要有curl扩展，一个是chat.txt文件要有写权限。

之前OpenAI官方API提供的最先进的模型是text-davinci-003，比官网的ChatGPT稍弱一些。最近OpenAI终于放出了gpt-3.5-turbo模型，理论上和官网的ChatGPT几乎没区别了。只是由于接口限制，问题和答案最多4096个tokens，实测1个汉字算2个tokens。

github上也有一些大神提供了基于官方web版chatgpt的代码（ https://github.com/acheong08/ChatGPT ）。原理就是把服务器模拟成一个客户端来和openai交互，用户所有请求通过服务器中转到openai。这个模式需要服务器IP是chatgpt支持的区域，并且稳定性差一些，问多了一段时间内可能会一直失败。好处是不限制问题和答案长度，不需要扣费。不过最新的模型放出来之后，这种方案就更加鸡肋了，好在之前没投入太多精力研究……

OpenAI官网的模型和接口调用介绍：

https://platform.openai.com/docs/models/moderation

https://platform.openai.com/docs/api-reference/chat/create

https://platform.openai.com/docs/guides/chat/introduction

https://platform.openai.com/docs/api-reference/models/list


对chatgpt感兴趣的同学们欢迎加群讨论

![微信截图_20230302202854](https://user-images.githubusercontent.com/5563148/222429139-f71c6bd3-8145-4038-9cd5-8654c7cf77c1.png)


