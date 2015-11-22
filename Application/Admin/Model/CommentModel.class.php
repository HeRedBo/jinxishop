<?php
namespace Admin\Model;
use Think\Model;
class CommentModel extends Model
{
	//发表评论的时候表单中运行提交的字段
	protected $insertFields = array('content','grade','goods_id');
	protected $_validate = array(
		array('content','require','评论的内容不能为空！',1,'regex',3),
		array('grade','/^[1-5]$/','分值必须是1-5之间的数字',1),
	);

	protected function _before_insert(&$data,$option)
	{
		
		$data['addtime']   = time();
		$data['member_id'] = session('mid');
		
		//处理印象表单
		$yx = I('post.yx');
		if($yx)
		{
			//先统一字符串中的，号都是使用英文的,
			$yx = str_replace('，',',',$yx);
			// 先把印象根据，转换为数组
			$yx = explode(',',$yx);
			$impModel = M('Impression');
			foreach ($yx as $k => $v) 
			{
				// 判断这个商品有没有这个印象
				$has = $impModel->field('id')->where(array(
					'goods_id' => array('eq',$data['goods_id']),
					'imp_name' => array('eq',$v)
				))->find();
				//这件商品已经有这个印象就把印象加1
				if($has)
					$impModel->where('id='.$has['id'])->setInc('imp_count');
				else
					$impModel->add(array(
						'goods_id' => $data['goods_id'],
						'imp_name' => $v
					));
			}
		}
	}
}