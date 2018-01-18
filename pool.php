<?php namespace core;

class Pool extends \core\DB{

	static public function userOnline($userid){
		$onlineTime=\core\Conf::get('onlineTime','mbyl',true);
		$db=new \core\DB;
		$table='online';
		$primary='userid';

		$lasttime=$db->get($table,'lasttime',[
			'userid'=>$userid
		]);

		if(TIME-$lasttime>$onlineTime) {#超过300秒更新

			$fields=[
				'lasttime'=>TIME,
				#'page'=>$title,#$_SERVER['REQUEST_URI']
			];

			$db->RENEW($table,$primary,$userid,$fields);
		}
	}

	static public function userVip($userid){
		$firstBill=300;
		$db=new \core\DB;

		$active=$db->get('user','active',[
			'id'=>$userid
		]);

		if($active) return;#ACTIVE不为0返回

		$sum=$db->sum('payment','rmb',[
			'userid'=>$userid,'state'=>1
		]);

		if($sum>=$firstBill) {#充值300元

			$success=$db->action(function($db) use($userid,$firstBill) {

				$agentid=$db->get('user','agentid',[
					'id'=>$userid
				]); 

				if($agentid) {#合伙人提成5%

					$rewardCoin=$firstBill*0.5;#奖励5%=5元=50积分

					$A[]=$db->insert('agent_user',[
						'userid'=>$userid,'agentid'=>$agentid,'coin'=>$rewardCoin,'time'=>TIME
					]);

					$A[]=$db->update('agent',[
						'coin[+]'=>$rewardCoin
					],[
						'agentid'=>$agentid
					]);
				}
					
				$A[]=$db->update('user',[
					'active'=>1
				],[
					'id'=>$userid
				]);

				if(!array_product($A)) return false;

				else return true;
			});

			$active=$db->get('user','active',[
				'id'=>$userid
			]);

			if($active) show('您已成功升级为正式会员');

		} else {

			$orderid=$db->get('payment','orderid',[
				'userid'=>$userid,'rmb'=>$firstBill,'state'=>0,
			]);

			if(!$orderid) {
				$P=new \Model\Payment;
				$orderid=$P->paymentCreate(USER,$firstBill);
			}

			show("充值{$firstBill}元平台费用，开始抢抢乐<br>支付订单编号{$orderid}","/?payment=paymentSelect&orderid=$orderid","5000");
		}
	}

	static public function phone2id($phone){
		$db=new \core\DB;
		return $db->get('user','id',[
			'phone'=>$phone
		]);
	}

	static public function isAgent($agentid){
		if($agentid) {		
			$db=new \core\DB;
			return $db->get('agent','active',[
				'agentid'=>$agentid
			]);
		}
	}

	static public function phonehidden($phone){
		return substr_replace($phone,'****',3,4);
	}

	static public function usernametext($userid){
		$db=new \core\DB;
		
		$User=$this->get('user',[
			'id','phone','nickname','fullname'
		],[
			'id'=>$userid
		]);

		if($User['fullname']) $recommender=$User['fullname'];

		else if($User['nickname']) $recommender=$User['nickname'];

		else if($User['phone']) $recommender=$User['phone'];

		return $recommender;
	}

    static public function bankname($bank){
        $bankConfig=Node('bank');

        if(array_key_exists($bank, $bankConfig)) $bankname=$bankConfig[$bank]['mean'];

        else $bankname=$bank ? $bank : '';

        return $bankname;
    }

    static public function expressName($express){
        $expressConfig=Node('express');

        if(array_key_exists($express, $expressConfig)) $expressName=$expressConfig[$express]['mean'];

        else $expressName=$express;

        return $expressName;
    }

	static public function hms($second){

		$h=floor($second/60/60%60);
		$m=floor($second/60%60);
		$s=floor($second%60);

        $mm=$m>9 ? $m : '0'.$m;
        $ss=$s>9 ? $s : '0'.$s;

        if($h) $hms[]=$h.'小时';
        if($m) $hms[]=$mm.'分';
        if($s) $hms[]=$ss.'秒';

        return implode('',$hms);
	}

	static public function zonetimelong($zone){
		$zoneConfig=\core\Conf::get('zoneConfig','mbyl',true);

		$timelong=$zoneConfig[$zone]['timelong'];

		return $timelong;
	}


    static public function zonename($zone){
		$zoneConfig=\core\Conf::get('zoneConfig','mbyl',true);
    }

    static public function userAddress($userid){
		$db=new \core\DB;
		
		$Address=$db->get('user_address',[
			'phone','contact','address','cityname','citycode'
		],[
			'userid'=>$userid
		]);

		$msg='';
		#正则过滤输入数据
		$Re=new \core\Regular;
		$phone=$Re->phone($Address['phone']) or $msg.='联系人电话未完善<br>';
		$citycode=$Re->citycode($Address['citycode']) or $msg.='城市信息未完善<br>';
		$len=mb_strlen($Address['contact'],'utf8');
		if($len<2) $msg.="联系人信息未完善<br>";
		$len=mb_strlen($Address['address'],'utf8');
		if($len<4) $msg.="地址信息未完善<br>";
		
		if($msg) show($msg,'?user=userAddress');
    }

}