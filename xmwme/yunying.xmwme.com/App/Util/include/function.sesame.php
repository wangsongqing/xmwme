<?php
function strExist($str1, $str2)
{
    return !(strpos($str2, $str1) === FALSE);
}

// 自动转换字符集 支持数组转换
function autoCharset($fContents,$from,$to)
{
    $from   =  strtoupper($from)=='UTF8'? 'utf-8':$from;
    $to       =  strtoupper($to)=='UTF8'? 'utf-8':$to;
    if( strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents)) ){
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if(is_string($fContents) ) {
        if(function_exists('mb_convert_encoding')){
            return mb_convert_encoding ($fContents, $to, $from);
        }elseif(function_exists('iconv')){
            return iconv($from,$to,$fContents);
        }else{
            return $fContents;
        }
    }
    elseif(is_array($fContents)){
        foreach ( $fContents as $key => $val ) {
            $_key =     autoCharset($key,$from,$to);
            $fContents[$_key] = autoCharset($val,$from,$to);
            if($key != $_key ) {
                unset($fContents[$key]);
            }
        }
        return $fContents;
    }
    else{
        return $fContents;
    }
}


/**
+----------------------------------------------------------
 * 把日期格式转换成 unix 时间戳
+----------------------------------------------------------
 * @param  date   $time 如 2009-12-31 22:15:28
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function getTimeStamp($time)
{
    $time  = explode(' ', $time);
    $timeA = explode('-', $time[0]);
    $timeB = explode(':', $time[1]);

    return mktime($timeB[0], $timeB[1], $timeB[2], $timeA[1], $timeA[2], $timeA[0]);
}

/**
 + -------------------------------
 * 构建fileCache对象
 + -------------------------------
 * @return boject
 + -------------------------------
 */
function getFileCacheObj(){
    require_once(Lib.'/Util/FileCache.php');
    $path = '.'.Resource."/FileCache/";
    $cache = new FileCache($path);
    return $cache;
}

/**
+----------------------------------------------------------
 * 写入文件缓存
+----------------------------------------------------------
 * @param string  $key    缓存键值
 * @param mixed   $data  被缓存的数据
 * $expire int     存储时间
+----------------------------------------------------------
 * @return boolean
+----------------------------------------------------------
 */
function setFileVar($key,$data,$expire=3600){
    $obj = getFileCacheObj();
    return $obj->set($key, $data,$expire);
}

/**
+----------------------------------------------------------
 * 读取文件缓存
+----------------------------------------------------------
 * @param string $key 缓存键值
+----------------------------------------------------------
 * @return mixed
+----------------------------------------------------------
 */
function getFileVar($key){
    $obj = getFileCacheObj();
    return $obj->get($key);
}

/**
+----------------------------------------------------------
 * 删除文件缓存
+----------------------------------------------------------
 * @param string $key 缓存键值
+----------------------------------------------------------
 * @return boolean
+----------------------------------------------------------
 */
function delFileVar($key){
    $obj = getFileCacheObj();
    return $obj->remove($key);
}

/**
+----------------------------------------------------------
 * 构造MmCache 对象
+----------------------------------------------------------
 * @return object
+----------------------------------------------------------
 */
function getCacheObj()
{
    static $import = true;
    static $cache  = null;
    if ($import)
    {
        require_once(Lib.'/Util/ICache.php');
        require_once(Lib.'/Util/MmCache.php');
    }
    $import = false;
    if ($cache == null)
    {
        $cache = new MmCache();
        $cache->prefix = 'run_';
        $cache->expire = 18000;
        $cache->compressed = true;
        $cache->configFile = 'Config/memcache.config.php';
    }
    return $cache;
}


/**
+----------------------------------------------------------
 * 读取缓存
+----------------------------------------------------------
 * @param string $key 缓存键值
+----------------------------------------------------------
 * @return mixed
+----------------------------------------------------------
 */
