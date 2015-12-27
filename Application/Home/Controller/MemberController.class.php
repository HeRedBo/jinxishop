<?php 
//会员登录控制器
namespace Home\Controller;
class MemberController extends BaseController
{
	/**
	 * 会员注册控制器
	 * @author Red-Bo
	 * @data 2015-10-28 07:43:41
	 */
	public function register()
	{
		if(IS_POST)
		{
			$model = D('Admin/Member');

			if($model->create(I('post.'),1))
			{
				if($model->add())
				{
					$this->success('注册成功,请登录到你邮箱完成邮件验证');exit;
				}
			}
			$this->error($model->getError());
		}
		$this->setPageInfo('会员注册','会员注册','会员注册',1,array('login'));
		$this->display();
	}

	/**
	 * 生成验证码
	 */
	public function checkcode()
	{	
		//创建验证码类
		$Verify = new \Think\Verify(array(
			'length'	=>4,
			'useNoise'	=>FALSE,
			'imageH' 	=>36,
			'imageW'	=>150,
			'fontSize' 	=>20,
			'fontttf'	=>'1.ttf',
			'useCurve' 	=>FALSE,
		));

		//验证码的生成
		$Verify->entry();

	}

	/**
	 * 会员邮箱验证方法
	 * @param 
	 * @return 
	 * @author Red-Bo
	 * @data 2015-10-28 22:39:22
	 */
	public function emailchk()
	{
		$code = I('get.code');
		if($code)
		{
			// 把这个验证码到数据库比较一下
			$model = M('Member');
			$email = $model->where(array('email_code'=>array('eq',$code)))->find();
			if($email)
			{
				$model->where(array('id'=>array('eq',$email['id'])))->setField('email_code','');
				$this->success('已经完成验证 现在可以去登陆',U('login'));exit;
			}
		}
	}
	/**
	 * 会员登录的方法
	 * @param 
	 * @return 
	 * @author Red-Bo
	 * @data 2015-10-28 18:44:36
	 */
	public function login()
	{
		if(IS_POST)
		{

			$model = D('Admin/Member');
			if($model->validate($model->_login_validate)->create(I('post.'),9))
			{
				if($model->login())
				{
					$returnUrl = session('returnUrl');
					if($returnUrl)
					{
						//先从session总删除掉 下次登陆正常跳转到首页
						session('returnUrl',null);
						redirect($returnUrl);
					}
					else
						redirect('/'); // 登陆成功跳转直接到首页
				}
				

					
			}
			$this->error($model->getError());
		}

		// 设置页面信息
		$this->setPageInfo('会员登录','会员登录','会员登录',1,array('login'));
		$this->display();
	}

	/**
	 * ajax 验证用户是否已经登录
	 * @param 
	 * @return 
	 * @author Red-Bo
	 * @data 2015-10-29 07:33:15
	 */
	public function ajaxChkLogin()
	{
		$uid 	= session('mid');
		
		if($uid)
		{
			$arr =array(
				'ok' => 1,
				'email' => session('email')
			);
		}
		else
		{
			$arr = array(
				'ok' =>0
			);
		}
		echo json_encode($arr);
	}

	//用户退出方法
	public function logout()
	{
		session(null);
		redirect('/');
	}

	/**
	 * 保存用户登陆当前页页面地址
	 */
	public function saveAndLogin()
	{
		// 获取ajax 是从哪个页面中发过来的
		session('returnUrl',$_SERVER['HTTP_REFERER']);
		//echo $_SERVER['HTTP_REFERER'];
	}

	/**
	 * QQ登陆方法
	 * @author Red-Bo
	 * @date 2015-12-28 00:16:53
	 */
	public function qqlogin()
	{
		// 调用QQ 的两个接口获取openid
		include_once("./Public/QQ/oauth/qq_callback.php");

		$member = D("Admin/Member");
		$user = $member->field("email")->where(array("openid" => array("eq" => $_SESSION['openid'])))->find();

		if($user)
		{
			// 如果有这个会员就让这个会员为登陆状态
			$member->email = $user['email'];
			if($member->login(FALSE) == FALSE)
			{
				header("Content-type:text/html;charset=utf8");
				die($member->getError());
			}
			echo <<<JS
			<script>
				opener.window.location.href='/';
				window.close();
			</script>
JS;
		}
		else
		{
			// 如果是第一次用QQ登陆那么应该显示一个表单引导用户关联一个账号
			redirect(U('login'));
		}

	}
}

