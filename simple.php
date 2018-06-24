<?php
    require 'HttpCurl.php';


    /*  实例化 HttpCurl类  */
    $HttpCurl = new HttpCurl();



    /*  模拟GET请求  */
    //基础请求
    //小提示：因为请求的url不一定会响应请求、测试需要放到可响应的地址
    $data = $HttpCurl->get('https://www.baidu.com',array('user_name'=>'jackhe','user_pwd'=>'123456'));


    /*  模拟POST请求  */
    //基础请求
    //小提示：因为请求的url不一定会响应请求、测试需要放到可响应的地址
    $data = $HttpCurl->post('https://www.baidu.com',array('user_name'=>'jackhe','user_pwd'=>'123456'));


    /*  模拟上传文件请求  */
    //基础请求、
    //小提示：因为请求的url不一定会响应上传请求、测试需要放到可响应的地址
    $data = $HttpCurl->upload('https://www.baidu.com',array('head_img'=>'./user.png'));


    /*  链式操作例子、以GET请求为例、允许多个同时使用  */
    //小提示：因为请求的url不一定会响应请求、测试需要放到可响应的地址
    //1：设置url
    $data = $HttpCurl->url('https://www.baidu.com')->get();
    //2：设置 携带参数
    $data = $HttpCurl->data(array('user_name'=>'jackhe','user_pwd'=>'123456'))->get('https://www.baidu.com');
    //3：设置返回数据类型
    $data = $HttpCurl->dataType('json')->get('https://www.baidu.com');


    /*  所有链式操作例子、方法除了 get|post|upload 外没有先后顺序  */
    //小提示：因为请求的url不一定会响应请求、测试需要放到可响应的地址
    $result = $HttpCurl->url('https://www.baidu.com')
             ->dataType('text')//设置返回类型、json后返回数组
             ->timeout(3)//设置请求 超时响应时间 （秒）
             ->showHeader(0)//设置响应头 1|0  true|false
             ->header(array('Content-Type:application/json;charset=utf-8'))//设置请求 header信息
             ->userAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36')//设置请求 header信息
             ->proxy('代理地址')//设置代理地址
             ->proxyPort('代理端口')//设置代理端口
             ->referer('来源url地址')//设置来源地址
             ->cainfo('您的证书地址')//设置证书地址
             ->data(array('id'=>1))
             ->post();

    //获取 http_code
    if($HttpCurl->getLastHttpCode() != 200){
        exit('http_code:error');
    }else{
        var_dump($result);
    }
