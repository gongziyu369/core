<?php namespace core;
#print_r($_REQUEST);exit;
#PRINT_R($_SERVER);exit;
#set_time_limit(0);
#header("Content-type: text/html;charset=$charset");
session_start();
define('CORE', str_replace("\\", "/", __DIR__));
require_once CORE.'/../vendor/autoload.php';
require_once CORE.'/wind.php';
require_once CORE.'/function.php';
define('APP', $_SERVER['DOCUMENT_ROOT']);
define('DEBUG',1);

if(DEBUG) {
/*	$whoops = new \Whoops\Run;
	$option=new \Whoops\Handler\PrettyPageHandler;
	$whoops->pushHandler($option);
	$whoops->register();*/
	ini_set('display_errors','On');
	error_reporting(E_ALL);# & ~E_NOTICE
} else {
	ini_set('display_errors','Off');
}

spl_autoload_register(__NAMESPACE__.'\Wind::loading');

define('HOST', $_SERVER['HTTP_HOST']);##当前网址域名 www.123.com
define('DOMAIN', substr(HOST,strpos(HOST,'.')+1));#域名主体 123.com
define('UPLOAD',APP.'/attach');
define('ATTACH','http://'.HOST.'/attach');
define('HTTPUSERAGENT',HTTPUSERAGENT());
#define('IP',IP());#CONST常量定义
define('REFERER',$_SERVER['HTTP_REFERER'] ?? '/');
define('AUTHCODE',md5('gongziyu'.$_SERVER['HTTP_USER_AGENT']));
define('TIME',$_SERVER['REQUEST_TIME']);
#if($_SERVER['REQUEST_METHOD']=='POST') define('POST',true);

#控制器变量 #方法变量
LIST($ctrl,$method)=Route::init();#加载URL路由选择器

$APP_INCLUDE=APP.'/include/include.php';
if(file_exists($APP_INCLUDE)) include($APP_INCLUDE);

#$define=config('define');extract($define);#使用GLOBAL来获取配置变量

try{
	if($ctrl=='ajax') AJAX();

	if($_SERVER['REQUEST_METHOD'] == 'POST') Wind::token();

	$nsCtrl='\\Ctrl\\'.ucfirst($ctrl);#控制器类名首字母大写
	$C=new $nsCtrl($ctrl);#dump($C);exit;

	$compact=$C->$method();
	$public=get_object_vars($C);
	if(isset($public)) extract($public);
	if(isset($compact)) extract($compact);
	if(count($_GET)) extract($_GET);

} catch(Exception $e){
	We::json(999,'core error:'.$e->getMessage());
}


$Nav=\core\Pool::ctrl_Method_Nav($ctrl,$method);#dump($Nav);exit;

################加载视图VIEW##########

if($ctrl!=$method) $view=$view ?? $ctrl.ucfirst($method);

$viewConfig=config('view');extract($viewConfig);#使用GLOBAL来获取配置变量

include view($view ?? $method);#include view($view ?? $ctrl.$method);
#WE::run();