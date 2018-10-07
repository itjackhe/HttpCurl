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
     * @var string 响应数据格式 text|json
     */
    private $dataType = 'text';


    /**
     * @var string 请求的url
     */
    private $url = '';

    /**
     * @var int 超时秒数
     */
    private $timeOut = 3;


    /**
     * @var array 请求携带数据
     */
    private $data = null;


    /**
     * @var array http header
     */
    private $header = null;


    /**
     * @var string http userAgent
     */
    private $userAgent = null;



    /**
     * @var string http proxy
     */
    private $proxy = null;


    /**
     * @var int http proxyPort
     */
    private $proxyPort = null;



    /**
     * @var int http 是否显示header信息
     */
    private $showHeader = 0;




    /**
     * @var string 来源页面地址
     */
    private $referer = null;




    /**
     * @var string 证书地址
     */
    private $cainfo = null;




    /**
     * @var int 最后一次 请求的http响应码
     */
    private $http_code = 0;



    /**
     * 构造方法
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @return $this
     */
    public function __construct()
    {
        //判断php版本、小于 5.3.0 提示用户更新
        if(version_compare(PHP_VERSION,'5.3.0', '<')){
            exit('<h1>Please upgrade PHP version to 5.3+</h1>');
        }
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
        $this->header = $header;
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
        $this->userAgent = $agent;
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
     * @param string $url 代理地址
     * @return $this
     */
    public function proxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }






    /**
     * 设置 http 代理端口
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param int $port 代理端口
     * @return $this
     */
    public function proxyPort($port)
    {
        $this->proxyPort = $port;
        return $this;
    }






    /**
     * 设置http响应header头
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param bool $show 是否显示
     * @return $this
     */
    public function showHeader($show = 0)
    {
        $show = $show == 1 || $show === true ? 1 : 0;
        $this->showHeader = $show;
        return $this;
    }








    /**
     * 设置来源页面地址
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $referer 来源地址
     * @return $this
     */
    public function referer($referer = null){
        $this->referer = $referer;
        return $this;
    }





    /**
     * 设置证书路径
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $path 证书路径
     * @return $this
     */
    public function cainfo($path) {
        $this->cainfo = $path;
        return $this;
    }




    /**
     * 响应数据格式 text|json
     * @access public
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
     * @return $this
     */
    public function data($data = null){

        if(!empty($data)){
            $this->data = $data;
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
     * 获取最后一次请求的 http_code
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @return int
     */
    public function getLastHttpCode(){
        return $this->http_code;
    }







    /**
     * get 请求方法
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url
     * @param array $data 请求携带数据
     * @return mixed
     */
    public function get($url,$data = null)
    {
        //设置 请求url
        $this->url($url);
        //设置 请求携带数据
        $this->data($data);
        $this->ch = curl_init();
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
     * @access public
     * @author jackhe
     * @date 2018-06-21
     * @param string $url 请求的url
     * @param array || string $data 数据
     * @return mixed
     */
    public function post($url,$data= null)
    {

        //设置 请求url
        $this->url($url);
        //设置 file路径数组
        $this->data($data);
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1 );

        // 设置post内容
        if(!empty($this->data)) {
            $data = array();
            //判断是字符串 并且 存在
            if(is_string($this->data)){
                $data = $this->data;
            }

            //判断是数组 并且 存在
            if(is_array($this->data) && (!empty($this->data))){
                foreach ($this->data as $key=>$val){

                    if(stripos($val,'@') === 0){
                        $val = trim($val,'@');
                        if(version_compare(PHP_VERSION,'5.5.0', '>=')){
                            $data[$key] = new CURLFile(realpath($val));
                        }else{
                            $data[$key] = '@'.realpath($val);
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
     * http 请求方法
     * @access private
     * @author jackhe
     * @date 2018-06-21
     * @return mixed
     */
    private function httpRequest(){

        //设置超时秒数
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeOut);

        //http请求头
        if(is_array($this->header)){
            curl_setopt($this->ch, CURLOPT_HTTPHEADER , $this->header);
        }

        //http头信息是否显示
        if(is_array($this->header)){
            curl_setopt($this->ch, CURLOPT_HEADER, $this->showHeader);
        }

        //用户代理
        if($this->userAgent) {
            //设置模拟用户使用的浏览器
            curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);
        }

        //代理地址
        if($this->proxy){
            curl_setopt ($this->ch, CURLOPT_PROXY, $this->proxy);
        }

        //代理端口
        if(is_int($this->proxyPort)){
            curl_setopt($this->ch, CURLOPT_PROXYPORT, $this->proxyPort);
        }

        //来源页面地址
        if ($this->referer){
            curl_setopt($this->ch, CURLOPT_REFERER , $this->referer);
        }

        //证书地址
        if($this->cainfo){
            curl_setopt($this->ch, CURLOPT_CAINFO, $this->cainfo);
        }

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
        $this->http_code = curl_getinfo($this->ch,CURLINFO_HTTP_CODE);
        //关闭 curl 句柄
        curl_close($this->ch);
        $this->ch = null;
        $this->data = null;
        //200状态码
        if($this->http_code == 200) {
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

}
