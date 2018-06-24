<?php
/**
 * Http Curl 工具类
 * @author  jackhe
 * @email  itjackhe@163.com
 * @version  v1.0.0
 * @date  2018-06-21
 */
class HttpCurl{

    /**
     * @var resource cURL句柄
     */
    private $ch = null;


    /**
     * @var int 超时秒数
     */
    private $timeOut = 3;



    /**
     * @var string 请求的url
     */
    private $url = '';


    /**
     * @var array 请求携带数据
     */
    private $data = null;


    /**
     * @var bool 请求携带数据的扩展
     */
    private $dataParams = false;




    /**
     * @var string 响应数据格式 text|json
     */
    private $dataType = 'text';



    /**
     * 构造方法
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @return $this
     */
    public function __construct()
    {
        $this->ch = curl_init();
    }





    /**
     * 设置 http header
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @param array $header http header
     * @return $this
     */
    public function header($header = null) {
        if(is_array($header)){
            curl_setopt($this->ch, CURLOPT_HTTPHEADER , $header);
        }
        return $this;
    }





    /**
     * 设置用户代理
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $agent 用户代理
     * @return $this
     */
    public function userAgent($agent = null)
    {
        if(!empty($agent)) {
            //设置模拟用户使用的浏览器
            curl_setopt($this->ch, CURLOPT_USERAGENT, $agent);
        }
        return $this;
    }




    /**
     * 设置用户代理
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url
     * @param array $data 请求携带数据
     * @return $this
     */
    public function timeout($timeout = 0)
    {
        //判断小于 1 、设置最小 为 3秒
        if($timeout < 1){
            $timeout = 3;
        }
        //这种
        $this->timeOut = $timeout;
        return $this;
    }



    /**
     * 设置 http 代理
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url
     * @param array $data 请求携带数据
     * @return $this
     */
    public function proxy($proxy)
    {
        if($proxy){
            curl_setopt ($this->ch, CURLOPT_PROXY, $proxy);
        }
        return $this;
    }






    /**
     * 设置 http 代理端口
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url
     * @param array $data 请求携带数据
     * @return $this
     */
    public function proxyPort($port)
    {
        if(is_int($port)) {
            curl_setopt($this->ch, CURLOPT_PROXYPORT, $port);
        }
        return $this;
    }






    /**
     * 设置http响应header头
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $show 是否显示
     * @return $this
     */
    public function showHeader($show = 0)
    {
        $show = $show == 1 || $show === true ? 1 : 0;
        curl_setopt($this->ch, CURLOPT_HEADER, $show);
        return $this;
    }








    /**
     * 设置来源页面地址
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @param string $referer 来源地址
     * @return $this
     */
    private function referer($referer = null){
        if (!empty($referer)){
            curl_setopt($this->ch, CURLOPT_REFERER , $referer);
        }
        return $this;
    }





    /**
     * 设置证书路径
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @param string $path 证书路径
     * @return $this
     */
    public function cainfo($path) {
        curl_setopt($this->ch, CURLOPT_CAINFO, $path);
        return $this;
    }




    /**
     * 响应数据格式 text|json
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @param string $type 响应数据格式
     * @return $this
     */
    public function dataType($type = 'text') {
        $this->dataType = $type;
        return $this;
    }






    /**
     * 设置请求携带数据
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param array $data 请求携带的参数
     * @param bool $params 请求携带的参数的额外参数、仅对 post方法有效
     * @return $this
     */
    public function data($data = null,$params = false){

        if(!empty($data)){
            $this->data = $data;
        }

        if(!empty($params)){
            $this->dataParams = $params;
        }
        return $this;
    }






    /**
     * 设置请求url地址
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url地址
     * @return $this
     */
    public function url($url = null){

        if(!empty($url)){
            $this->url = $url;
        }
        return $this;
    }







    /**
     * get 请求方法
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url
     * @param array $data 请求携带数据
     * @return mixed
     */
    public function get($url,$data = null)
    {
        //设置 请求url
        $this->url = $this->url($url);
        //设置 请求携带数据
        $this->data($data);

        //设置 get 参数
        if((!empty($this->data)) && is_array($this->data)){
            //判断 url 存在 ?
            if(strpos($this->url,'?') !== false){
                $this->url .= http_build_query($this->data);
            }else{
                $this->url .= '?' . http_build_query($this->data);
            }
        }

        //执行 http请求 并 返回响应内容
        return $this->httpRequest();
    }





