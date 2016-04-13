<?php 
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController
{
	public function index()
	{
		
		$this->display();
	}

	public function top()
	{
		$this->display();
	}

	public function menu()
	{
		//按钮显示
		$adminId = session('id');
		/******取出当前管理员所拥有的前两级的权限*****/
		//取出当前管理员所有的权限
		if($adminId == 1)
			$sql = 'SELECT * FROM shop_privilege';
		else
			$sql = 'SELECT b.* FROM shop_role_privilege a 
						LEFT JOIN shop_privilege b ON a.pri_id = b.id 
						LEFT JOIN shop_admin_role c on a.role_id = c.role_id
							WHERE c.admin_id ='.$adminId;
		$db = M();
		$pri = $db->query($sql);
		$btn = array(); //放前两级的权限
		foreach ($pri as $k=>$v) {
			//找顶级权限
			if($v['parent_id'] ==0)
			{
				//在循环遍历这个顶级权限的子权限
				foreach ($pri as $k1 => $v1) 
				{
					if($v1['parent_id'] == $v['id'])
					{
						$v['children'][] = $v1;
					}
				}
				$btn[] = $v;
			}
		}
		
		$this->assign('btn',$btn);
		$this->display();
	}

	public function drag()
	{
		$this->display();
	}

	public function main()
	{
		$this->display();
	}

	
	//layout 测试
	public function test()
	{
		$this->display();
	}
}