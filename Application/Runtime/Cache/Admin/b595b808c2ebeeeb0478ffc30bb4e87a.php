<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>ITCAST-SHOP 管理中心</title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
</head>

<frameset border="0" framespacing="0" rows="76,*">
  <frame scrolling="no" frameborder="no" name="header-frame" id="header-frame" src="<?php echo U('Admin/Index/top') ?>"></frame>
  <frameset id="frame-body" border="0" framespacing="0" cols="200, 10, *">
    <frame scrolling="yes" frameborder="no" name="menu-frame" id="menu-frame" src="<?php echo U('Admin/Index/menu'); ?>"></frame>
    <frame scrolling="no" frameborder="no" name="drag-frame" id="drag-frame" src="<?php echo U('Admin/Index/drag') ?>"></frame>
    <frame scrolling="yes" frameborder="no" name="main-frame" id="main-frame" src="<?php echo U('Admin/Index/main') ?>"></frame>
  </frameset>
</frameset>

</html>