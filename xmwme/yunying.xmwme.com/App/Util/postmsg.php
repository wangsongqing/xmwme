<?php
/*
 * Web Service 服务器返回来的汉字是实体UNICODE编码,形如&#x79FB;&#x52A8;
 * 在正常情况,浏览器可以正常显示。不过也可以使用下面的解码函数把字符串
 * 编码为GB2312字符串
 */

//返回单个的UTF8字符编码的相应的GB2312字符
function _Msg_DecodeChar($unicodeChar)
{
    $str="";
    if ($unicodeChar < 0x80) {
         $str.=$unicodeChar;
    } else if ($unicodeChar < 0x800) {
         $str.=chr(0xC0 | $unicodeChar>>6);
         $str.=chr(0x80 | $unicodeChar & 0x3F);
    } else if ($unicodeChar < 0x10000) {
         $str.=chr(0xE0 | $unicodeChar>>12);
         $str.=chr(0x80 | $unicodeChar>>6 & 0x3F);
         $str.=chr(0x80 | $unicodeChar & 0x3F);
    } else if ($unicodeChar < 0x200000) {
         $str.=chr(0xF0 | $unicodeChar>>18);
         $str.=chr(0x80 | $unicodeChar>>12 & 0x3F);
         $str.=chr(0x80 | $unicodeChar>>6 & 0x3F);
         $str.=chr(0x80 | $unicodeChar & 0x3F);
    }
    return iconv('UTF-8', 'GB2312', $str);
}
//////////////////////////////////////////////////

function Msg_ToGb2312($strCoded)
{
	//分割字符串
	preg_match_all("/&#x.{4};|&.{2,4};|.+/U", $strCoded, $r);

	$unicodeArray = $r[0];
	foreach($unicodeArray as $k=>$v)
	{
		if(strpos($v, "&#x") === 0)
		{
			$unicodeArray[$k] = _Msg_DecodeChar(hexdec(substr($v,3,-1)));
		}
		elseif(strpos($v, "&") === 0)
		{
			if (strpos($v, "lt") === 1)
				$unicodeArray[$k] = "<";
			elseif (strpos($v, "gt") === 1)
				$unicodeArray[$k] = ">";
			elseif (strpos($v, "quot") === 1)
				$unicodeArray[$k] = "\"";
			elseif (strpos($v, "apos") === 1)
				$unicodeArray[$k] = "\'";
			elseif (strpos($v, "amp") === 1)
				$unicodeArray[$k] = "&";
		}
	}
  return join("",$unicodeArray);
}


//发送SOAP请求

	//HTTP请求头字符串外壳
	define("HTTP_HEADER", "SOAPAction: \"http://%s/services/EsmsService/%s\"\r\n" .
		"User-Agent: SOAP Toolkit 3.0\r\n" . 
		"Host: %s:8080\r\n" .
		"Content-Length: %d\r\n" .
		"Connection: Keep-Alive\r\n" .
		"Pragma: no-cache\r\n\r\n");
	
	//HTTP请求体字符串外壳
	define("HTTP_REQUEST_DATA", "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>" .
		"<SOAP-ENV:Envelope SOAP-ENV:encodingStyle=\"\" " . 
		"xmlns:SOAPSDK1=\"http://www.w3.org/2001/XMLSchema\" " .
		"xmlns:SOAPSDK2=\"http://www.w3.org/2001/XMLSchema-instance\" " .
		"xmlns:SOAPSDK3=\"http://schemas.xmlsoap.org/soap/encoding/\" " .
		"xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\">" .
		"<SOAP-ENV:Body SOAP-ENV:encodingStyle=\"\">" .
		"<%s SOAP-ENV:encodingStyle=\"\">" .				//soap请求动作
		"<n1 SOAP-ENV:encodingStyle=\"\">%s</n1>" .		//用户名
		"<n2 SOAP-ENV:encodingStyle=\"\">%s</n2>" .		//密码
		"</%s>"	.						//soap请求动作
		"</SOAP-ENV:Body>" .
		"</SOAP-ENV:Envelope>");
