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


<!-- 列表 -->
<div class="list-div" id="listDiv">

	<form action="/Admin/Goods/goods_number/id/3.html" method="POST">
		<table cellpadding="3" cellspacing="1">
    	<tr>
    		<?php foreach ($attr as $k => $v): ?>
    			<th><?php echo ($v["0"]["attr_name"]); ?></th>
    		<?php endforeach ?>
            <th>库存</th>
			<th>操作</th>
        </tr>
		<?php if ($gnData): ?>
			<?php foreach ($gnData as $k0 => $v0): if($k0 ==0) $opt = " + "; else $opt = " - "; ?>
			<tr>
				<?php foreach ($attr as $k => $v):?>
				<td align="center">
				<select name="goods_attr_id[]">
					<?php foreach ($v as $k1 => $v1): if(strpos(','.$v0['goods_attr_id'].',',','.$v1['id'].',') !==false) $select ='selected="selected"'; else $select = ''; ?>
						<option  <?php echo ($select); ?> value="<?php echo ($v1["id"]); ?>"><?php echo ($v1["attr_value"]); ?></option>
					<?php endforeach; ?>
				</select>
				</td>
			<?php endforeach ?>
			<td align="center"><input type="text" name="goods_number[]" value="<?php echo ($v0["goods_number"]); ?>" ></td>
			<td><input type="button" value="<?php echo ($opt); ?>"  onclick="addnew(this)"> </td>
		</tr>
		<?php endforeach ?>
		<?php else: ?>
			<tr>
				<?php foreach ($attr as $k => $v):?>
				<td align="center">
				<select name="goods_attr_id[]">
					<option value="">请选择</option>
					<?php foreach ($v as $k1 => $v1): if(strpos(','.$v0['goods_attr_id'].',',','.$v1.',') !==false) $select ='selected="selected"'; else $select = ''; ?>
						<option <?php echo ($select); ?> value="<?php echo ($v1["id"]); ?>"><?php echo ($v1["attr_value"]); ?></option>
					<?php endforeach; ?>
				</select>
				</td>
			<?php endforeach ?>
			<td align="center"><input type="text" name="goods_number[]" ></td>
			<td><input type="button" value="+"  onclick="addnew(this)"> </td>
			</tr>
		<?php endif; ?>
		<tr>
			<td colspan="<?php echo count($attr)+2 ?>" align="center">
				<input type="submit" value="提交">
			</td>
		</tr>
	</table>
	</form>

</div>
<script>
$('#addtimefrom').datepicker(); $('#addtimeto').datepicker(); 

function addnew(obj)
{
	var tr = $(obj).parent().parent();

	if($(obj).val() =="+")
	{
		//克隆
		var newstr = tr.clone();
		//把+变 - 
		newstr.find(':button').val('-');
		$(obj).parent().parent().after(newstr);
	}
	else
		tr.remove();

}
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