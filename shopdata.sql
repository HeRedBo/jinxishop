-- myshop商城数据库
-- 表前缀 shop
-- 商品表
CREATE DATABASE myshop default charset =utf8;
use myshop;
DROP TABLE IF EXISTS shop_goods;
CREATE TABLE IF NOT EXISTS shop_goods
(
	id mediumint unsigned not null auto_increment,
	goods_name varchar(45) not null comment '商品名称',
	goods_sn varchar(60) not null comment '商品编号',
	cat_id tinyint not null default 0 comment '商品分类id',
	brand_id smallint unsigned not null comment '品牌的id',
	shop_price decimal(10,2) not null default '0,00' comment '商品价格',
	market_price decimal(10,2) not null default '0,00' comment '市场价',
	goods_ori varchar(128) NOT NULL DEFAULT '' COMMENT ' 商品原图的路径',
  	goods_thumb varchar(128) NOT NULL DEFAULT '' COMMENT ' 商品小图的路径',
	jifen int unsigned not null comment '赠送积分',
	jyz int unsigned not null comment '赠送经验值',
	jifen_price mediumint not null comment '如果使用积分,需要的积分制',
	is_promote tinyint unsigned not null default '0' comment '是否促销 0:不促销 1:促销',
	promote_price decimal(10,2) not null default '0.00' comment '促销价',
	promote_start_time int unsigned not null default '0' comment '促销开始时间',
	promote_end_time int unsigned not null default '0' comment '出现结束时间', 
	goods_number smallint unsigned not null default 0 comment '商品库存',
	market_num smallint unsigned not null default 0 comment '产品的销售量',
	goods_desc longtext comment '商品描述',
	seo_keyword varchar(30)  not null default '' comment 'SEO优化_描述',
	seo_description varchar(150) not null default '' comment 'SEO优化_描述',
	type_id mediumint unsigned not null default '0' comment '商品类型id',
	sort_num tinyint unsigned not null default '100' comment '排序数字',
	is_on_sale tinyint unsigned not null default 1 comment '是否上架：1:上架 0:下架',
	is_new tinyint unsigned not null default 0 comment '是否新品',
	is_hot tinyint unsigned not null default 0 comment '是否热卖',
	is_best tinyint unsigned not null default 0 comment '是否精品',
	is_delete tinyint unsigned not null default 0 comment  '是否删除, 1：已经删除 0：未删除',
	addtime int unsigned not null comment '添加时间',
	primary key (id),
	key price(shop_price),
	key cat_id(cat_id),
	key brand_id(brand_id),
	key is_on_sale(is_on_sale),
	key is_new(is_new),
	key is_hot(is_hot),
	key is_best(is_best),
	key is_delete(is_delete),
	key sort_num(sort_num),
	key promote_start_time(promote_start_time),
	key promote_end_time(promote_end_time),
	key addtime(addtime)
)engine= MyISAM default charset =utf8;

--- 2015-10-11 新增商品市场价格 积分值 经验值字段





-- 从ecshop 复制数据
INSERT INTO shop_goods (goods_name,goods_sn,shop_price,goods_ori,goods_img,goods_thumb,goods_number,goods_desc,is_on_sale,is_delete,addtime) SELECT goods_name,goods_sn,shop_price,original_img,goods_img,goods_thumb,goods_number,goods_desc,is_on_sale,is_delete,add_time FROM ecshop.ecs_goods;
sc create mysvn binpath= "D:\SVN\SVN Server\bin\svnserve.exe --service -r D:/SVN/myApp" start= auto



-- 商品栏目表的创建
DROP TABLE IF EXISTS shop_category;
CREATE TABLE shop_category
(
	id smallint unsigned primary key not null auto_increment,
	cat_name varchar(30) not null comment '栏目名称',
	parent_id smallint unsigned not null comment '父级栏目id'
)charset = utf8 engine = MyISAM;

