<?php
namespace Admin\Model;
use Think\Model;
//商品模型
class GoodsModel extends Model
{
	//在添加是调用create 方法运行接收的字段
	protected $insertFields = array('goods_name','goods_sn','shop_price','goods_number','goods_desc','is_on_sale');
	//在修改时候表单中可以有哪些字段
	protected $updateFields = array('id','goods_name','goods_sn','shop_price','goods_number','goods_desc','is_on_sale');
	//数据自动验证 控制器中creat方法时自动调用
	protected $_validate = array(
		array('goods_name','require','商品名称不能为空',1),
		array('goods_name','1,45','商品名称必须是1-45个字符',1,'length'),
		array('shop_price','currency','价格必须是货币格式',1),
		array('goods_number','require','商品库存不能为空',1),
		array('goods_number','number','商品库存必须为数值',1),
		array('is_on_sale','0,1','是否上架只能是0,1两个值',1,'in')
	);

	/**
	 * TP在调用add方法之前会自动调用_before_insert()方法，我们可以吧在插入数据库之前要执行的代码写入到这里
	 * @param 表单中的数据(要插入到数据库中的数据) 是一个以为数据
	 * @param 额外信息，当前模型对应的实际的表名是什么 是一个一维数组
	 * 说明:在这个函数中要改变这个函数的外部的$data，需要按钮应用传递 否则修改也无效
	 * 说明:如果return false 是指控制器中的add 方法返回了false
	 */
	protected function _before_insert(&$data,$option)
	{
		$data['addtime'] = time();
		//上传商品图片
		if(isset($_FILE['goods_img']) && $_FILES['goods_img']['error'] == 0)
		{
			$ret = uploadOne('goods_img','Goods',array(
				array(230,230),
				array(100,100)
			));
			if($ret['ok'] ==1)
			{
				//文件上传成功
				$data['goods_ori'] = $res['images'][0];
				$data['goods_img'] = $res['images'][1];
				$data['goods_thumb'] = $res['images'][2];
			}
			else
			{
				$this->error = $ret['error'];
				return FALSE;
			}
			/*$rootPath 	= C('IMG_rootPath');
			$upload  	= new \Think\Upload(array(
				'rootPath'=>$rootPath
			));//实例化文件上传类
			$upload->maxSize 	= (int)C('IMG_maxSize') * 1024 * 1024 ;//设置附件上传大小
			//$upload->rootPath = $rootPath;//设置文件上传的根目录
			$upload->savePath 	= 'Goods/';//设置二级目录的名称
			
			$upload->autoSub 	= false;
			//上传文件
			$info = $upload->upload();
			if(!$info)
			{
				//先把文件失败的原因存到模型中，由控制器最终在获取这个错误信息并显示
				$this->error = $upload->getError();
				return FALSE;//将错误信息返回给控制器
			}
			else
			{	
				$imgName 		= $info['goods_img']['savepath'] . $info['goods_img']['savename'];
				//拼出缩略图的文件名
				$thumbimgName 	= $info['goods_img']['savepath'] . 'thumb_'. $info['goods_img']['savename'];

				//生成缩略图
				$image = new \Think\Image();
				//打开要处理的图片
				$image->open($rootPath.$imgName);
				$image->thumb(150,150)->save($rootPath.$thumbimgName);

				//将图片的路径保存到要保存的数据中
				$data['goods_ori'] = $imgName;
				$data['goods_thumb'] = $thumbimgName;
			}*/

		}

 	}

 	public  $_edit_validate = array(
 		
 	);

