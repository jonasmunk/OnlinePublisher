<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

class ValidateUtils {

	function validateEmail($email) {
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/i",$email)>0;
	}
	
	function validateUrl($url) {
		$pattern = "/\Ahttp:\/\/[a-z0-9\-\.]+\.[a-z0-9]{2,3}\/[a-z0-9.\?&\/\#=_\-\)\(;]*\z/i";
	   $num = preg_match($pattern,$url);
	   return ($num>0);
	}
}