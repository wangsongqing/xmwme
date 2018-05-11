<?php

/**
* 返回action middle ware 的模块映射
*     return array(
*	    'manage_user'             => 'manage_user' ,
*	    'manage_log'             => 'manage_log',
*	    'import_user'            =>'import_user',
*	);
*/
function actionModels(){
    $file = Config.'/datatable.config.php';
    if(file_exists($file)){
	require $file;
	$_arr = array();
	foreach($tbl as $key=>$val){
	    $_arr[$key] = '../api/Model/'.$key;
	}
	return $_arr;
    }
}

/**
 * 模型方法，去获取数据对象
 * @param string $action
 * @return object
 */
function M($action=''){
    if(empty($action)){return false;}
    $model = SystemParams::getDB($action);
    return $model;
}


/**
 * 翻页自定义组合
 *
 * @param array   $pageBar 组合要素
 * @param int     $param   是否显示总条数，总页数；1显示总条数，2显示总页数，3显示总条数总页数
 * @param boolean $script  是否带有下拉,默认为true
 *
 * @return string 组合html代码
 */
function pageBar($pageBar, $param=0, $script=false)
{
	$html = '';
	
	switch ($param) {
		case 1:
			$html .= '共' . $pageBar['recordNum'] . '条' . '&nbsp;';
			break;
		case 2:
			$html .= '共' . $pageBar['pageNum']   . '页' . '&nbsp;';
			break;
        case 3:
            $html .= '共' . $pageBar['recordNum'] . '条/' . '共' . $pageBar['pageNum'] . '页' . '&nbsp;';
            break;
        default:
			break;
	}
	
	$html .= '<a href="' . $pageBar['first'] . '">首页</a>' . '&nbsp;' .
			 '<a href="' . $pageBar['pre']   . '">上页</a>' . '&nbsp;' .
			 '<a href="' . $pageBar['next']  . '">下页</a>' . '&nbsp;' .
			 '<a href="' . $pageBar['last']  . '">尾页</a>' . '&nbsp;' ;

    if($param == 4){
        $html = '';
        $html .= '<i class="total">共' . $pageBar['recordNum'] . '条纪录</i>';
        $html .= '<a class="updown" href="' . $pageBar['pre']   . '">上一页</a>';
        $html .= $pageBar['point'];
        $html .= '<a class="updown nexts" href="' . $pageBar['next']  . '">下一页</a>';
    }

	$html .= $script ? $pageBar['jump'] : '';

	return $html;
}


//创建多级目录
function createDir($path)
{
	if(file_exists($path)) return true;
	$dirs = explode('/', $path);
	$total = count($dirs);
	$temp = '';
	for($i=0; $i<$total; $i++)
	{
		$temp .= $dirs[$i].'/';
		if (!is_dir($temp))
		{
			if(!@mkdir($temp)) return false;
			@chmod($temp, 0777);
		}
	}
	return true;
}

/**
 * 证书加密
 * @return bool
 × str 解密变量
 */
function run_encryption($str)
{
    extension_loaded('openssl') or die('php需要openssl扩展支持');
    /**
     * 生成Resource类型的密钥，如果密钥文件内容被破坏，openssl_pkey_get_private函数返回false
     */
    $privateKey = openssl_pkey_get_private(RSAprivateKey);
    $privateKey or die('密钥或者公钥不可用');
    $decryptData= '';//解密后的数据
    if(!empty($str)){
        if(!preg_match('~[^0-9a-f]~', $str)){
            //十六进度数据
            $content	= pack('H*', $str);
            if (openssl_private_decrypt($content, $decryptData, $privateKey)) {
                //echo $decryptData;
            }
        }
    }
    return $decryptData;
}
/**
 * app-RSA数据加密+解密
 * @param unknown $str
 * @param string $type
 * @return string
 */
