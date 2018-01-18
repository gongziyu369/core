<?php namespace core;

class Sms{

	static public function msg($mobile,$mod){

		$verify=rand(1000,9999);

		$_SESSION['mobile']=$mobile;
		$_SESSION['verify']=$verify;

		if($mod=='userSign') $modName="新会员注册";

		else if($mod=='passwordMofify') $modName="会员密码修改";

		else if($mod=='passwordForget') $modName="会员取回密码";

		else if($mod=='mobileBind') $modName="手机号绑定";

		$text="{$mobile}您好，{$modName} 验证码为：{$verify}";

		return $text;
	}

	static public function send($mobile,$sendmsg){

		require_once CORE.'/lib/ChuanglanSmsHelper/ChuanglanSmsApi.php';

		$clapi  = new \ChuanglanSmsApi();

		$result = $clapi->sendSMS($mobile,$sendmsg);

		if(!is_null(json_decode($result))){
			
			$output=json_decode($result,true);

			if(isset($output['code'])  && $output['code']=='0'){

				$msg='短信发送成功！' ;

			} else {
				$msg=$output['errorMsg'];
			}
		} else {
			$msg=$result;
		}

		return $msg;
	}
}