<?php
#class MVC{

	 function Ctrl($c,$m) {#构成控制器文件路径

		$ctrlfile = HOME."/Ctrl/$c.php";

		if(file_exists($ctrlfile)) require_once $ctrlfile;

		else show("不存在的Ctrl文件{$ctrlfile}","/");

		$className=$c.'Ctrl';

		$class=new $className;

		$class->$m();
	}

	 function Model($c) {

		$modelfile = HOME."/Model/$c.php";

		if(file_exists($modelfile)) require_once $modelfile;

		else show("不存在的Model文件{$model}","/");

		#$className=$class.'Model';

		$class=new $c;

		return $class;
	}


	function View($theFile) {#$scriptPath=substr($scriptFileName,0,strrpos($scriptFileName,'/'));

		$cacheFile=CORE.'/Cache/'.HOST."_{$theFile}.php";
		$sourceFile=HOME."/View/$theFile.htm";
		$sourceFile=file_exists($sourceFile) ? $sourceFile : CORE."/View/$theFile.htm";

		if(!is_file($sourceFile)) show("404 View Not Found.",'/');

		#if(@filemtime($sourceFile)>@filemtime($cacheFile)) {

			require_once CORE.'/viewParse.php';

			viewParse($sourceFile, $cacheFile);
		#}

		return $cacheFile;
	}

	 function View222($class) {

		require_once ROOT."/View/$class.php";

		$classView=$class.'View';

		$object=new $classView;

		return $object;

		#include view('agent');
	}

#}


class autoloader {    
    public static $loader;    
        
    public static function init() {    
        if (self::$loader == NULL)    
            self::$loader = new self ();    
            
        return self::$loader;    
    }    
        
    public function __construct() {    
        spl_autoload_register ( array ($this, 'model' ) );    
        spl_autoload_register ( array ($this, 'helper' ) );    
        spl_autoload_register ( array ($this, 'controller' ) );    
        spl_autoload_register ( array ($this, 'library' ) );    
    }    
        
    public function library($class) {    
        set_include_path ( get_include_path () . PATH_SEPARATOR . '/lib/' );    
        spl_autoload_extensions ( '.library.php' );    
        spl_autoload ( $class );    
    }    
        
    public function controller($class) {    
        $class = preg_replace ( '/_controller$/ui', '', $class );    
            
        set_include_path ( get_include_path () . PATH_SEPARATOR . '/controller/' );    
        spl_autoload_extensions ( '.controller.php' );    
        spl_autoload ( $class );    
    }    
        
    public function model($class) {    
        $class = preg_replace ( '/_model$/ui', '', $class );    
            
        set_include_path ( get_include_path () . PATH_SEPARATOR . '/model/' );    
        spl_autoload_extensions ( '.model.php' );    
        spl_autoload ( $class );    
    }    
        
    public function helper($class) {    
        $class = preg_replace ( '/_helper$/ui', '', $class );    
            
        set_include_path ( get_include_path () . PATH_SEPARATOR . '/helper/' );    
        spl_autoload_extensions ( '.helper.php' );    
        spl_autoload ( $class );    
    }    
   
}    
   
//call    
autoloader::init (); 