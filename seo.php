<?php
class seo extends DB{
	public $table='node';
	public $primary='node';

	public function title(){

		$route=new route;
		$c=$route->ctrl;
		$m=$route->action;

		if($m==$c) $AND=['node'=>$c,'active[>]'=>0];

		elseif($m) $AND=['parent'=>$c,'node'=>$m,'active[>]'=>0];

		$Array=$this->get($this->table,[
			'mean'
		],[
			'AND'=>$AND,
		]);

		$SEO['titlehtml']=$SEO['title']=$Array['mean'] ? $Array['mean'] : $c.'->'.$m;

		return $SEO;
	}

	public function shopInfo($shopname){

		$SEO['titlehtml']=$SEO['title']=$shopname.' 详情';#eval("\$title=$title;");

		return $SEO;
	}

	public function shopCenter($shopname,$shopid){

		#$SEO['titlehtml']="1商家中心";
		$SEO['titlehtml']="<a href='?shop&m=shopInfo&shopid=$shopid'>$shopname</a> 商家中心";

		$SEO['title']="商家中心";

		return $SEO;
	}


}