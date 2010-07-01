<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Settings.php');

class ValidateUtil {

	function validateEmail($email) {
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$email);
	}
	
	function validateUrl($url) {
		$pattern = "/\Ahttp:\/\/[a-z0-9\-\.]+\.[a-z0-9]{2,3}\/[a-z0-9.\?&\/\#=_\-\)\(;]*\z/i";
	   $num = preg_match($pattern,$url);
	   return ($num>0);
	}
}