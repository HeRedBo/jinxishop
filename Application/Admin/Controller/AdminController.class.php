<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;
class AdminController extends IndexController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Admin/Admin');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p')));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
        //取出所有的角色
        $roleModel = M('Role');
        $roleData = $roleModel->select();
        $this->assign('roleData',$roleData);
		$this->setPageBtn('添加', '列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.id');
        //先判断是否有权修改
        $adminId = session('id'); //取出管理员的ID
       
        if($adminId > 1 && $adminId != $id)
            $this->error('无权修改！');
    	if(IS_POST)
    	{
    		$model = D('Admin/Admin');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	$model = M('Admin');
    	$data = $model->find($id);
    	$this->assign('data', $data);
         //取出所有的角色
        $roleModel = M('Role');
        $roleData = $roleModel->select();
        //取出管理员所在的角色的ID
        $arModel = M('AdminRole');
        $rid = $arModel->field('GROUP_CONCAT(role_id) role_id')->where(array('admin_id'=>array('eq',$id)))->find();

        $this->assign('rid',$rid['role_id']);
        $this->assign('roleData',$roleData);

		$this->setPageBtn('修改', '列表', U('lst?p='.I('get.p')));
		$this->display();
    }

    /**
     * ajax修改管理员的启用转态
     * date 2015-10-07 12:32
     * @return [type] [description]
     */
    public function ajaxUpdateIsuse()
    {
        $adminId = I('get.id');
        if($adminId == 1)
            return FALSE;
        $model = M('Admin');
        $info = $model->find($adminId);
        if($info['is_use'] ==1)
        {
            $model->where(array('id'=>array('eq',$adminId)))->setField('is_use',0);
            echo 0;
        }
        else
        {
            $model->where(array('id'=>array('eq',$adminId)))->setField('is_use',1);
            echo 1;
        }
    }
    
    public function delete()
    {
    	$model = D('Admin/Admin');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Admin/Admin');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		$this->setPageBtn('列表', '添加', U('add'));
    	$this->display();
    }
}