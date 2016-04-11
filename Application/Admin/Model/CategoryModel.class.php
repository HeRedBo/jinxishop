<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model 
{
	protected $insertFields = array('cat_name','parent_id');
	protected $updateFields = array('id','cat_name','parent_id');
	protected $_validate = array(
		array('cat_name', 'require', '栏目名称不能为空！', 1, 'regex', 3),
		array('cat_name', '1,30', '栏目名称的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('parent_id', 'require', '父级栏目id不能为空！', 1, 'regex', 3),
		array('parent_id', 'number', '父级栏目id必须是一个整数！', 1, 'regex', 3),
	);
	/******************* 递归相关方法 *************************/
	public function getTree()
	{
		$data = $this->select();
		return $this->_reSort($data);
	}
	private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
				$this->_reSort($data, $v['id'], $level+1, FALSE);
			}
		}
		return $ret;
	}
	public function getChildren($id)
	{
		$data = $this->select();
		return $this->_children($data, $id);
	}
	private function _children($data, $parent_id=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['id'];
				$this->_children($data, $v['id'], FALSE);
			}
		}
		return $ret;
	}
	/********************** 其他方法 *************************/
	public function _before_delete($option)
	{
		// 先找出所有的子分类
		$children = $this->getChildren($option['where']['id']);
		// 如果有子分类都删除掉
		if($children)
		{
			$children = implode(',', $children);
			/**
			 * 执行 _before_delete 直接后就要商品数据 
			 * 原因： 如果不这样子 在调用delete 方法的时候回自动调用 _before_delete 钩子函数 这样子会陷入死循环
			 */
			$this->execute("DELETE FROM shop_category WHERE id IN($children)");
		}
	}

	/**
	 * 获取前天导航上分类的数据 
	 * @return array|boolen
	 * @author Red-Bo
	 * @data 2015-10-25 21:05:52
	 */
	public function getNavCatData()
	{
		//先取出顶级分类
		$cat  = $this->where('parent_id = 0')->select();
		//循环每个顶级分类在取出他的二级分类
		foreach ($cat as $k => $v) {
			$cat[$k]['children'] = $this->where('parent_id='.$v['id'])->select();
			//循环每个二级分类取出三级分类
			foreach ($cat[$k]['children'] as $k1 => $v1) {
				$cat[$k]['children'][$k1]['children'] = $this->where('parent_id='.$v1['id'])->select();
			}
		}
		return $cat;
	}
	/**
	 * 对于以上方法的不足之处 
	 * SQL语句执行太多 性能不好 需要优化
	 */
	
	/**
	 * 优化以上函数
	 * @param 
	 * @return 
	 * @author Red-Bo
	 * @data 2015-10-25 21:33:21
	 */
	
	public function newGetNavData()
	{
		//
		$data = S('catData');

		if($data)
			return $data;
		else
		{
			$data = array();
			//先取出所有的分类
			$allCat = $this->limit(13)->select();
			//在从所有的分类中取出顶级分类
			foreach ($allCat as $k => $v) 
			{	
				//循环找出这个顶级分类的二级分类
				if($v['parent_id'] ==0)
				{
					foreach ($allCat as $k1 => $v1) 
					{
						if($v1['parent_id'] == $v['id'])
						{
							foreach ($allCat as $k2 => $v2) 
							{
								if($v2['parent_id'] == $v1['id'])
								{
									$v1['children'][] = $v2;
								}

							}

							$v['children'][] = $v1;
						}
					}
				}
			 $data[] = $v;
			}
		}
		//将数据加入缓存
		S('catData',$data);
		return $data;
	}
    
}