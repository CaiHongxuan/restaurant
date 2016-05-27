<?php
/**
 * 自定义打印函数
 * @param  array  $array [description]
 * @return [type]        [description]
 */
// function p($array){
// 	dump($array, 1, '<pre>', 0);
// }

/**
 * 分页函数
 * @param  [object]  $model     [模型]
 * @param  boolean $condition [条件]
 * @param  integer $rollPage  [分页栏每页显示的页数]
 * @param  string  $order     [排序条件]
 * @return [array]             [包含结果集(list)和分类(page)的数组]
 */
function page($model, $rollPage=10, $order='', $condition=array()){
	import('ORG.Util.Page');// 导入分页类
	// 总记录数
	$count = $model->count();
	// 实例化分页类，传入总记录数和每页显示的记录数
	$Page = new Page($count, $rollPage);
	// 分页显示输出
	foreach ($condition as $key => $value) {
		$Page->parameter .= "$key=".urlencode($value).'&';
	}
	$array = array();
	$array['show'] = $Page->show();
	$array['list'] = $model->where($condition)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
	return $array;
}

/**
 * 发送邮件
 * @param  [type] $address [收件人地址]
 * @param  [type] $message [邮件正文]
 * @param  [type] $title   [邮件标题]
 * @return [type]          [description]
 */
function sendEmail($address,$message,$title){
	vendor('PHPMailer.class#phpmailer');
	vendor('PHPMailer.class#smtp');
	vendor('PHPMailer.class#pop3');
	$mail = new PHPMailer();
	// 设置PHPMailer使用SMTP服务器发送Email
	$mail->IsSMTP();
	// 设置邮件的字符编码，若不指定，则为'UTF-8'
	$mail->CharSet = 'UTF-8';
	// 添加收件人地址，可以多次使用来添加多个收件人
	$mail->AddAddress($address);
	// 启用 SMTP 验证功能
	$mail->SMTPAuth   = true;
	// SMTP 安全协议
	//$mail->SMTPSecure = "ssl";
	// 设置邮件正文
	$mail->Body = $message;
	// 支持HTML格式
	$mail->IsHTML(true);
	// 设置邮件头的From字段。
	$mail->From = C('MAIL_ADDRESS');
	// 设置发件人名字
	$mail->FromName = '宏玄餐饮B2C店';
	// 设置邮件标题
	$mail->Subject = $title;
	// 设置SMTP服务器。
	$mail->Host = C('MAIL_SMTP');
	// 设置为“需要验证”
	$mail->SMTPAuth = true;
	// 设置用户名和密码。
	$mail->Username = C('MAIL_LOGINNAME');
	$mail->Password = C('MAIL_PASSWORD');
	// 发送邮件。
	return($mail->Send());
}
?>
