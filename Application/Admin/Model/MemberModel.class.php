<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model
{
	// 注册时 表单中的运行提交的字段
	protected $insertFields = array('email','password','cpassword','chkcode','mustclick');
	//注册时的表单验证规则
	protected $_validate =array(
		array('mustclick','require','必须同意注册协议才能注册',1),
		array('email','require','email不能为空！',1),
		array('email','email','email格式不正确',1),
		array('password','require','密码不能为空',1),
		array('password','6,20','密码必须是6-20为字符',1,'length'),
		array('cpassword','password','两次密码输入不一致',1,'confirm'),
		array('chkcode','require','验证码不能为空',1),
		array('chkcode', 'require', '验证码不能为空！', 1),
		array('chkcode','chk_chkcode','验证码输入有误',1,'callback'),
		array('email','','email已经被注册过了！',1,'unique'),
	);

	//登陆时候的验证规则
	public $_login_validate = array(
		array('email','require','email不能为空！',1),
		array('email','email','email格式不正确',1),
		array('password','require','密码不能为空',1),
		array('password','6,20','密码必须是6-20为字符',1,'length'),
		array('chkcode', 'require', '验证码不能为空！', 1),
		array('chkcode','chk_chkcode','验证码输入有误',1,'callback'),
	);
	/**
	 * 验证验证码
	 */
	public function chk_chkcode($code)
	{
		$verify = new \Think\Verify();
		return $verify->check($code);
	}

	// 在会员记录数据插入数据库之前
	protected function _before_insert(&$data,$option)
	{
		$data['addtime'] = time();
		//生成email 验证码
		$data['email_code'] = md5(uniqid());
		//会员密码加密
		$data['password'] = md5($data['password'].C('MD5_KEY'));
	}

	//会员注册成功之后发送邮件
	protected function _after_insert($data,$option)
	{
		$content =<<<HTML
		<span>欢迎注册京西会员,请点击一下链接地址完成email验证。</span>
			<span><a href="http://www.myshop.com/Home/Member/emailchk/code/{$data['email_code']}" >点击完成注册</a></span>
HTML;
		//把生成的验证码发送到会员的邮箱中
		sendMail($data['email'],'京西商城邮箱验证',$content);
	}

	/**
	 * 模型登陆方法 | 设置登陆方法没有密码也可以登陆 QQ登陆需要用到
	 * @author Red-Bo
	 * @data 2015-10-29 00:08:12
	 */
	public function login($usePassword = TRUE)
	{
		$email 		= $this->email;
		if($usePassword)
			$password 	= $this->password;
		$user = $this->where(array('email' => array('eq',$email)))->find();

		if($user)
		{
			// 判断是否已经通过邮件验证
			if(empty($user['email_code']))
			{
				if($usePassword)
				{
					if(md5($password.C('MD5_KEY')) != $user['password'])
					{
						$this->error = "密码错误！";
						return FALSE;
					}
					
				}
			
					session('mid',$user['id']);
					session('email',$user['email']);
					session('jyz',$user['jyz']);
					//取出当前登陆会员所在的这个级别id和这个级别的折扣
					$mlModel = M('MemberLevel');
					$ml = $mlModel->field('id,rate')->where("{$user['jyz']} BETWEEN bottom_num AND top_num")->find();
					
					//$ml = $mlModel->field('id,rate')->where("{$user['jyz']} BETWEEN bottom_num AND top_num")->find();
					
					session('level_id',$ml['id']);
					session('rate',$ml['rate']/100);

					// 用户登录需要把购物车数据从cookie 移动到数据库
					$cartModel = D("Admin/Cart");
					$cartModel->moveDataToDb();
					// 如果有openid 就绑定到这个账号上
					if(isset($_SERVER['openid']))
					{
						$this->where(array('id' => array('eq',$user['id'])))->setField('openid',$_SERVER['openid']);
					}
					return TRUE;

				
			}
			else
			{
				$this->error = "账号还没有通过邮件验证！";
				return FALSE;
			}
		}
		else
		{
			$this->error = "账号不存在！";
			return FALSE;
		}

	}
}