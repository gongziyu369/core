<?php namespace core;

class Regular{

	public function __call($method,$arg){
		return true;
		#echo '<br>method: <b>'.$method.'</b> in '.__CLASS__.' arg: '.implode(',', $arg);
	}

	public function int($subject){
		if(is_numeric($subject))
			if(floor($subject)==$subject)
				return $subject;
	}

	public function number($subject){
		$pattern="/^\d+/";
		if(preg_match($pattern, $subject)) return $subject;
	}

	public function string($subject){#echo mb_internal_encoding();
		$len=mb_strlen($subject,'utf8');
		if($len<=20) return $subject;
	}

	public function fullname($subject){
		$pattern="/^[\x{4e00}-\x{9fa5}]+$/u";
		if(preg_match($pattern,$subject)) return $subject;
	}

	public function account($subject){
		$len=mb_strlen($subject,'utf8');
		if($len<=20 && $len>=4) return $subject;
	}

	public function phone($subject){
		#echo 'subject:'.$subject;exit;
		#$pattern="/^[0-9]{11}$/";
		#$pattern="/^1[34578][0-9- ０-９－　]{9}$/";
		$pattern="/^1[34578][0-9]{9}$/";
		if(preg_match($pattern, $subject)) return $subject;
	}

	public function mobile($subject){
		$pattern="/^1[34578][0-9]{9}$/";
		if(preg_match($pattern, $subject)) return $subject;
	}

	public function password($subject){
		#$pattern="/^[\S]{6,}$/";
		$pattern="/^[a-zA-Z0-9]{6,}$/";
		if(preg_match($pattern, $subject)) return $subject;
	}
	public function repassword($password,$repassword){
		if($password===$repassword) return $repassword;
	}
	public function verify($verify){
		if(empty($_SESSION['verify'])) return true;
		if($verify == $_SESSION['verify']) return true;
		#unset($_SESSION['verify']);
	}

	public function vphone($phone){
		if(empty($_SESSION['phone'])) return true;
		if($phone == $_SESSION['phone']) return true;
	}

	public function vmobile($mobile){
		if(empty($_SESSION['mobile'])) return true;
		if($mobile == $_SESSION['mobile']) return true;
	}

	public function money($subject){
		$pattern="/^\d+(\.[0-9]{1,2})?$/";
		if(preg_match($pattern, $subject)) return $subject;
	}

	public function json($json){#echo $_SESSION['jsontoken'];echo '<br>';echo $json;exit;
		$jsontoken=md5($json);
		if($_SESSION['jsontoken'] != $jsontoken) show("大侠手下留情。。");
		else unset($_SESSION['jsontoken']);
	}

	public function citycode($subject){
		$pattern="/^\d{6}$/";
		if(preg_match($pattern, $subject)) return $subject;
	}

/*	public function formtoken($formtoken){#echo $formtoken;exit;
		if($_SESSION['formtoken'] != $formtoken) show("页面重复提交，请返回");
		else unset($_SESSION['formtoken']);
	}*/






	public function refund($subject){
		$pattern="/[1-9]\d*/";
		if(!preg_match($pattern, $subject)) return "金额格式不正确";
	}

	public function ticket($subject){
		$pattern="/^[1-9]\d*$/";
		if(!preg_match($pattern, $subject)) return "数量不正确";
	}

}