<?php namespace core;

class Ajax{

	public function __construct(){
		global $Re;
		$Re = new Regular;
		$this->AJAXOK=Config::key('define','AJAXOK');
		$this->SUCCESS=Config::key('define','SUCCESS');
		$this->FAILED=Config::key('define','FAILED');
	}

	public function modify(){#ajaxUpdate
		header("Cache-Control: no-cache, must-revalidate");#extract($_POST);

		$db=new \core\DB;

		$table=post('table') or msg('no table');
		$primary=post('primary') or msg('no primary');
		$field=post('field') or msg('no field');
		$value=post('value');
		$mk=post('mk');

		if(strlen($value)==0) $value=NULL;

		$result=$db->update($table,[
			$field=>$value
		],[
			$primary=>$mk
		]);

		if($result) $msg=$this->SUCCESS; else $msg=$this->FAILED;#if(DEBUG) $msg=$sql."PRIMARY:$table->$mk ";

		return $msg;
	}

	public function account(){

		global $Re;

		$account=post('account');

		$result=$Re->account($account);

		return $result ? $this->AJAXOK : '连线名称格式不正确';
	}

	public function phone(){
		global $Re;

		$phone=post('phone');

		$result=$Re->phone($phone);

		return $result ? $this->AJAXOK : '电话格式不正确';
	}

	public function mobile(){
		global $Re;

		$mobile=post('mobile');

		$result=$Re->mobile($mobile);

		return $result ? $this->AJAXOK : '电话格式不正确';
	}

	public function password(){
		global $Re;

		$password=post('password');

		$result=$Re->password($password);

		return $result ? $this->AJAXOK : '密码至少6位以上';
	}

	public function repassword(){

		$password=post('password');

		$repassword=post('repassword');

		if($password==$repassword) return $this->AJAXOK;

		else return "两次输入不相同";
	}

	public function verify(){

		$verify=post('verify');

		if(isset($verify) && $verify==$_SESSION['verify']) $msg = $this->AJAXOK;

		else $msg = "验证码不正确";

		return $msg;
		#<script>document.getElementById('verify').innerHTML="<img src='ok.gif'>"</script>
	}


	public function sendVerify(){
		$mobile=post('mobile');
		$mod=post('mod');

		$text=Sms::msg($mobile,$mod);#
		ECHO $text;EXIT;
		
		return Sms::send($mobile,$text);
	}
}



/*

	public function agentPhone(){

		$phone=get('phone');

		$that = new \Model\user;

		$userid = $that->phone2id($phone);

		if($userid) {
			$User= $that->userGet($userid);

			$result='电话:'.$User['phone'].'<br>';
			$result.='昵称:'.$User['username'].'<br>';
			$result.='真实姓名:'.$User['fullname'].'<br>';

		} else {
			$result='不存在此会员';
		}

		return $result;
	}*/

/*
if($value || $value=='0') $sql="UPDATE `$table` SET `$field` = '$value' WHERE `$primary`='$mk'";
else $sql="UPDATE `$table` SET `$field` = NULL WHERE $primary='$mk'";

$update=$db->update($sql);
if(DEBUG) $msg=$sql."PRIMARY:$table->$mk ";
if($update) $msg=$SUCCESS; else $msg=$FAILED;
*/
#echo $msg;

/*行不通  SET后变量 '$'  必须加单引号
$value=$value? addslashes(trim($value)) : 'NULL'; 
$sql="UPDATE `$table` SET `$field`='$value' WHERE $primary='$mk'";
*/