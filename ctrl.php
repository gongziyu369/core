<?php namespace core;

class CTRL{
	protected $M;
	public function __construct($ctrl=''){#spl_autoload_register("\core\WE::loadMODEL");

		if($ctrl) {
			$this->ctrl=$ctrl;

			$nsModel = '\\Model\\'.ucfirst($ctrl);
			
			$this->M = new $nsModel();#dump($this->M);exit;

			$this->table=$this->M->table ?? NULL;
			$this->primary=$this->M->primary ?? NULL;
		}
	}

	public function url($array){

		if(count($array)) return '?'.http_build_query($array);
	}

	public function __call($method,$arguments){#var_dump($arguments)

		exit($method .'NO_AUTH_METHODby_ctrl_call');
	}

	public function __get($key){

		exit($key .'NOT_EXISTS_by_ctrl_get');
	}

	#public function __destruct(){}
}

/* public function newModel($ctrl){
		#$this->newModel($ctrl);
		global $M;

        #数据库模型文件加载
		$modelfile = APP."/Model/$ctrl.php";

		if(file_exists($modelfile)) require_once $modelfile;

		$modelClass="{$ctrl}Model";

		$M=new $modelClass($ctrl);

		$this->table=$M->table;

		$this->primary=$M->primary;
	}*/