<?php
return array(
	'tableName' => 'shop_brand',    // 表名
	'tableCnName' => '品牌',  // 表的中文名
	'moduleName' => 'Admin',  // 代码生成到的模块
	'digui' => 0,             // 是否无限级（递归）
	'diguiName' => '',        // 递归时用来显示的字段的名字，如cat_name（分类名称）
	'pk' => 'id',    // 表中主键字段名称
	'topPriName' => '商品管理',    // 把品牌的权限放到商品管理这个权限的子权限
	/********************* 要生成的模型文件中的代码 ******************************/
	'insertFields' => "array('brand_name','site_url','goods_desc','sort','is_show')",
	'updateFields' => "array('id','brand_name','site_url','goods_desc','sort','is_show')",
	'validate' => "
		array('brand_name', 'require', '品牌名称不能为空！', 1, 'regex', 3),
		array('brand_name', '1,45', '品牌名称的值最长不能超过 45 个字符！', 1, 'length', 3),
		array('site_url', 'require', '品牌网站地址不能为空！', 1, 'regex', 3),
		array('site_url', '1,60', '品牌网站地址的值最长不能超过 60 个字符！', 1, 'length', 3),
		array('sort', 'number', '商品排序必须是一个整数！', 2, 'regex', 3),
		array('is_show', 'number', '是否显示 0:不显示 1:显示必须是一个整数！', 2, 'regex', 3),
	",
	/********************** 表中每个字段信息的配置 ****************************/
	'fields' => array(
		'brand_name' => array(
			'text' => '品牌名称',
			'type' => 'text',
			'default' => '',
		),
		'site_url' => array(
			'text' => '品牌网站地址',
			'type' => 'text',
			'default' => '',
		),
		'brand_logo' => array(
			'text' => '品牌logo',
			'type' => 'file',
			'thumbs' => array(
				array(350, 350, 2),
				array(150, 150, 2),
				array(50, 50, 2),
			),
			'save_fields' => array('brand_logo', 'big_brand_logo', 'mid_brand_logo', 'sm_brand_logo'),
			'default' => '',
		),
		'goods_desc' => array(
			'text' => '商品描述',
			'type' => 'text',
			'default' => '',
		),
		'sort' => array(
			'text' => '商品排序',
			'type' => 'text',
			'default' => '100',
		),
		'is_show' => array(
			'text' => '是否显示 0:不显示 1:显示',
			'type' => 'text',
			'default' => '1',
		),
	),
	/**************** 搜索字段的配置 **********************/
	'search' => array(
		array('brand_name', 'normal', '', 'like', '品牌名称'),
		array('site_url', 'normal', '', 'like', '品牌网站地址'),
		array('goods_desc', 'normal', '', 'eq', '商品描述'),
		array('sort', 'normal', '', 'eq', '商品排序'),
		array('is_show', 'normal', '', 'eq', '是否显示 0:不显示 1:显示'),
	),
);