<?php
return array(
	'tableName' => 'shop_goods',    // 表名
	'tableCnName' => '商品',  // 表的中文名
	'moduleName' => 'Admin',  // 代码生成到的模块
	'digui' => 0,             // 是否无限级（递归）
	'diguiName' => '',        // 递归时用来显示的字段的名字，如cat_name（分类名称）
	'pk' => 'id',    // 表中主键字段名称
	/********************* 要生成的模型文件中的代码 ******************************/
	'insertFields' => "array('goods_name','goods_sn','cat_id','brand_id','shop_price','market_price','goods_ori','goods_thumb','jifen','jyz','jifen_price','promote_price','promote_start_time','promote_end_time','goods_number','market_num','goods_desc','seo_keyword','seo_description','type_id','sort_num','is_on_sale','is_new','is_hot','is_best','is_delete')",
	'updateFields' => "array('id','goods_name','goods_sn','cat_id','brand_id','shop_price','market_price','goods_ori','goods_thumb','jifen','jyz','jifen_price','promote_price','promote_start_time','promote_end_time','goods_number','market_num','goods_desc','seo_keyword','seo_description','type_id','sort_num','is_on_sale','is_new','is_hot','is_best','is_delete')",
	'validate' => "
		array('goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3),
		array('goods_name', '1,45', '商品名称的值最长不能超过 45 个字符！', 1, 'length', 3),
		array('goods_sn', 'require', '商品编号不能为空！', 1, 'regex', 3),
		array('goods_sn', '1,60', '商品编号的值最长不能超过 60 个字符！', 1, 'length', 3),
		array('cat_id', 'number', '商品分类id必须是一个整数！', 2, 'regex', 3),
		array('brand_id', 'require', '品牌的id不能为空！', 1, 'regex', 3),
		array('brand_id', 'number', '品牌的id必须是一个整数！', 1, 'regex', 3),
		array('shop_price', 'currency', '商品价格必须是货币格式！', 2, 'regex', 3),
		array('market_price', 'currency', '市场价必须是货币格式！', 2, 'regex', 3),
		array('goods_ori', '1,128', ' 商品原图的路径的值最长不能超过 128 个字符！', 2, 'length', 3),
		array('goods_thumb', '1,128', ' 商品小图的路径的值最长不能超过 128 个字符！', 2, 'length', 3),
		array('jifen', 'require', '赠送积分不能为空！', 1, 'regex', 3),
		array('jifen', 'number', '赠送积分必须是一个整数！', 1, 'regex', 3),
		array('jyz', 'require', '赠送经验值不能为空！', 1, 'regex', 3),
		array('jyz', 'number', '赠送经验值必须是一个整数！', 1, 'regex', 3),
		array('jifen_price', 'require', '如果使用积分,需要的积分制不能为空！', 1, 'regex', 3),
		array('jifen_price', 'number', '如果使用积分,需要的积分制必须是一个整数！', 1, 'regex', 3),
		array('promote_price', 'currency', '促销价必须是货币格式！', 2, 'regex', 3),
		array('promote_start_time', 'number', '促销开始时间必须是一个整数！', 2, 'regex', 3),
		array('promote_end_time', 'number', '出现结束时间必须是一个整数！', 2, 'regex', 3),
		array('goods_number', 'number', '商品库存必须是一个整数！', 2, 'regex', 3),
		array('market_num', 'number', '产品的销售量必须是一个整数！', 2, 'regex', 3),
		array('seo_keyword', '1,30', 'SEO优化_描述的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('seo_description', '1,150', 'SEO优化_描述的值最长不能超过 150 个字符！', 2, 'length', 3),
		array('type_id', 'number', '商品类型id必须是一个整数！', 2, 'regex', 3),
		array('sort_num', 'number', '排序数字必须是一个整数！', 2, 'regex', 3),
		array('is_on_sale', 'number', '是否上架：1:上架 0:下架必须是一个整数！', 2, 'regex', 3),
		array('is_new', 'number', '是否新品必须是一个整数！', 2, 'regex', 3),
		array('is_hot', 'number', '是否热卖必须是一个整数！', 2, 'regex', 3),
		array('is_best', 'number', '是否精品必须是一个整数！', 2, 'regex', 3),
		array('is_delete', 'number', '是否删除, 1：已经删除 0：未删除必须是一个整数！', 2, 'regex', 3),
	",
	/********************** 表中每个字段信息的配置 ****************************/
	'fields' => array(
		'goods_name' => array(
			'text' => '商品名称',
			'type' => 'text',
			'default' => '',
		),
		'goods_sn' => array(
			'text' => '商品编号',
			'type' => 'text',
			'default' => '',
		),
		'cat_id' => array(
			'text' => '商品分类id',
			'type' => 'text',
			'default' => '0',
		),
		'brand_id' => array(
			'text' => '品牌的id',
			'type' => 'text',
			'default' => '',
		),
		'shop_price' => array(
			'text' => '商品价格',
			'type' => 'text',
			'default' => '0.00',
		),
		'market_price' => array(
			'text' => '市场价',
			'type' => 'text',
			'default' => '0.00',
		),
		'goods_ori' => array(
			'text' => ' 商品原图的路径',
			'type' => 'file',
			'thumbs' => array(
				array(150,150,2),
			),
			'save_fields' => array('goods_ori','goods_thumb'),
			'default' => '',
		),
		'jifen' => array(
			'text' => '赠送积分',
			'type' => 'text',
			'default' => '',
		),
		'jyz' => array(
			'text' => '赠送经验值',
			'type' => 'text',
			'default' => '',
		),
		'jifen_price' => array(
			'text' => '如果使用积分,需要的积分制',
			'type' => 'text',
			'default' => '',
		),
		'promote_price' => array(
			'text' => '促销价',
			'type' => 'text',
			'default' => '0.00',
		),
		'promote_start_time' => array(
			'text' => '促销开始时间',
			'type' => 'text',
			'default' => '0',
		),
		'promote_end_time' => array(
			'text' => '出现结束时间',
			'type' => 'text',
			'default' => '0',
		),
		'goods_number' => array(
			'text' => '商品库存',
			'type' => 'text',
			'default' => '0',
		),
		'market_num' => array(
			'text' => '产品的销售量',
			'type' => 'text',
			'default' => '0',
		),
		'goods_desc' => array(
			'text' => '商品描述',
			'type' => 'html', //-> 生成在线编辑器
			'default' => '',
		),
		'seo_keyword' => array(
			'text' => 'SEO优化_描述',
			'type' => 'text',
			'default' => '',
		),
		'seo_description' => array(
			'text' => 'SEO优化_描述',
			'type' => 'text',
			'default' => '',
		),
		'type_id' => array(
			'text' => '商品类型id',
			'type' => 'text',
			'default' => '0',
		),
		'sort_num' => array(
			'text' => '排序数字',
			'type' => 'text',
			'default' => '100',
		),
		'is_on_sale' => array(
			'text' => '是否上架：1:上架 0:下架',
			'type' => 'radio',
			'values' => array(
				'1' => '是',
				'0' => '否'
			),
		),
		'is_new' => array(
			'text' => '是否新品',
			'type' => 'radio',
			'values' => array(
				'1' => '是',
				'0' => '否'
			),
		),
		'is_hot' => array(
			'text' => '是否热卖',
			'type' => 'radio',
			'values' => array(
				'1' => '是',
				'0' => '否'
			),
		),
		'is_best' => array(
			'text' => '是否精品',
			'type' => 'radio',
			'values' => array(
				'1' => '是',
				'0' => '否'
			),
		),
		
	),
	/**************** 搜索字段的配置 **********************/
	'search' => array(
		array('goods_name', 'normal', '', 'like', '商品名称'),
		array('cat_id', 'normal', '', 'eq', '商品分类id'),
		array('brand_id', 'normal', '', 'eq', '品牌的id'),
		array('shop_price', 'between', 'shop_pricefrom,shop_priceto', '', '商品价格'),
		array('is_hot', 'in', '1-是,0-否', '', '是否热卖'),
		array('is_new', 'in', '1-是,0-否', '', '是否新品'),
		array('is_best', 'in', '1-是,0-否', '', '是否精品'),
		array('is_on_sale', 'in', '1-上架,0-下架', '', '是否上架'),
		array('type_id', 'normal', '', 'eq', '商品类型id'),
		array('addtime', 'betweenTime', 'addtimefrom,addtimeto', 'eq', '添加时间'),

	),
);