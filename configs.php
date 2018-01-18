<?php

/**
* 
*/
class Config implements \ArrayAccess 
{

	protected $path;
	protected $configs=array();

	function __construct($path) {

		$this->path=$path;

	}

	function offsetGet($key){

		if(empty($this->configs[$key])){

			$file_path=$this->path.'/'.$key.'.php';

			$config=require $file_path;

			$this->configs[$key]=$config;
		}

		return $this->configs[$key];

	}

	function offsetSet($key,$value){

	}
}