<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

class StringUtils {
	
	function isBlank($str) {
		return $str===null && strlen(trim($str))>0;
	}
}