<?php
/**
 * 会员管理控制器
 */
Class UserAction extends CommonAction{
	/**
	 * 空操作
	 * @return [type] [description]
	 */
	public function _empty(){
		$this->error('页面不存在');
	}
	
	/**
	 * 显示所有会员
	 * @return [type] [description]
	 */
	public function index(){
		$users = M('generaluser')->field('password,loginip',true);
		// 分页显示
		$result = page($users,10,'id DESC');

		$this->assign('list',$result['list']);
		$this->assign('page',$result['show']);
		$this->display();
	}

	/**
	 * 改变锁定状态
	 * @return [type] [description]
	 */
	public function changeLocked(){
		//$locked = I('locked',0,'intval');
		if(M('generaluser')->where(array('id' => I('bid','','intval')))->setField('locked',I('locked',0,'intval'))){
			$this->success('修改成功');
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除单个用户
	 * @return [type] [description]
	 */
	public function deleteUser(){
		$id = I('bid','','intval');
		// 删除用户的同时删除该用户的订单信息
		if((M('books')->where(array('user_id' => $id))->delete()) && (M('generaluser')->where(array('id' => $id))->delete())){
			//删除该用户的购物车数据
			M('shoppingcar')->where(array('user_id' => $id))->delete();
			// 将前台的session数据清空
			unset($_SESSION['user_id']);
			unset($_SESSION['user_name']);
			unset($_SESSION['user_email']);
			unset($_SESSION['login_time']);
			unset($_SESSION['login_ip']);

			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	/**
	 * 批量删除用户
	 * @return [type] [description]
	 */
	public function deleteMulUsers(){
		$ids = I('id','','intval');
		if ($ids == '') {
			$this->error('未选中删除数据');
		}
		// 将tp_generaluser表的多id主键拼接成以','分隔的字符串
		// $ids = implode(',',$ids);
		// 删除多个主键的数据记录
		foreach ($ids as $value) {
			// 删除用户的同时删除该用户的订单信息
			if((M('books')->where(array('user_id' => $value))->delete()) && (M('generaluser')->where(array('id' => $value))->delete())){
				//删除用户的购物车数据
				M('shoppingcar')->where(array('user_id' => $value))->delete();
				// 将前台的session数据清空
				unset($_SESSION['user_id']);
				unset($_SESSION['user_name']);
				unset($_SESSION['user_email']);
				unset($_SESSION['login_time']);
				unset($_SESSION['login_ip']);
			}else{
				$this->error('删除失败');
			}
		}
		$this->success('删除成功');
	}
}
?>