function getVar($key)
{
    return getCacheObj()->get($key);
}


/**
+----------------------------------------------------------
 * 写入缓存
+----------------------------------------------------------
 * @param string  $key    缓存键值
 * @param mixed   $value  被缓存的数据
+----------------------------------------------------------
 * @return boolean
+----------------------------------------------------------
 */
function setVar($key, $value, $expire=18000)
{
    getCacheObj()->expire = $expire;
    return getCacheObj()->set($key, $value);
}


/**
+----------------------------------------------------------
 * 删除缓存
+----------------------------------------------------------
 * @param  string $key 缓存键值
+----------------------------------------------------------
 * @return boolean
+----------------------------------------------------------
 */
function delVar($key)
{
    return getCacheObj()->remove($key);
}
//通过key 获取认证数据
function getAuth($key)
{
    //当前登录IP与，CookieIP是否一致
    $loginIp  = getIp();

    $auth = getCookie('manageAuth');
    $auth = explode("\t", $auth);

    list($user['admin_id'],$user['admin_name'],$user['mobile'],$user['password'],$user['url_code'],$user['group_id'],$user['group_name'], $user['loginIp'], $user['loginTime']) = empty($auth) || count($auth) < 9 ? array( '', '', '', '','', '', '', '', '') : $auth;

    if ($key == 'all')
    {
        return $user;
    }
    else
    {
        return isset($user[$key]) ? $user[$key] : '';
    }
}

//设置认证数据 adminAuth
function setAuth($user, $time=0)
{
    addCookie('manageAuth', "{$user['admin_id']}\t{$user['admin_name']}\t{$user['mobile']}\t{$user['password']}\t{$user['url_code']}\t{$user['group_id']}\t{$user['group_name']}\t{$user['loginIp']}\t{$user['loginTime']}", $time);
}

//通过key 更新认证数据
function updateAuth($key, $value)
{
    $user = getAuth('all');
    if (isset($user[$key]))
    {
        $user[$key] = $value;
        setAuth($user, 360000);
    }
}
//判断当前用户是否登录
function loginCheck()
{
    $user = getAuth('all');
    if (empty($user)) return false;
    //getVar登录成功后设置
    $username = isset($user['admin_name']) ? $user['admin_name']   : '';
    $password = isset($user['password']) ? $user['password'] : '';
    if (empty($username) || empty($password)) return false;
    return $password == getVar('manage_'.$username) ? true : false;
}

//简化信息提示输出
function showMsg($msg, $url = '', $isXML = false, $script = array())
{
    static $import = true;
    static $msgObj = null;
    if (isset($_REQUEST['ajax'])) {
        if($isXML) {
            header('Expires: -1');
            header('Cache-Control: no-cache, private, post-check=0, pre-check=0, max-age=0');
            header('Pragma: no-cache');
            header('Content-Type: application/xml; charset=utf-8');
            echo '<?xml version="1.0" encoding="utf-8"?>';
            echo "<root><![CDATA[".trim($msg)."]]></root>";
        } else {
            echo $msg;
        }
        exit();
    } else {
        if ($import) {
            require_once(Lib.'/Util/Message.php');
            $import = false;
        }
        if (null == $msgObj) {
            $msgObj = new Message();
        }
        $msgObj->show($msg, $url, $script);
    }
}

//得到自定义字符串对象
function getStringObj()
{
    static $import = true;
    static $stringObj = null;
    if ($import) {
        require_once(App.'/Util/String.php');
        $import = false;
    }
    if (null == $stringObj) {
        $stringObj = new String();
    }
    return $stringObj;
}

//截取字符串
function strCut($string, $cutLength, $isTrim = false, $charset = 'utf-8', $enHtml = true)
{
    return getStringObj()->cut($string, $cutLength, $isTrim, $charset, $enHtml);
}

