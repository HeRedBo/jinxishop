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
    	$this->setPageInfo('京西商城首页','关键字','描述',1,array('index'),array('index'));
    	$this->display();
    	
    }

    /**
     * 商品详情
     * @param int $goodsId;
     * @return array
     * @author Red-Bo
     * @data 2015-11-03 00:43:01
     */
    
    public function goods()
    {
        // 接收商品的ID
        $goodsId  = I('get.id');
        $goodsModel = M('Goods');
        $info = $goodsModel->find($goodsId);
        
        //取出商品的图片
        $gpModel = M('GoodsPics');
        $gpData = $gpModel->where(array('goods_id'=>array('eq',$goodsId)))->select();

        /****************** 取出商品的属性 *****************/
        //取出商品的单选属性
        $gaModel = M('GoodsAttr');
        $_gaData1= $gaModel->field('a.*,b.attr_name')->alias('a')->join('LEFT JOIN shop_attribute b on a.attr_id = b.id')->where(array('a.goods_id'=>array('eq',$goodsId),'b.attr_type'=> array('eq',1)))->select();
        $gaData1 = array();
        foreach ($_gaData1 as $k => $v) 
        {
            $gaData1[$v['attr_name']][] =$v;
        }
       
       //取出商品的唯一属性 
       $gaData2 = $gaModel->field('a.*,b.attr_name')->alias('a')->join('LEFT JOIN shop_attribute b on a.attr_id = b.id')->where(array('a.goods_id'=>array('eq',$goodsId),'b.attr_type'=> array('eq',0)))->select();
       
       //把取出的数据assign到页面中
       $this->assign(array(
            'info'      => $info,
            'gpData'    => $gpData,
            'gaData1'   => $gaData1,
            'gaData2'   => $gaData2

        ));

        //设置页面信息、关键字、描述、是否展开、CSS信息
        $this->setPageInfo($info['goods_name'],'商品详情页',$info['seo_keyword'],0,array('goods','common','jqzoom'),array('goods','jqzoom-core'));
        //显示页面
        $this->display();
    }

    /**
     * 计算会员价格
     * @author Red-Bo
     * @date 2015-11-04 22:31:43
     */
    public function ajaxGetPrice()
    {   
        //计算会员价格
        $goodsId    = I('get.goods_id');
        $goodsModel = D('Admin/Goods');

        /**
         * 最近浏览功能实现
         * 功能实现(cookie)：在cookie中保存一个一维数组存储最近浏览过的10件商品的id
         * 难点：cookie 只保存字符串,如何保存一个数组 ? => 序列化 
         * 
         */
        $recentDisplay = isset($_COOKIE['recentDisplay']) ? unserialize($_COOKIE['recentDisplay']) : array();
        //var_dump($recentDisplay);exit;
        //将刚刚浏览的商品这件商品放到商品的最前面
        array_unshift($recentDisplay, $goodsId);
        //去重
        $recentDisplay = array_unique($recentDisplay);
        if(count($recentDisplay) > 10)
            $recentDisplay = array_slice($recentDisplay,0,1);
        //把处理好的数组保存到cookie中
        $aMonth = 30 * 86400;
        $data = serialize($recentDisplay);
        setcookie('recentDisplay',$data,time() + $aMonth,'/','myshop.com');
        # 将获取会员价格的函数封装到后台的商品模型中区
        echo $goodsModel->getMemberPrice($goodsId);

    }

    /**
     * 取出最近浏览的商品 
     * @return 
     * @author Red-Bo
     * @date 2015-11-05 07:39:54
     */
    public function ajaxGetRecentDisplayGoods()
    {
        //先从cookie取出商品的id
        $recentDisplay = isset($_COOKIE['recentDisplay']) ? unserialize($_COOKIE['recentDisplay']) : array();
        if($recentDisplay)
        {
            //在根据商品的id取出商品的信息
            $goodsModel = M('Goods');
            $goodsIds = implode( ',',$recentDisplay);
            $goods = $goodsModel->field('id,goods_name,goods_thumb')->where(array('id'=>array('in',$goodsIds)))->order("INSTR(',$goodsIds,',CONCAT(',',id,','))")->select();
            /*echo $goodsModel->getLastSql();
            var_dump($goods);exit;*/
            echo json_encode($goods);
        }
    }

    /*
     * ajax 判断用户是否是登陆
     * 
     */
    public function ajaxGetComment()
    {
        $ret = array('login' => 0);
        $mid = session('mid');
        if($mid)
        {
            $ret['login'] = 1;
        }
        echo json_encode($ret);
    }


    //测试方法
    public function Test()
    {
    	$image = new \Think\Image(); 
        $image->open('./wallbg.jpg');
        // 生成一个缩放后填充大小150*150的缩略图并保存为thumb.jpg
        $image->thumb(150, 150,\Think\Image::IMAGE_THUMB_FILLED)->save('./thumb.jpg');
    }
}