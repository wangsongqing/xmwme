<?php
/**
 +------------------------------------------------------------------------------
 * Run Framework 消息队列接口类
 +------------------------------------------------------------------------------
 * @date    17-06
 * @author Jimmy Wang <jimmywsq@163.com>
 * @version 1.0
 +------------------------------------------------------------------------------
 */
interface IQueue
{
	/**
	 * 数据入队
	 *
	 * @param  mixed    $data   待入队数据
	 * @access public
	 * @return bool
	 */
	public function push($data);


	/**
	 * 数据出队
	 *
	 * @access public
	 * @return mixed
	 */
	public function pop();


	/**
	 * 队列长度(队列中元素个数)
	 *
	 * @access public
	 * @return int
	 */
	public function size();
	
}
?>