-- 插入数据
INSERT INTO shop_category (id,cat_name,parent_id) SELECT cat_id,cat_name,parent_id FROM ecshop.ecs_category;

------------------------RBAC--------------------------
-- 2015-10-06 管理员表的创建
DROP TABLE IF EXISTS shop_admin;
CREATE TABLE shop_admin
(
	id tinyint unsigned primary key not null auto_increment,
	username varchar(30) not null comment '用户账号',
	password char(32) not null comment '密码',
	is_use tinyint unsigned not null default '1' comment '是否启用 1 启用 0：禁用'
)charset =utf8 engine = MyISAM comment '管理员';;

-- 初始化管理账号
INSERT INTO shop_admin VALUES(1,'root','0192023a7bbd73250516f069df18b500',1);
admin
-- 权限表
DROP TABLE IF EXISTS shop_privilege;
CREATE TABLE shop_privilege 
(
	id smallint unsigned primary key not null auto_increment,
	pri_name varchar(30) not null comment '权限名称',
	module_name varchar(20) not null comment '模块名称',
	controller_name varchar(20) not null comment '控制器名称',
	action_name varchar(20) not null comment '方法名称',
	parent_id smallint unsigned not null default '0' comment '上级权限的ID, 0: 代表顶级权限'

)charset = utf8 engine = MyISAM COMMENT '权限表';

-- 角色权限表
DROP TABLE IF EXISTS shop_role_privilege;
CREATE TABLE shop_role_privilege
(
	pri_id smallint unsigned not null comment '权限的ID',
	role_id smallint unsigned not null comment '角色的id',
	key pri_id(pri_id),
	key role_id(role_id)
)charset = utf8 engine = MyISAM COMMENT '角色权限表';

-- 角色表
DROP TABLE IF EXISTS shop_role;
CREATE TABLE shop_role(
	id smallint unsigned primary key not null auto_increment,
	role_name varchar(30) not null comment '角色名称'
)charset =utf8 engine = MYISAM comment '角色表';

-- 管理员角色表
DROP TABLE IF EXISTS shop_admin_role;
CREATE TABLE shop_admin_role 
(
	admin_id tinyint unsigned not null comment '管理员的ID',
	role_id smallint unsigned not null comment '角色的id',
	key admin_id(admin_id),
	key role_id(role_id)
)charset = utf8 engine = MYISAM comment '管理员角色表';

-- 写sql语句取出id为3的管理员所拥有的所有的权限
-- 流程 1. 取出id为3这个管理员所在的角色的id
SELECT role_id FROM shop_admin_role WHERE admin_id = 3;
-- 2. 在取出这些角色所拥有的权限的id
SELECT pri_id FROM shop_role_privilege WHERE role_id (1的结果)
-- 3. 根据权限的id 取出这些权限的信息
SELECT * FROM shop_privilege wnere id IN (2的结果)

-- 最终
SELECT * FROM shop_privilege where id IN (
	SELECT pri_id FROM shop_role_privilege WHERE role_id in 
		(SELECT role_id FROM shop_admin_role WHERE admin_id = 3)
)

-- 写法二 
SELECT a.* FROM shop_privilege a, shop_role b, shop_admin_role c 
WHERE c.admin_id = 3 
AND b.pri_id = a.id 
AND b.role_id =c.role_id

-- 写法三
SELECT b.* 
	FROM shop_role_privilege a 
		LEFT JOIN shop_privilege b ON a.pri_id = b.id
		LEFT JOIN shop_admin_role c ON a.role_id  = c.id
			WHERE c.admin_id =3


-- 取出角色的权限列表
SELECT a.* ,GROUP_CONCAT(c.pri_name) pri_names FROM shop_role a 
LEFT JOIN shop_role_privilege b on a.id = b.role_id 
LEFT JOIN shop_privilege c on b.pri_id = c.id GROUP BY a.id;

----------- RBAC 完结！ ------------

