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