<?php
return [
'STOREHOST'=>'http://'.STORE,
'STOREDIR'=>CORE.'/../'.STORE,
'qrcode'=>'images/qrcode',
'shopENV'=>'images/shopENV',
'shopLogo'=>'images/shopLogo',
'shopLicense'=>'images/shopLicense',
'userAvatar'=>'images/avatar',
'userQrcode'=>'images/qrcode',
'modelPhoto'=>APP.'/'.DOMAIN.'/'.'model',
'httpModelPhoto'=>'http://'.DOMAIN.'/model',

'KEY'=>'IX7BZ-2HVKR-53JWR-WYNN5-XCMYO-4KBHJ',#'KEY=>'2BKBZ-TL5W2-TYPU5-CSFBM-HXSAJ-FFFD2',
'cycle'=>30,
'pagesize'=>10,
'kindConfig'=>['K'=>'1000','A'=>'100',],
'ticketStateConfig'=>['0'=>'待返现','1'=>'已返现',],

'EX'=>[
    'ticket'=>'推广券',
    'money'=>'返还金',
],

'shopStateConfig'=>[
    '1'=>'通过',
    '2'=>'测试帐号',
    '-1'=>'待审核',
    '-2'=>'资料未完善',
    '0'=>'未开通',
    #null=>'无记录',
],

'refundPayConfig'=>[
    '0'=>'等待打款',
    '1'=>'已打款',
    '4'=>'账户户名不符',
    '5'=>'资料不完善',
    '6'=>'帐号解析失败',
    '7'=>'请更换银行卡',
    '9'=>'用户已取消',
],

'sourceConfig'=>[
    'jifen'=>'积分',
    'queue'=>'返现',
],

'queueStateConfig'=>[
    '0'=>'待返现',
    '1'=>'已返现',
],

'timeSlot'=>[
    '0'=>'今天',
    '1'=>'昨天',
    '2'=>'前天',
    '7'=>'一周',
    '30'=>'一月',
],

'cityState'=>[
    '0'=>'未开发',
    '1'=>'有商家',
    '2'=>'有代理商',
],

//'refundActiveConfig=>[
//'0'=>'未打款',
//'1'=>'已打款',
//],

//代理商打款状态
'agentrefundActive'=>[
    '0'=>'未打款',
    '1'=>'已打款',
],

'poolConfig'=>[
    '0'=>'默认手动',
    '1'=>'全自动开启',
    #'2'=>'绿色通道开启',
    #'4'=>'手动',
],

];