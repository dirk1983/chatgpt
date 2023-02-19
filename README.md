# chatgpt
PHP版调用openai的api接口进行问答的Demo。

页面UI简洁，支持连续对话，支持保存查询日志。

核心代码只有一两个文件，没有用任何框架，修改调试很方便，只需要修改message.php中的API_KEY即可使用。

index.php前面的代码还可以实现区分内外网IP，内网直接访问，外网通过BASIC认证后可访问。可以根据需要删掉注释并进行修改。

适合放在公司内网，让同事们一起体验chatGPT的强大功能，或者自己用。

FAQ：
部署调试时请注意两点，一个是要有curl扩展，一个是chat.txt文件要有写权限。

严格地说，这个接口和官网的网页版chatgpt是不一样的，基于GPT3（官方chatgpt是GPT3.5），可以理解为稍弱版chatgpt。由于接口限制，问题和答案最多4096个字节，UTF-8编码一个汉字3个字节，所以只有1000多个汉字。由于接口是官方提供的，所以其实这个版本的稳定性是很好的。

github上也有一些大神提供了基于官方web版chatgpt的代码，原理就是把服务器模拟成一个客户端来和openai交互，用户所有请求通过服务器中转到openai。这个模式需要服务器IP是chatgpt支持的区域，并且稳定性差一些，问多了一段时间内可能会一直失败。好处是不限制问题和答案长度，不需要扣费。各位有兴趣可以了解一下：https://github.com/slippersheepig/chatgpt-html

![微信截图_20230216175637](https://user-images.githubusercontent.com/5563148/219332005-da550336-723d-4eef-9a67-ae16b0cca8ea.png)


对chatgpt感兴趣的同学们欢迎加群讨论

![微信截图_20230216182019](https://user-images.githubusercontent.com/5563148/219337838-35db2149-18c6-439c-827c-0889330a34f5.png)
