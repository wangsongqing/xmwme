<?php
class SendMail 
{
	var $server = "";    //地址或IP 如：smtp.tom.com 
	var $port = "25";    //端口  
	var $auth = "1";    //服务器是否要求身份验证 
	var $auth_username = ""; 
	var $auth_password = ""; 
	var $mail_from = "";   //发件人 如：wggtyj@tom.com 
	var $email_from = "";   //发件人 
	var $email_to = "";     //收件人，多个用逗号隔开 如：44490400@qq.com,44343454@qq.com 
	var $maildelimiter = "1";     //1使用 CRLF 作为分隔符(通常为 Windows 主机),0使用 LF 作为分隔符(通常为 Unix/Linux 主机),2使用 CR 作为分隔符(通常为 Mac 主机) 
	var $email_subject = "";   //标题 
	var $email_message = "";   //内容 
	var $mailusername = "1";   //收件人地址中包含用户名 
	var $charset = "gbk"; 
	var $bbname = "wer";    //网站名称 
	var $ishtml = true;     //是否使用html 
	var $ContentType = "text/plain"; 
	
	
	function send(){ 
		$this->maildelimiter = $this->maildelimiter == 1 ? "\r\n" : ($this->maildelimiter == 2 ? "\r" : "\n"); 
		$this->mailusername = isset($this->mailusername) ? $this->mailusername : 1; 
		$this->email_subject = '=?'.$this->charset.'?B?'.base64_encode(str_replace("\r", '', str_replace("\n", '', '['.$this->bbname.'] '.$this->email_subject))).'?='; $this->email_message = chunk_split(base64_encode(str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $this->email_message))))))); 
		$this->email_from = (preg_match('/^(.+?) \<(.+?)\>$/',$this->email_from, $from) ? '=?'.$this->charset.'?B?'.base64_encode($from[1])."?= <$from[2]>" : $this->email_from); 
		
		foreach(explode(',', $this->email_to) as $touser) { 
			$tousers[] = preg_match('/^(.+?) \<(.+?)\>$/',$touser, $to) ? ($this->mailusername ? '=?'.$this->charset.'?B?'.base64_encode($to[1])."?= <$to[2]>" : $to[2]) : $touser; 
		}
		
		$this->email_to = implode(',', $tousers); 
		if($this->ishtml==true) $this->ContentType = "text/html"; 
		$headers = "From: $this->email_from{$this->maildelimiter}X-Priority: 3{$this->maildelimiter}X-Mailer: Discuz! {$this->maildelimiter}MIME-Version: 1.0{$this->maildelimiter}Content-type: $this->ContentType; charset=$this->charset{$this->maildelimiter}Content-Transfer-Encoding: base64{$this->maildelimiter}";
		$this->port = $this->port ? $this->port : 25; 
		
		if(!$fp = fsockopen($this->server, $this->port, $errno, $errstr, 30)) { 
			writeLog("$this->server:$this->port CONNECT - Unable to connect to the SMTP server"); 
			return false;
		}
		stream_set_blocking($fp, true); 
		
		$lastmessage = fgets($fp, 512); 
		if(substr($lastmessage, 0, 3) != '220') { 
			$errorlog('SMTP', "$this->server:$this->port CONNECT - $lastmessage"); 
		}
		
		fputs($fp, ($this->auth ? 'EHLO' : 'HELO')." discuz\r\n"); 
		$lastmessage = fgets($fp, 512); 
		
		if(substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250) { 
			writeLog("$this->server:$this->port HELO/EHLO - $lastmessage"); 
			return false;
		}
		
		while(1) { 
			if(substr($lastmessage, 3, 1) != '-' || empty($lastmessage)) { 
				break; 
			}
			$lastmessage = fgets($fp, 512); 
		}
		
		if($this->auth) { 
			fputs($fp, "AUTH LOGIN\r\n"); 
			$lastmessage = fgets($fp, 512); 
			if(substr($lastmessage, 0, 3) != 334) { 
				writeLog("$this->server:$this->port AUTH LOGIN - $lastmessage"); 
				return false;
			}
			fputs($fp, base64_encode($this->auth_username)."\r\n"); 
			$lastmessage = fgets($fp, 512); 
			if(substr($lastmessage, 0, 3) != 334) { 
				writeLog("$this->server:$this->port USERNAME - $lastmessage"); 
				return false;
			}
			fputs($fp, base64_encode($this->auth_password)."\r\n"); 
			$lastmessage = fgets($fp, 512); 
			if(substr($lastmessage, 0, 3) != 235) { 
				writeLog("$this->server:$this->port PASSWORD - $lastmessage"); 
				return false;
			}
			$this->email_from = $this->mail_from; 
		}
		
		fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $this->email_from).">\r\n"); 
		$lastmessage = fgets($fp, 512); 
		if(substr($lastmessage, 0, 3) != 250) { 
			fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $this->email_from).">\r\n"); 
			$lastmessage = fgets($fp, 512); 
			if(substr($lastmessage, 0, 3) != 250) { 
				writeLog("$this->server:$this->port MAIL FROM - $lastmessage"); 
				return false;
			} 
		}
		
		$email_tos = array(); 
		foreach(explode(',', $this->email_to) as $touser) { 
			$touser = trim($touser); 
			if($touser) { 
				fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n"); 
				$lastmessage = fgets($fp, 512); 
				if(substr($lastmessage, 0, 3) != 250) { 
					fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n"); 
					$lastmessage = fgets($fp, 512); 
					writeLog("$this->server:$this->port RCPT TO - $lastmessage"); 
					return false;
				} 
			}
		}
		
		fputs($fp, "DATA\r\n"); 
		$lastmessage = fgets($fp, 512); 
		if(substr($lastmessage, 0, 3) != 354) { 
			writeLog("$this->server:$this->port$this->server:$this->port DATA - $lastmessage"); 
			return false;
		} 
		$headers .= 'Message-ID: <'.gmdate('YmdHs').'.'.substr(md5($this->email_message.microtime()), 0, 6).rand(100000, 999999).'@'.$_SERVER['HTTP_HOST'].">{$this->maildelimiter}"; 
		fputs($fp, "Date: ".gmdate('r')."\r\n"); 
		fputs($fp, "To: ".$this->email_to."\r\n"); 
		fputs($fp, "Subject: ".$this->email_subject."\r\n"); 
		fputs($fp, $headers."\r\n"); 
		fputs($fp, "\r\n\r\n"); 
		fputs($fp, "$this->email_message\r\n.\r\n"); 
		fputs($fp, "QUIT\r\n"); 
		return true; 
	}
} 
?>