//格式化文件大小
function sizeCount($size)
{
    if ($size < 1024) {
        return $size.'B';
    }
    $units = array(
        'TB' => 1024 * 1024 * 1024 * 1024,
        'GB' => 1024 * 1024 * 1024,
        'MB' => 1024 * 1024,
        'KB' => 1024,
    );
    foreach ($units as $unit => $value) {
        if ($size >= $value) {
            $size = round($size/$value, 2) .$unit;
            break;
        }
    }
    return $size;
}

/**
 * 清除字符串中html编码实体
 *
 * @param string $string
 * @return string
 * @author fengxu
 */
function clearHtmlEntities($string)
{
    $entities = array_values(get_html_translation_table(HTML_ENTITIES));
    return str_replace($entities, '', $string);
}
/**
 * 请求远程地址
 *
 * @param string $url 请求url
 * @param mixed $postFields 请求的数据
 * @param string $referer 来源网址
 * @param integer $timeOut 请求超时时间
 * @return mixed 错误返回false，正确返回获取的字符串
 * @author fengxu
 */
function httpRequest($url, $postFields = null, $referer = null, $timeOut = 10)
{
    if(empty($url) || !preg_match("#https?://[\w@\#$%*&=+-?;:,./]+#i", $url)) {
        return false;
    }
    $isPost = empty($postFields) ? false : true;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($isPost) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    }
    curl_setopt($ch, CURLOPT_USERAGENT, ($userAgent = ini_get('user_agent')) ? $userAgent : 'Edai Broswer');
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //对于大于或等于400的状态码返回false
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept-Charset: GB2312,utf-8',
        'Accept-Language: zh-cn,zh',
        'Connection: close',
    ));
    $response = curl_exec($ch);
    return $response;
}

/**
 * 抓取文件内容
 *
 * @param string   $fileUrl 抓取内容的地址
 * @param string   $parameter 参数
 * @param int      $timeout 过期时间
 * @param string   $method  GET POST
 *
 * @return string
 */
function getcontent($fileUrl, $parameter, $method='GET', $timeout=60)
{
    $opts = array(
        'http'=>array(
            'method'=>$method,
            'timeout'=>$timeout,
            'header'=>"User-Agent: Mozilla/5.0\n"
        )
    );
    $context = stream_context_create($opts);

    $parameter = serialize($parameter);

    $str = base64_encode(authcode($parameter, 'ENCODE', FaceKey));   //加密
    $url = $fileUrl.'/?str='.$str;
    //echo $url;die;
    $result =parsecontent(file_get_contents($url, false, $context));
    return $result;
}


/**
 * 返回内容
 *
 * @param max      $str
 *
 * @return string
 */
function retruncontent($str)
{
    return base64_encode(serialize($str));
}

/**
 * 解析内容
 *
 * @param string      $str
 *
 * @return string
 */
function parsecontent($str)
{
    $data = unserialize(base64_decode($str));
    return $data;
}
/**
 * 手机归属地获取
 * @param string $mobile
 * @return Array ( [province] => 湖北 [city] => 荆州  [company] => 中国移动 [card] => 移动全球通卡 )
 */
