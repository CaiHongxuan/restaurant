<?php
/**
 * 空模块
 */
Class EmptyAction extends Action{
	public function index(){
		$this->error('页面不存在');
	}
}
?>