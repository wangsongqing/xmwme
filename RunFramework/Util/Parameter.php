<?php
class Parameter
{

	/**
     +----------------------------------------------------------
     * 数据验证
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array  $data   待验证的数据(键值对)
     * @param array  $fields 要验证的字段
	 *
	 * @return array         返回错误信息、验证后的数据
     +----------------------------------------------------------
     */
	public function validate($data, $fields)
	{
		$value = $error = array();

		foreach ($fields as $field)
		{
			if (!isset($field[1]) || !isset($field[2]) || empty($field[1]) || empty($field[2]))
			{
				$value[$field[0]] = isset($data[$field[0]]) ? trim($data[$field[0]]) : '';
				continue;
			}

			if (isset($data[$field[0]]))
			{
				if (!$this->regex($data[$field[0]], $field[1]))
				{
					$error[] = $field[2];
				}
				else
				{
					$value[$field[0]] = trim($data[$field[0]]);
					$length           = isset($field[3]) ? intval($field['3']) : 0;
					$value[$field[0]] = $length > 0 ? substr($value[$field[0]], 0, $length-1) : $value[$field[0]];
				}
			}
			else
			{
				$error[] = $field[2];
			}
		}

		return array(
			'error' => empty($error) ? '' : implode('@@@', $error),
			'value' => $value,
			);
	}


	/**
     +----------------------------------------------------------
     * 使用正则验证数据
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $value  要验证的数据
     * @param string $rule   验证规则
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    public function regex($value, $rule)
	{
        $validate = array(
            'require'  => '/.+/',
            'email'    => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'url'      => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',
            'currency' => '/^\d+(\.\d+)?$/',
            'number'   => '/\d+$/',
            'zip'      => '/^[1-9]\d{5}$/',
            'integer'  => '/^[-\+]?\d+$/',
            'double'   => '/^[-\+]?\d+(\.\d+)?$/',
            'english'  => '/^[A-Za-z]+$/',
	    'phone'    => '/^1[3|4|5|7|8][0-9]{9}$/'
        );
        // 检查是否有内置的正则表达式
        if(isset($validate[strtolower($rule)]))
		{
			$rule = $validate[strtolower($rule)];
		}
		return preg_match($rule,$value)===1;
    }
}
?>