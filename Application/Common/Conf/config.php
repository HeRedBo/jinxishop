<?php
return array(
	//'配置项'=>'配置值'
	
	/*数据库配置信息*/
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_NAME'   => 'myshop', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => 'root', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'shop_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集

	/************* 图片相关配置 ***************/
	'IMG_maxSize' => '3M',
	'IMG_exts'	  => array('jpg','pjpeg','bmp','gif','png','jpeg'),
	'IMG_rootPath'=>'./Uploads/',
	'IMG_URL'=>'/Uploads/',
	/************ 修改I函数底层过滤时使用的函数 ***************/
	'DEFAULT_FILTER'=>'trim,removeXSS',
	/***********MD5 时复杂化加密***********/
	'MD5_KEY'  => 'asdf1234&_123!#asd889',
	
	/* URL设置 */
	'URL_CASE_INSENSITIVE'	=>	true,	//默认false表示区分大小写 true表示不区分大小写
	'URL_MODEL'				=>	2,	//URL访问模式

	/*模板相关配置*/
	'TMPL_PARSE_STRING'		=>	array(
		//设置后台模板样式路径
		'__ADMIN_IMG__' =>	__ROOT__ . '/Public/Admin/images',
		'__ADMIN_CSS__'	=>	__ROOT__ . '/Public/Admin/styles',
		'__ADMIN_JS__'	=>	__ROOT__ . '/Public/Admin/js',
	),

	/* 分页页码设置 */
	'ADMIN_PERPAGE' => 15,
	/*日志设置*/
	'LOG_RECORD'            =>  false,   // 默认不记录日志
	'LOG_TYPE'              =>  'File', // 日志记录类型 默认为文件方式
	'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
	'LOG_EXCEPTION_RECORD'  =>  false,    // 是否记录异常信息日志
	
	/*追踪信息*/
	'SHOW_PAGE_TRACE' 		=>true,		//追踪信息 主要在开发阶段应用

	/**************** 发邮件的参数配置 ******************/
	'MAIL_ADDRESS' 	=> 'hhbjkd@163.com',
	'MAIL_FROM'		=> '小波',
	'MAIL_SMTP'		=> 'smtp.163.com',
	'MAIL_LOGINNAME'=> 'hhbjkd',
	'MAIL_PASSWORD' => 'hhbjkd19930228'

);