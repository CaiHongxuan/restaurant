<?php
/**
 * @Author: CJX
 * @Date:   2016-01-13 00:54:41
 * @Last Modified by:   Administrator
 * @Last Modified time: 2016-02-19 17:17:03
 */

/**
 * 用户视图控制器
 */
Class UserAction extends Action{
	/**
	 * 空操作
	 * @return [type] [description]
	 */
	public function _empty(){
		$this->error('页面不存在');
	}

	/**
	 * 显示用户个人界面
	 * @return [type] [description]
	 */
	public function index(){
		$user_id = $_SESSION['user_id'];
		if(!$user_id){
			$this->redirect('Index/Index/index');
		}
		$shops = M('books')->where(array('user_id' => $user_id))->order('booktime DESC')->field('user_id',true)->select();
		if($shops){
			foreach ($shops as $key => $value) {
				// 拆分为各个订单
				$foods[] = $value;
				// 每个订单包含的菜品
				$food_id = explode(',',$value['food_id']);
				$count = explode(',',$value['count']);
				$sum = 0;
				foreach ($food_id as $k => $v) {
					$foods[$key]['food'][] = M('foods')->where(array('id' => $v))->field('type,totalcount,status',true)->find();
					// 修改图片的路径
					$path = explode('./',$foods[$key]['food'][$k]['imageurl']);
					$foods[$key]['food'][$k]['imageurl'] = __ROOT__ . '/' . $path[1];
					$foods[$key]['food'][$k]['count'] = $count[$k];  // 每个菜品订购数量
					// 每个订单的总价
					$sum += $foods[$key]['food'][$k]['price']*$count[$k]*$foods[$key]['food'][$k]['discount']/10;
				}
				// 每个订单的总价
				$foods[$key]['total'] = $sum;
				// 所有未支付订单的总价
				if($foods[$key]['pay'] == 0){
					$total += $sum;
				}
			}

			if(isset($total)){
				$this->assign('total',$total);
			}
			$this->assign('foods',$foods);
		}
		$this->display();
	}

	/**
	 * 显示用户注册
	 * @return [type] [description]
	 */
	public function register(){
		$this->display();
	}

	/**
	 * 处理用户注册表单处理
	 * @return [type] [description]
	 */
	public function registerHandle(){
		if(!IS_POST) halt('页面不存在');

		$pwd = I('password','','md5');
		$password = md5($pwd . 'tp');
		$email = I('email');
		$username = I('name');
		$data = array(
			'email'      => $email,
			'username'   => $username,
			'password'   => $password,
			'phone'      => I('phone'),
			'address'    => I('address','','trim'),
			'createDate' => time(),
			'logintime'  => time(),
			'loginip'    => get_client_ip()
		);
		// 插入用户数据
		if($id = M('generaluser')->add($data)){
			$token = '亲爱的' . $username . '</br>欢迎在宏玄餐饮B2C店用餐</br>点击下面链接即可完成注册</br><a target="_blank" href="' . $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/Index/User/activate.html?id=' . md5($id) . '">' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . '/Index/User/activate.html?id=' . md5($id) . '</a>';
			if(sendEmail($email,$token,'请激活您的账户，完成注册')){
				$this->success('注册成功！请打开邮箱激活用户',U('Index/Login/index'));
			}
			else{
				$this->error('发送失败！');
			}
		}else{
			$this->error('邮箱已被注册！');
		}
	}

	/**
	 * 激活用户
	 * @return [type] [description]
	 */
	public function activate(){
		$id = I('id');
		$user_ids = M('generaluser')->field('id')->select();
		foreach ($user_ids as $value) {
			// 将经过md5加密的用户id进行比对
			if (md5($value['id']) == $id) {
				M('generaluser')->where(array('id' => $value['id']))->setField('locked',0);
				$this->success('激活成功',U('Index/Login/index'));
				return;
			}
		}
		$this->error('激活失败',U('Index/Index/index'));
	}

	/**
	 * 显示我的购物车
	 * @return [type] [description]
	 */
	public function myshoppingcar(){
		$this->display();
	}

	/**
	 * 用户提交订单
	 * @return [type] [description]
	 */
	public function addOrder(){
		if(!I('foods') || !I('quantity')){
			$this->error('购物车是空的');
		}
		$data = array(
			'food_id'  => I('foods'),
			'count'    => I('quantity'),
			'user_id'  => I('user_id',null,'intval'),
			'booktime' => time()
		);
		if(M('books')->add($data)){
			$this->success('提交成功，请尽快完成支付');
		}else{
			$this->error('提交失败');
		}
	}

	/**
	 * 将单个未付款且未取消的订单改为已付款状态
	 * @return [type] [description]
	 */
	public function paySingle(){
		$book_id = I('id','','intval');
		$book = M('books');
		if($book->where(array('id' => $book_id,'pay' => 0))->getField('status') == 1){
			$book->where(array('id' => $book_id))->setField(array('status' => 2, 'pay' => 1));
			$this->success('支付成功');
		}elseif($book->where(array('id' => $book_id,'pay' => 0,'status' => array('NEQ','3')))->setField('pay',1)){
			$this->success('支付成功');
		}else{
			$this->error('支付失败');
		}
	}

	/**
	 * 将所有未付款且未取消的订单改为已付款状态
	 * @return [type] [description]
	 */
	public function pay(){
		if(!IS_POST){
			halt('页面不存在');
		}
		$books = M('books');
		if($books->where(array('pay' => 0,'status' => 1))->setField(array('status' => 2, 'pay' => 1))){
			$this->success('支付成功');
		}elseif($books->where(array('pay' => 0,'status' => array('NEQ','3')))->setField('pay',1)){
			$this->success('支付成功');
		}else{
			$this->error('支付失败');
		}
	}


////////////////////
///////////////// //
/////          // //
// 以下几个功能废弃 //
/////          // //
///////////////// //
////////////////////

	/**
	 * 显示我的购物车
	 * @return [type] [description]
	 */
	/*public function myshoppingcar(){
		$user_id = I('user_id','','intval');
		$shops = M('shoppingcar')->where(array('user_id' => $user_id))->field('food_id,count')->select();
		if($shops){
			foreach ($shops as $key => $value) {// 修改图片的路径
				$foods[] = M('foods')->where(array('id' => $value['food_id']))->field('type,totalcount,status',true)->find();
				$path = explode('./',$foods[$key]['imageurl']);
				$foods[$key]['imageurl'] = __ROOT__ . '/' . $path[1];
				$foods[$key]['count'] = $value['count'];
				$sum += $foods[$key]['price']*$value['count']*$foods[$key]['discount']/10;
				$num = $key + 1;
			}
			$this->assign('sum',$sum);
			$this->assign('num',$num);
			$this->assign('foods',$foods);
		}
		$this->display();
	}*/

	/**
	 * 添加菜品进购物车
	 * @return [type] [description]
	 */
	/*public function additem(){
		$user_id = I('user_id',null,'intval');
		if(M('generaluser')->where(array('id' => $user_id))->find()){
			$data = array(
				'user_id' => $user_id,
				'food_id' => I('food_id',null,'intval')
			);
			if(M('shoppingcar')->where($data)->find()){
				M('shoppingcar')->where($data)->setInc('count');
				$this->success('加入购物车成功');
			}else{
				if(M('shoppingcar')->add($data)){
					$this->success('加入购物车成功');
				}else{
					$this->error('加入购物车失败');
				}
			}
		}else{
			$this->error('用户不存在，请先注册或登录');
		}
	}*/

	/**
	 * 从购物车移走菜品
	 * @return [type] [description]
	 */
	/*public function deleteitem(){
		if(M('shoppingcar')->where(array('user_id' => I('user_id',null,'intval'),'food_id' => I('food_id',null,'intval')))->delete()){
			$this->success('成功将该菜品从购物车移走');
		}else{
			$this->error('从购物车移走该菜品失败');
		}
	}*/

	/**
	 * 用户提交支付处理
	 * @return [type] [description]
	 */
	/*public function pay(){
		$user_id = I('user_id','','intval');
		$shoppingcar = M('shoppingcar')->where(array('user_id' => $user_id))->select();
		if(!$shoppingcar){
			$this->error('购物车没有菜品');
		}
		foreach ($shoppingcar as $key => $value) {
			$food_id[] = $value['food_id'];
			$count[]   = $value['count'];
		}
		$data = array(
			'food_id' => implode(',',$food_id),
			'count'   => implode(',',$count),
			'user_id' => $user_id,
			'booktime' => time(),
			'pay'     => 1
		);
		if(M('books')->add($data)){
			$this->success('提交成功');
			M('shoppingcar')->where(array('user_id' => $user_id))->delete();
		}else{
			$this->error('提交失败');
		}
	}*/
}
?>
