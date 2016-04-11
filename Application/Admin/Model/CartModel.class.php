<?php 
namespace Admin\Model;
use Think\Model;
class CartModel extends Model
{
	// 商品加入购物车
	public function addToCart($goods_id,$goods_attr_id,$goods_number = 1)
	{
		$mid = session('mid');
		// 如果登陆了加入到购物车 如果未登录就报错到cookie;
		if($mid)
		{
			$cartModel = M('Cart');
			$has = $cartModel->where(array(
				'member_id' => array('eq',$mid),
				'goods_id'  => array('eq',$goods_id),
				'goods_attr_id' =>array('eq',$goods_attr_id),
			))->find();
			if($has)
			{
				$cartModel->where(array('id = '.$has['id']))->setInc(array(
					'goods_number' => $goods_number));
			}
			else
			{
				$cartModel->add(array(
					'goods_id' 		=> $goods_id,
					'goods_attr_id' => $goods_attr_id,
					'goods_number'  => $goods_number,
					'member_id' 	=> $mid,
				));
			}
		}
		else
		{

			// 先从cookie 中取出数据
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();

			// 把商品加入到这个数值中 
			$key = $goods_id .'-'. $goods_attr_id;

			if(isset($cart[$key]))
				$cart[$key] += $goods_number;
			else
				$cart[$key] = $goods_number;
			// 把这个数值保存到cookie中
			
			$aMonth = 30 * 86400;
			setcookie('cart',serialize($cart), time() + $aMonth,'/','myshop.com');
		}
	}
	
	/**
	 * 购物车列表
	 * @return array
	 * @author Red-Bo
	 * @date 2015-11-15 22:28:36
	 */
	public function cartList()
	{
		
		$mid = session('mid');
		if($mid)
		{
			$cartModel = M('Cart');
			$_cart = $cartModel->where(array('member_id'=> array('eq',$mid)))->select();
			
		}
		else
		{
			$_cart_ = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			
			// 转化这个数组结构和从数据库重的数组结构一样 都是二维数组
			$_cart = array();
			foreach ($_cart_ as $k => $v) 
			{
				$_k = explode('-',$k);
				$_cart[] = array(
					'goods_id' 		=> $_k[0],
					'goods_attr_id' => $_k[1],
					'goods_number' 	=> $v,
					'member_id' 	=> 0
				);	
			}
		}
		// 循环购物车的每件商品 根据id取出商品详情页面信息 
		$goodsModel = D('Admin/Goods');
	
		foreach ($_cart as $k => $v) 
		{
			$ginfo = $goodsModel->field('goods_thumb,goods_name')->find($v['goods_id']);
			$_cart[$k]['goods_name'] = $ginfo['goods_name'];
			$_cart[$k]['goods_thumb']= $ginfo['goods_thumb'];
			// 计算会员的价格 
			$_cart[$k]['price'] = $goodsModel->getMemberPrice($v['goods_id']);
			
			//把上的属性的id转化为商品属性的字符串
			$_cart[$k]['goods_attr_str'] = $goodsModel->converGoodsAttrIdToGoodsAttrStr($v['goods_attr_id']);

			
 		}
 		return $_cart;
	}

	//把cookie中的数据迁移到数据库中并清空cookie中的数据
	public function moveDataToDb()
	{
		$mid = session('mid');
		if($mid)
		{
			// 先从cookie 中取出数据
			$cart = isset($_COOKIE['cart']) ? unserialize($_COOKIE['cart']) : array();
			// 把商品加入到这个数值中 
			if($cart)
			{
				foreach ($cart as $k => $v) 
				{
					// 从小标解析出商品的ID,和商品的属性ID
					$_k = explode('-',$k);
					$this->addToCart($_k[0],$_k[1],$v);
				}
				//清空cookie
				setcookie('cart','', time()-1,'/','myshop.com');
			}
			
		}
	}

	// 更新数据
	public function updateData($gid,$gaid,$gn)
	{

		$mid = session('mid');
		if($mid)
		{
			$cartModel = M('Cart');
			if($gn ==0)
			{
				$cartModel->where(array(
					'goods_id' 		=> array('eq',$gid),
					'goods_attr_id' => array('eq',$gaid),
					'member_id' 	=> array('eq',$mid)
				))->delete();
			}
			else
			{
				$cartModel->where(array(
					'goods_id' 		=> array('eq',$gid),
					'goods_attr_id' => array('eq',$gaid),
					'member_id' 	=> array('eq',$mid)
				))->setField('goods_number',$gn);
			}
		}
		else
		{
			// 先从cookie 取出数据
			$cart = isset($_COOKIE['cart']) ? unserialize( $_COOKIE['cart']) : array();
			$key = $gid .'-'. $gaid;

			if($gn == 0)
				unset($cart[$key]);
			else
				$cart[$key] = $gn;
			$aMonth = 30 * 86400;

			setcookie('cart',serialize($cart),time() + $aMonth, '/','myshop.com');

 
		}
	}

	/**
	 * 
	 */
}