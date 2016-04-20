<?php
namespace Admin\Controller;
use \Admin\Controller\IndexController;

header("Content-type:text/html;charset=utf8");

class GoodsController extends IndexController 
{
    
    /**
     * [商品新增方法]
     * @author Red-Bo
     * @date 2016-01-22 14:09:24
     */
    public function add()
    {
        set_time_limit(0);
    	if(IS_POST)
    	{

    		$model = D('Admin/Goods');
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
         //取出所有的商品类型
        $typeModel = M('type');
        $typeData = $typeModel->select();
        $this->assign('typeData',$typeData);
        //取出所有分类
        $cateModel = D('Category');
        $catData = $cateModel->getTree();
        $this->assign('catData',$catData);
        //取出所有的品牌
        $brandModel = D('Brand');
        $brandData = $brandModel->field('id,brand_name')->select();
        $this->assign('brandData',$brandData);
        //取出会员级别
        $mlModel = D('MemberLevel');
        $mlData  = $mlModel->select();
        $this->assign('mlData',$mlData);
		$this->setPageBtn('添加商品', '商品列表', U('lst?p='.I('get.p')));
		$this->display();
    }

    /**
     * 商品编辑
     * @author Red-Bo
     * @date 2016-01-22
     * @return [type] [description]
     */
    public function edit()
    {
    	$id = I('get.id');
    	if(IS_POST)
    	{
    		$model = D('Admin/Goods');
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
        //取出所有的商品类型
        $typeModel = M('Type');
        $typeData = $typeModel->select();
        $this->assign('typeData',$typeData);
        // 取出所有的商品分类
        $catModel = D('Category');
        $catData = $catModel->getTree();
        $this->assign('catData',$catData);
        // 取出所有的商品的品牌
        $brandModel = M('Brand');
        $brandData = $brandModel->select();
        $this->assign('brandData',$brandData);
        //取出所有的会员级别
        $mlModel = M('MemberLevel');
        $mlData  = $mlModel->select();
        $this->assign('mlData',$mlData);
         //取出当前商品会员价格的数据
        $mpModel = M('MemberPrice');
        $_mpData = $mpModel->where(array('goods_id'=>array('eq',$id)))->select();
        
        //先把数据二维转一维
        $mpData = array();
        foreach ($_mpData as $k => $v) 
        {
            $mpData[$v['level_id']] = $v['price'];
        }
       
        $this->assign('mpData',$mpData);

        //取出有修改的商品基本信息
        $model = M('Goods');
    	$data = $model->find($id);
       
    	$this->assign('data', $data);
       
        //取出当前商品的扩展分类
        $gcModel = M('GoodsCat');
        $extCatId = $gcModel->field('cat_id')->where(array('goods_id'=>array('eq',$id)))->select();
        
        $this->assign('extCatId',$extCatId);
       

        //取出当前商品的相关属性
        $gaModel = M('GoodsAttr');
        //select a.*,b.attrname from shop_goods_attr a left join shop_attribute b on a.attr_id = b.id;
        $gaData = $gaModel
                ->field('a.*,b.attr_name,b.attr_type,b.attr_option_values')->alias('a')
                ->join('left join shop_attribute b on a .attr_id = b.id')
                ->where(array('a.goods_id'=>array('eq',$id)))
                ->order('a.attr_id ASC')
                ->select();

        /******** 取出当前商品属性不存在的后添加新的属性 *********/
      
        //循环属性数组取出当前商品已经拥有的属性的id
        $attr_id = array();
        foreach ($gaData as $k => $v) {
            $attr_id[] = $v['attr_id'];
        }

        $attr_id = array_unique($attr_id);
        //取出当前类型下的后添加的新属性
        $attrModel = M('Attribute');
        $otherAttr = $allAttrId = $attrModel
                                 ->field('id attr_id,attr_name,attr_type,attr_option_values')
                                 ->where(array('type_id'=>array('eq',$data['type_id']),'id'=>array('not in',$attr_id)))
                                 ->select();
       
        if($otherAttr)
        {
            //将新的属性和原属性合并起来
            $gaData = array_merge($gaData,$otherAttr);
            //重新根据attr_id 字段重新排序这个合并之后的二维数组
            usort($gaData, 'attr_id_sort');
        }
        $this->assign('gaData',$gaData);
        //取出当前商品的图片
        $gpModel = M('GoodsPics');
        $gpData = $gpModel->where(array('goods_id'=>array('eq',$id)))->select();
        $this->assign('gpData',$gpData);

		$this->setPageBtn('修改商品', '商品列表', U('lst?p='.I('get.p')));
		$this->display();
    }

   /**
    * 商品加入回收站
    * @param int $goods_id 商品的id
    * @return boolen 
    */
   public function recycle()
   {
        $model = M('Goods');
        $model->where(array('id'=>array('eq',I('get.id'))))->setField('is_delete',1);
        $this->success('操作成功！',U('lst',array('p'=>I('get.p',1))));
   }
    
    /**
     * 商品还原
     * @param int $goods_id 要恢复的商品的id
     * @return 
     */
    public function restore()
    {
        $model = M('Goods');
        $model->where(array('goods_id'=>array('eq',I('get.id'))))->setField('is_delete',0);
    }

    /**
     * 回收站
     * date 2015-10-14 21:14
     */
    public function recyclelst()
    {
        $model = D('Admin/Goods');
        $data = $model->search(20,1);
        $this->assign(array(
            'data' =>$data['data'],
            'page' => $data['page']
        ));

        $this->setPageBtn('商品回收站','商品列表',U('lst'));
        $this->display();
    }

    /**
     * @author Red-Bo
     * @date 2016-01-22 14:14:51
     * @return [type] [description]
     */
    public function delete()
    {
    	$model = D('Admin/Goods');
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

    /**
     * 商品列表
     * @author Red-Bo
     * @date 2016-01-22 14:16:27
     */
    public function lst()
    {
    	$model = D('Admin/Goods');
    	$data = $model->search(20,0);
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));
        
		$this->setPageBtn('商品列表', '添加商品', U('add'));
    	$this->display();
    }

    /**
     * 获取商品的属性值
     * @return [type] [description]
     */
    public function ajaxGetAttr()
    {
        $type_id = I('get.type_id');
        $attrModel = D('Attribute');
        //根据id在属性表中取出相应的数据
        $attrData = $attrModel->where(array('type_id'=>array('eq',$type_id)))->select(); 
        echo json_encode($attrData); 
    }

    /**
     * ajax 删除图片
     */
    public function ajaxDelImage()
    {
        $picId = I('get.pic_id');
        $gpModel = M('GoodsPics');

        //先取出商品的路劲
        $pic = $gpModel->field('pic,sm_pic')->find($picId);
        
        //把图片从硬盘删除
        deleteImage($pic);
        //在从数据库中把图片的数据也删除掉
        echo $gpModel->delete($picId);
    } 

    /**
     * AKAX 删除商品属性
     */
    public function ajaxDelGoodsAttr()
    {
        $gaid = I('get.gaid');
        $gaModel = M('GoodsAttr');
        echo  $gaModel->delete($gaid);
    }

    /**
     * 商品库存管理
     * @param int $goods_id 商品的id
     * @return  : void
     * @author  : Red-Bo
     * @data: 2015-10-23 23:08:01
     */
    public function goods_number()
    {
        //接收商品的id
        $goodsId = I('get.id');
        if(IS_POST)
        {
           $gai = I('post.goods_attr_id');
           $gn = I('post.goods_number');
           $gnModel = M('GoodsNumber');

           //先清空设置原本设置的数据
           $gnModel->where(array('goods_id'=>array('eq',$goodsId)))->delete();
           //先计算两个数组的比例是多少
           $rate = count($gai) / count($gn);
           $_i = 0 ; // 从第ID数组中的第几个开始那数据
           foreach ($gn as $k => $v) 
           {
               //把每次拿过来的ID
                $_arr = array();
                //从id的数组拿到第 rate个
                for ($i=0; $i < $rate ; $i++) { 
                    $_arr[] = $gai[$_i];
                    $_i++;
                }
                //升序排列数组
                sort($_arr);
                //拼接字符串
                $_arr = implode(',',$_arr);
                $gnModel->add(array(
                    'goods_id'=>$goodsId,
                    'goods_number'=>$v,
                    'goods_attr_id'=>$_arr, //升序拍好的id的字符串
                ));
           }
            $this->success('设置成功！',U('lst', array('p' => I('get.p', 1))));exit;
        }
        /**
         * 根据商品的id取出这件商品同一个属性有多个值的属性
         * 原理: 
         * ①先取出这件商品有多个值的属性id 
         * ②在套用sql只取出这些属性的值的记录
         * ③连接属性表 取出属性的名称 (表单要用)
         */
        $sql = 'SELECT a.*,b.attr_name 
                FROM shop_goods_attr a 
                LEFT JOIN shop_attribute b on a.attr_id = b.id 
                WHERE attr_id IN(SELECT attr_id FROM shop_goods_attr WHERE goods_id ='.$goodsId.' GROUP BY attr_id HAVING count(*) >1) AND a.goods_id ='.$goodsId;
        $DB = M();
        $_attr = $DB->query($sql);

        $attr = array();
        foreach ($_attr as $k => $v) {
            $attr[$v['attr_id']][] =$v;
        }
        
        $this->assign('attr',$attr);

        //先取出已经当前当前商品已经设置过商品库存的数据
        $gnModel =M('GoodsNumber');
        $gnData = $gnModel->where(array('goods_id' =>array('eq',$goodsId)))->select();
        $this->assign('gnData',$gnData);
       
        $this->setPageBtn('库存管理', '商品列表', U('lst'));
        $this->display();
    }
    

    /**
     * 
     * @param  void
     * @return void
     * @author Red-Bo
     * @date 2016-04-20 23:24:54
     */
    public function ajaxChangeGStatus()
    {
        $action = I('post.action');
        $id     = I('post.id');
        $val    = I('post.val');
        $model = D('Admin/Goods');
        $res   = $model->changeGoodsStatus($id,$action,$val);
        if($res !== false) {
           echo 1;
        } else {
            echo "服务器错误,数据更新失败";
        }

    }
}