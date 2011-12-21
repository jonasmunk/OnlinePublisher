<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class ValidateUtils {

	function validateEmail($email) {
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/i",$email)>0;
	}

	function validateHref($url) {
		if ($url[0]=='#') {
			return true;
		}
		return ValidateUtils::validateUrl($url);
	}
	
	function validateUrl($url) {
		$pattern = "/\Ahttp[s]?:\/\/[a-z0-9\-\.]+\.[a-z0-9]{2,3}[\/]?[a-z0-9.\?&\/\#=_\-\%)\(;,+]*\z/i";
		$num = preg_match($pattern,$url);
		return ($num>0);
	}
	
	function validateDigits($str=null) {
		if ($str===null) {return false;}
		$str = strval($str);
		$pattern = "/[0-9]+/";
	   	$num = preg_match($pattern,$str,$matches);
		return @$matches[0]===$str;
	}
}