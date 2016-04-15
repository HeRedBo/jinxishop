<?php 
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller
{
	/**
	 * 管理员登陆
	 * @return [type] [description]
	 * @author RedBo
	 * date 2015-10-06 10:24
	 */
	public function login()
	{	
		if(IS_POST)
		{	

			$model = D('Admin');
			//使用validate 方法进行数据验证 默认是$_valita 这里选择我们自己设置的验证规则
			//3 是我们自己设定的 在Tp中1代表添加 2代表修改 其他则是我们自己定义
			if($model->validate($model->_login_validate)->create('',3))
			{
				if(TRUE === $model->login())
					redirect(U('Admin/Index/index'));
			}
			$this->error($model->getError());
		}
		$this->display();
	}

	/**
	 * 验证码的生成
	 * @return [type] [description]
	 * @author RedBo
	 * date 2015-10-06 10:23
	 */
	public function checkcode()
	{	
		//创建验证码类
		$Verify = new \Think\Verify(array(
			'length'	=>4,
			'useNoise'	=>FALSE,
			'imageH' 	=>25,
			'imageW'	=>100,
			'fontSize' 	=>12,
			'fontttf'	=>'1.ttf',
			'useCurve' 	=>FALSE,
		));

		//验证码的生成
		$Verify->entry();
	}

	/**
	 * 
	 * @param  void
	 * @return void
	 * @author Red-Bo
	 * @date 2016-04-15 07:57:24
	 */
	public function layout()
	{
		#情况session 跳转会登录页面
		session('id',null);
		session('username',null);
		# 跳转到系统首页
		$this->success('退出成功','login');
	}
}