function getMobile($mobile = '')
{
    $phone = trim($mobile);
    if(empty($phone)) {
        return false;
    }
    $phone = mb_substr($phone, 0, 7);
    $mobileApiUrl = 'http://apis.juhe.cn/mobile/get';//接口文档地址：https://www.juhe.cn/docs/api/id/11
    $urlAIP = $mobileApiUrl . '?key=a587c0734612835a8ba812f8b33f8610&dtype=json&phone=' . $phone;
    $rs     = httpRequest($urlAIP);
    if (empty($rs)) {
        return false;
    }
    $rs = (Array)json_decode($rs);

    if (isset($rs['result'])) {
        $rs = (Array)$rs['result'];
    }
    if (isset($rs['province']) && $rs['province']) {
        if (isset($rs['areacode'])) {
            unset($rs['areacode']);
        }
        if (isset($rs['zip'])) {
            unset($rs['zip']);
        }
        return $rs;
    } else {
        return false;
    }
}
/**
+----------------------------------------------------------
 * 获取指定文件的文件类型
+----------------------------------------------------------
 * @param  string  $filename    文件路径与文件名
+----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function getFileType($filename)
{
    $file = fopen($filename, "rb");
    $bin = fread($file, 2); //只读2字节
    fclose($file);
    $strInfo = @unpack("C2chars", $bin);
    $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
    $fileType = '';
    switch ($typeCode)
    {
        case 7790:
            $fileType = 'exe';
            break;
        case 7784:
            $fileType = 'midi';
            break;
        case 8297:
            $fileType = 'rar';
            break;
        case 255216:
            $fileType = 'jpg';
            break;
        case 7173:
            $fileType = 'gif';
            break;
        case 6677:
            $fileType = 'bmp';
            break;
        case 13780:
            $fileType = 'png';
            break;
        default:
            echo 'unknown';
    }
    return $fileType;
}
/**
 * 获取格式化后的数字
 *  @param $num 数字
 */
function sprintf_num($num=0) {
    $num = floor($num*100)/100;
    $num = sprintf('%.2f',$num);
    return $num;
}

/**检验是否为手机
 * @param $str
 * @return int
 */
function isMobile($str)
{
    $mobileMatch = "/^1(4|3|5|8|7)\d{9}$/";
    if (preg_match($mobileMatch, $str) ) {
        return 1;
    }
    return 0;
}

/**检验是否为邮箱
 * @param $str
 * @return int
 */
function isEmail($str)
{
    $mailMatch   = "/^[\w\.\-]+@[\w\.\-]+\.[a-zA-Z]+$/";
    if ( preg_match($mailMatch, $str) ) {
        return 1;
    }
    return 0;
}
/**
 * 隐藏电话号码
 * @param  string   $phone
 * @return void
 */
function hiddenPhone($phone)
{
    if ($phone && is_numeric($phone) && !strpos($phone, '@')) {
        if ($phone && strlen($phone) > 10) {
            $start = substr($phone,0,7);
            $phone = $start.'****';
        } else {
            $start = substr($phone,0,5);
            $phone = $start.'***';
        }
    }
    return $phone;
}

/**
 * 获取前台页面展示的路径
 * @param unknown $filename
 * @return string
 */
function get_img_url($filename){
    if(strpos($filename,'Resource')===false)
    {
        $filename = '/Resource/upload/'.$filename;
    }
    if (!file_exists(dirname(__FILE__).'/../..'.$filename)){
        $filename = '/Resource/images/logo.gif';
    }
    return $filename;
}
/**
 * 下载远程图片到本地
 * @param unknown $url
 * @param unknown $dirname
 * @return string|boolean
 */
function saveImage($url,$dirname){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $package = curl_exec($ch);
    $httpinfo = curl_getinfo($ch);

    curl_close($ch);
    $media = array_merge(array('mediaBody' => $package), $httpinfo);

    //求出文件格式
    preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
    $fileExt = $extmatches[1];
    $filename = time().rand(10,99999).".{$fileExt}";
    if(!file_exists($dirname)){
        mkdir($dirname,0777,true);
    }
    $re = file_put_contents($dirname.$filename,$media['mediaBody']);
    if ($re){
        return $filename;
    }
    return false;
}
/**
 * 生成缩略图
 * @param unknown $file_path 原图路径
 * @param number $width 宽度
 * @param number $height 高度
 * @return string 文件名
 */
function thumbImage($file_path,$width=120,$height=120){
    include(App . '/Util/ImageResize.php');
    $thumbpath = $file_path.'.'.$width.'X'.$height.'.'.pathinfo(basename($file_path),4);

    $imgresize = new ImageResize(); //创建图片缩放和裁剪类

    $imgresize->load($file_path); //载入原始图片

    $imgresize->resize($width, $height);
    $imgresize->save($thumbpath);

    return basename($thumbpath);
}

