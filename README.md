# Wordpress后台登录安全码插件

本插件主要功能为Wordpress后台登录时的主动获取安全码，需要配合微信个人订阅号命令机器人（https://github.com/lijialong1314/weixin-command）使用。

## 插件部署方法

1、把login-sec-code目录放到wp-content/plugins目录下

2、修改config.php配置，是redis的连接信息，用于保存生成的安全码，利用自动过期功能，可以自动销毁验证码

3、登录后台开启插件，配置您自己的openid，此openid为您自己部署的订阅号机器人或者使用我部署的（@PocketRobot）

4、进入登录界面测试，在微信中（可以关注微信订阅号@PocketRobot）输入

```
/code
```

获取验证码后，填入表单。

5、登录，完成。

## 截图

微信中的操作截图：

![](https://ws2.sinaimg.cn/large/62831495ly1fvyapv7sg2j20yi1pcqgw.jpg)


Wordpress后台登录截图：

![](https://ws2.sinaimg.cn/large/62831495ly1fvyarge9puj209j0cumxf.jpg)