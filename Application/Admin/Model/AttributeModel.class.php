<?php
namespace Admin\Model;
use Think\Model;
class AttributeModel extends Model 
{
	protected $insertFields = array('attr_name','type_id','attr_type','attr_input_type','attr_option_values');
	protected $updateFields = array('id','attr_name','type_id','attr_type','attr_input_type','attr_option_values');
	protected $_validate = array(
		array('attr_name', 'require', '属性名称不能为空！', 1, 'regex', 3),
		array('attr_name', '1,32', '属性名称的值最长不能超过 32 个字符！', 1, 'length', 3),
		# 验证属性类型的值只能为0或者1
		array('attr_type',array(0,1),'属性值不合法',1,'in'),
		array('attr_input_type',array(0,1),'录入方式不合法',1,'in'),
		
		
	);

	public function search($pageSize = 20)
	{
		/******************* 搜索 *************************/
		$where['type_id'] = I('get.type_id');
		
		/******************* 翻页 ********************/
		$count = $this->alias('a')->where($where)->count();
		$page = new \Think\Page($count, $pageSize);
		// 配置翻页的样式
		$page->setConfig('prev', '上一页');
		$page->setConfig('next', '下一页');
		$data['page'] = $page->show();
		/**************************** 取数据 ****************************/
		$data['data'] = $this->alias('a')->where($where)->group('a.id')->limit($page->firstRow.','.$page->listRows)->select();
		return $data;
	}
	// 添加前
	protected function _before_insert(&$data, $option)
	{
	}
	// 修改前
	protected function _before_update(&$data, $option)
	{
	}
	// 删除前
	protected function _before_delete($option)
	{
		if(is_array($option['where']['id']))
		{
			$this->error = '不支持批量删除';
			return FALSE;
		}
	}
	/************************************ 其他方法 ********************************************/
}