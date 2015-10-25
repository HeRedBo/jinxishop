<?php
namespace Admin\Controller;
use \Admin\Controller\CommonController;
class GoodsController extends CommonController {
	/**
	 * 商品新增方法
	 */
    public function add()
    {	

    	//2.处理表单
    	if(IS_POST)
    	{	
    		//3.先生成模型
    		//D和M的区别: D生存的是我们自己创建的模型对象，M生成TP自带的模型对象
    		//这里要生成我们创建的模型,因为这里要使用我们自己创建的模型中的验证规则来验证表单
    		//这里用M可以添加可以成功但是验表单的功能证会是失败,因为验证规则则是在我们自己定义的模型中的，而M生成的是TP自带的模型里没有验证规则
    		$model = D("Goods");

    		//4. a.接收表单中所有的数据并存到模型中 b.使用I函数过滤数据 c.根据模型中定义的规则来验证表单
    		if($model->create(I('post.'),1))
    		{

    			// 5. 插入数据库
    			if($model->add())
    			{
    				// 6. 提示信息
    				$this->success('操作成功！',U('lists'));
    				// 7. 停止执行后面的代码
    				exit;
    			}
    		}
    		// 8. 如果上面的失败,获取失败的原因
    		$error = $model->getError();
    		// 9. 显示错误信息 并跳回到上一步
    		$this->error($error);
    	}
    	//1.显示模板
        $this->setPageBtn('添加商品','商品列表',U('lists?page='.I('get.page')));
    	$this->display();
    }

    /**
     * 商品列表方法
     * @author RedBo
     * date 2015-10-04 
     */
    public function lists()
    {
    	
    	//创建goods模型
    	$model = D('Goods');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page'=> $data['page']
    	));
    	// 1.生成表单
        $this->setPageBtn('商品列表','添加商品',U('add')); 
    	$this->display();
    }

    /**
     * 商品删除方法
     * @author RedBo
     * date 2015-10-04 23:26
     */
    public function delete()
    {
        $model = D('Goods');
        $model->delete(I('get.id'));
        $this->success('操作成功！',U('lists?page='.I('get.page')));
    }

    /**
     * 商品修改方法
     * @return [type] [description]
     * @author RedBo
     * date 2015-10-05 22:32
     */
    public function edit()
    {  
        //接收商品的id
        $id = I('get.id');
        $model = D('Goods');
        if(IS_POST)
        {  
           if($model->create(I('post.'),2))
           {
                if(FALSE !== $model->save())
                {   
                    $this->success('操作成功！',U('lists?page='.I('get.page')));
                    exit;
                }
           }
           //如果失败 显示错误信息
           $this->error($model->getError());
        }
        $info = $model->where('id='.$id)->find();
        $this->assign('info',$info);
        //生成表单
        $this->display();
    }
}