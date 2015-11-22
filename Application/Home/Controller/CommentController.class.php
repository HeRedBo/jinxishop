<?php 
namespace Home\Controller;
class CommentController extends BaseController
{
	/**
	 * 评论添加方法
	 * @return boolen
	 * @author Red-Bo
	 * @date 2015-11-07 18:11:02
	 */
	public function add()
	{
		//判断用户是否登陆
		$mid = session('mid');
	
		if(!$mid)
		{
			echo json_encode(array(
				'ok' => 0,
				'error' => '必须要登陆'
			));
			exit;
		}

		if(IS_POST)
		{
			$model = D('Admin/Comment');

			// 更具表单并根据模型总定义的规则进行表单验证
			if($model->create(I('post.'),1))
			{
				
				if($model->add())
				{
					// 取出会员的头像
					$memberModel = M('Member');
					$face = $memberModel->field('face')->find($mid);
					$realFace = !$face['face'] ? '/Public/Home/images/'.rand(0,4).'.jpg':'Public/Home/'.$face['face'];

					echo json_encode(array(
						'ok' => 1,
						'content' => I('post.content'),
						'addtime' => date('Y-m-d H:i'),
						'star'    => I('post.grade'),
						'email'   => session('email'),
						'face' 	  => $realFace
					));
					exit;
				}
			}
			echo json_encode(array(
				'ok' => 0,
				'error'=> $model->getError()
			));
			
		}
	}

	/**
	 * 获取评论的方法
	 * @param 
	 * @return 
	 * @author Red-Bo
	 * @date 2015-11-08 17:48:56
	 */
	public function ajaxGetComment()
	{
		//分页
		$perpage = 5;
		$p = I('get.p');
		$p = !empty($p) ? $p : 1;
		$goodsId = I('get.goods_id');
		$offset = ($p-1)*$perpage;
		$comment =M('Comment');
		$data = $comment->field('a.*,B.email,B.face,COUNT(C.id) reply_count')->alias('a')->join('LEFT JOIN shop_member B ON a.member_id = B.id LEFT JOIN shop_reply C ON a.id = C.comment_id ')->where(array('a.goods_id' => array('eq',$goodsId)))->limit("$offset,$perpage")->
			group('a.id')->order('a.id DESC')->select();

			// 处理返回的数据
			foreach ($data as $k => $v) 
			{
				$data[$k]['face'] = $v['face'] ? '/Public/Home/images/'.$v['face'] : '/Public/Home/images/'.rand(0,4).'.jpg';
				$data[$k]['addtime'] = date('Y-m-d H:i',$v['addtime']);
			}
			
			echo json_encode($data);
	}
}