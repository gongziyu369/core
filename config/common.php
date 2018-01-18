<?php
return [
'charset'=>'UTF-8',
'APP'=>APP,
'HOST'=>HOST,
'DOMAIN'=>DOMAIN,
'HTTPHOST'=>'http://'.HOST,
'SCRIPTNAME'=>$_SERVER['SCRIPT_NAME'],
'HTTPUSERAGENT'=>HTTPUSERAGENT,
'TIME'=>TIME,
'ui'=>'ui',
'api'=>'API',
'images'=>'images',
'theajax'=>'',
'ajaxResult'=>'',

'AJAXOK'=>"<img src='/ui/ok.gif'>",
'dateformat'=>"Y年m月d日H时i分s秒",
'READY'=>"<font color=green>READY</font><br>",
'SUCCESS'=>"<font color=Purple>修改成功!</font><br>",
'FAILED'=>"<font color=red>失败failed!</font><br>",
'onlineTime'=>300,

'sortConfig'=>[
	'0'=>'无',
	'1'=>'太极',
	'2'=>'两仪',
	'3'=>'三界',
	'4'=>'四象',
	'5'=>'五',
	'6'=>'六扇',
	'7'=>'七',
	'8'=>'八卦',
	'9'=>'九',
	'255'=>'正',
],
];