 	/**
 	 * 商品修改时候自动回调用此方法
 	 * @return [type] [description]
 	 */
 	protected function _before_update(&$data,$option)
 	{
 		//上传商品图片
		if(isset($_FILES['goods_img']) && $_FILES['goods_img']['error'] == 0)
		{
			$ret = uplaodOne('goods_img','Goods',array(
				array(230,230),
				array(100,100)
			));
			if($ret['ok'] ==1)
			{	

				//文件上传成功
				$data['goods_ori'] = $ret['images'][0];
				$data['goods_img'] = $ret['images'][1];
				$data['goods_thumb'] = $ret['images'][2];
			}
			else
			{
				$this->error = $ret['error'];
				return FALSE;
			}

			deleteImage(array(
				I('post.old_goods_ori'),
				I('post.old_goods_img'),
				I('post.old_goods_thumb'),
			));
			/*$rootPath 	= C('IMG_rootPath');
			$upload  	= new \Think\Upload(array(
				'rootPath'=>$rootPath
			));//实例化文件上传类
			$upload->maxSize 	= (int)C('IMG_maxSize') * 1024 * 1024 ;//设置附件上传大小
			//$upload->rootPath = $rootPath;//设置文件上传的根目录
			$upload->savePath 	= 'Goods/';//设置二级目录的名称
			
			$upload->autoSub 	= false;
			//上传文件
			$info = $upload->upload();
			if(!$info)
			{
				//先把文件失败的原因存到模型中，由控制器最终在获取这个错误信息并显示
				$this->error = $upload->getError();
				return FALSE;//将错误信息返回给控制器
			}
			else
			{	
				$imgName 		= $info['goods_img']['savepath'] . $info['goods_img']['savename'];
				//拼出缩略图的文件名
				$thumbimgName 	= $info['goods_img']['savepath'] . 'thumb_'. $info['goods_img']['savename'];

				//生成缩略图
				$image = new \Think\Image();
				//打开要处理的图片
				$image->open($rootPath.$imgName);
				$image->thumb(150,150)->save($rootPath.$thumbimgName);

				//将图片的路径保存到要保存的数据中
				$data['goods_ori'] = $imgName;
				$data['goods_thumb'] = $thumbimgName;

				//删除商品的原图片
				$irp = C('IMG_rootPath');
		 		//删除图片
		 		unlink($irp . $images['goods_ori']);
		 		unlink($irp . $images['goods_thumb']);
		 		unlink($irp . $images['goods_img']);
			}*/

		}

 	}

 	/**
 	 * 商品列表
 	 * @author RedBo
 	 * date 2015-10-04 13:53
 	 */
 	public function search()
 	{

 		/******* 搜索 *******/
 		$where = array();
 		//商品名称的搜索
 		$goodsName = I('get.goods_name');
 		if($goodsName)
 			$where['goods_name'] = array('like',"%$goodsName%");
 		//价格的搜索
 		$startPrice = I('get.start_price');
 		$endPrice = I('get.end_price');
 		if($startPrice && $endPrice)
 			$where['shop_price'] = array('between',array($startPrice,$endPrice));
 		elseif ($startPrice)
 			$where['price']		 = array('egt',$startPrice);
 		elseif ($endPrice) 
 			$where['price'] 	 = array('elt',$endPrice);
 		//时间的搜索
 		$startAddtime = I('get.start_addtime');
 		$endAddtime   = I('get.end_time');
 		if($startAddtime && $endAddtime)
 			$where['addtime'] = array('between',array(strtotime("$start_addtime 00:00:01") , strtotime("$endAddtime 23:59:59")));
 		elseif($startAddtime)
 			$where['addtime'] = array('egt',strtotime("$startAddtime 00:00:01"));
 		elseif($endAddtime)
 			$where['addtime'] = array('elt',strtotime("$endAddtime 23:59:59"));
 		//上架的搜索
 		$isOnSale = I('get.is_on_sale',-1);
 		if($isOnSale !=-1)
 			$where['is_on_sale'] = array('eq',$isOnSale);
 		//是否删除的搜索
 		$isDelete = I('get.is_delete',-1);
 		if($isDelete != -1)
 			$where['is_delete']	 = array('eq',$isDelete);
 		/**************** 排序 ********************/
 		$orderby 	= 'id';
 		$orderway	= 'asc';
 		$odby = I('get.odby');
 		if($odby && in_array($odby , array('id_asc','id_desc','price_asc','price_desc')))
 		{
 			if($odby == 'id_desc') 
 				$orderway = 'desc';
 			elseif ($odby == 'price_asc')
 				$orderby = 'shop_price';
 			elseif ($odby == 'price_desc')
 			{
 				$orderby  = 'shop_price';
 				$orderway = 'desc' ;
 			}
 		}
 		/************ 分页 *************/
 			//总的记录数
 			$count = $this->where($where)->count();
 			$perpage = C('ADMIN_PERPAGE');
 			//生成分页对象
 			import('Org.Util.Page');
 			$page 		= new \Page($count,$perpage,'',array('','current'));
 			$limit 		= $page->limit;
 			$pageString = $page->fpage();
 			//取出当前页数的数据
 			$data = $this->where($where)->limit($limit)->order("$orderby $orderway")->select();
 			//echo $this->getLastSql();
 			return array(
 				'page' => $pageString,
 				'data' => $data
 			);
 	}

 	/**
 	 * 控制器调用delete方法之前会自动调用
 	 */
 	protected function _before_delete($option)
 	{
 		//先根据商品的id取出这件商品的图片的路径
 		$images = $this->field('goods_ori,goods_thumb,goods_img')->find($option['where']['id']);
 		//var_dump($images);exit;
 		//从配置文件中读取文件存放的路径
 		//$irp = U('');
 		
 		$irp = C('IMG_rootPath');
 		//删除图片
 		unlink($irp . $images['goods_ori']);
 		unlink($irp . $images['goods_thumb']);
 		unlink($irp . $images['goods_img']);
 	}
}