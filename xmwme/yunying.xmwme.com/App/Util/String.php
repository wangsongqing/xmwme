<?php
class String {
	
	/**
	 * 字符串转义
	 *
	 * @access public
	 * @param  string|array $input
	 * @return mixed
	 * @author fengxu
	 */
	public function escape($input) {
		if(!is_string($input) && is_array($input)) {
			return $input;
		}
		if(is_string($input)) {
			return addslashes($input);
		} else {
			return array_map(array($this, 'escape'), $input);
		}
	}
	
	/**
	 * 字符串截取函数
	 *
	 * @access public
	 * @param string $string 待截取的字符串
	 * @param integer $cutLength 截取长度
	 * @param boolean $isTrim 是否去除字符串首尾的空字符，\r，\n，\t...
	 * @param boolean $enHtml html编码是否转换为实体
	 * @param string $charset 当前字符集
	 * @return string
	 * @author fengxu
	 */
	public function cut($string, $cutLength, $isTrim = false, $enHtml = false, $charset = 'utf-8') {
		if ($isTrim) {
			$string = trim($string);
		}
		$strLength = strlen($string);
		if ($strLength <= $cutLength) {
			return $string;
		}
		$cutString = '';
		$n = $tn = $noc = 0;
		if('gbk' == $charset) {
			for ($i=0; $i<$cutLength; $i++) {
				if (ord($string[$i]) > 127) {
					$tn = 2;
					$cutString .= $string[$i].$string[++$i];
				} else {
					$tn = 1;
					$cutString .= $string[$i];
				}
			}
			if(strlen($cutString) > $cutLength) {
				$cutString = substr($cutString, -$tn);
			}
		} elseif ('utf-8' == $charset) {
			while ( $n < $strLength ) {
				$t = ord ( $string [$n] );
				if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
					$tn = 1;
					$n ++;
					$noc ++;
				} elseif (194 <= $t && $t <= 223) {
					$tn = 2;
					$n += 2;
					$noc += 2;
				} elseif (224 <= $t && $t <= 239) {
					$tn = 3;
					$n += 3;
					$noc += 2;
				} elseif (240 <= $t && $t <= 247) {
					$tn = 4;
					$n += 4;
					$noc += 2;
				} elseif (248 <= $t && $t <= 251) {
					$tn = 5;
					$n += 5;
					$noc += 2;
				} elseif ($t == 252 || $t == 253) {
					$tn = 6;
					$n += 6;
					$noc += 2;
				} else {
					$n ++;
				}
				if ($noc >= $cutLength) {
					break;
				}
			}
			if ($noc > $cutLength) {
				$n -= $tn;
			}	
			$cutString = substr($string, 0, $n);		
		}
		if ($enHtml) {
			$cutString = $this->html($cutString);
		}
		return $cutString;
	}
	
	/**
	 * 是否把html编码转换成实体
	 *
	 * @param string $string
	 * @param string encode|decode $type 转换还是恢复
	 * @return string
	 * @author fengxu
	 */
	public function html($string, $type='encode') {
		if('encode' == $type) {
			$string = htmlspecialchars($string, ENT_QUOTES, null, false);
		} else {
			$string = htmlspecialchars_decode($string, ENT_QUOTES);
		}
		return $string;
	}
	
	/**
	 * 除去字符串中的脚本
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 * @author fengxu
	 */
	public function cleanJs($string) {
		$searchs = array(
			'#\<script[^\>]+>[^\<]+\</script\>#i',
			'#on(click|dbclick|mousemove|mousedown|mouseup|mouseover|mouseout|keydown|keyup|keypress)\s*=\s*
			((\'[^\']+\')|("[^\"]+"))#ix',
			'#javascript:#i',
		);
		return preg_replace($searchs, '', $string);
	}
}
