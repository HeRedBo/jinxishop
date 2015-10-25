<?php 
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller
{
	/**
	 * 登录验证 (_initialize)
	 */
	public function __construct()
	{	
		//先调用父类的构造函数
		parent::__construct();
		//获取当前管理员的ID
		$adminId = session('id');
		if(!$adminId)
			redirect(U('Admin/Login/login'));
		//1.先获取当前管理员将要访问的这个页面 --- 使用TP 三个常量
		$url = MODULE_NAME .'/' . CONTROLLER_NAME .'/' .ACTION_NAME;
		//查询数据库是否存在当前管理员没有访问这个页面的权限
		$where = 'module_name ="'.MODULE_NAME.'" AND controller_name ="'.CONTROLLER_NAME.'" AND action_name ="'.ACTION_NAME.'"';
		//任何人只要登陆就可以进入后台
		if(CONTROLLER_NAME =='Index')
			return TRUE;
		if($adminId ==1)
			$sql ='SELECT COUNT(*) has FROM shop_privilege';
		else
			$sql ='SELECT COUNT(a.role_id) has 
					FROM shop_role_privilege a 
						LEFT JOIN shop_privilege b ON a.pri_id = b.id 
						LEFT JOIN shop_admin_role c ON a.role_id = c.role_id
							WHERE c.admin_id = '.$adminId.' AND '.$where;
		$db = M();
		$pri = $db->query($sql);
		if($pri[0]['has'] < 1)
			$this->error('你无权访问！');
		
		
		//验证登陆
	}

	/**
	 * 布局页面赋值
	 * @param [string] $title   [description]
	 * @param [string] $btnName [description]
	 * @param [string] $btnLink [description]
	 * @author RedBo
	 * date 2015-10-06 10:51
	 */
	public function setPageBtn($title,$btnName,$btnLink)
	{
		$this->assign(array(
			'_page_title'	=>$title,
			'_page_btn_name'=>$btnName,
			'_page_btn_link'=>$btnLink
		));
	}
}