    /**
     * post 请求方法
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url
     * @param array $data 请求携带数据
     * @param bool $params 是否将 @ 开头的数据进行上传
     * @return mixed
     */
    public function post($url,$data = null,$params = false)
    {
        //设置 请求url
        $this->url = $this->url($url);
        //设置 请求携带数据
        $this->data($data);
        //设置 请求携带额外参数
        $this->dataParams($params);

        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1 );
        // 设置post内容
        if(!empty($this->data)) {
            $data = array();
            //判断需要检查上传文件
            if($this->dataParams === true){
                //判断是字符串 并且 存在
                if(is_string($this->data) && (!empty($this->data))){
                    $this->data = $this->data;
                }else if(is_array($this->data) && (!empty($this->data))){//判断是数组 并且 存在
                    foreach ($this->data as $key=>$data){
                        if(strpos($data,'@') === 0){
                            if(version_compare(PHP_VERSION,'5.5.0', '>=')){
                                $data[$key] = new CURLFile(realpath($data));
                            }else{
                                $data[$key] = '@'.realpath($data);
                            }
                        }else{
                            $data[$key] = $data;
                        }
                    }
                }
            }

            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }

        //执行 http请求 并 返回响应内容
        return $this->httpRequest();
    }




    /**
     * post 上传文件方法
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url
     * @param array | string $data file路径数组
     * @return mixed
     */
    public function upload($url,$data= null)
    {
        //设置 请求url
        $this->url = $this->url($url);
        //设置 file路径数组
        $this->data($data);

        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1 );

        // 设置post内容
        if(!empty($this->data)) {
            $data = array();
            //判断是字符串 并且 存在
            if(is_string($this->data) && (!empty($this->data))){
                $this->data = array('data'=>$this->data);
            }

            //判断是数组 并且 存在
            if(is_array($this->data) && (!empty($this->data))){

                foreach ($this->data as $key=>$data){
                    if(version_compare(PHP_VERSION,'5.5.0', '>=')){
                        $data[$key] = new CURLFile(realpath($data));
                    }else{
                        $data[$key] = '@'.realpath($data);
                    }
                }
            }
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }

        //执行 http请求 并 返回响应内容
        return $this->httpRequest();
    }




    /**
     * http 请求方法
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @return mixed
     */
    private function httpRequest(){

        //设置超时秒数
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeOut);

        //处理 https
        if(stripos($this->url, 'https://') !== FALSE) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($this->ch, CURLOPT_SSLVERSION, 1);
        }

        //设置 url
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1 );

        //执行请求
        $content = curl_exec($this->ch);
        //获取请求 http_code
        $http_code = curl_getinfo($this->ch,CURLINFO_HTTP_CODE);
        //关闭 curl 句柄
        curl_close($this->ch);

        //200状态码
        if($http_code == 200) {

            //json格式
            if($this->dataType == 'json'){
                //返回数组
                return json_decode($content,true);
            }

            return $content;
        }else{
            return false;
        }

    }




    /**
     * 析构方法
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @return void
     */
    public function __destruct()
    {
        //关闭 curl 句柄
        curl_close($this->ch);
    }



}



    //实例化 HttpCurl 类实例
    //$HttpCurl = new \HttpCurl();

    /*get 请求*/
    /*
    //使用 get 第二参数传递数据
    $HttpCurl->get('http://www.baidu.com',array(1,2,4,5));
    //使用 data 传递 数据
    $HttpCurl->data(array(1,2,4,5))->get('http://www.baidu.com');
    //使用 url 传递 url
    $HttpCurl->url('http://www.baidu.com')->data(array(1,2,4,5))->get();
    //使用 url 传递 url 并 使用 get方法第二参数传递数据
    $HttpCurl->url('http://www.baidu.com')->get(null,array(1,2,4,5));
    */

    /*post 请求*/
    /*
    //使用 get 第二参数传递数据
    $HttpCurl->post('http://www.baidu.com',array(1,2,4,5));
    //使用 data 传递 数据
    $HttpCurl->data(array(1,2,4,5))->post('http://www.baidu.com');
    //使用 url 传递 url
    $HttpCurl->url('http://www.baidu.com')->data(array(1,2,4,5))->post();
    //使用 url 传递 url 并 使用 post方法第二参数传递数据
    $HttpCurl->url('http://www.baidu.com')->post(null,array(1,2,4,5));
    */

    /*文件上传 请求、支持多个文件*/
    /*
    //使用 upload 第二参数传递文件名
    $HttpCurl->upload('http://www.baidu.com',array('logo'=>'./logo.png'));
    //使用 data 传递 数据
    $HttpCurl->data(array('logo'=>'./logo.png'))->upload('http://www.baidu.com');
    //使用 url 传递 url
    $HttpCurl->url('http://www.baidu.com')->data(array('http://www.baidu.com'))->post();
    //使用 url 传递 url 并 使用 upload方法第二参数传递数据
    $HttpCurl->url('http://www.baidu.com')->upload(null,array('http://www.baidu.com'));
    */
