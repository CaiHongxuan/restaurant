<?php
/**
 * 菜品控制器
 */
Class FoodAction extends CommonAction{
	/**
	 * 空操作
	 * @return [type] [description]
	 */
	public function _empty(){
		$this->error('页面不存在');
	}

	/**
	 * 显示所有菜品
	 * @return [type] [description]
	 */
	public function index(){
		$foods = M('foods');
		// 分页显示
		$result = page($foods,10,'id DESC');

		$this->assign('list',$result['list']);
		$this->assign('page',$result['show']);
		$this->display();
	}

	/**
	 * 添加菜品
	 */
	public function addFood(){
		$this->display();
	}

	/**
	 * 添加菜品表单处理
	 */
	public function addFoodHandle(){
		if(!IS_POST) halt('页面不存在');

		// 上传图片
		import('ORG.Net.UploadFile');
		$upload = new UploadFile(); // 实例化上传类
		$upload->maxSize = 1024*1024*10; // 附件最大值
		$upload->allowExts = array('jpg','jpeg','gif','png');// 允许上传的图片类型
		$uploadpath = './Uploads/food_pics/';
		$upload->savePath = $uploadpath; //上传目录
		// 上传目录不存在则创建
		if(!file_exists($uploadpath)){
			mkdir($uploadpath,0777);
		}

		if(!$upload->upload()){
			$this->error($upload->getErrorMsg());
		}else{
			$info = $upload->getUploadFileInfo();
		}

		// 将从空字符串转换为默认值
		$discount = I('discount',10,'floatval');
		if($discount == 0){
			$discount = 10.0;
		}
		$data = array(
			'foodname' => I('foodname'),
			'price' => I('price',0,'floatval'),
			'type' => I('type','','strval'),
			'discount' => $discount,
			'imageurl' => $info[0]['savepath'] . $info[0]['savename'],
			'extra' => I('extra',null)
		);
		if($foodid = M('foods')->add($data)){
			$this->success('添加成功',U('Admin/Food/index'));
		}else{
			$this->error('添加失败');
		}
	}

	/**
	 * 删除菜品表单处理
	 * @return [type] [description]
	 */
	public function deleteFoodHandle(){
		$id = I('fid', '', 'intval');
		$foods = M('foods');
		// 该菜品的图片路径
		$imageurl = $foods->where(array('id' => $id))->getField('imageurl');
		// 删除数据的同时删除该菜品的图片
		if(unlink($imageurl) && $foods->where(array('id' => $id))->delete()){
			$this->success('删除成功',U('Admin/Food/index'));
		}else{
			$this->error('删除失败');
		}
	}

	/**
	 * 删除多个菜品表单处理
	 * @return [type] [description]
	 */
	public function deleteFoodsHandle(){
		$ids = I('id','','intval');
		if ($ids == '') {
			$this->error('未选中删除数据');
		}

		$foods = M('foods');
		foreach ($ids as $value) {
			// 该菜品的图片路径
			$imageurl = $foods->where(array('id' => $value))->getField('imageurl');
			// 删除数据的同时删除该菜品的图片
			if(unlink($imageurl) && $foods->where(array('id' => $value))->delete()){
				continue;
			}else{
				$this->error('删除失败');
			}
		}
		$this->success('删除成功',U('Admin/Food/index'));
	}
}
?>
