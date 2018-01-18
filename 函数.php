<?php


function arraykeysort($dataArray,$key,$order='asc'){//asc是升序 desc是降序

	$array_column=$newArray=array();

	foreach($dataArray as $k=>$v) $array_column[$k]=$v[$key];

	if($order=='asc') asort($array_column);

	else arsort($array_column);

	foreach($array_column as $k=>$v) $newArray[]=$dataArray[$k];#$newArray[$k]=$dataArray[$k];

	return $newArray;
}


//获取url地址后的参数
function get_url($where){
    $page = null;
    $arge = $_SERVER["QUERY_STRING"];
    $arr = explode('&', $arge);
    foreach ($arr as $k => $v)
    {
        if (strpos($v, 'page=') !== false)
        {
            $page = $k;
        }
        foreach($where as $wv)
        {
            if (strpos($v, $wv.'=') !== false)
            {
                $del[] = $k;
            }
        }
    }

    if(!empty($del)) {
        foreach ($del as $dv) {
            unset($arr[$dv]);
        }
    }
    unset($arr[$page]);
    return implode('&', $arr);
}

//获取知道地区所有城市
function agentCityArray($agent){

	$medoo=new DB;

    $agentArray = array($agent);

    $agent_1 = $medoo->db->select('city', ['citykey'], [
        "AND" => [
            'parent' => $agent,
        ]
    ]);

    if(!empty($agent_1)) {
        foreach ($agent_1 as $city) {
            $agentArray[] = $city['citykey'];
            $agent_2 = $medoo->db->select('city', ['citykey'], [
                "AND" => [
                    'parent' => $city['citykey'],
                ]
            ]);
            if(!empty($agent_2)){
                foreach ($agent_2 as $city_2) {
                    $agentArray[] = $city_2['citykey'];
                }
            }
        }
    }

    return $agentArray;
}
/*
*用于接口返回josn数据
*
*/
function outputs($data = ''){
	$dataArray = '';
	$dataArray['code'] = GetHttpStatusCode(HOST);
	$dataArray['data'] = $data;
	$dataArray['time'] = time();
	echo json_encode($dataArray);exit;//数据返回时就终止执行
	
	
}
/*
 *获取http的状态
 * 
 */
function GetHttpStatusCode($url){ 
     $curl = curl_init();
     curl_setopt($curl,CURLOPT_URL,$url);//获取内容url 
     curl_setopt($curl,CURLOPT_HEADER,1);//获取http头信息 
     curl_setopt($curl,CURLOPT_NOBODY,1);//不返回html的body信息 
     curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//返回数据流，不直接输出 
     curl_setopt($curl,CURLOPT_TIMEOUT,60); //超时时长，单位秒 
     curl_exec($curl);
     $rtn= curl_getinfo($curl,CURLINFO_HTTP_CODE);
     curl_close($curl);
     return  $rtn;
 }
//方便调试原样输出
function pre($data){
	echo '<pre>';
		print_r($data);
	echo '</pre>';
	exit;
}

/*
 *获取会员头像  URL
 * 
 */
function getUserAvatar($img){
	global $HOSTWWW,$images,$userAvatar;
	return $img ? 'http://'.HOST.'/'.$userAvatar.'/'.$img : 'http://'.HOST.'/'.$images.'avatar.png';
	//return $img ? $HOSTWWW.'/'.$userAvatar.'/'.$img : $HOSTWWW.'/'.$images.'/avatar.png';

}


/*
 *获取商家Logo  URL
 * 
 */
function getshopLogo($img){
	
	global $HOSTWWW,$images,$shopLogo;
	return $img ? 'http://'.HOST.'/'.$shopLogo.'/'.$img : 'http://'.HOST.'/'.$images.'/shopLogo.png';
	//return $img ? $HOSTWWW.'/'.$shopLogo.'/'.$img : $HOSTWWW.'/'.$images.'/shopLogo.png';


}

/*
 *获取商家营业执照  URL
 * 
 */
function getshopLicense($img){

	global $HOSTWWW,$images,$shopLicense;
	return $img ? 'http://'.HOST.'/'.$shopLicense.'/'.$img : 'http://'.HOST.'/'.$images.'/shopLogo.png';
	//return $img ? $HOSTWWW.'/'.$shopLicense.'/'.$img : $HOSTWWW.'/'.$images.'/shopLogo.png';

}

/*
 *获取商家环境图片  URL
 * 
 */
function getshopEnv($img){

	global $HOSTWWW,$images,$shopENV;
	return $img ? 'http://'.HOST.'/'.$shopENV.'/'.$img : 'http://'.HOST.'/'.$images.'/shopLogo.png';
	//return $img ? $HOSTWWW.'/'.$shopENV.'/'.$img : $HOSTWWW.'/'.$images.'/shopLogo.png';

}


/*
 *获取访问者设备
 * 
 */
function getOS()  {  
    $agent = strtolower($_SERVER["HTTP_USER_AGENT"]);  
      
    if(strpos($agent, "windows nt")) {  
    $platform = 'windows';  
    } elseif(strpos($agent, "macintosh")) {  
    $platform = 'mac';  
    } elseif(strpos($agent, "ipod")) {  
    $platform = 'ipod';  
    } elseif(strpos($agent, "ipad")) {  
    $platform = 'ipad';  
    } elseif(strpos($agent, "iphone")) {  
    $platform = 'ios';  
    } elseif (strpos($agent, "android")) {  
    $platform = 'android';  
    } elseif(strpos($agent, "unix")) {  
    $platform = 'unix';  
    } elseif(strpos($agent, "linux")) {  
    $platform = 'linux';  
    } else {  
    $platform = 'other';  
    }  
      
    return $platform;  
}
/*
 *生成随机数
 * 
 */
function randpw($len=8,$format='ALL'){
	$is_abc = $is_numer = 0;
	$password = $tmp ='';  
	switch($format){
		case 'ALL':
			$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		break;
		case 'CHAR':
			$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		break;
		case 'NUMBER':
			$chars='0123456789';
		break;
		default :
			$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		break;
	}
	mt_srand((double)microtime()*1000000*getmypid());
	while(strlen($password)<$len){
		$tmp =substr($chars,(mt_rand()%strlen($chars)),1);
		if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
			$is_numer = 1;
		}
		if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
			$is_abc = 1;
		}
		$password.= $tmp;
	}
	if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
		$password = randpw($len,$format);
	}
	return $password;
}



function paystate($state){
	switch ($state)
	{
	case 0:
	  return '等待打款';
	  break;  
	case 1:
	  return '已打款';
	  break;  
	case 4:
	  return '账户户名不符';
	  break;
	case 5:
	  return '资料不完善';
	  break;
	case 6:
	  return '帐号解析失败';
	  break;
	case 7:
	  return '请更换银行卡';
	  break;
	case 9:
	  return '用户已取消';
	  break;
	default:
	  return '系统有误';
	}
}
