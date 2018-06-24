# HttpCurl
HttpCurl工具类

    /*get 请求*/
    //使用 get 第二参数传递数据
    (new \HttpCurl())->get('http://www.baidu.com',array(1,2,4,5));
    //使用 data 传递 数据
    (new \HttpCurl())->data(array(1,2,4,5))->get('http://www.baidu.com');
    //使用 url 传递 url
    (new \HttpCurl())->url('http://www.baidu.com')->data(array(1,2,4,5))->get();
    //使用 url 传递 url 并 使用 get方法第二参数传递数据
    (new \HttpCurl())->url('http://www.baidu.com')->get(null,array(1,2,4,5));


    /*post 请求*/
    //使用 get 第二参数传递数据
    (new \HttpCurl())->post('http://www.baidu.com',array(1,2,4,5));
    //使用 data 传递 数据
    (new \HttpCurl())->data(array(1,2,4,5))->post('http://www.baidu.com');
    //使用 url 传递 url
    (new \HttpCurl())->url('http://www.baidu.com')->data(array(1,2,4,5))->post();
    //使用 url 传递 url 并 使用 post方法第二参数传递数据
    (new \HttpCurl())->url('http://www.baidu.com')->post(null,array(1,2,4,5));


    /*文件上传 请求、支持多个文件*/

    //使用 upload 第二参数传递文件名
    (new \HttpCurl())->upload('http://www.baidu.com',array('logo'=>'./logo.png'));
    //使用 data 传递 数据
    (new \HttpCurl())->data(array('logo'=>'./logo.png'))->upload('http://www.baidu.com');
    //使用 url 传递 url
    (new \HttpCurl())->url('http://www.baidu.com')->data(array('logo'=>'./logo.png'))->upload();
    //使用 url 传递 url 并 使用 upload方法第二参数传递数据
    (new \HttpCurl())->url('http://www.baidu.com')->upload(null,array('logo'=>'./logo.png'));
