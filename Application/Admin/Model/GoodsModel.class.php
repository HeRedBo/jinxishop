<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model 
{
	protected $insertFields = array('goods_name','goods_sn','cat_id','brand_id','shop_price','market_price','goods_ori','goods_thumb','jifen','jyz','jifen_price','promote_price','promote_start_time','promote_end_time','goods_number','market_num','goods_desc','seo_keyword','seo_description','type_id','sort_num','is_on_sale','is_new','is_hot','is_best','is_delete','is_promote');
	protected $updateFields = array('id','goods_name','goods_sn','cat_id','brand_id','shop_price','market_price','goods_ori','goods_thumb','jifen','jyz','jifen_price','promote_price','promote_start_time','promote_end_time','goods_number','market_num','goods_desc','seo_keyword','seo_description','type_id','sort_num','is_on_sale','is_new','is_hot','is_best','is_delete','is_promote');
	protected $_validate = array(
		array('goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3),
		array('goods_name', '1,45', '商品名称的值最长不能超过 45 个字符！', 1, 'length', 3),
		array('goods_sn', 'require', '商品编号不能为空！', 1, 'regex', 3),
		array('goods_sn', '1,60', '商品编号的值最长不能超过 60 个字符！', 1, 'length', 3),
		array('cat_id', 'number', '商品分类id必须是一个整数！', 2, 'regex', 3),
		array('brand_id', 'require', '品牌的id不能为空！', 1, 'regex', 3),
		array('brand_id', 'number', '品牌的id必须是一个整数！', 1, 'regex', 3),
		array('shop_price', 'currency', '商品价格必须是货币格式！', 2, 'regex', 3),
		array('market_price', 'currency', '市场价必须是货币格式！', 2, 'regex', 3),
		array('goods_ori', '1,128', ' 商品原图的路径的值最长不能超过 128 个字符！', 2, 'length', 3),
		array('goods_thumb', '1,128', ' 商品小图的路径的值最长不能超过 128 个字符！', 2, 'length', 3),
		array('jifen', 'require', '赠送积分不能为空！', 1, 'regex', 3),
		array('jifen', 'number', '赠送积分必须是一个整数！', 1, 'regex', 3),
		array('jyz', 'require', '赠送经验值不能为空！', 1, 'regex', 3),
		array('jyz', 'number', '赠送经验值必须是一个整数！', 1, 'regex', 3),
		array('jifen_price', 'require', '如果使用积分,需要的积分制不能为空！', 1, 'regex', 3),
		array('jifen_price', 'number', '如果使用积分,需要的积分制必须是一个整数！', 1, 'regex', 3),
		array('promote_price', 'currency', '促销价必须是货币格式！', 2, 'regex', 3),
		array('seo_keyword', '1,30', 'SEO优化_描述的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('seo_description', '1,150', 'SEO优化_描述的值最长不能超过 150 个字符！', 2, 'length', 3),
		array('type_id', 'number', '商品类型id必须是一个整数！', 2, 'regex', 3),
		array('sort_num', 'number', '排序数字必须是一个整数！', 2, 'regex', 3),
		array('is_on_sale', 'number', '是否上架：1:上架 0:下架必须是一个整数！', 2, 'regex', 3),
		array('is_new', 'number', '是否新品必须是一个整数！', 2, 'regex', 3),
		array('is_hot', 'number', '是否热卖必须是一个整数！', 2, 'regex', 3),
		array('is_best', 'number', '是否精品必须是一个整数！', 2, 'regex', 3),
		array('is_delete', 'number', '是否删除, 1：已经删除 0：未删除必须是一个整数！', 2, 'regex', 3),
	);

	public function search($pageSize = 20,$is_delete = 0)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		$where['is_delete'] = $is_delete;
		if($goods_name = I('get.goods_name'))
			$where['goods_name'] = array('like', "%$goods_name%");
		if($cat_id = I('get.cat_id'))
			$where['cat_id'] = array('eq', $cat_id);
		if($brand_id = I('get.brand_id'))
			$where['brand_id'] = array('eq', $brand_id);
		$shop_pricefrom = I('get.shop_pricefrom');
		$shop_priceto = I('get.shop_priceto');
		if($shop_pricefrom && $shop_priceto)
			$where['shop_price'] = array('between', array($shop_pricefrom, $shop_priceto));
		elseif($shop_pricefrom)
			$where['shop_price'] = array('egt', $shop_pricefrom);
		elseif($shop_priceto)
			$where['shop_price'] = array('elt', $shop_priceto);
		$is_hot = I('get.is_hot');
		if($is_hot != '' && $is_hot != '-1')
			$where['is_hot'] = array('eq', $is_hot);
		$is_new = I('get.is_new');
		if($is_new != '' && $is_new != '-1')
			$where['is_new'] = array('eq', $is_new);
		$is_best = I('get.is_best');
		if($is_best != '' && $is_best != '-1')
			$where['is_best'] = array('eq', $is_best);
		$is_on_sale = I('get.is_on_sale');
		if($is_on_sale != '' && $is_on_sale != '-1')
			$where['is_on_sale'] = array('eq', $is_on_sale);
		if($type_id = I('get.type_id'))
			$where['type_id'] = array('eq', $type_id);
		$addtimefrom = I('get.addtimefrom');
		$addtimeto = I('get.addtimeto');
		if($addtimefrom && $addtimeto)
			$where['addtime'] = array('between', array(strtotime("$addtimefrom 00:00:00"), strtotime("$addtimeto 23:59:59")));
		elseif($addtimefrom)
			$where['addtime'] = array('egt', strtotime("$addtimefrom 00:00:00"));
		elseif($addtimeto)
			$where['addtime'] = array('elt', strtotime("$addtimeto 23:59:59"));
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->field('a.*,IFNULL(sum(b.goods_number),0) gn')->alias('a')->join('left join shop_goods_number b on a.id = b.goods_id')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
		$data['addtime'] = time();
		//把促销时间转换为时间戳
		if($data['is_promote'] == 1)
		{
			$data['promote_start_time'] = strtotime($data['promote_start_time']);
			$data['promote_end_time'] = strtotime($data['promote_end_time']);
		}
		if(isset($_FILES['goods_ori']) && $_FILES['goods_ori']['error'] == 0)
		{
			$ret = uploadOne('goods_ori', 'Admin', array(
				array(150, 150, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['goods_ori'] = $ret['images'][0];
				$data['goods_thumb'] = $ret['images'][1];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
	}
	// 商品基本信息插入到数据库中之后
	protected function _after_insert($data,$option){
		
		
		
		/********* 处理商品的扩展分类 **********/
		$eci =I('post.ext_cat_id');
		if($eci)
		{
			$gcModel = M('GoodsCat');
			foreach ($eci as $v) {
				//如果分类为空就跳过处理下一个
				if(empty($v))
					continue;
				$gcModel->add(array(
					'goods_id'=>$data['id'],
					'cat_id'  =>$v
				));
			}

		}

		/********** 处理会员价格 ************/
		$mp = I('post.mp');
		$mpModel = M('MemberPrice');
		foreach ($mp as  $k=>$v) {
			if(empty($v))
				continue;
			$mpModel->add(array(
				'goods_id'=>$data['id'],
				'level_id'=>$k,
				'price' => $v
			));
		}

		/********* 处理商品的属性 ********/
		$ga = I('post.ga');
		$ap = I('post.attr_price');
		if($ga)
		{
			$gaModel = D('GoodsAttr');
			foreach ($ga as $k => $v) 
			{
				foreach($v as $k1 => $v1)
				{
					if(empty($v1))
						continue;
					$price = isset($ap[$k][$k1]) ? $ap[$k][$k1]:'';
					
					$gaModel->add(array(
						'goods_id'	=>$data['id'],
						'attr_id' 	=>$k,
						'attr_value'=>$v1,
						'attr_price'=>$price
					));
				}

			}
		}
		
		/*********** 处理商品的图片 ****************/
		if(hasImage('pics'))
		{
			$gpModel = M('GoodsPics');
			//批量上传商品的图片数组，改造成每一个图片一个以为数组的形式
			$pics = array();
		
			foreach($_FILES['pics']['name'] as $k => $v)
			{
				if($_FILES['pics']['size'][$k] ==0)
					continue;
				$pics[] = array(
					'name'=>$v,
					'type'=>$_FILES['pics']['type'][$k],
					'tmp_name'=>$_FILES['pics']['tmp_name'][$k],
					'error' => $_FILES['pics']['error'][$k],
					'size'  => $_FILES['pics']['size'][$k]
				);

			}
			
			// 使用我们原本定义好的上传文件的函数uploadOne 函数进行文件的上传
			//循环每一张图片一个一个的上传
			
			$_FILES = $pics;
			foreach ($pics as $k => $v) {
				$ret = uploadOne($k,'Goods',array(array(150,150)));
				if($ret['ok'] ==1)
				{

					$gpModel->add(array(
						'goods_id'=>$data['id'],
						'pic'     =>$ret['images'][0],
						'sm_pic'  => $ret['images'][1],
					));
				}
			}
		}
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{

		/********* 判断商品类型有没有被修改 **********/
		if(I('post.old_type_id') != $data['type_id'])
		{
			//删除当前商品所有之前的属性
			$gaModel = M('GoodsAttr');
			$gaModel->where(array('goods_id'=>array('eq',$option['where']['id'])))->delete();
		}

		//如果没有勾选促销价格就手动这只更新为0
		if(!isset($_POST['is_promote']))
		{
			$data['is_promote'] = 0;
		}
		else
		{
			$data['promote_start_time'] = strtotime(I('post.promote_start_time'));
			$data['promote_end_time'] = strtotime(I('post.promote_end_time'));
		}
		
		if(isset($_FILES['goods_ori']) && $_FILES['goods_ori']['error'] == 0)
		{
			$ret = uploadOne('goods_ori', 'Admin', array(
				array(150, 150, 2),
			));
			if($ret['ok'] == 1)
			{
				$data['goods_ori'] = $ret['images'][0];
				$data['goods_thumb'] = $ret['images'][1];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
			deleteImage(array(
				I('post.old_goods_ori'),
				I('post.old_goods_thumb'),
	
			));
		}
	}

	//修改了商品的基本信息之后
	protected function _after_update($data,$option)
	{
		/*************** 处理商品的扩展分类 *******************/
		$exi = I('post.ext_cat_id');
		$gcModel = M('GoodsCat');
		//先清除商品原扩展的分类的数据
		$gcModel->where(array('goods_id'=>array('eq',$option['where']['id'])))->delete();
		//如果有新的数据有在添加一遍
		if($exi)
		{
			foreach ($exi as $k => $v) {
				if(empty($v))
					continue;
				$gcModel->add(array(
					'goods_id'=>$option['where']['id'],
					'cat_id' => $v
				));
				
			}
		}

		/********** 处理会员价格 ***********/
		$mpModel = M('MemberPrice');
		$mpModel->where(array('goods_id'=>array('eq',$option['where']['id'])))->delete();
		$mp = I('post.mp');
		if($mp)
		{
			foreach ($mp as $k => $v) {
				if(empty($v))
					continue;
				$mpModel->add(array(
					'goods_id' => $option['where']['id'],
					'level_id' => $k,
					'price'    => $v,
				));
			}
		}

		/*************** 处理商品的图片 ******************/
		//判断有没有图片
		if(hasImage('pics'))
		{
			$gpModel = M('GoodsPics');
			//批量上传
			$pics = array();
			foreach ($_FILES['pics']['name'] as $k => $v)
			{
				if($_FILES['pics']['size'] ==0)
					continue;
				$pics[] = array(
					'name' =>$v,
					'type' =>$_FILES['pics']['type'][$k],
					'tmp_name'=>$_FILES['pics']['tmp_name'][$k],
					'error'=>$_FILES['pics']['error'][$k],
					'size' => $_FILES['pics']['size'][$k],
				);
			}
			// 在后面的调用uploadOne 方法会使用$_FILES 数组上传图片 我们要把我们处理好的数组给$_FILES 这样子上传时候就是使用我们处理好的数组
			$_FILES  = $pics;
			foreach ($pics as $k => $v) {
				$ret = uploadOne($k,'Goods',array(array(150,150)));
				if($ret['ok'] ==1)
				{
					$gpModel->add(array(
						'goods_id'=>$data['id'],
						'pic'     =>$ret['image'][0],
						'sm_pic'  => $ret['image'][1],
					));
				}
			}
		}

		/************** 处理商品属性 *************/
		//处理新属性
		$ga = I('post.ga');
		$ap = I('post.attr_price');

		
		$gaModel = M('GoodsAttr');
		foreach ($ga as $k => $v)
		{
			foreach ($v as $k1 => $v1) 
			{
				if(empty($v1))
					continue;
				$price = isset($ap[$k][$k1]) ? $ap[$k][$k1] :'';
				$gaModel->add(array(
					'goods_id' => $option['where']['id'],
					'attr_id'  => $k,
					'attr_value' => $v1,
					'attr_price' => $price,
				));
				

			}
		}

		// 处理原属性
		$oldga = I('post.old_ga');
		$oldap = I('post.old_attr_price');
		//循环遍历更新一边所有的旧属性
		foreach ($oldga as $k => $v) {
			foreach ($v as $k1 => $v1) {
				# 要修改的字段
				$oldField = array('attr_value'=> $v1);
				# 如果有对应的价格就把价格也修改
				if(isset($oldap[$k]))
					$oldField['attr_pirce'] = $oldap[$k][$k1];
				$gaModel->where(array('id'=>array('eq',$k1 )))->save($oldField);
			}
		}
	}



	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
		$images = $this->field('goods_ori,goods_thumb')->find($option['where']['id']);
		deleteImage($images);

		/*********** 先删除商品的其他信息 ***************/
		// 扩展分类
		$model = M('GoodsCat');
		$model->where(array('goods_id'=>array('eq',$option['where']['id'])))->delete(); 

		// 会员价格 
		$model = M('MemberPirce');
		$model->where(array('goods_id'=>array('eq',$option['where']['id'])))->delete();

		//商品属性
		$model = M('Goods_attr');
		$model->where(array('goods_id'=>array('eq',$option['where']['id'])))->delete();
		//商品库存量
		$model = M('GoodsNumber');
		$model->where(array('goods_id'=>array('eq',$option['where']['id'])))->delete();
		//商品相册图片
		$model = M('GoodsPics');
		//想取出图片的路径
		$pics = $model->where()->select();
		foreach ($pics as $p) {
			deleteImage($p);
		}
		$model->where(array('goods_id'=>array('eq',$option['where']['id'])))->delete();
		
	}
	/************************* 其他方法 **************************/
	//获取当前正在促销的商品
	public function getPromoteGoods($limit = 5)
	{
		$now = time();
		return $this->field('id,goods_name,promote_price,goods_thumb')->where(
		array(
			'is_on_sale'=> array('eq',1), // 上架
			'is_delete' => array('eq',0), //没有删除
			'is_promote'=> array('eq',1), //促销的商品
			'promote_start_time' =>array('elt',$now),
			'promote_end_time'   =>array('egt',$now),
		))->limit($limit)->order('sort_num ASC')->select();
	}

	//最新的商品
	public function getNew($limit = 5)
	{
		return $this->field('id,goods_name,shop_price,goods_thumb')->where(
		array(
			'is_on_sale'=> array('eq',1), // 上架
			'is_delete' => array('eq',0), //没有删除
			'is_new'=> array('eq',1),
		))->limit($limit)->order('sort_num ASC')->select();
	}

	//热销商品
	public function getHot($limit = 5)
	{
		return $this->field('id,goods_name,shop_price,goods_thumb')->where(
		array(
			'is_on_sale'=> array('eq',1), // 上架
			'is_delete' => array('eq',0), //没有删除
			'is_hot'=> array('eq',1),
			))->limit($limit)->order('sort_num ASC')->select();
	}

	// 精品
	public function getBest($limit = 5)
	{
		return $this->field('id,goods_name,shop_price,goods_thumb')->where(array(
			'is_on_sale'=> array('eq',1), // 上架
			'is_delete' => array('eq',0), //没有删除
			'is_best'=> array('eq',1),
		))->limit($limit)->order('sort_num ASC')->select();
	}

	/**
	 * 计算会员价格
	 * @param int $goodsId
	 * @return string 
	 * @author Red-Bo
	 * @date 2015-11-05 00:47:19
	 */
	public function getMemberPrice($goodsId)
	{
		$now = time();
		//先判断是否有促销
		$price = $this->field('shop_price,is_promote,promote_price,promote_start_time,promote_end_time')->find($goodsId);
		if($price['is_promote'] ==1 && ($price['promote_start_time'] < $now && $price['promote_end_time'] > $now ))
		{
			return $price['promote_price'];
		}
		//如果会员没有登陆直接使用本店价
		$memberId = session('mid');
		if(!$memberId)
			return $price['shop_price'];
		//就算会员价格
		$mpModel = M('MemberPirce');
		$mprice = $mpModel->field('price')->where(array('goods_id'=>array('eq',$goodsId),'level_id'=>array('eq',session('mid'))))->find();
		// 如果会员有会员价格就直接使用会员价格
		if($mprice)
			return $mprice['price'];
		else
			//如果没有设置会员价格 就按照级别的折扣率来算
			return session('rate') * $price['shop_price'];
	}
}