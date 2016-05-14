<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class StringBuilder {
	
	var $str = '';
	var $separator = null;
	
	function StringBuilder($str='') {
		$this->str = $str;
	}
		
	function append($str) {
		if (!$this) {
			return new StringBuilder($str);
		}
		if (strlen($str)==0) {
			return $this;
		}
		if ($this->separator!==null && strlen($this->str)>0) {
			$this->str.=$this->separator;
		}
		$this->separator = null;
		$this->str.=$str;
		return $this;
	}
	
	function separator($sep) {
		$this->separator = $sep;
		return $this;
	}
	
	function toString() {
		return $this->str;
	}
}