function _Msg_SendSoapRequest($userName, $password, $soapAction, $returnTag)
{
	$Msg_WebServerIP = "211.147.239.62:9088";
	$soapError = "ERROR";

	//HTTP请求的数据
	$requestData = sprintf(HTTP_REQUEST_DATA, $soapAction, $userName, $password, $soapAction);

	//HTTP请求头
	$httpHeader = sprintf(HTTP_HEADER, $Msg_WebServerIP, $soapAction, $Msg_WebServerIP, strlen($requestData));

	$url = "POST /services/EsmsService?wsdl HTTP/1.1\r\n";

	$sock = fsockopen($Msg_WebServerIP, 8080);

	if ($sock == 0)
		return $soapError;
	
	fputs($sock, $url . $httpHeader . $requestData);	//发送HTTP请求到服务器

	//跳过HTTP的文件头
	for ($i = 0; $i < 7; $i++)
		fgets($sock, 100);
	
	$tagBegin = sprintf("<%s", $returnTag); 
	$tagEnd = sprintf("</%s>", $returnTag);

	//获取XML字符串
	$buffer = "";
	$segGets = fgets($sock, 4096 * 3);
	while (strpos($segGets, $tagEnd) == FALSE)
	{
		$buffer .= $segGets;
		$segGets = fgets($sock, 4096 * 3);
		if ($segGets == FALSE)
			break;
	}
	fclose($sock);
	$buffer .= $segGets;

	$beginPos = strpos($buffer, $tagBegin);
	if ($beginPos == FALSE)
		return "";

	$beginPos = strpos($buffer, ">", $beginPos + strlen($tagBegin)) + 1;
	$endPos = strPos($buffer, $tagEnd, $beginPos);
	if ($endPos == FALSE)
		return "";

	return substr($buffer, $beginPos, $endPos - $beginPos);
}

//以下函数都是通过发送SOAP请求从服务器上获取相应的数据
//获取余额
function Msg_GetRemainFee($userName, $password)
{
	$result = _Msg_SendSoapRequest($userName, $password, "getRemainFee", "getRemainFeeReturn");
	if ($result == "ERROR")
		return -6;	//用户名密码出错
	if ($result == "")
		return -1;

	return intval($result);
}

//获取帐号信息
function Msg_GetConfigInfo($userName, $password)
{
	return  _Msg_SendSoapRequest($userName, $password, "getConfigInfo", "getConfigInfoReturn");
}

//获取回复信息
function Msg_GetMoMessage($userName, $password)
{
	return _Msg_SendSoapRequest($userName, $password, "getMOMessage", "getMOMessageReturn");
}

//提取标签内的值，返回标签后面的位置
function Msg_ExtractValue($soapReturn, $tag, $beginPos, &$value)
{
	$beginTag = sprintf("[%s]", $tag);	//开始标签
	$endTag = sprintf("[%s!]", $tag);	//结束标签

	$posValue = strpos($soapReturn, $beginTag, $beginPos);	
	if ($posValue === false)
		return false;
       	$posValue += strlen($beginTag);	//值的开始位置

	$posEnd = strpos($soapReturn, $endTag, $posValue);	//结束标签的位置
	if ($posEnd === false)
		return false;

	$lenValue = $posEnd - $posValue;	//值的长度
	if ($lenValue < 0)
		return false;

	$value = substr($soapReturn, $posValue, $lenValue);	//提取值

	return $posEnd + strlen($endTag);
}

class MOMessage
{
	var $id;
	var $destid;
	var $srcterminalid;
	var $msgcontent;
	var $receivetime;
}

//从字符串分析出一个MOMessage对象
function _Msg_Parse_MOMessage($singleMOMessage, $beginPos, &$msg)
{
	$value = "";
	$posValue = Msg_ExtractValue($singleMOMessage, "id", $beginPos, $value);
	if ($posValue === false)
		return false;

	$msg->id = $value;

	$posValue = Msg_ExtractValue($singleMOMessage, "destid", $posValue, $value);
	$msg->destid = $value;

	$posValue = Msg_ExtractValue($singleMOMessage, "srcterminalid", $posValue, $value);
	$msg->srcterminalid = $value;

	$posValue = Msg_ExtractValue($singleMOMessage, "msgcontent", $posValue, $value);
	$msg->msgcontent = $value;

	$posValue = Msg_ExtractValue($singleMOMessage, "receivetime", $posValue, $value);
	$msg->receivetime = $value;

	return $posValue;
}

