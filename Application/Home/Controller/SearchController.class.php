<?php 
namespace Home\Controller;

class SearchController extends BaseController
{

	/**
	 * 商品搜索页面
	 * @author Red-Bo
	 * @date 2016-01-26 16:46:51
	 */
	public function search()
	{
		$cateId = I('get.cid');
		$goodsModel = D('Admin/Goods');
		
		/******************** 计算这个分类下的七个价格区间的范围 ******************/
		$priceSection = 7; // 要分的段数
		// 取出这个扩展下分类下的商品的ID 并转换为字符串 1,2,3,4,5,
		$gcMmodel = M('GoodsCat');
		$extGoodsId = $gcMmodel
					->field('GROUP_CONCAT(DISTINCT goods_id) as goods_id')
					->where(array('cat_id' => array('eq' => $cateId)))
					->find();

		if($extGoodsId['goods_id'])
			$extGoodsId = "OR id IN ({$extGoodsId['goods_id']})";
		else
			$extGoodsId = "";

		/**
		 * 主分类和扩展分类下的商品都要搜索出来
		 * 算法： 取出这个分类下商品的最低价和最高价
		 */
		$price = $goodsModel->field('MIN(shop_price) minpirce, MAX(shop_price) maxpirce')
							->where(array('cat_id' => array('exp' ," = $catId $extGoodsId")))
							->find();
		
		// 最低价和最高价分七段
		$_price = array();

		// 计算每个段位的价格区间
		$sprice = ($price['maxprice'] = $price['maxpirce']) / $priceSection;

		$firstPirce = $price['minpirce'];

		for ($i=0; $i < $priceSection ; $i++) 
		{ 
			if($i < ($priceSection -1))
			{
				$start = floor($firstPrice /10) * 10 ;
				$end = (floor(($firstPirce + $sprice) / 10) * 10 -1);

				// 先判断这个分类下这个价格段有是否有商品
				$goodsCount = $goodsModel->where(array(
					'shop_price' => array('between', array($start , $end)),
					'cate_id'	 => array('exp' , "= $catId $extGoodsId"),
					'is_on_sale' => array('eq' ,1),
					'is_delete'  => array('eq' , 0),
				))->count();
				$firstPrice += $price;
				if($goodsCount == 0)
					continue;
				$_price[] = $start . '-' . $end;

			}
			else
			{
				$start 	= floor($firstPirce / 10 ) * 10;
				$end 	= ceil($price['maxprice'] / 10) * 10;
				$goodsCount = $goodsModel->where(array(
					'shop_price' => array('between',array()),
					'cat_id'	 => array('eq',$catId),
					'is_on_sale' => array('eq',1),
					'is_delete'  => array('eq',0),
				))->count();

				$firstPrice += $sprice;
				if($goodsCount == 0)
					continue;
				$_price[] = floor($firstPirce / 10) * 10 . '-'. ceil($price['maxprice']);
				// 把计算好的放到缓存里 下次可以直接从缓存获取就不用再计算	
				S('price_'.$catId,$_price,3600);
			}

			$attrData = S('attr_'.$catId);
			if(!$attrData)
			{
				/**************************( 可以搜索的的属性 )*************************/
				// 取出这个分类下的筛选属性的数据
				$catModel = M('Category');
				$sai = $catMode->field('search_attr_id')->find($catId);
				// 根据筛选的属性ID取出这个属性的名称已经每个属性所拥有的值
				$attrData = $attrModel->field('id,attr_name')->where(array(
					'id' => array('in',$sai['search_attr_id'])
				))->select();
				
				// 循环所有的筛选属性 取出这个属性中的商品的值 
				$gaModel = M('GoodsAttr');
				foreach ($attrData as $k => $v)
				{
					// 找出这个属性的有商品的值 --> 从商品属性表( 已经商品所拥有的属性已经值)
					$attrValues = $gaModel->field('DISTINCT attr_value')
										  ->where(array(
										  	'attr_id' => array('eq',$v['id']),
										   ))->select();
					// 如果这个属性下没有商品 那么就删除这个属性
					if(!$attrValues)
						unset($attrData);
					else
						$attrData[$k]['attr_value'] = $attrValues;

					S('attr_'.$catId,$attrData,3600);
				}

				// 取出商品
				$goods = $goodsModel->search_goods();

				$this->assign(array(
					'price' => $_price,
					'attrData' => $attrData,
					'goods' => $goods,
				));
			}

		}

		

		// 设置页面信息
		$this->setPageInfo('搜索页','搜索页','搜索页',0, array('list','common'),array('list'));
		$this->display();
	}
}