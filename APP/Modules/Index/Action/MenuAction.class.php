<?php
/**
 * @Author: CJX
 * @Date:   2016-01-12 00:16:22
 * @Last Modified by:   Administrator
 * @Last Modified time: 2016-01-20 00:01:44
 */

/**
 * 菜品菜单控制器
 */
Class MenuAction extends Action{
	/**
	 * 显示所有菜品菜单
	 * @return [type] [description]
	 */
	public function index(){
		$foods = M('foods');
		$result = page($foods,10,'id DESC');
		// 修改图片的路径
		foreach ($result['list'] as $key => $value) {
			$path = explode('./',$value['imageurl']);
			$result['list'][$key]['imageurl'] = __ROOT__ . '/' . $path[1];
		}

		$this->assign('list',$result['list']);
		$this->assign('page',$result['show']);
		$this->display();
	}

	/**
	 * 显示分类菜品
	 * @return [type] [description]
	 */
	public function category(){
		$foods = M('foods')->field('totalcount',true)->select();
		// 菜品的分类（待改进，不应为enum类型）
		foreach ($foods as $key => $value) {
			// 修改图片的路径
			$path = explode('./',$value['imageurl']);
			$value['imageurl'] = __ROOT__ . '/' . $path[1];

			if($value['type']=='主餐'){
				$mainfoods[] = $value;
			}else if($value['type']=='饮料'){
				$drinks[] = $value;
			}else{
				$otherfoods[] = $value;
			}
		}
		$this->assign('otherfoods',$otherfoods);
		$this->assign('drinks',$drinks);
		$this->assign('mainfoods',$mainfoods);
		$this->display();
	}
}
?>
