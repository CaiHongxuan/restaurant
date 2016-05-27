<?php
/**
 * 后台登录控制器
 */
Class LoginAction extends Action{
	/**
	 * 空操作
	 * @return [type] [description]
	 */
	public function _empty(){
		$this->error('页面不存在');
	}

	/**
	 * 登录显示
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
	 * 登录操作
	 * @return [type] [description]
	 */
	public function login(){
		if (!IS_POST) {
			halt('页面不存在');
		}
		if(I('passcode', '', 'md5') != $_SESSION['verify']){
			$this->error('验证码错误');
		}
		$username = I('username');
		$password = I('password', '', 'md5');
		$password = md5($password . 'tp');
		$user = M('user')->where(array('username' => $username))->find();
		if(!$user || $password != $user['password']){
			$this->error('账号或密码错误');
		}
		if($user['locked']){
			$this->error('用户被锁定');
		}

		$data = array(
			'id'        => $user['id'],
			'logintime' => time(),
			'loginip'   => get_client_ip()
		);
		M('user')->save($data);

		// 保存session
		session('uid', $user['id']);
		session('username', $user['username']);
		session('logintime', date('Y-m-d H:i:s', $data['logintime']));
		session('loginip', get_client_ip());

		$this->redirect('Admin/Index/index');
	}
}
?>
