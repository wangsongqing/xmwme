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
	    $_arr[$key] = $key;
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
function edaiapp_RSA($str,$type='decode'){
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

    

