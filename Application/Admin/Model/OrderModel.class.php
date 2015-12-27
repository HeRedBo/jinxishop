<?php
namespace Admin\Model;
use Think\Model;
class OrderModel extends Model
{
	protected $insertFields = array('shr_name','shr_province','shr_city','shr_area','shr_address','shr_tel','pay_method','post_method');
	
	// 数据验证规则
	protected $_validate = array(
		array('shr_name', 'require', '收件人地址不能为空！', 1, 'regex', 3),
		array('shr_province', 'require', '收件人所在省份不能为空！', 1, 'regex', 3),
		array('shr_city', 'require', '收件人所在城市不能为空！', 1, 'regex', 3),
		array('shr_area', 'require', '收件人所在地区不能为空！', 1, 'regex', 3),
		array('shr_address', 'require', '收件人地址不能为空！', 1, 'regex', 3),
		array('shr_tel', 'require', '收件人电话不能为空！', 1, 'regex', 3),
		array('pay_method', 'require', '支付方式不能为空', 1, 'regex', 3),
		array('post_method', 'require', '发货方式不能为空！', 1, 'regex', 3),
	);

	protected function _before_insert(&$data,$option)
	{
		//判断购物车是否有商品
		$cartModel = D('Admin/Cart');
		$cartData = $cartModel->cartList();
		if(count($cartData) == 0)
		{
			$this->error = '必须先购买商品才可以下单！';
			return FALSE;
		}

		// 循环购物车的每件商品检查库存量够不够, 并且计算总价
		// 加锁-> 解决高并发库存混乱
		$this->fp = fopen('./lock.txt','r');
		flock($this->fp,'LOCK_EX');
		$tp = 0; // 总价
		$gnModel = M('GoodsNumber');
		foreach ($cartData as $k => $v) 
		{
			//取出这件商品的库存
			$gn = $gnModel->field('goods_number')->where(array(
				'goods_id' => array('eq',$v['goods_id']),
				'goods_attr_id' => array('eq',$v['goods_attr_id']),
			))->find();
			
			if($gn['goods_number'] < $v['goods_number'])
			{
				$this->error = '商品库存量不足无法下单';
				return FALSE;
			}
			// 总价
			$tp = $v['price'] * $v['goods_number'];
		}
		
		$data['member_id'] = session('mid');
		$data['addtime']   = time();
		$data['total_price'] = $tp;

		//启用事务
		mysql_query('START TRANSACTION');
	}

	/**
	 * 下单完成 清除购物车
	 * @param array $data
	 * @param array $option
	 * @return 
	 * @author Red-Bo
	 * @date 2015-11-30 07:30:25
	 */
	protected function _after_insert($data,$option)
	{
		// 把购物出中的数据存到订单商品表
		$cartModel = D('Admin/Cart');
		$cartData = $cartModel->cartList();
		// 循环每件商品, 1 :减少商品库存 2：插入高指定的商品表中
		$gnModel = M('GoodsNumber');
		$ogModel = M('OrderGoods');
		foreach ($cartData as $k => $v) 
		{
			// 减少库存量
			$rs = $gnModel->where(array(
				'goods_id' => array('eq',$v['goods_id']),
				'goods_attr_id' => array('eq',$v['goods_attr_id']),
			))->setDec('goods_number',$v['goods_number']);

			if($rs === FALSE)
			{
				mysql_query('ROLLBACK');
				return FALSE;
			}
			// 将商品插入订单商品表
			$rs = $ogModel->add(array(
				'order_id' 	=> $data['id'],
				'mumber_id' => session('mid'),
				'goods_id' 	=> $v['goods_attr_id'],
				'goods_attr_id' => $v['goods_attr_id'],
				'goods_attr_str'=> $v['price'],
				'goods_number'  => $v['goods_number']
			));
			if($rs === FALSE)
			{
				mysql_query('ROLLBACK');
				return FALSE;
			}

			mysql_query('COMMIT'); // 提交事务
			// 释放锁
			flock($this->fp,LOCK_NN);
			flock($this->fp);
		}
	}

	/**
	 * 设置订单的保存转态
	 * @author Red-Bo
	 * @date 2015-12-27 23:31:19
	 */
	public function setPaid($order_id)
	{
		// 更新订单的支付状态为已支付
		$this->where(array('id' => array('eq',$id)))->setField('pay_status',1);
		// 总价会员点的积分制 -- 订单的总价是多少就增加多少的经验值
		$info = $this->field('total_price,member_id')->find($id);
		$this->execute('UPDATE shop_member SET jyz =jyz +'.$info['total_price'],'jifen = jifen +'.$info['total_price'].' WHERE id = '.$info['member_id']);
	}
}