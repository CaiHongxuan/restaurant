<?php
/**
 * @Author: CJX
 * @Date:   2016-01-13 15:00:18
 * @Last Modified by:   CJX
 * @Last Modified time: 2016-01-16 16:13:57
 */

/**
 * 空模块
 */
Class EmptyAction extends Action{
	public function index(){
		$this->error('页面不存在');
	}
}
?>
