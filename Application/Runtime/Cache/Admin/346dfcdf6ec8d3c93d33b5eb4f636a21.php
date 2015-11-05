<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/styles/main.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/styles/page.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/Admin/js/jquery.js"></script>

<!-- 时间日期插件引入样式文件 -->
<link href="/Public/datepicker/jquery-ui-1.9.2.custom.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/datepicker/datepicker_zh-cn.js"></script>
<script type="text/javascript" src="/Public/datepicker/jquery-ui-1.9.2.custom.min.js"></script>
<!-- 在线编辑器的引入样式代码 -->
<script type="text/javascript" src="/Public/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/Public/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="/Public/ueditor/lang/zh-cn/zh-cn.js"></script>
</head>
<body>

<h1>
<span class="action-span"><a href="<?php echo $_page_btn_link ?>"><?php echo $_page_btn_name; ?></a></span>
<span class="action-span1"><a href="">管理中心</a> </span><span id="search_id" class="action-span1"> - <?php echo $_page_title ?> </span>
<div style="clear:both"></div>
</h1>

<!-- 页面中的内容 -->

<style type="text/css">
</style>
<!-- start goods form -->
<div class="tab-div">
    <!-- tab bar -->
    <div id="tabbar-div">
      <p>
        <span class="tab-front">基本信息</span>
        <span class="tab-back">商品描述</span>
        <span class="tab-back">会员价格</span>
        <span class="tab-back">商品属性</span>
        <span class="tab-back">商品相册</span>
      </p>
    </div>

    <!-- tab body -->
    <div id="tabbody-div">
    <form name="main_form" method="POST" action="/Admin/Goods/add.html" enctype="multipart/form-data">
       <!--  基本信息 -->
        <table cellspacing="1" class="table_content" cellpadding="3" width="100%">
            <tr>
                <td class="label">商品名称：</td>
                <td>
                    <input  type="text" name="goods_name" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">商品编号：</td>
                <td>
                    <input  type="text" name="goods_sn" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">商品分类：</td>
                <td>
                   <select name ='cat_id'>
                       <option value=''>选择分类</option>
                       <?php foreach ($catData as $k => $v): ?>
                           <option value="<?php echo ($v["id"]); ?>"><?php echo str_repeat('-',$v['level'] * 4); echo ($v["cat_name"]); ?></option>
                       <?php endforeach; ?>
                   </select>
                    <span class="required">*</span>
                </td>
            </tr>
             <tr>
                <td class="label">扩展分类：</td>
                <td>
                    <input type="button" onclick="$(this).parent().append($(this).next('select').clone())" value="添加">
                   <select name ='ext_cat_id[]'>
                       <option value=''>选择分类</option>
                       <?php foreach ($catData as $k => $v): ?>
                           <option value="<?php echo ($v["id"]); ?>"><?php echo str_repeat('-',$v['level']*4); echo ($v["cat_name"]); ?></option>
                       <?php endforeach ?>
                      
                   </select>
                </td>
            </tr>
            <tr>
                <td class="label">商品品牌:</td>
                <td>
                    <select name ="brand_id">
                        <option value="">选择品牌</option>
                        <?php foreach ($brandData as $k => $v): ?>
                            <option value="<?php echo ($v["id"]); ?>"><?php echo ($v["brand_name"]); ?></option>
                        <?php endforeach ?>
                    </select>
                    
                </td>
            </tr>
            <tr>
                <td class="label">本店价：</td>
                <td>
                    <input  type="text" name="shop_price" value="0.00" />
                </td>
            </tr>
            <tr>
                <td class="label">市场价：</td>
                <td>
                    <input  type="text" name="market_price" value="0.00" />
                </td>
            </tr>
            <tr>
                <td class="label"> 商品原图的路径：</td>
                <td>
                	<input type="file" name="goods_ori" /> 
                </td>
            </tr>
            <tr>
                <td class="label">赠送积分：</td>
                <td>
                    <input  type="text" name="jifen" value="0" />
                </td>
            </tr>
            <tr>
                <td class="label">赠送经验值：</td>
                <td>
                    <input  type="text" name="jyz" value="0" />
                </td>
            </tr>
            <tr>
                <td class="label">如果使用积分,需要的积分制：</td>
                <td>
                    <input  type="text" name="jifen_price" value="0" />
                </td>
            </tr>
            <tr>
                <td class="label"><input type="checkbox" value="1"  name="is_promote" id="is_promote" onclick="if($(this).attr('checked')) $('.promote_price').removeAttr('disabled'); else $('.promote_price').attr('disabled','disabled');"><label for="is_promote">促销价：</label></td>
                <td>
                    <input class="promote_price" type="text" name="promote_price" value="0.00" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td class="label">促销开始时间：</td>
                <td>
                    <input class="promote_price" id="promote_start_time" type="text" name="promote_start_time" value="0" disabled="disabled"/>
                </td>
            </tr>
            <tr>
                <td class="label">出现结束时间：</td>
                <td>
                    <input class="promote_price" id="promote_end_time" type="text" name="promote_end_time" value="0" disabled="disabled" />
                </td>
            </tr>
            <tr>
                <td class="label">产品的销售量：</td>
                <td>
                    <input  type="text" name="market_num" value="0" />
                </td>
            </tr>
            <tr>
                <td class="label">SEO优化_描述：</td>
                <td>
                    <input  type="text" name="seo_keyword" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">SEO优化_描述：</td>
                <td>
                    <input  type="text" name="seo_description" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">排序数字：</td>
                <td>
                    <input  type="text" name="sort_num" value="100" />
                </td>
            </tr>
            <tr>
                <td class="label">是否上架：1:上架 0:下架：</td>
                <td>
                	<input type="radio" name="is_on_sale" value="1" checked="checked"  />是 
                	<input type="radio" name="is_on_sale" value="0" />否 
                </td>
            </tr>
            <tr>
                <td class="label">是否新品：</td>
                <td>
                	<input type="radio" name="is_new" value="1"  checked="checked" />是 
                	<input type="radio" name="is_new" value="0" />否 
                </td>
            </tr>
            <tr>
                <td class="label">是否热卖：</td>
                <td>
                	<input type="radio" name="is_hot" value="1"  checked="checked" />是 
                	<input type="radio" name="is_hot" value="0"  />否 
                </td>
            </tr>
            <tr>
                <td class="label">是否精品：</td>
                <td>
                	<input type="radio" name="is_best" value="1"  checked="checked" />是 
                	<input type="radio" name="is_best" value="0" />否 
                </td>
            </tr>
         
        </table>
        <!-- 商品描述 -->
         <table cellspacing="1"  class="table_content" cellpadding="3" width="100%" style="display:none">
           <tr>
                <td>
                     <textarea name="goods_desc" id="goods_desc"></textarea>
                </td>
          </tr>
         </table>
           <!-- 会员价格 -->
         <table cellspacing="1"  class="table_content" cellpadding="3" width="100%" style="display:none">
            <tr>
                <td style="font-size:18px; font-weight: bold;">会员价格(如果你没有填会员价格就按照折扣率计算价格，如果填了就填按照填写的价格算，不在折扣)</td>
            </tr>
              <?php foreach ($mlData as $k => $v): ?>
                  <tr>
                    <td>
                    <?php echo ($v["level_name"]); ?>(<?php echo $v['rate']/10; ?>折)：￥
                    <input type="text" name="mp[<?php echo ($v["id"]); ?>]" size ="10" id="">元
                    </td>
                  </tr>
              <?php endforeach ?>
         </table>
           <!-- 商品属性 -->
         <table cellspacing="1"  class="table_content" cellpadding="3" width="100%" style="display:none">
            <tr>
                <td >商品类型: </td>
                <td>
                    <select name="type_id" id="">
                        <option value="">选择类型</option>
                        <?php foreach ($typeData as $k => $v): ?>
                            <option value="<?php echo ($v["id"]); ?>"><?php echo ($v["type_name"]); ?></option>
                        <?php endforeach ;?>
                    </select>
                </td>
            </tr>
            <tr><td id="attr_container"></td></tr>
         </table>
    
           <!-- 商品相册 -->
         <table cellspacing="1" class="table_content"  cellpadding="3" width="100%" style="display:none" >
             <tr>
                <td>
                    <input onclick="$(this).parent().parent().parent().append('<tr><td><input type=\'file\' name =\'pics[]\'/></td></tr>');" type="button" value="添加一张图片">
                </td>
            </tr>
         </table>
         <div class="button-div">
          <input type="submit" value=" 确定 " class="button" />
          <input type="reset" value=" 重置 " class="button" />
        </div>
    </form>
