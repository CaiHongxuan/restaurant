<?php
/**
 * 订单管理控制器
 */
Class OrderAction extends CommonAction{
	/**
	 * 空操作
	 * @return [type] [description]
	 */
	public function _empty(){
		$this->error('页面不存在');
	}
	
	/**
	 * 显示所有订单
	 * @return [type] [description]
	 */
	public function index(){
		$orders = M('books');
		import('ORG.Util.Page');
		$count = $orders->count();
		$Page = new Page($count,10);
		// 分页显示输出
		$show = $Page->show();
		$list = $orders
		->join(C('DB_PREFIX').'generaluser ON ' . C('DB_PREFIX').'generaluser.id = ' . C('DB_PREFIX').'books.user_id')
		->field(C('DB_PREFIX').'books.*, ' . C('DB_PREFIX').'generaluser.phone, ' . C('DB_PREFIX').'generaluser.address')
		->order('booktime DESC')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
	 * 显示未处理订单列表
	 * @return [type] [description]
	 */
	public function untreatedList(){
		$orders = M('books');
		import('ORG.Util.Page');
		$count = $orders->where(array('status' => 0))->count();
		$Page = new Page($count,10);
		// 分页显示输出
		$show = $Page->show();
		$list = $orders
		->join(C('DB_PREFIX').'generaluser ON ' . C('DB_PREFIX').'generaluser.id = ' . C('DB_PREFIX').'books.user_id')
		->field(C('DB_PREFIX').'books.*, ' . C('DB_PREFIX').'generaluser.phone, ' . C('DB_PREFIX').'generaluser.address')
		->where(array('status' => 0))
		->order('booktime DESC')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
	 * 显示未结算订单列表
	 * @return [type] [description]
	 */
	public function unpaidList(){
		$orders = M('books');
		import('ORG.Util.Page');
		$count = $orders->where('pay=0 AND status<>3')->count();
		$Page = new Page($count,10);
		// 分页显示输出
		$show = $Page->show();
		$list = $orders
		->join(C('DB_PREFIX').'generaluser ON ' . C('DB_PREFIX').'generaluser.id = ' . C('DB_PREFIX').'books.user_id')
		->field(C('DB_PREFIX').'books.*, ' . C('DB_PREFIX').'generaluser.phone, ' . C('DB_PREFIX').'generaluser.address')
		->where('pay=0 AND status<>3')
		->order('booktime DESC')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
	 * 显示已完成订单列表
	 * @return [type] [description]
	 */
	public function successfulList(){
		$orders = M('books');
		import('ORG.Util.Page');
		$count = $orders->where(array('status' => 2))->count();
		$Page = new Page($count,10);
		// 分页显示输出
		$show = $Page->show();
		$list = $orders
		->join(C('DB_PREFIX').'generaluser ON ' . C('DB_PREFIX').'generaluser.id = ' . C('DB_PREFIX').'books.user_id')
		->field(C('DB_PREFIX').'books.*, ' . C('DB_PREFIX').'generaluser.phone, ' . C('DB_PREFIX').'generaluser.address')
		->where(array('status' => 2))
		->order('booktime DESC')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
	 * 显示已取消订单列表
	 * @return [type] [description]
	 */
	public function abolishedList(){
		$orders = M('books');
		import('ORG.Util.Page');
		$count = $orders->where(array('status' => 3))->count();
		$Page = new Page($count,10);
		// 分页显示输出
		$show = $Page->show();
		$list = $orders
		->join(C('DB_PREFIX').'generaluser ON ' . C('DB_PREFIX').'generaluser.id = ' . C('DB_PREFIX').'books.user_id')
		->field(C('DB_PREFIX').'books.*, ' . C('DB_PREFIX').'generaluser.phone, ' . C('DB_PREFIX').'generaluser.address')
		->where(array('status' => 3))
		->order('booktime DESC')
		->limit($Page->firstRow.','.$Page->listRows)
		->select();

		$this->assign('list',$list);
		$this->assign('page',$show);
		$this->display();
	}

	/**
	 * 改变订单未处理状态为已处理状态
	 * @return [type] [description]
	 */
	public function changeProcessed(){
		$id = I('bid','','intval');
		if(M('books')->where(array('id' => $id))->getField('pay')){
			M('books')->where(array('id' => $id))->setField('status',2);
			$this->success('修改成功');
		}elseif(M('books')->where(array('id' => $id))->setField('status',1)){
			$this->success('修改成功');
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 改变多个订单未处理状态为已处理状态
	 * @return [type] [description]
	 */
	public function changeMulProcessed(){
		$ids = I('id','','intval');
		if ($ids == '') {
			$this->error('没选中数据');
		}

		$book = M('books');
		foreach ($ids as $value) {
			if($book->where(array('id' => $value))->getField('pay')){
				$book->where(array('id' => $value))->setField('status',2);
			}else{
				$book->where(array('id' => $value))->setField('status',1);
			}
		}
		$this->success('修改成功');
	}

	/**
	 * 取消订单
	 * @return [type] [description]
	 */
	public function cancelOrder(){
		$id = I('bid','','intval');
		if(M('books')->where(array('id' => $id))->setField('status',3)){
			$this->success('修改成功');
		}else{
			$this->error('修改失败');
		}
	}

	/**
	 * 删除订单
	 * @return [type] [description]
	 */
	public function deleteOrder(){
		$id = I('bid','','intval');
		if (M('books')->where(array('id' => $id))->delete()) {
			$this->success('删除成功');
		}else{
			$this->error('删除失败');
		}
	}

	/**
	 * 订单详情
	 * @return [type] [description]
	 */
	public function detail(){
		$id = I('bid','','intval');
		$order = M('books');

		// 订单详情
		$this->order = $order
		->join(C('DB_PREFIX') . 'generaluser ON ' . C('DB_PREFIX').'generaluser.id = ' . C('DB_PREFIX').'books.user_id')
		->field(C('DB_PREFIX').'books.*, ' . C('DB_PREFIX').'generaluser.username, ' . C('DB_PREFIX').'generaluser.phone, ' . C('DB_PREFIX').'generaluser.address')
		->where(array(C('DB_PREFIX').'books.id' => $id))
		->select();

		// 菜品及数量清单
		$list = $order->where(array('id' => $id))->field('food_id,count')->select();
		$foods_id = explode(',', $list[0]['food_id']);
		$count    = explode(',', $list[0]['count']);

		$food = M('foods');
		foreach ($foods_id as $key => $value) {
			$foods = $food->where(array('id' => $value))->field('foodname,price,discount,extra')->select();
			$data[] = array(
				'foodname' => $foods[0]['foodname'], // 菜品名称
				'extra' => $foods[0]['extra'], // 菜品说明
				'price' => $foods[0]['price'], // 单价
				'discount' => $foods[0]['discount'], // 折扣
				'count' => $count[$key], // 数量
				'total' => $foods[0]['price'] * $count[$key] * $foods[0]['discount'] / 10 // 该菜品总价
			);
			$sum += $data[$key]['total']; // 订单总价
		}
		// 渲染到模板
		$this->assign('sum', $sum);
		$this->assign('foods', $data);
		$this->display();
	}
}
?>