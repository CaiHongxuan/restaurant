<?php
/**
 * @Author: CJX
 * @Date:   2016-01-05 18:16:57
 * @Last Modified by:   Administrator
 * @Last Modified time: 2016-01-21 14:17:14
 */

/**
 * 前台首页控制器
 */
Class IndexAction extends Action{
	/**
	 * 显示首页
	 * @return [type] [description]
	 */
	public function index(){
		$foods = M('foods')->order('id DESC')->limit(3)->field('id,foodname,extra,price,discount,imageurl,status')->select();
		// 修改图片的路径
		foreach ($foods as $key => $value) {
			$path = explode('./',$value['imageurl']);
			$foods[$key]['imageurl'] = __ROOT__ . '/' . $path[1];
		}
		$this->assign('foods',$foods);
		$this->display();
	}

	/**
	 * 显示关于我们页面
	 * @return [type] [description]
	 */
	public function aboutus(){
		$this->display();
	}

	/**
	 * 注销登录
	 * @return [type] [description]
	 */
	public function logout(){
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
		unset($_SESSION['user_email']);
		unset($_SESSION['login_time']);
		unset($_SESSION['login_ip']);
		//session_unset();
		//session_destroy();
		$this->redirect('Index/Index/index');
	}
}
