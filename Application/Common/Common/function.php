<?php 
	//公共的函数库
	/**
	 * 邮件发送函数
	 */
	function sendMail($to,$title,$content)
	{
		require_once('./Public/PHPMailer_v5.1/class.phpmailer.php');
		$mail = new PHPMailer();
		//设置为发送邮件
		$mail->IsSMTP();
		//是否允许发送html代码作为邮件的内容
		$mail->IsHTML();
		//是否需要身份验证
		$mail->SMTPAuth = TRUE;
		$mail->CharSet  = 'UTF-8';
		/*设置邮件服务器的账号是什么*/ 
		$mail->From 	= C('MAIL_ADDRESS');
		$mail->FromName = C('MAIL_FROM');
		$mail->Host = C('MAIL_SMTP');
		$mail->Username = C('MAIL_LOGINNAME');

		$mail->Password = C('MAIL_PASSWORD');
		// 发送邮件的端口默认是25
		$mail->Port = 25;
		
		//邮件标题
		$mail->Subject = $title;
		//收件人
		$mail->AddAddress('1196450116@qq.com');
		//邮件内容
		//$mail->Body    = $content;
		$mail->MsgHTML($content);
		return($mail->Send());

	}
	/**
	 * 过滤函数 防止网站受XSS 攻击 (单例模式)
	 */
	function removeXSS($val)
	{
		static $obj = null;
		if($obj ==null)
		{
			require('./Public/HTMLPurifier/HTMLPurifier.includes.php');
			$config = HTMLPurifier_config::createDefault();
			//保留a标签的target属性
			$config->set('HTML.TargetBlank',TRUE);
			$obj = new HTMLPurifier($config);	
		}
		return $obj->purify($val);
	}

	/**
	 * 图片上传函数
	 *$ret = uploadOne('logo', 'Goods', array(
	 *		array(600, 600),
	 *		array(300, 300),
	 *		array(100, 100),
	 *	));
	 *返回值：
	 *if($ret['ok'] == 1)
	 *	{
	 *		$ret['images'][0];   // 原图地址
	 *		$ret['images'][1];   // 第一个缩略图地址
	 *		$ret['images'][2];   // 第二个缩略图地址
	 *		$ret['images'][3];   // 第三个缩略图地址
	 *	}
	 *	else 
	 *	{
	 *		$this->error = $ret['error'];
	 *		return FALSE;
	 *	}
	 * @return [type] [description]
	 * date 2015-10-06 12:59;
	 */
	function uploadOne($imgName,$dirname,$thumb = array())
	{
		//上传商品图片
		if(isset($_FILES[$imgName]) && $_FILES[$imgName]['error'] == 0)
		{
			$rootPath 	= C('IMG_rootPath');
			$upload  	= new \Think\Upload(array(
				'rootPath'=>$rootPath //设置文件上传的根目录
			));//实例化文件上传类
			$upload->maxSize 	= (int)C('IMG_maxSize') * 1024 * 1024 ;//设置附件上传大小
			 $upload->exts      =  array('jpg', 'gif', 'png', 'jpeg');// 
			$upload->savePath 	= $dirname.'/';//设置二级目录的名称
			$upload->autoSub 	= false;
			//上传文件
			//上传文件时指定一个要上传的图片的名称,否则会把表单中所有的图片都处理，
			$info = $upload->upload(array( $imgName=>$_FILES[$imgName]) );
			if(!$info)
			{
				return array(
					'ok' => 0,
					'error' =>$upload->getError(),
				);
			}
			else
			{	
				$ret['ok'] = 1;
				$ret['images'][0] = $orimgname = $info[$imgName]['savepath'] . $info[$imgName]['savename'];
				//判断是否生成缩略图
				if($thumb)
				{
					
					$image = new \Think\Image();
					//循环生成缩略图
					foreach ($thumb as $k => $v) 
					{
						$ret['images'][$k + 1] = $info[$imgName]['savepath'] . 'thumb_'.$k.'_'.$info[$imgName]['savename'];
						//打开要处理的图片
						$image->open($rootPath.$orimgname);
						$image->thumb($v[0],$v[1])->save($rootPath.$ret['images'][$k + 1]);
					}
				}
			return $ret;		
			}
		}
	}


	/**
	 * 显示图片
	 * @param string $url 商品的路径
	 * @param int $width 图片显示的宽度
	 * @paran int $height 商品的显示高度	
	 * @return string商品的html标签
	 * @author Red-Bo
	 * @date 2016-01-22 15:33:09
	 */
	function showImage($url,$width='',$height ='')
	{
		$url = '/Uploads/'.$url;
		if($width)
			$width = "width='{$width}'";
		if($height)
			$height = "height='{$height}'";
		echo "<img src='$url' $width $height />";
	}

	/**
	 * 删除图片
	 * @param array $images 图片数据数组
	 * @author Red-Bo
	 * date 2015-10-06 13:01
	 */
	function deleteImage($images)
	{
		
		//先取出图片的所在目录
		$irp = C('IMG_rootPath');
		foreach ($images as  $v) 
		{
			@unlink($irp.$v);
		}
	}

	/**
	 * 判断批量上传的数组中有没有上传至少一张图片
	 * @param stirng $imgNmame 图片上传的表单名称
	 * @return boolen 
	 * @author Red-Bo
	 * @date 2016-01-22 15:38:48
	 */
	function hasImage($imgName)
	{
		foreach ($_FILES[$imgName]['error'] as $k => $v)
		{
			if($v == 0)
				return true;
		}
		return FALSE;
	}

	/**
	 * 二维数组排序算法
	 */
	function attr_id_sort($a,$b)
	{
		if($a['attr_id'] == $b['attr_id'])
			return 0;
		return ($a['attr_id'] < $b['attr_id']) ? -1 :1;
	}