function xmwme_RSA($str,$type='decode'){
    $returnstr= "";
    if($type=='encode'){//公钥加密
        $pu_key = openssl_pkey_get_public(RSA_public_key);//这个函数可用来判断公钥是否是可用的
        openssl_public_encrypt($str,$returnstr,$pu_key);//私钥加密
        $returnstr = base64_encode($returnstr);//加密后的内容通常含有特殊字符，需要编码转换下，在网络间通过url传输时要注意base64编码是否是url安全的
    }

    if($type=='decode'){//私钥解密
        $pi_key =  openssl_pkey_get_private(RSA_private_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
        openssl_private_decrypt(base64_decode($str),$returnstr,$pi_key);//私钥加密的内容通过公钥可用解密出来
    }
    return $returnstr;
}


/**
 +----------------------------------------------------------
 * 获取控制器名
 +----------------------------------------------------------
 * @return string
 +---------------------------------------------------------- 
 */
function getModule()
{
	$default = 'index' ;
	if(defined('IsRewrite') && IsRewrite)
	{
		$url  = explode('/',$_SERVER['REQUEST_URI']);
		$temp = array();
		foreach($url as $k=>$v) if($v !=null) $temp[] = strtolower(trim($v));
		$key  = Root == '/' ? 0 : count(explode('/',Root)) - 2;
		$mod  = isset($temp[$key]) ? $temp[$key] : $default;
	}
	else
	{
		$mod = isset($_REQUEST['mod']) ? $_REQUEST['mod'] : $default;
	}
    if(strpos($mod,'?')!==false){
        return $default;
    }
	return $mod;
}


/**
 +----------------------------------------------------------
 * 获取当前请求的操作(方法)
 +----------------------------------------------------------
 * @return string
 +---------------------------------------------------------- 
 */
function getAction()
{
	$default = 'index' ;
	if(defined('IsRewrite') && IsRewrite)
	{
		$url  = explode('/',$_SERVER['REQUEST_URI']);
		$temp = array();
		foreach($url as $k=>$v) if($v !=null) $temp[] = strtolower(trim($v));
		$key  = Root == '/' ? 0 : count(explode('/',Root)) - 2;
		$action  = isset($temp[$key+1]) ? $temp[$key+1] : $default;
	}
	else
	{
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : $default;
	}
    if(strpos($action,'?')!==false){
        return $default;
    }
	return $action;
}

function getIp() {
	if (getenv("HTTP_CDN_SRC_IP") && strcasecmp(getenv("HTTP_CDN_SRC_IP"), "unknown"))
        $ip = getenv("HTTP_CDN_SRC_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
		$ip = getenv("REMOTE_ADDR");
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
		$ip = $_SERVER['REMOTE_ADDR'];
	else
		$ip = "";
	if(strpos($ip,',')>0){
        $ip_arr = explode(',', $ip);
        $ip = $ip_arr['0'];
    }
    
	return $ip;
}

//记录日志，便于调试
function writeLog($content, $file='log.log')
{
	if(preg_match('/php/i',$file) || is_array($content)) return ;
	$logDir  = LogDir;
	if (!file_exists($logDir)) 
	{
		@mkdir($logDir);
		@chmod($logDir, 0777);
	}
	$content = "【".date("Y-m-d H:i:s", time())."】\t\t".$content."\r\n";
	file_put_contents($logDir.'/'.$file, $content, FILE_APPEND);
}

function revisionKey($filter)
{
	$revisionKey = '';
	if ( is_array($filter) && !empty($filter) ) {
		foreach ($filter as $key => $value) {
			if (is_array($value)) {
				$revisionKey .= "{" . $key . ":" . array_shift($value) . "}";
			} else {
				$revisionKey .= "{" . $key . ":" . $value . "}";
			}
		}
	}
	return $revisionKey;
}

/**
 +----------------------------------------------------------
 * 构造sql查询条件
 +----------------------------------------------------------
 * @param  array  $rule   数据查询规则
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function where($rule,$isCondition = 0)
{
	$where = '';
	$order = array();
	if ( isset($rule['exact']) && is_array($rule['exact']) && !empty($rule['exact']) ) {
		foreach ($rule['exact'] as $key => $value) {
			$kv[] = "$key='$value'";
		}
		$where = "( " . implode(' AND ', $kv) . " )";
	}

	if(!$isCondition){

        if ( isset($rule['other']) && $rule['other']!='' ) {
            $other = "( " . $rule['other'] . " )";
            $where.= $where ? " AND $other " : $other;
        }
		if ( isset($rule['in']) && is_array($rule['in']) && !empty($rule['in']) ) {
			$kv = array();
			foreach ($rule['in'] as $key => $value) {
				if ( is_array($value) ) {
					$kv[] = "$key in (". implode(',', $value) .")";
				}
			}
			$in = "( " . implode(' AND ', $kv) . " )";;
			$where .= $where ? " AND $in " : $in;
		}
	
		if ( isset($rule['scope']) && is_array($rule['scope']) && !empty($rule['scope']) ) {
			$kv = array();
			foreach ($rule['scope'] as $key => $value) {
				if ( is_array($value) && count($value) == 2  && $value[0] < $value[1]) {
					$kv[] = "( " . $key ." >= ". $value[0] . " AND ". $key ." <= ". $value[1]. " )";
				}
			}
			$scope = implode(' AND ', $kv);
			$where .= $where ? " AND $scope " : $scope;
		}
	
		if ( isset($rule['like']) && is_array($rule['like']) && !empty($rule['like']) ) {
			$kv = array();
			foreach ($rule['like'] as $key => $value) {
				if (!empty($value)) {
					$kv[] = $key.' like \'%'. $value . '%\'';
				}
			}
			if ( !empty($kv) ) {
				$like = '( ' . implode(' or ', $kv) . ')';
				$where .= $where ? " AND $like " : $like;
			}
		}
	
    	$where = $where ? ' where '.$where : '';
    
    	if ( isset($rule['order']) && is_array($rule['order']) )
    	{
    		foreach($rule['order'] as $key => $value) {
    			$order[] = $key. ' '.$value;
    		}
    		if ( !empty($order) ) {
    			$where .= ' order by '. implode(',', $order);
    		}
    	}
	}

	return $where;
}


/**
 * 1）密码长度必须在8-32位字符；
 * 2）密码强度必须包含数字、字母、特殊字符中的至少2种，不能输入空格；
 * @param $password
 */
function check_password($password)
{
    if( empty($password) ){
	return array('0'=>'密码不能为空');
    }
    if(preg_match("/[\x80-\xff]/",$password)===1)
    {
	return array('0'=>'密码不能有中文');
    }
    if(preg_match("/(\r|\n|\s)/",$password)===1)
    {
	return array('0'=>'密码不能有空格、换行及回车等字符');
    }
    if( strlen($password) < 8 || strlen($password) >32){
	//return array('0'=>'密码长度为8-32个字符之间');
    }
    if(preg_match('/^[0-9]+$/',$password)||preg_match('/^[a-zA-Z]+$/',$password)||preg_match('/^[^a-zA-Z0-9]+$/',$password))
    {
	//return array('0'=>'密码至少为数字、字母、字符的2种组合');
    }

    return true;
}


/**
 * 注册和取出系统对象以及中间变量
 */ 
class SystemParams {
        
        private static $parmas = array () ;
        
        public static function set($key, $value){
            SystemParams::$parmas[$key] = $value ;    
        }
        
        public static function get($key){  
            if(isset( SystemParams::$parmas[$key])){  
                return SystemParams::$parmas[$key]  ; 
            }   
        } 
        
        /**
         * 获取Model对象
         * @param unknown $apiName
         */
        public static function getDB($table){
//	    var_dump($table);exit;
            return SystemParams::get('OB')->import($table);
        }
        
        /**
         * 获取ObApi对象
         * @param unknown $apiName
         */
        public static function getObApi(){
            $arguments = func_get_args();
            $apiName = array_shift($arguments);
            loadOB($apiName) ; 
            $class = new ReflectionClass($apiName);
            return $class->newInstanceArgs($arguments);//返回类的实例
        }
        
    }


function loadOB($obName) {
    
    include_once(dirname(__FILE__). '/OB.class.php') ;
    
    try{
        
        if(file_exists(dirname(__FILE__).'/'.$obName.'.class.php')){ 
            include_once(dirname(__FILE__).'/'.$obName.'.class.php') ;    
        } 
        else{
            throw new Exception($obName .' class error ');
        }
    }
    catch(Exception $e){
         exit($e) ;
    }
}

/**
 * 随机生成字符串 字段salt需要
 +----------------------------
 * @param int $length 
 +----------------------------
 */
function saltRound($length=4){
    $code = '';
    for ($i = 1; $i <= $length; $i++) {
	$code .= chr(rand(97, 122));
    }
    return $code;
}

    /**
     * 读取excel的内容
     +-------------------------
     * @param String $path
     +-------------------------
     * @return array
     +--------------------------
     */
    function readExcel($path){
	require_once App.'/Util/phpExcel/PHPExcel.php';
	require_once App.'/Util/phpExcel/PHPExcel/IOFactory.php';

	$upType = pathinfo($path, PATHINFO_EXTENSION);// 获取文件后缀 
	$excelType = ($upType == "xls") ? 'Excel5' : 'Excel2007';
	if (!file_exists($path)) {
	    exit("not found " . $path . ".\n");
	}

	$reader = PHPExcel_IOFactory::createReader($excelType); //设置以Excel5格式(Excel97-2003工作簿)
	$PHPExcel = @$reader->load($path); // 载入excel文件
	$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
	$highestRow = $sheet->getHighestRow(); // 取得总行数
	$highestColumm = $sheet->getHighestColumn(); // 取得总列数

	$dataset = array();
	// 循环读取每个单元格的数据
	for ($row = 2; $row <= $highestRow; $row++) {//行数是以第1行开始
	    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
	        $dataset[$row - 1][$column] = $sheet->getCell($column . $row)->getValue();            
	    }
	}
	return $dataset;
}
/**
 * 初始化微信类
 * @return \Wechat
 */
function initWechat(){
    require_once App.'/Util/wechat.class.php';
    /*$options = array(
        'appid' 		 => WX_AppID,
        'appsecret' 	 => WX_AppSecret,
        'token' 		 => WX_Token,
        'encodingaeskey' => WX_EncodingAESKey
    );*/
    $options = array(
            'token'=>'tokenaccesskey', //填写你设定的key
            'encodingaeskey'=>'encodingaeskey', //填写加密用的EncodingAESKey
            'appid'=>'wxdk1234567890', //填写高级调用功能的app id
            'appsecret'=>'xxxxxxxxxxxxxxxxxxx' //填写高级调用功能的密钥
    );
    $wechat = new Wechat($options);
    return $wechat;
}

/**
 * 生成订单
 * @param int $user_id
 * @return string
 */
function get_order_sn($user_id){
    if($user_id <= 0) return false;
    $order_str = date('ymdHi').sprintf("%07d", $user_id);
    return $order_str;
}


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
    $sql = 'insert ignore into xm_'.$table.'('.$keys.') values('.$values.');';
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
    $sql = 'update xm_'.$table.' set '.join(',', $update).' '.$where.';';
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
   
/**隐藏电话中间数字
 * @param $phone
 */
function hiddenPhoneMiddle($phone)
{
    if ($phone && is_numeric($phone) && !strpos($phone, '@')) {
        if ($phone && strlen($phone) > 10) {
            $phone = substr_replace($phone,'******',3,6);
        }
    }
    return $phone;
}

/**
 * 格式化金钱
 * @param $money 数目
 * @param int $scale 有效位数
 * @return string
 */
function format_money($money, $scale=2)
{
    return bcadd($money,0,$scale);
}

/**
 * 发送验证码短信
 * @param type $telephone
 * @param type $code
 * @param type $TemplateCode
 */
function SmsSendText($telephone, $code, $TemplateCode){
    require_once App."/Util/SignatureHelper.php";
    $params = array ();

    // *** 需用户填写部分 ***

    // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    $accessKeyId = "LTAIQRhggaErpet3";
    $accessKeySecret = "hXC6l277RbquTgihoQfTgvOSsk5ABd";

    // fixme 必填: 短信接收号码
    $params["PhoneNumbers"] = $telephone;

    // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
    $params["SignName"] = "食物百科网";

    // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
    $params["TemplateCode"] = $TemplateCode;

    // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
    $params['TemplateParam'] = Array (
        "code" => $code,
    );
    // fixme 可选: 设置发送短信流水号
    //$params['OutId'] = "12345";

    // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
    //$params['SmsUpExtendCode'] = "1234567";


    // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
    if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
        $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
    }

    // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
    $helper = new SignatureHelper();

    // 此处可能会抛出异常，注意catch
    try{
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        );
    } catch (Exception $e) {
        writeLog('用户：'.$telephone.'发送验证码失败，失败原因：'.$e->getMessage(), 'sms.log');
    }
    if($content->Code!='OK'){
        writeLog(var_export($content),'return_sms_error.txt');
    }
    return $content;
}


