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
}