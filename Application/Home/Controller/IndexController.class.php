<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {


    public function index()
    {
        $goodsModel = D('Admin/Goods');
        //获取首页相关商品信息
        $goodsPro = $goodsModel->getPromoteGoods();
        $goodsHot = $goodsModel->getHot();
        $goodsBest= $goodsModel->getBest();
        $goodsNew= $goodsModel->getNew();
        /*var_dump($goodsPro,$goodsHot,$goodsBest,$goodsNew);exit;*/
        $this->assign(array(
            'goodsPor' => $goodsPro,
            'goodsHot' => $goodsHot,
            'goodsBest'=> $goodsBest,
            'goodsNew' =>$goodsNew
        ));

    	//设置页面信息、关键字、描述、是否展开、CSS信息
    	$this->setPageInfo('首页','关键字','描述',1,array());
    	$this->display();
    	
    }

    //测试方法
    public function Test()
    {
    	$catModel = D('Admin/Category');
    	$catData = $catModel->newGetNavData();
    	$this->display();
    }
}