//从字符串分析出一组MOMessage对象
function Msg_Parse_MOMessage($MOMessageArr)
{
	$msg = new MOMessage;
	$posValue = 0;
	$posValue = _Msg_Parse_MOMessage($MOMessageArr, $posValue, $msg);
	if ($posValue === false)
		return null;

	$msgArray = array($msg);

	$msg = new MOMessage;
	$posValue = _Msg_Parse_MOMessage($MOMessageArr, $posValue, $msg);
	while ($posValue !== false)
	{
		array_push($msgArray, $msg);
		$posValue = _Msg_Parse_MOMessage($MOMessageArr, $posValue, $msg);
	}
	return $msgArray;
}


//计算字符串的长度.以字符为单位
function _Msg_strlen($str)
{
	$count = 0;
	$i = 0;
	$len = strlen($str);
	while ($i < $len)
	{
		if (ord($str[$i]) > 128)
			$i += 2;
		else
			$i += 1;
		$count++;
	}
	return $count;
}
/////////////////////////////////////////////////////

/*
 * 发送短信的函数，发送成功则返回真。其中的$isPhoneNumber表示短信是否发送到小灵通。
 * 如果短信的长度超过规定的字数，信息将分段发送
 * 如果号码的个数超过100个，则不予发送
 */
function _Msg_MobileNumberType($mobileNumber)
{
	if (($mobileNumber[0] == '1') && (strlen($mobileNumber) == 11))
		return 1;	//手机号码
	if (($mobileNumber[0] == '0')
	       	&& ((strlen($mobileNumber) == 10) || (strlen($mobileNumber) == 12)))
		return 0;	//小灵通号码
	
	return -1;	//无效号码
}

//发送单条信息
function Msg_PostSingle($userName, $password, $to, $text, $subId)
{
	$maxMessageTextLen = 0;
	switch(_Msg_MobileNumberType($to))
	{
	case 1:
		$maxMessageTextLen = 140;	//手机信息是70个字(包含后缀)
		break;
	case 0:
		$maxMessageTextLen = 45;       //小灵通是45个字(包含后缀)
		break;
	default:
		return -15;	//号码出错
	}

	if (_Msg_strlen($text) > $maxMessageTextLen)	//判断不确切,因为实际的长度必须减去后缀
		return -5;

	return _Msg_FinalPostMessage($userName, $password, $to, $text, $subId);
}

function Msg_PostBlockNumber($userName, $password, $numberArray, $text, $subId)
{
	$count = count($numberArray);
	if ($count > 100)
		return -5;	//群发的号码数量太多

	$mobileType =_Msg_MobileNumberType($numberArray[0]);
	$to = $numberArray[0];
	for ($i = 1; $i < $count; $i++)
	{
		if ($mobileType != _Msg_MobileNumberType($numberArray[$i]))
			return -5;	//混合的号码
		$to = $to . "+" . $numberArray[$i];
	}

	$maxMessageTextLen = 0;
	switch($mobileType)
	{
	case 1:
		$maxMessageTextLen = 70;	//手机信息是70个字(包含后缀)
		break;
	case 0:
		$maxMessageTextLen = 45;       //小灵通是45个字(包含后缀)
		break;
	default:
		return -15;	//号码出错
	}

	return _Msg_FinalPostMessage($userName, $password, $to, $text, $subId);
}

define("POST_STRING", "http://%s/cgi-bin/sendsms?"."username=%s&password=%s&to=%s&text=%s&subid=%s&msgtype=4");
function _Msg_FinalPostMessage($userName, $password, $to, $text, $subId)
{
	$Msg_GatewayServer = "211.147.239.62:9050";
	$textUrl = urlencode($text);  //必须编成URL码
	$httpRequest = sprintf(POST_STRING, $Msg_GatewayServer, $userName, $password, $to, $textUrl, $subId);
	if (file_get_contents($httpRequest) != "0") //发送信息
		return -99;
	return 0;	//发送成功
}

?>