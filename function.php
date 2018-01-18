<?php
/*视图函数*/
function view($tpl) {

	return \core\Wind::view($tpl);
}
/*语言函数*/
function Lang($key) {

	\core\Wind::Lang($key);
}
/*消息函数*/
function msg($key,$url=NULL,$timeout=NULL){#return \core\Wind::msg(func_get_args());

	return \core\Wind::msg($key,$url,$timeout);
}
/*跳转函数*/
function Go($url=NULL) {

	\core\Wind::Go($url);
}
/*配置函数*/
function config($file,$key=null){

	return \core\Config::get($file,$key);
}
/*$_GET参数获取函数*/
function get($key){

	return \core\Wind::get($key);
}
/*$_POST参数获取函数*/
function post($key){

	return \core\Wind::post($key);
}
/*分类数据库加载函数*/
function cat($table){

	$catTable=config('cat');

	$catid=$catTable[$table] ?? \core\Wind::bug('not found :'.$table);

	$M=new \Model\Category;

	return $M->categoryList($catid,true);
}
/*TOKEN令牌函数*/
function token(){
	$_SESSION['token']=md5(microtime());
	session_write_close();
	#return "<input type='hidden' name='token' value=$_SESSION['token']\"/>";
	return "<input type='hidden' name='token' value='$_SESSION[token]'/>";
	#return "<input type='hidden' name='token' value='$_SESSION[token]'></input>";
}



/*function show($msg,$url='javascript:history.go(-1);',$timeout='300000') {

    $common=config('common');extract($common);

	if($url) {
		$do="location.href='$url'";
	} else {
		$do="history.go(-1);";
		$url="javascript:history.go(-1);";
	}
	include view(__FUNCTION__);exit;
}*/

/*URL*/
function url($array){

	return \core\Route::url($array);
}

function error($code){

	return \core\Wind::error($code);
}

function json($errno,$errmsg,$data){

	return \core\Wind::json($errno,$errmsg,$data);
}

function AJAX(){

	$A=new \core\Ajax;

	$tip=post('tip');

	$method = $tip ? $tip :'modify';

	$msg=$A->$method();

	echo $msg;exit;
}





function Node($node){

	$db=new \core\DB;

	$dataArray=$db->select('node',[
		'key','mean','parent','sort'
	],[
		'AND'=>['parent'=>$node,'active'=>1],
		'ORDER'=>['sort'=>'DESC'],
	]);

	return $dataArray;
}

#设计为动态调用 考虑__Call  __autoload
function activeNode($node){

	$N=new \core\Node;

	$nodeArray=$N->activeNode($node);

	return $nodeArray;
}





function HTTPUSERAGENT(){

	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) $httpuseragent='weixin';

	else $httpuseragent=NULL;

	return $httpuseragent;
}




function swal($msg,$url='javascript:history.go(-1);',$timeout='3000') {

	include view(__FUNCTION__);exit;
}

function placeholderInput($text) {
print <<<EOT
title="$text" placeholder="$text" onfocus="{placeholder=''}" onblur="if(placeholder==''){placeholder='$text'}"
EOT;
}
#title="$text" value="$text" onfocus="if(value=='$text'){value=''}" onblur="if(value==''){value='$text'}"

/*
*microsecond 微秒     millisecond 毫秒 
*返回时间戳的毫秒数部分 
*/
function millisecond() {
	list($usec, $sec) = explode(" ", microtime());
	$msec=round($usec*1000);
	return $msec;
}

/*

function IP(){
	if(getenv('HTTP_CLIENT_IP')){
		$ip=getenv('HTTP_CLIENT_IP');
	}elseif(getenv('HTTP_X_FORWARDED_FOR')){
		$ip=getenv('HTTP_X_FORWARDED_FOR');
	}elseif(getenv('REMOTE_ADDR')){
		$ip=getenv('REMOTE_ADDR');
	}else{
		$ip=$HTTP_SERVER_VARS['REMOTE_ADDR'];
	}
	return $ip;
}


#判断查询字段
function searchField($keyword){

    if(preg_match("/^\d{11}$/", $keyword))$method="phone";

    else if(preg_match("/^\d{1,10}$/", $keyword)) $method="id";
    
    else $method='cn';#中文字符

    return $method;
}
function SMS($phone,$sendmsg){

	require_once CORE.'/lib/ChuanglanSmsHelper/ChuanglanSmsApi.php';

	$clapi  = new ChuanglanSmsApi();

	$result = $clapi->sendSMS($phone, $sendmsg);

	if(!is_null(json_decode($result))){
		
		$output=json_decode($result,true);

		if(isset($output['code'])  && $output['code']=='0'){

			echo '短信发送成功！' ;

		} else {
			echo $output['errorMsg'];
		}
	} else {
			echo $result;
	}

	exit;#return $msg;
}
function paging($count) {

	$page=isset($_GET['page']) ? $_GET['page'] : 1;
	#if($page==NULL) $page=1;

	$pagesize=10;
	$leftright=4;#左右列表跨度

	if($count==0) return;

	$Array['count']=$count;
	$Array['page']=(int)$page;
	$Array['pagesize']=$pagesize;

	$Array['pagecount']=(int)ceil($count/$pagesize);#总页面数

	$Array['prepage']=$page-1;
	$Array['nextpage']=$page+1;
	if($page>$Array['pagecount'] || $page<=0) show("不存在的页数");
	$Array['keystart']=($page-1)*$pagesize;//$rightsize=($page+1)*$pagesize;

	$left=$page-$leftright >0 ? $page-$leftright : 1;
	$right=$page+$leftright < $Array['pagecount'] ? $page+$leftright : $Array['pagecount'];
	for($i=$left;$i<=$right;$i++) $Array['pagelist'][]=$i;#print_r($Array);exit;
	#dump($Array);exit;
	return $Array;
}

function formtoken(){
	$_SESSION['formtoken']=md5(microtime());
	session_write_close();
	return "<input type='hidden' name='formtoken' value='$_SESSION[formtoken]'></input>";
}

function itemFieldArray($table) {
	global $db;#,$dbName
	$sql="SHOW COLUMNS FROM `$table`;";
	$query=$db->query($sql);
	while($fetch=$query->fetch_array(MYSQLI_ASSOC)) {
		@$itemFieldArray[$n++]=$fetch['Field'];
	}#print_r($itemFieldArray);exit;
	return $itemFieldArray;
}

function array_columns($input, $columnKey, $indexKey=null){
    if(!function_exists('array_column')){ 
        $columnKeyIsNumber  = (is_numeric($columnKey))?true:false; 
        $indexKeyIsNull            = (is_null($indexKey))?true :false; 
        $indexKeyIsNumber     = (is_numeric($indexKey))?true:false; 
        $result                         = array(); 
        foreach((array)$input as $key=>$row){ 
            if($columnKeyIsNumber){ 
                $tmp= array_slice($row, $columnKey, 1); 
                $tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null; 
            }else{ 
                $tmp= isset($row[$columnKey])?$row[$columnKey]:null; 
            } 
            if(!$indexKeyIsNull){ 
                if($indexKeyIsNumber){ 
                  $key = array_slice($row, $indexKey, 1); 
                  $key = (is_array($key) && !empty($key))?current($key):null; 
                  $key = is_null($key)?0:$key; 
                }else{ 
                  $key = isset($row[$indexKey])?$row[$indexKey]:0; 
                } 
            } 
            $result[$key] = $tmp; 
        } 
        return $result; 
    }else{
        return array_column($input, $columnKey, $indexKey);
    }
}
*/