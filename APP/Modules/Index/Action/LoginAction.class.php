<?php
/**
 * @Author: CJX
 * @Date:   2016-01-12 13:39:59
 * @Last Modified by:   Administrator
 * @Last Modified time: 2016-01-27 22:55:01
 */

/**
 * 登录视图控制器
 */
Class LoginAction extends Action{
	/**
	 * 显示登录首页
	 * @return [type] [description]
	 */
	public function index(){
		$this->display();
	}

	/**
	 * 验证码
	 * @return [type] [description]
	 */
	public function verify(){
		import('ORG.Util.Image');
		Image::buildImageVerify(6, 1, 'png', 80, 32);
	}

	/**
	 * 登录验证
	 * @return [type] [description]
	 */
	public function login(){
		if(!IS_POST){
			halt('页面不存在');
		}
		if(I('passcode','','md5') != $_SESSION['verify']){
			$this->error('验证码错误');
		}

		$email = I('email');
		$password = I('password','','md5');
		$password = md5($password . 'tp');
		$user = M('generaluser')->where(array('email' => $email))->find();
		if(!$user || $password != $user['password']){
			$this->error('账号或密码错误');
		}
		if($user['locked']){
			$this->error('用户未激活');
		}

		$data = array(
			'id' => $user['id'],
			'logintime' => time(),
			'loginip' => get_client_ip()
		);
		M('generaluser')->save($data);

		// 保存session
		session('user_id', $user['id']);
		session('user_name',$user['username']);
		session('user_email',$user['email']);
		session('login_time', date('Y-m-d H:i:s', $data['logintime']));
		session('login_ip', get_client_ip());

		$this->success('登录成功',U('Index/Index/index'));
	}
}
?>
