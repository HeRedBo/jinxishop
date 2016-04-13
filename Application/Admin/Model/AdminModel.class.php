<?php
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model 
{
	protected $insertFields = array('username','password','cpassword','is_use');
	protected $updateFields = array('id','username','password','cpassword','is_use');

	//添加修改管理员时候用
	protected $_validate = array(
		array('username', 'require', '用户账号不能为空！', 1, 'regex', 3),
		array('username', '', '用户账号已存在！', 1, 'unique', 3),
		array('username', '1,30', '用户账号的值最长不能超过 30 个字符！', 1, 'length', 3),
		// 下面的规则只有添加时生效，修改时不生效，第六个参数代表什么时候验证：1：添加时验证 2：修改时 3：所有情况都验证
		array('password', 'require', '密码不能为空！', 1, 'regex', 1),
		array('cpassword', 'password', '两次密码输入不一样', 1, 'confirm', 3),
		array('is_use', 'number', '是否启用 1 启用 0：禁用必须是一个整数！', 2, 'regex', 3),
	);

	//登陆表单的验证规则
	public  $_login_validate = array(
		array('username','require','用户名不能为空',1),
		array('password','require','密码不能为空',1),
		array('captcha','require','验证码不能为空！',1),
		array('captcha','checkcode','验证码输入有误！',1,'callback'),
	);

	/**
	 * 验证码验证方法
	 */
	public function checkcode($code)

	{
		$verify = new \Think\Verify();
		return $verify->check($code);
	}

	/**
	 * 管理员登陆
	 * @author RedBo
	 * @return [type] [description]
	 * date 2015-10-06
	 */
	public function login()
	{
		//获取表单中的用户名和密码
		$username 	= $this->username;
		$password 	= $this->password;
		//先查询数据库是否存在这个账号
		$user = $this->where(array(
			'username' => array('eq',$username),
		))->find();
		//判读有没有这个账号
		if($user)
		{
			//判断账号是否可用(超级管理员不能禁用)
			if($user['id'] == 1 || $user['is_use'] == 1)
			{
				//判断密码
				if($user['password'] == md5($password.C('MD5_KEY')))
				{
					//把用户的id和用户名存到session中
					session('id',$user['id']);
					session('username',$user['username']);
					return TRUE;
				}
				else
				{
					$this->error = '密码输入错误！';
					return FALSE;
				}
			}
			else
			{
				$this->error = '账号被禁用！';
				return FALSE;
			}
			
		}
		else
		{
			$this->error = '用户名不存在！';
			return FALSE;
		}
	}

	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($username = I('get.username'))
			$where['username'] = array('like', "%$username%");
		 
		$is_use = I('get.is_use');
		if($is_use != '' && $is_use != '-1')
			$where['is_use'] = array('eq', $is_use);
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/**************** 取数据 ******************/
		$data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前 密码加密入库
	protected function _before_insert(&$data, $option)
	{
		$data['password'] =md5($data['password'].C('MD5_KEY'));
	}
	protected function _after_insert($data,$option)
	{
		$roleId = I('post.role_id');
		if($role_id){
			$arModel = M('AdminRole');
			foreach ($roleId as $v) {
				$arModel->add(array(
					'admin_id'=>$data['id'],
					'role_id' => $v,
				));
			}
		}
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{	
		// 如果是超级管理员必须是启用的
		if($option['where']['id'] == 1)
			$data['is_use'] =1; //直接设置为启用转态
		$roleId = I('post.role_id');
		//先请空原来的数据
		$arModel = M('AdminRole');
		$arModel->where(array('admin_id'=>array('eq',$option['where']['id'])))->delete();
		if($roleId)
		{
			foreach ($roleId as $v) {
				$arModel->add(array(
					'admin_id'=>$option['where']['id'],
					'role_id' =>$v,
				));
			}
		}
		//判断密码为空就不修改这个字段
		if(empty($data['password']))
			unset($data['password']);
		else
			$data['password'] = md5($data['password'].C('MD5_KEY'));

	}
	// 删除前
	protected function _before_delete($option)
	{	
		//管理员不能被删除
		if($option['where']['id']==1)
		{
			$this->error ='超级管理员不能被删除！';
			return FALSE;
		}
		// 在删除admin表中的管理员信息之前先删除admin_role表中这个管理员对应的数据
		$arModel = M('AdminRole');
		$arModel->where(array('admin_id' =>array('eq',$option['where']['id'])))->delete();
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
	}
	/************* 其他方法 ***************************/
}