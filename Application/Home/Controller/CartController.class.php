<?php 
namespace Home\Controller;
class CartController extends BaseController
{
	public function add()
	{

		$cartModel    = D('Admin/Cart');
		$goodsAttrIds = I('post.goods_attr_id');
		if($goodsAttrIds)
		{
			// 把属性ids升序排序 因为后台商品属性也是升序排序的 以便取出库存量
			sort($goodsAttrIds);
			$goods_attr_id = implode(',',$goodsAttrIds);
			
		}
		$cartModel->addToCart(I('post.goods_id'),$goodsAttrIds,I('post.amount'));
		redirect(U('lst'));
	}

	public function lst()
	{
		$cartModel = D('Admin/Cart');
		$data = $cartModel->cartList();

		$this->assign('data',$data);
		$this->setPageInfo('购物车','购物车','购物车',1,array('cart'),array('cart1'));
		$this->display();
	}

	/**
	 * ajax更新购物车数据
	 * @param 
	 * @return 
	 * @author Red-Bo
	 * @date 2015-11-17 01:26:38
	 */
	public function ajaxUpdateData()
	{

		$gid = I('get.gid');
		$gn  = I('get.gn');
		$gaid= I('get.gaid');
		$cartModel = D('Admin/Cart');
		$data = $cartModel->updateData($gid,$gaid,$gn);
	}

	/**
	 * 商品下单
	 * @author Red-Bo
	 * @date 2015-11-26 23:27:23
	 */
	public function order()
	{

		$mid = session('mid');
		// 如果会员没有登陆 跳到登陆页面 登陆成功跳转到这个页面
		if(!$mid)
		{
			 // 把当前页面的地址存到session 中 这样登陆成功就跳回来
			 session('returnUrl',U('order'));
			 redirect(U('Home/Member/login'));
		}

		// 下订单就进行订单处理
		if(IS_POST)
		{
			$orderModel = D('Admin/Order');
			if($orderModel->create(I('post.'),1))
			{
				if($id = $orderModel->add())
				{
					$this->success('下单成功',U('order_ok?id='.$id));exit;
				}
			}
			$this->error($orderModel->getError());
		}

		$cartModel = D('Admin/Cart');
		$data = $cartModel->cartList();
		$this->assign('data',$data);
		// 显示表单
		$this->setPageInfo('下订单','下订单','下订单',1,array('fillin'),array('cart2'));
		$this->display();
	}

	/**
	 * 下单成功显示显示的页面
	 * @param 
	 * @return 
	 * @author Red-Bo
	 * @date 2015-12-27 21:56:05
	 */
	public function order_ok()
	{
		$id = I('get.id');
		// 查询这个订单的总价是多少
		$orderModel = M("Order");
		$tp = $orderModel->field('total_price')->find($id);
	/*********************** 生成支付宝按钮  ******************************/
		
		require_once("./Public/alipay/alipay.config.php");
		require_once("./Public/alipay/lib/alipay_submit.class.php");



	    //支付类型
	    $payment_type = "1";
	    //必填，不能修改
	    //服务器异步通知页面路径 -- 接收支付宝发来的消息的地址
	    $notify_url = "http://www.myshop.com/Home/Cart/respond";
	    //需http://格式的完整路径，不能加?id=123这类自定义参数

	    //页面跳转同步通知页面路径
	    $return_url = "http://www.myshop.com/Home/Cart/success";
	    //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

	    //卖家支付宝帐户
	    $seller_email = '13925185424';
	    //必填

	    //商户订单号
	    $out_trade_no = $id;
	    //商户网站订单系统中唯一订单号，必填

	    //订单名称
	    $subject = "京西商城订单支付";
	    //必填

	    //付款金额
	    $total_fee = $tp['total_price'];
	    //必填

	    //订单描述

	    $body = '京西商城订单支付';
	    //商品展示地址
	    $show_url = '';
	    //需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html

	    //防钓鱼时间戳
	    $anti_phishing_key = "";
	    //若要使用请调用类文件submit中的query_timestamp函数

	    //客户端的IP地址
	    $exter_invoke_ip = $_SERVER['REMOTE_ADDR'];
	    //非局域网的外网IP地址，如：221.0.0.1


	/************************************************************/

	//构造要请求的参数数组，无需改动
	$parameter = array(
			"service" => "create_direct_pay_by_user",
			"partner" => trim($alipay_config['partner']),
			"payment_type"	=> $payment_type,
			"notify_url"	=> $notify_url,
			"return_url"	=> $return_url,
			"seller_email"	=> $seller_email,
			"out_trade_no"	=> $out_trade_no,
			"subject"		=> $subject,
			"total_fee"		=> $total_fee,
			"body"			=> $body,
			"show_url"		=> $show_url,
			"anti_phishing_key"	=> $anti_phishing_key,
			"exter_invoke_ip"	=> $exter_invoke_ip,
			"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);

		//建立请求
		$alipaySubmit = new \AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "立即以GET方式跳转到阿里巴巴旗下的支付宝支付网站实现在线的支付功能");
	
		$this->assign('btn',$html_text);

		$this->setPageInfo('下单成功','下单成功','下单成功',1,array("success"));
		$this->display();
	}

	/**
	 * 支付宝异步回调通知函数
	 * @author Red-Bo
	 * @date 2015-12-27 23:04:05
	 */
	public function respond()
	{
		require_once("./Public/alipay/lib/alipay_notify.class.php");
	}

	/**
	 * 支付成功跳转页面
	 * @author Red-Bo
	 * @date 2015-12-27 23:09:03
	 */
	public function success()
	{
		echo "支付成功！";
	}
	
}
 																																										
