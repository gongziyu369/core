<?php namespace core;

class SqlServer extends \Medoo\Medoo {
	
	public function __construct(){

		$option=config('sqlserver');

		parent::__construct($option);
	}

}