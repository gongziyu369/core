<?php namespace core;

class Cat extends \core\DB{
	public $table='category';

	public function All($catid){

		$dataArray=$this->select($this->table,[
			'catid','parent','name','sort'
		],[
			'AND'=>['parent'=>$catid,'state'=>1],
			'ORDER'=>['sort'=>'DESC'],
		]);

		return $dataArray;
	}
}