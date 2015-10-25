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

<!-- 搜索 -->
<div class="form-div search_form_div">
    <form method="GET" name="search_form">
		
			栏目名称：
	   		<input type="text" name="cat_name" size="15" value="<?php echo I('get.cat_name'); ?>" />
			父级栏目id：
	   		<input type="text" name="parent_id" size="5" value="<?php echo I('get.parent_id'); ?>" />
		<input type="submit" value=" 搜索 " class="button" />
    </form>
</div>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >栏目名称</th>
            <th >父级栏目id</th>
			<th width="60">操作</th>
        </tr>
		<?php foreach ($data as $k => $v): ?>            
			<tr class="tron">
				<td><?php echo str_repeat('&nbsp;', 4*$v['level']); echo $v['cat_name']; ?></td>
				<td><?php echo $v['parent_id']; ?></td>
		        <td align="center">
		        	<a href="<?php echo U('edit?id='.$v['id'].'&p='.I('get.p')); ?>" title="编辑">编辑</a> |
	                <a href="<?php echo U('delete?id='.$v['id'].'&p='.I('get.p')); ?>" onclick="return confirm('确定要删除吗？');" title="移除">移除</a> 
		        </td>
	        </tr>
        <?php endforeach; ?> 
	</table>
</div>
<script>
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