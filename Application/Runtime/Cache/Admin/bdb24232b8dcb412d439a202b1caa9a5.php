<?php if (!defined('THINK_PATH')) exit();?><html xmlns="http://www.w3.org/1999/xhtml"><head>
<title></title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<link type="text/css" rel="stylesheet" href="/Public/Admin/styles/general.css">


<style type="text/css">
#header-div {
  background: #278296;
  border-bottom: 1px solid #FFF;
}

#logo-div {
  height: 50px;
  float: left;
}

#license-div {
  height: 50px;
  float: left;
  text-align:center;
  vertical-align:middle;
  line-height:50px;
}

#license-div a:visited, #license-div a:link {
  color: #EB8A3D;
}

#license-div a:hover {
  text-decoration: none;
  color: #EB8A3D;
}

#submenu-div {
  height: 50px;
}

#submenu-div ul {
  margin: 0;
  padding: 0;
  list-style-type: none;
}

#submenu-div li {
  float: right;
  padding: 0 10px;
  margin: 3px 0;
  border-left: 1px solid #FFF;
}

#submenu-div a:visited, #submenu-div a:link {
  color: #FFF;
  text-decoration: none;
}

#submenu-div a:hover {
  color: #F5C29A;
}

#loading-div {
  /*clear: right;*/
  text-align: right;
  display: block;
}

#menu-div {
  background: #80BDCB;
  font-weight: bold;
  height: 24px;
  line-height:24px;
}

#menu-div ul {
  margin: 0;
  padding: 0;
  list-style-type: none;
}

#menu-div li {
  float: left;
  border-right: 1px solid #192E32;
  border-left:1px solid #BBDDE5;
}

#menu-div a:visited, #menu-div a:link {
  display:block;
  padding: 0 20px;
  text-decoration: none;
  color: #335B64;
  background:#9CCBD6;
}

#menu-div a:hover {
  color: #000;
  background:#80BDCB;
}

#submenu-div a.fix-submenu{clear:both; margin-left:5px; padding:1px 5px; *padding:3px 5px 5px; background:#DDEEF2; color:#278296;}
#submenu-div a.fix-submenu:hover{padding:1px 5px; *padding:3px 5px 5px; background:#FFF; color:#278296;}
#menu-div li.fix-spacel{width:30px; border-left:none;}
#menu-div li.fix-spacer{border-right:none;}
</style>

</head>
<body style="cursor: auto;">
<div id="header-div">
  <div style="bgcolor:#000000;" id="logo-div"><img alt="ECSHOP - power for e-commerce" src="/Public/Admin/images/ecshop_logo.gif"></div>
  <div style="bgcolor:#000000;" id="license-div"></div>
  <div id="submenu-div">
    <div style="padding: 5px 10px 0 0; clear:right;text-align: right; color: #FF9900;width:40%;float: right;" id="send_info">
            <a class="fix-submenu" target="main-frame" href="index.php?act=clear_cache">清除缓存</a>
      <a class="fix-submenu" target="_top" href="">退出</a>
    </div>
        <div style="padding: 5px 10px 0pt 0pt; text-align: right; color: rgb(255, 153, 0); display: none; width: 40%; float: right;" id="load-div"><img width="16" height="16" style="vertical-align: middle" alt="正在处理您的请求..." src="/Public/Admin/images/top_loader.gif"> 正在处理您的请求...</div>
  </div>
</div>
<div id="menu-div">
  <ul>
    <li class="fix-spacel">&nbsp;</li>
        <li class="fix-spacer">你好， 你上次的登录时间是：</li>
        <li class="fix-spacer">&nbsp;</li>
  </ul>
  <br class="clear">
</div>

</body></html>