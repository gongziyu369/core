<?php namespace core;

class Link{
	static public $PAGE;
	static public $ORDER;
	static public $SORT;
	CONST LEFTRIGH=4;
	CONST PAGESIZE=10;

	public function __construct($orderby=null,$sortby='DESC'){

		self::$PAGE=$_GET['page'] ?? 1;
        self::$ORDER=$_GET['order'] ?? $orderby;
        self::$SORT=$_GET['sort'] ?? $sortby;

		unset($_GET['page']);

		$_GET = array_filter($_GET);
	}

	public function COND($typeConfig=null){
		$this->typeConfig = $typeConfig;

		$COND['ORDER'] = $this->ORDER();

		$COND['LIMIT'] = $this->LIMIT();

		$COND['AND']= $this->AND();

		return $COND;#array_filter
	}

	public function ORDER(){

        if(self::$ORDER && self::$SORT) return [self::$ORDER=>self::$SORT];
	}

	public function LIMIT(){
		$page=self::$PAGE;
		$pagesize=self::PAGESIZE;

		$keystart=($page-1)*$pagesize;//$rightsize=($page+1)*$pagesize;

		return [$keystart,$pagesize];
	}

	public function AND(){

		if(isset($_GET['searchkeyword'])) return $this->search();

		else return $_GET;
	}

	public function search(){#$typeConfig=current(func_get_args());

		$searchkeyword=$_GET['searchkeyword'] ?? NULL;

		unset($_GET['searchkeyword']);

		$type=$this->getType($searchkeyword);

		if($type) {

			if(array_key_exists($type,$this->typeConfig)) {

				foreach($this->typeConfig[$type] as $field){
					$Search['OR'][$field]=$searchkeyword;
				}

			} else Wind::bug('TYPECONFIG_EMPTY');

		} else Wind::bug('SEARCHKEYWORD_TYPE_EMPTY');
		#dump($Search);exit;

		return $Search;
	}

	#判断查询字段
	public function getType($searchkeyword){

	    if(preg_match("/^\d{11}$/", $searchkeyword)) $type="sj";

	    else if(preg_match("/^\d{1,10}$/", $searchkeyword)) $type="id";
	    
		else if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $searchkeyword)) $type='cn';

		#else Go($this->Link['base']);

	    return $type ?? '';
	}


	public function PAGING($count) {
		$page=self::$PAGE;
		$pagesize=self::PAGESIZE;

		if($count==0) return;

		$PAGING['count']=$count;
		$PAGING['current']=(int)$page;
		$PAGING['pagesize']=$pagesize;

		$PAGING['pagecount']=(int)ceil($count/$pagesize);#总页面数

		$PAGING['prepage']=$page-1;
		$PAGING['nextpage']=$page+1;
		if($page>$PAGING['pagecount'] || $page<=0) show("不存在的页数");

		$leftright=self::LEFTRIGH;#左右列表跨度
		$left=$page-$leftright >0 ? $page-$leftright : 1;
		$right=$page+$leftright < $PAGING['pagecount'] ? $page+$leftright : $PAGING['pagecount'];
		for($i=$left;$i<=$right;$i++) $PAGING['pagelist'][]=$i;#dump($PAGING);exit;

		return $PAGING;
	}

	public function URL(){
		$ctrl=Route::$ctrl;
		$method=Route::$method;

		$Url['base'] = $ctrl==$method ? "?$ctrl" : "?$ctrl=$method";
		$Url['page'] = $Url['base'].$this->query($_GET);#dump($Url);exit;

		unset($_GET['order']);
		unset($_GET['sort']);

		$argList=func_get_args();
		
		foreach ($argList as $arg) {

			$_array = $_GET;

			$_array['order'] = $arg;

			if(self::$ORDER == $arg) $_array['sort'] = $this->change(self::$SORT);

			$Url[$arg] = $Url['base'].$this->query($_array);
		}

		return $Url;
	}

	public function query($_array){

		if(count($_array)) {

			$string = '&'.http_build_query($_array);

			return $string;
		}
	}

	public function change($_sort='DESC'){#反转当前字段排序方式$array=['ASC','DESC'];

		if($_sort=='DESC') $_sort='ASC';

		else if($_sort=='ASC') $_sort='DESC';

		return $_sort;
	}
}


/*
$sort=$sort?$sort:'DESC';
if($orderby) $order="ORDER BY $orderby $sort";
else $order="ORDER BY `$primary` $sort";
#LIMIT语句
if($limit) $limit="LIMIT $limit";


	public function _set($Array){

		foreach ($Array as $key => $value) $other[]=$key.'='.$value;

		if(isset($other)) $string='&'.implode('&', $other);

		return $this->Link['base'].@$string;
	}

http_build_query() 使用对象

class myClass { 
   var $foo; 
   var $baz; 
   function myClass() { 
    $this->foo = 'bar'; 
    $this->baz = 'boom'; 
   } 
} 
$data = new myClass(); 
echo http_build_query($data); 
输出： foo=bar&baz=boom 

#$url = 'http://username:password@hostname/path?arg=value&b=222#anchor';dump(parse_url($url),PHP_URL_PATH);


		#$query=parse_url(REFERER,PHP_URL_QUERY);dump($query);exit;
		#parse_str
/*$str = "db=mysql&pro[]=java+tutorial&pro[]=php+tutorial";
parse_str($str);
echo $db."<br/>";
echo $pro[0]."<br/>";
echo $pro[1]."<br/>";*/


























/*
class Link{
	static public $Link;

	public function __construct(){
		$ctrl=$GLOBALS['ctrl'];
		$method=$GLOBALS['method'];

		self::$Link['base'] = ($ctrl==$method) ? "?$ctrl" : "?$ctrl=$method";
		self::setlink('page');

		#$Link['order']=
		
		foreach (array_keys($_GET) as $key) {
			self::setlink($key);
		}#dump(self::$Link);exit;
	}

	static public function setlink($wipe){

		foreach ($_GET as $key => $value){
			if($key != $wipe) $other[]=$key.'='.$value;
		}

		if(isset($other)) $string='&'.implode('&', $other);

		self::$Link[$wipe]=self::$Link['base'].@$string;
	}
}*/