</div>
</div>
<script>

 //点击切换table
 $('#tabbar-div p span').click(function() {
     //获取点击的是第几个按钮
     var i = $(this).index();
     // 显示第 i 个table
     $(".table_content").eq(i).show();
     //隐藏其他的table
     $(".table_content").eq(i).siblings('.table_content').hide();
     //把原来选择的样式去掉
     $(".tab-front").removeClass('tab-front').addClass("tab-back");
     //切换点击按钮的样式为选择转态
     $(this).removeClass('tab-back').addClass("tab-front");
 });

$("#promote_start_time").datepicker({ dateFormat: "yy-mm-dd" }); 
$("#promote_end_time").datepicker({ dateFormat: "yy-mm-dd" }); 
UE.getEditor('goods_desc', {
	"initialFrameWidth" : "100%",   // 宽
	"initialFrameHeight" : 360,      // 高
	"maximumWords" : 10000            // 最大可以输入的字符数量
});

// 当选择类型时候 执行ajaxh获取数据
$("select[name='type_id']").change(function() {
    //获取当前选中类型的id
    var type_id = $(this).val();
    if(type_id !=''){
        $.ajax({
            //大U生产的地址默认带后缀 第三个参数就是设置去掉 .html后缀 否则会报错
            url: "<?php echo U('ajaxGetAttr','',false); ?>/type_id/"+type_id,
            type: 'GET',
            dataType: 'json',
           
            success:function(data){
                var html = "";
                //循环服务器返回的JSON数据
                $(data).each(function(k,v){
                    html +="<p>";
                    html += v.attr_name + " : " ;
                    //更具属性的类型生产不同的表单元素
                    //1，如果属性是一个可选值那么会有一个加号
                    //2，如果属性有可选值就是一个下拉框
                    //3，如果属性是唯一的就生产一个文本框
                    if(v.attr_type ==1)
                        html +="<a onClick='addnew(this)' href='javascript:;'>[+]</a>";
                    //判断是否有可选值
                    if(v.attr_option_values =='')
                        html += "<input type='text' name='ga["+v.id+"][]' />";
                    else
                    {
                        //先把可可选值转换成数组
                        var _attr = v.attr_option_values.split(",");
                        html += "<select name='ga["+v.id+"][]'>";
                        html += "<option value = ''>请选择</option>";
                        //循环每个可选值构造option
                        for (var i = 0;i < _attr.length;i++) {
                            html += "<option value='"+_attr[i]+"'>"+_attr[i]+"</option>"
                        };
                        html += "</select>";
                    }
                    if(v.attr_type == 1){ // 单选属性 使用表单框让用户
                        html += "属性价格: ￥ <input  size='8' name='attr_price["+v.id+"][]' type='text' /> 元";
                    }
                    html +="</p>";
                });
                $("#attr_container").html(html);
                
            }
           
            
        });
       
        
    } else
        $("#attr_container").html('');
});

//点击+ 号
function addnew(obj){
    //选中a便签所在的p标签
    var p = $(obj).parent();
    //先获取a标签中的内容
    if($(obj).html() =="[+]")
    {
        // 把p克隆一份
        var newp = p.clone();
        //把克隆出来的p里里面的a标签点的变成-号
        newp.find('a').html("[-]");
        //放在后面
        p.after(newp);
    }
    else
    {
        p.remove();
    }
}
</script>

<div id="footer">

版权所有 &copy; 2012-2015 传智播客 - PHP培训 - class</div>

</div>

</body>
</html>
<script type="text/javascript" src="/Public/Admin/js/tron.js"></script>