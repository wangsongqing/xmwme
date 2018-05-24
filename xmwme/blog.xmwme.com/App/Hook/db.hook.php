<?php
class DbHook
{
	/**
	 * SQL语句
	 */
	public  $sql    = null;

	/**
	 * SQL日志
	 */
	public  $sqlLog = null;

	/**
	 * 开始执行SQL的时间
	 */
	private $startTime  = null;

	/**
	 * 结束时间
	 */
	private $endTime    = null;

	/**
	 * SQL计数器
	 */
	private $loop  = 0;

	/**
	 * 记录SQL日志
	 *
	 * @access public
	 * @return void
	 */
	public function work()
	{
		++$this->loop;
// 		$this->sqlLog .= "<fieldset><legend><font color=red>第 {$this->loop} 次查询</font></legend>{$this->sql}</fieldset><br>";
// 		$this->endTime = array_sum(split(' ', microtime()));
// 		writeLog($this->sql, 'sql.log');
	}


	/**
	 * 获取执行SQL的时间
	 *
	 * @access public
	 * @return void
	 */
	public function getTime()
	{
		echo '<div align=center>Process: '.number_format((array_sum(split(' ', microtime())) - $this->endTime), 6).'s</div>';
	}
}
?>