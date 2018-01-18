<?php namespace core;

class DB extends \Medoo\Medoo {
	
	public function __construct(){

		$option=config('database');

		parent::__construct($option);

		$this->_setField();
	}

	private function _setField(){

		if(isset($this->table)) {

			$tableStructure = $this->query("DESC $this->table")->fetchAll(\PDO::FETCH_ASSOC);#SHOW COLUMNS FROM

			$this->FieldArray=array_column($tableStructure,'Field');

			foreach ($tableStructure as $array) {

				if($array['Key']=='PRI') $this->primary = $array['Field'];#同时存在AUTO自增和PRI需要判断PRIMARY
			}
		}
	}

	private function sifting($fields){

		foreach ($fields as $key => $value) {

			if(!in_array($key,$this->FieldArray)) unset($fields[$key]);
		}

		return $fields;
	}

/*	public function _ONE($where,$fields='*'){

		return $this->get($this->table,$fields,$where);
	}*/

	public function ONE($mk,$fields='*'){

		return $this->get($this->table,$fields,[
			$this->primary=>$mk,
		]);
	}

	public function LIST($COND,$fields=null){

		$fields = $fields ?? $this->FieldArray ?? '*';

		if( empty($COND['ORDER']) ) $COND['ORDER']=[$this->primary=>'DESC'];

		return $this->select($this->table,$fields,array_filter($COND));
	}

	#删除指定数据
	public function DEL($gets){#
		$fields=$this->sifting($gets);

		$query = $this->delete($this->table,$fields);

		return $query->rowCount();
	}

	public function INS($fields){
		$fields=$this->sifting($fields);

		$query=$this->insert($this->table,$fields);

		$SQLSTATE=current($query->errorInfo());

		if($SQLSTATE == '00000') return $this->id();
	}

	public function SAVE($fields){
		$fields=$this->sifting($fields);

		if(array_key_exists($this->primary,$fields)) {

			$mk=$fields[$this->primary];

			unset($fields[$this->primary]);
			
		} else Wind::bug('PRIMARY_NOT_EXISTS');

		$query=$this->update($this->table,$fields,[
			$this->primary=>$mk,
		]);

		$SQLSTATE=current($query->errorInfo());

		if($SQLSTATE == '00000') return $mk;

	}
	
	#通过主键查询一个指定的字段
	public function K2V($field,$mk){

		return $this->get($this->table,$field,[
			$this->primary=>$mk
		]);
	}


	#实现select 返回 1=>aaa,2=>bbb,3=>ccc一维数组结果

	public function key2value($mk,$field){

		return $this->get($this->table,[
			$field
		],[
			$this->primary=>$mk
		])
		[$field];
	}

	#特定主键是否已经存在
	public function exist($mk){

		$has=$this->has($this->table,[
			$this->primary=>$mk,
		]);

		return $has;
	}
	#新增指定数据
	public function APPEND($fields){

		return $this->insert($this->table,$fields);
	}

	#删除指定数据
	public function REMOVE($mk){

		$success = $this->delete($this->table,[
			$this->primary=>$mk,
		]);

		return $success;
	}

	#修改指定数据
	public function MODIFY($mk,$fields){

		$success = $this->update($this->table,$fields,[
			$this->primary=>$mk,
		]);

		return $success;
	}

	public function RENEW($table,$primary,$mk,$fields){

		$has=$this->has($table,[
			$primary=>$mk,
		]);

		if($has) {
			$success=$this->update($table,$fields,[
				$primary=>$mk,
			]);
		} else {
			$fields[$primary]=$mk;

			$success=$this->insert($table,$fields);
		}

		return $success;
	}
}

/*
#public $db;
#$this->db = new medoo(require ROOT.'/database.php');

	public function total($table,$AND){

		$count=$this->count($table, $AND);

		return $count;
	}*/
