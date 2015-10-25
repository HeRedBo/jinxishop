<?php
return array(
	'tableName' => 'shop_attribute',    // 表名
	'tableCnName' => '商品属性表',  // 表的中文名
	'moduleName' => 'Admin',  // 代码生成到的模块
	'digui' => 0,             // 是否无限级（递归）
	'diguiName' => '',        // 递归时用来显示的字段的名字，如cat_name（分类名称）
	'pk' => 'id',    // 表中主键字段名称
	/********************* 要生成的模型文件中的代码 ******************************/
	'insertFields' => "array('attr_name','type_id','attr_type','attr_input_type','attr_value')",
	'updateFields' => "array('id','attr_name','type_id','attr_type','attr_input_type','attr_value')",
	'validate' => "
		array('attr_name', 'require', '属性名称不能为空！', 1, 'regex', 3),
		array('attr_name', '1,32', '属性名称的值最长不能超过 32 个字符！', 1, 'length', 3),
		array('type_id', 'require', '属性的id不能为空！', 1, 'regex', 3),
		array('type_id', 'number', '属性的id必须是一个整数！', 1, 'regex', 3),
		array('attr_type', 'number', '属性的类型 0: 表示唯一属性 1:单选属性必须是一个整数！', 2, 'regex', 3),
		array('attr_input_type', 'number', '属性值的录入方式 0:手工录入 1:列表选择必须是一个整数！', 2, 'regex', 3),
		array('attr_value', '1,150', '属性的默认值,列表选择使用 多个是用","隔开的值最长不能超过 150 个字符！', 2, 'length', 3),
	",
	/********************** 表中每个字段信息的配置 ****************************/
	'fields' => array(
		'attr_name' => array(
			'text' => '属性名称',
			'type' => 'text',
			'default' => '',
		),
		'type_id' => array(
			'text' => '属性的id',
			'type' => 'text',
			'default' => '',
		),
		'attr_type' => array(
			'text' => '属性的类型 0: 表示唯一属性 1:单选属性',
			'type' => 'text',
			'default' => '0',
		),
		'attr_input_type' => array(
			'text' => '属性值的录入方式 0:手工录入 1:列表选择',
			'type' => 'text',
			'default' => '0',
		),
		'attr_value' => array(
			'text' => '属性的默认值,列表选择使用 多个是用","隔开',
			'type' => 'text',
			'default' => '',
		),
	),
	/**************** 搜索字段的配置 **********************/
	'search' => array(
		array('attr_name', 'normal', '', 'like', '属性名称'),
		array('type_id', 'normal', '', 'eq', '属性的id'),
		array('attr_type', 'normal', '', 'eq', '属性的类型 0: 表示唯一属性 1:单选属性'),
		array('attr_input_type', 'normal', '', 'eq', '属性值的录入方式 0:手工录入 1:列表选择'),
		array('attr_value', 'normal', '', 'like', '属性的默认值,列表选择使用 多个是用","隔开'),
	),
);