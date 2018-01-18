<?php
/**
* 1 确定日志存储方式
* 2.写日志
*/
class log {
	
	#function __construct(argument) {# code...}

	static $class;

	static public function init(){
		#确定存储方式
		#$drive=conf::get('DRIVE','log');
		#dump($drive);exit;

		#$class=
		
		self::$class=new $class;
	}

	public function log($name){

		self::$class->log($name);
	}
}