<?php
namespace Admin\Model;
use Think\Model;
class BrandModel extends Model 
{
	protected $insertFields = array('brand_name','site_url','brand_logo','sort','is_show');
	protected $updateFields = array('id','brand_name','site_url','brand_logo','sort','is_show');

	protected $_validate = array(
		array('brand_name', 'require', '品牌名称不能为空！', 1, 'regex', 3),
		array('brand_name', '1,45', '品牌名称的值最长不能超过 45 个字符！', 1, 'length', 3),
		array('site_url', 'require', '品牌网站地址不能为空！', 1, 'regex', 3),
		array('site_url', '1,60', '品牌网站地址的值最长不能超过 60 个字符！', 1, 'length', 3),
		array('sort', 'number', '商品排序必须是一个整数！', 2, 'regex', 3),
		array('is_show', 'number', '是否显示 0:不显示 1:显示必须是一个整数！', 2, 'regex', 3),
	);
	public function search($pageSize = 20)
	{
		/**************************************** 搜索 ****************************************/
		$where = array();
		if($brand_name = I('get.brand_name'))
			$where['brand_name'] = array('like', "%$brand_name%");
		if($site_url = I('get.site_url'))
			$where['site_url'] = array('like', "%$site_url%");
		if($goods_desc = I('get.goods_desc'))
			$where['goods_desc'] = array('eq', $goods_desc);
		if($sort = I('get.sort'))
			$where['sort'] = array('eq', $sort);
		if($is_show = I('get.is_show'))
			$where['is_show'] = array('eq', $is_show);
		/************************************* 翻页 ****************************************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/************************************** 取数据 ******************************************/
		$data['data'] = $this->alias('a')
							 ->where($where)
							 ->group('a.id')
							 ->limit($page->firstRow.','.$page->listRows)
							 ->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
		if(isset($_FILES['brand_logo']) && $_FILES['brand_logo']['error'] == 0)
		{
			$ret = uploadOne('brand_logo', 'Admin', array( ));
			if($ret['ok'] == 1)
			{
				$data['brand_logo']     = $ret['images'][0];
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
		}
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{

		
		if(isset($_FILES['brand_logo']) && $_FILES['brand_logo']['error'] == 0)
		{
			$ret = uploadOne('brand_logo', 'Admin', array());
			if($ret['ok'] == 1)
			{
				$data['brand_logo']     = $ret['images'][0];
				
			}
			else 
			{
				$this->error = $ret['error'];
				return FALSE;
			}
			deleteImage(array(
				I('post.old_brand_logo'),
			
	
			));
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
		$images = $this->field('brand_logo,big_brand_logo,mid_brand_logo,sm_brand_logo')->find($option['where']['id']);
		deleteImage($images);
	}
	
}