----------- 继续商品模块 ---------
----------- 商品模块的核心设计 8 张表的设计
DROP TABLE IF EXISTS shop_type;
CREATE TABLE shop_type(
	id tinyint unsigned primary key not null auto_increment,
	type_name varchar(30) not null comment '类型名称',
	index(type_name)
)charset = utf8 engine = MyISAM comment '商品类型';

DROP TABLE IF EXISTS shop_attribute;
CREATE TABLE shop_attribute
(
	id mediumint unsigned primary key not null auto_increment,
	type_id tinyint not null comment '商品类型的id',
	attr_name varchar(30) not null comment '属性名称',
	attr_type tinyint unsigned not null default '0' comment '属性的类型0: 唯一 1: 可选',
	attr_option_values varchar(150) not null default '' comment '属性的可选值,多个可选值用","隔开',
	key type_id(type_id)
)charset = utf8 engine =MyISAM comment '属性';
-------------------------------------------------
-- 商品属性表
DROP TABLE IF EXISTS shop_attribute;
CREATE TABLE shop_attribute (
	id mediumint unsigned primary key not null auto_increment,
	attr_name varchar(32) not null comment '属性名称',
	type_id tinyint unsigned not null comment '属性的id',
	attr_type tinyint not null default 0 comment '属性的类型 0: 表示唯一属性 1:单选属性',
	attr_input_type tinyint not null default 0 comment '属性值的录入方式 0:手工录入 1:列表选择',
	attr_value varchar(150) not null default '' comment '属性的默认值,列表选择使用 多个是用","隔开',
	index(type_id)
) charset utf8 engine MyISAM comment '商品属性表';

-- 会员价格
	-- 会员级别 -- 会员商品 ---> 多对多的表对于关系
DROP TABLE IF EXISTS shop_member_level;
CREATE TABLE shop_member_level (
	id mediumint unsigned not null auto_increment,
	level_name varchar(30) not null comment '级别名称',
	bottom_num int unsigned not null comment '积分下限',
	top_num int unsigned not null comment '积分上限',
	rate tinyint unsigned not null default '100' comment '折扣率 以百分比计算 如 九折=90',
	primary key(id)
) charset = utf8 engine = MyISAM comment '会员级别';

DROP TABLE IF EXISTS shop_member_price ;
CREATE TABLE shop_member_price(
	goods_id mediumint unsigned not null comment '商品id',
	level_id mediumint unsigned not null comment '级别id',
	price decimal(10,2) not null comment '这个级别的价格'

) charset = utf8 engine = MyISAM comment '级别价格';

-- 商品相册
DROP TABLE IF EXISTS shop_goods_pics;
CREATE TABlE shop_goods_pics(
	id mediumint unsigned not null auto_increment,
	pic varchar(150) not null comment '图片',
	sm_pic varchar(150) not null comment '缩略图',
	goods_id mediumint unsigned comment '商品的id',
	primary key(id),
	key goods(goods_id)
)charset = utf8 engine = MyISAM comment '商品图片';

-- 商品的属性 多对多关系数据
DROP TABLE IF EXISTS shop_goods_attr;
CREATE TABLE shop_goods_attr(
	id int unsigned not null auto_increment,
	goods_id mediumint unsigned not null comment '商品的id',
	attr_id  mediumint unsigned not null comment '属性的id',
	attr_value varchar(150) not null default '' comment '属性的值',
	attr_prive decimal(10,2) not null default '0.00' comment '属性的价格',
	primary key(id),
	key goods_id(goods_id),
	key attr_id(attr_id)
) charset = utf8 engine = MyISAM comment '商品属性';

DROP TABLE IF EXISTS shop_goods_number;
CREATE TABLE shop_goods_number(
	id int unsigned not null auto_increment,
	goods_id mediumint unsigned not null comment '商品的id',
	goods_number mediumint unsigned not null comment '库存量',
	goods_attr_id int unsigned not null comment '商品属性的id列表',
	# 这列保存的id是shop_goods_attr 表id 既可以知道是什么也可以知道属性是什么 如果有多个id组合就用, 隔开保存一个字符串 并且保存是要按id升序存 将来前台查询数据库属性ID升序拼成的字符串让后查出数据库
	primary key(id),
	key goods_id(goods_id),
	key attr_id(goods_attr_id)
) charset = utf8 engine = MyISAM comment '商品库存表';

