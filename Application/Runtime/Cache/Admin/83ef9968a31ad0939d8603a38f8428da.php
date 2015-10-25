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

<div class="main-div">
    <form name="main_form" method="POST" action="/Admin/Attribute/add/type_id/1.html" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">属性名称：</td>
                <td>
                    <input  type="text" name="attr_name" value="" />
                </td>
            </tr>
            <tr>
                <td class="label">选择商品类型：</td>
                <td>
                    <select name="type_id" id="">
                        <option value="">请选择类型...</option>
                        <?php foreach ($typeData as $k => $v): if($v['id'] == $typeId) $select ='selected ="selected"'; else $select = ''; ?>
                            <option <?php echo ($select); ?> value="<?php echo ($v["id"]); ?>"><?php echo ($v["type_name"]); ?></option>

                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label">属性的类型:</td>
                <td>
                    <input  type="radio" name="attr_type" value="0" checked="checked" id="attr_type0" />
                    <label for="attr_type0">唯一属性</label>
                    <input type="radio" name="attr_type" value="1"id="attr_type1"> 
                    <label for="attr_type1">可选属性</label>
                </td>
            </tr>
            <tr>
                <td class="label">属性值的录入方式：</td>
                <td>
                    <input  type="radio" name="attr_input_type" checked="checked" value="0" id="input_type0"/> 
                    <label for="input_type0">手工输入</label>
                    <input  type="radio" name="attr_input_type" value="1" id="input_type1" /> 
                    <label for="input_type1">从下面列表选择(多个使用逗号隔开)</label>
                </td>
            </tr>
            <tr>
                <td class="label">属性的可选值：</td>
                <td>
                    <textarea name="attr_value" id="" cols="60" rows="3"></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    $(document).ready(function() {
        //先让可选列表输入状态为禁用状态
        $("textarea[name='attr_value']").attr('disabled', true);
        $("input[name='attr_input_type']").click(function() {
           var ipType = $(this).val();
           if(ipType==1)
                $("textarea[name='attr_value']").attr('disabled', false);
            else
                $("textarea[name='attr_value']").attr('disabled', true);


        });
    });
</script>

<div id="footer">

版权所有 &copy; 2012-2015 传智播客 - PHP培训 - class</div>

</div>

</body>
</html>
<script type="text/javascript" src="/Public/Admin/js/tron.js"></script>
<script type="text/javascript">
    $('#start_addtime').datepicker({dateFormat:'yy-mm-dd'});
    $('#end_addtime').datepicker({ dateFormat:'yy-mm-dd'});
</script>