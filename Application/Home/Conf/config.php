<?php 
return array(
'HTML_CACHE_ON'     =>    true, // 开启静态缓存
'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
'HTML_FILE_SUFFIX'  =>    '.shtml', // 设置静态缓存文件后缀
'HTML_CACHE_RULES'  =>     array(  
	'Index:index' => array('index',3600), // 为首页生成一个1小时的缓存页面 页面名字就index.shtml
)
);