/**
 * 判断浏览器  1、pc 2、iPhone 3、ipad 4、Android
 * @return int
 */
function terminal()
{
    //获取USER AGENT
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    //分析数据
    $is_pc = (strpos($agent, 'windows nt')) ? true : false;
    $is_iphone = (strpos($agent, 'iphone')) ? true : false;
    $is_ipad = (strpos($agent, 'ipad')) ? true : false;
    $is_android = (strpos($agent, 'android')) ? true : false;

    //输出数据
    if ($is_pc) {
        return 1;
    }
    if ($is_iphone) {
        return 2;
    }
    if ($is_ipad) {
        return 3;
    }
    if ($is_android) {
        return 4;
    }
}

/**
 * 随机生成字符串
 * @param $length
 * @return null|string
 */
function getRandChar($length){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;
    for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }
    return $str;
}
/**
 * 数字连续相加(第一个数字+第二个数字+....)
 * @return string
 */
function num_add(){
    $args = func_get_args();
    $num = func_num_args();
    if ($num<1){
        return 0;
    }
    $total = 0;
    foreach ($args as $v){
        $total = bcadd($total,$v,3);
    }
    return $total;
}

/**
 * 数字连续相减(第一个数字-第二个数字-....)
 * @return string
 */
function num_sub(){
    $args = func_get_args();
    $num = func_num_args();
    if ($num<1){
        return 0;
    }
    $total = $args[0];
    unset($args[0]);
    
    if (!empty($args)){
        foreach ($args as $v){
            $total = bcsub($total,$v,3);
        }
    }
    return $total;
}
/**
 * 获取插入的sql语句，用于压入redis，对core库
 * @param unknown $table
 * @param unknown $insert_data
 * @return boolean|string
 */
function get_insert_sql($table,$insert_data){
    if (empty($insert_data)){
        return false;
    }
    $keys = "`".join("`,`", array_keys($insert_data))."`";
    $values = "'".join("','", $insert_data)."'";
    $sql = 'insert ignore into zm_'.$table.'('.$keys.') values('.$values.');';
    return $sql;
}
/**
 * 获取更新的sql语句，用于压入redis，对core库
 * @param unknown $table
 * @param unknown $update_data
 * @param unknown $rules
 * @param unknown $and_self
 * @return boolean|string
 */
function get_update_sql($table,$update_data,$rules,$add_self=array()){
    if (empty($update_data) || empty($rules)){
        return false;
    }
    $update = array();
    foreach ($update_data as $k=>$v){
        if ( array_key_exists($k, $add_self) ) {
            if ($add_self[$k] == 'add') {
                $update[] = "`$k`=`$k` + $v";
            } else if ($add_self[$k] == 'minus') {
                $update[] = "`$k`=`$k` - $v";
            } else {
                return false;
            }
        } else {
            $update[] = "`$k`='$v'";
        }
    }
    $where = where($rules);
    $sql = 'update zm_'.$table.' set '.join(',', $update).' '.$where.';';
    return $sql;
}

/**
 * 给二维数组排序
 * @param type $arrays 数组
 * @param type $sort_key 按某个字段排序
 * @param type $sort_order 排序规则：升序还是降序
 * @param type $sort_type
 * @return boolean
 */
 function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC){   
        if(is_array($arrays)){   
            foreach ($arrays as $array){   
                if(is_array($array)){   
                    $key_arrays[] = $array[$sort_key];   
                }else{   
                    return false;   
                }   
            }   
        }else{   
            return false;   
        }  
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
        return $arrays;   
    }
    
    /**
    * 判断是否为微信
    * @return bool
    */
   function isWeixin()
   {
       if(isset($_SERVER['HTTP_USER_AGENT'])){
           if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false && strpos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') === false ){
               return false;//非微信
           }else{
               return true;
           }
       }else{
         return  false;
       }
   }
?>