-- 商品扩展分类表

DROP TABLE IF EXISTS shop_goods_cat;
CREATE TABLE shop_goods_cat(
	goods_id mediumint unsigned not null comment '商品的id',
	cat_id smallint unsigned not null comment '分类的id'
)charset = utf8 engine = MyISAM comment '商品扩展分类表';


-- 商品平牌表
DROP TABLE IF EXISTS shop_brand;
CREATE TABLE IF NOT EXISTS shop_brand
(
	id mediumint unsigned not null auto_increment,
	brand_name varchar(45) not null comment '品牌名称',
	site_url varchar(60) not null comment '品牌网站地址',
	brand_logo varchar(150) not null default '' comment '品牌logo',
	goods_desc longtext comment '商品描述',
  	sort mediumint unsigned not null default '100' comment '商品排序',
  	is_show tinyint unsigned not null default '1' comment '是否显示 0:不显示 1:显示',
	primary key (id),
	key sort(sort),
	key is_show(is_show)
)engine= MyISAM default charset = utf8 comment '商品品牌表';

--- 商品优惠价格表
DROP TABLE IF EXISTS shop_youhui_price;
CREATE TABLE shop_youhui_price(
	goods_id mediumint unsigned not null comment '商品的id',
	youhui_num int unsigned not null comment '数量',
	youhui_price decimal(10,2) not null comment '优惠价格',
	key goods(goods_id)
)engine= MyISAM default charset = utf8 comment '商品优惠价格';




 


-- 锁机制测试数据表
create table lock01
(
	id tinyint unsigned not null
)charset =utf8;

-- 修改表中的图像位置sql
start transaction;
UPDATE shop_goods SET goods_ori = substring(goods_ori,8),goods_img = substring(goods_img,8),goods_thumb = substring(goods_thumb,8);

UPDATE shop_goods SET goods_ori = concat('Goods/',goods_ori),goods_img = concat('Goods/',goods_img),goods_thumb = concat('Goods/',goods_thumb);


select substring(goods_ori,8
) from shop_goods;

-------会员表
DROP TABLE IF EXISTS shop_member;
CREATE TABLE shop_member(
	id mediumint unsigned not null auto_increment,
	email varchar(60) not null comment '会员账号',
	password char(32) not null comment '密码',
	face varchar(150) not null default '' comment '头像',
	addtime int unsigned not null comment '注册时间',
	email_code char(32) not null default '' comment '邮件验证的验证码 会员验证通过之后 会把这个字段清空 如果这个字段为空说明会员已经通过email验证',
	jifen int unsigned not null default '0' comment '积分',
	jyz int unsigned not null default '0' comment '经验值',
	openid char(64) not null defaut '' comment '对应的QQ的openid',
	primary key (id)
)engine= MyISAM default charset = utf8 comment '会员表';
alter table shop_member add jifen int unsigned not null default '0' comment '积分';
alter table shop_member add jyz int unsigned not null default '0' comment '经验值';
alter table shop_member add openid char(64) not null default '' comment '对应的QQ的openid';
--------------- 商品评论表
DROP TABLE IF EXISTS `shop_comment`;
CREATE TABLE shop_comment 
(
	id mediumint unsigned not null auto_increment,
	content varchar(1000) not null comment '评论内容',
	star tinyint unsigned not null comment '星级',
	addtime int unsigned not null default '3' comment '打分',
	member_id mediumint unsigned not null comment '会员的id', 
	goods_id mediumint unsigned not null comment '商品的id',
	used smallint unsigned not null default '0' comment '有用的数量',
	primary key(id),
	key goods_id(goods_id)
)engine= MyISAM default charset = utf8 comment '评论表';

