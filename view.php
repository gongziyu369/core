<?php namespace core;

class View{
	private $suffix='.htm';

	public function __construct($tpl){
        $appSourceFile = APP.'/'.VIEW.'/'.THEME.'/'.$tpl.$this->suffix;
        $coreSourceFile = CORE.'/view/'.$tpl.$this->suffix;

        if(is_file($appSourceFile)) $this->sourceFile=$appSourceFile;

        else if(is_file($coreSourceFile)) $this->sourceFile=$coreSourceFile;

        else exit('NO_VIEW_FILE:'.$tpl);

	    $this->cacheFile=APP.'/../cache/'.HOST.'/'.THEME."/$tpl.php";

		$dirname=dirname($this->cacheFile);

		if(!is_dir($dirname)) mkdir($dirname,0777,true);
	}

	public function checkRefresh($refresh=true){

	    if($refresh==true) {

	    	return true;#时时更新

	    } else if(@filemtime($this->sourceFile)>@filemtime($this->cacheFile) ){

	    	return true;#智能更新

	    } else {

	    	return false;#手动更新
	    }
	}

	public function parse() {
		$sourceFile=$this->sourceFile;
		$objectFile=$this->cacheFile;

		if(!@$fp = fopen($sourceFile, 'r')) exit("$sourceFile not access!");
		$parse = @fread($fp, filesize($sourceFile));
		fclose($fp);

		$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(\-\>)?[a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
		$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";

		$parse = preg_replace("/([\n\r]+)\t+/s", "\\1", $parse);
		$parse = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $parse);
		$parse = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $parse);

		$parse = preg_replace_callback("/$var_regexp/s",
			function ($matches) {
				return $this->addquote('<?='.$matches[1].'?>');
			},$parse);

		$parse = preg_replace_callback("/\<\?\=\<\?\=$var_regexp\?\>\?\>/s",
			function ($matches) {
				return $this->addquote('<?='.$matches[1].'?>');
			},$parse);

		$parse = "<? if(!defined('CORE')) exit('Access Denied'); ?>\n$parse";

		$parse = preg_replace("/[\n\r\t]*\{view\s+([a-z0-9_]+)\}[\n\r\t]*/is", "\n<? include view('\\1'); ?>\n", $parse);
		$parse = preg_replace("/[\n\r\t]*\{view\s+(.+?)\}[\n\r\t]*/is", "\n<? include view(\\1); ?>\n", $parse);

		$parse = preg_replace_callback("/[\n\r\t]*\{eval\s+(.+?)\}[\n\r\t]*/is",
			function ($matches) {
				return $this->stripvtags('<? '.$matches[1].'; ?>');
			},$parse);

		$parse = preg_replace_callback("/[\n\r\t]*\{echo\s+(.+?)\}[\n\r\t]*/is",
			function ($matches) {
				return $this->stripvtags('<? echo '.$matches[1].'; ?>');
			},$parse);

		$parse = preg_replace_callback("/([\n\r\t]*)\{if\s+(.+?)\}([\n\r\t]*)/is",
			function ($matches) {
				return $this->stripvtags($matches[1].'<? if('.$matches[2].') { ?>'.$matches[3]);
			},$parse);
		$parse = preg_replace_callback("/([\n\r\t]*)\{elseif\s+(.+?)\}([\n\r\t]*)/is",
			function ($matches) {
				return $this->stripvtags($matches[1].'<? } elseif('.$matches[2].') { ?>'.$matches[3]);
			},$parse);
		$parse = preg_replace("/\{else\}/i", "<? } else { ?>", $parse);
		$parse = preg_replace("/\{\/if\}/i", "<? } ?>", $parse);

		$parse = preg_replace_callback("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r\t]*/is",
			function ($matches) {
				return $this->stripvtags('<? if(is_array('.$matches[1].')) foreach('.$matches[1].' as '.$matches[2].') { ?>');
			},$parse);
		$parse = preg_replace_callback("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*/is",
			function ($matches) {
				return $this->stripvtags('<? if(is_array('.$matches[1].')) foreach('.$matches[1].' as '.$matches[2].' => '.$matches[3].') { ?>');
			},$parse);
		$parse = preg_replace("/\{\/loop\}/i", "<? } ?>", $parse);

		$parse = preg_replace("/\{$const_regexp\}/s", "<?=\\1?>", $parse);
		$parse = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $parse);

		if(!@$fp = fopen($objectFile, 'w')) exit("$objectFile not found or have no access!");

		$parse = preg_replace_callback("/\"(http)?[\w\.\/:]+\?[^\"]+?&[^\"]+?\"/",
			function ($matches) {
				return $this->transamp($matches[0]);
			},$parse);

		$parse = preg_replace_callback("/\<script[^\>]*?src=\"(.+?)\"(.*?)\>\s*\<\/script\>/is",
			function ($matches) {
				return $this->stripscriptamp($matches[1], $matches[2]);
			}, $parse);

		flock($fp, 2);
		fwrite($fp, $parse);
		fclose($fp);
	}

	public function transamp($str) {
		$str = str_replace('&', '&amp;', $str);
		$str = str_replace('&amp;amp;', '&amp;', $str);
		return $str;
	}

	public function addquote($var) {
		return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
	}

	public function stripvtags($expr, $statement = '') {
		$expr = str_replace('\\\"', '\"', preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
		$statement = str_replace('\\\"', '\"', $statement);
		return $expr.$statement;
	}

	public function stripscriptamp($s, $extra) {
		$s = str_replace('&amp;', '&', $s);
		return "<script src=\"$s\" type=\"text/javascript\"$extra></script>";
	}
}