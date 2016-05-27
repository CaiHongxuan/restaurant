// 验证码变换数字
function change_code(obj){
	$(".passcode").attr('src',verifyURL+'/'+Math.random());
	return false;
}