-- 回复表
DROP TABLE IF EXISTS shop_reply;
CREATE TABLE shop_reply
(
	id mediumint unsigned not null auto_increment,
	content varchar(1000) not null comment '回复的内容',
	addtime int unsigned not null comment '回复时间',
	member_id mediumint unsigned not null comment '会员ID',
	comment_id mediumint unsigned not null comment '评论的ID',
	primary key(id),
	key comment_id(comment_id)
)engine=MyISAM default charset=utf8 comment '回复';


--------- 用户点击有用的评论
DROP TABLE IF EXISTS shop_clicked_use;
CREATE TABLE shop_clicked_use
(
	member_id mediumint unsigned not null comment '会员的id',
	comment_id mediumint unsigned not null comment '评论id',
	primary key (member_id,comment_id) #因为这两个字段查询会一起用 所以直接创建联合索引
)engine= MyISAM default charset = utf8 comment '用户点击有用的评论';
# 判断会员是偶点击过3这天评论
# SELECT COUNT(*) FROM shop_clicked_use WHERE member_id = 1 and comment_id = 3;

DROP TABLE IF EXISTS shop_impression;
CREATE TABLE shop_impression
(
	id mediumint unsigned not null auto_increment,
	imp_name varchar(30) not null comment '印象的标题',
	imp_count smallint unsigned not null default '1' comment '印象出现的次数',
	goods_id mediumint unsigned not null comment '商品id',
	primary key (id),
	key goods_id(goods_id)
)engine= MyISAM default charset = utf8 comment '印象';

DROP TABLE IF EXISTS shop_cart ;
CREATE TABLE shop_cart
(
	id mediumint unsigned not null auto_increment,
	goods_id mediumint unsigned not null comment '商品的id',
	goods_attr_id varchar(30) not null default '' comment '选择商品的属性',
	goods_number int unsigned not null comment '购买数量',
	member_id mediumint unsigned not null comment '会员的id',
	primary key (id),
	key member_id(member_id)
) engine = InnoDB default charset = utf8 comment '购物车';

# 商城订单表
DROP TABLE IF EXISTS shop_order;
CREATE TABLE shop_order(
	id mediumint unsigned not null auto_increment comment '主键id',
	member_id mediumint unsigned not null comment '会员id',
	addtime int unsigned not null comment '下单时间',
	# 收货人信息
	shr_name varchar(30) not null comment '收获人',
	shr_province varchar(30) not null comment '省',
	shr_city varchar(30) not null comment '市',
	shr_area varchar(30) not null comment '地区',
	shr_tel varchar(30) not null comment '联系方式',
	shr_address varchar(100) not null comment '收货人详细地址',
	total_price decimal(10,2) not null comment '订单总价',
	post_method varchar(30) not null comment '发货方式',
	pay_status tinyint not null default '0' comment '支付状态, 0:未支付,1:已支付', 
	post_status tinyint unsigned not null default '0' comment '发货状态 0:未发货 1:已发货 2:已经到货',
	primary key (`id`),
	key member_id(`member_id`)
)engine = InnoDB default charset = utf8 comment '订单表';

# 订单商品表
DROP TABLE IF EXISTS shop_order_goods;
CREATE TABLE shop_order_goods
(
	order_id mediumint unsigned not null comment '订单的id',
	member_id mediumint unsigned not null comment '会员的id',
	goods_id mediumint unsigned not null comment '商品的id',
	goods_attr_id varchar(30) not null default '' comment '选择的属性的id,如果有用，隔开',
	goods_attr_str varchar(150) not null default '' comment '选择的属性的字符串',
	goods_price decimal(10,2) not null comment '商品的价格',
	goods_number int unsigned not null comment '购买商品的数量',
	key order_id(`order_id`),
	key goods_id(`goods_id`)
)engine = InnoDB default charset = utf8 comment '订单商品';
