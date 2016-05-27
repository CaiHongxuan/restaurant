<?php
/**
 * 后台首页控制器
 */
Class IndexAction extends CommonAction{
	/**
	 * 空操作
	 * @return [type] [description]
	 */
	public function _empty(){
		$this->error('页面不存在');
	}
	
	/**
	 * 首页显示
	 * @return [type] [description]
	 */
	public function index(){
		$this->display();
	}

	/**
	 * 注销登录
	 * @return [type] [description]
	 */
	public function logout(){
		session_unset();
		session_destroy();
		$this->redirect('Admin/Login/index');
	}
	
}