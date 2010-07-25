<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

class StringUtils {
	
	function isBlank($str) {
		return $str===null && strlen(trim($str))>0;
	}
	
	function escapeXML($input) {
		$output=$input;
		$output=str_replace('&', '&amp;', $output);
		$output=str_replace('<', '&lt;', $output);
		$output=str_replace('>', '&gt;', $output);
		return $output;
	}
	
	function escapeNumericXML(&$input) {
		$output = StringUtils::htmlNumericEntities($input);
		$output = str_replace('&#151;', '-', $output);
		$output = str_replace('&#146;', '&#39;', $output);
		$output = str_replace('&#147;', '&#8220;', $output);
		$output = str_replace('&#148;', '&#8221;', $output);
		$output = str_replace('&#128;', '&#243;', $output);
		$output = str_replace('"', '&quot;', $output);
		return $output;
	}

	function htmlNumericEntities(&$str){
	  return preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
	}
	
	function escapeNumericXMLBreak($input,$break) {
		$output = StringUtils::escapeNumericXML($input);
		$output = str_replace("&#13;&#10;", $break, $output);
		$output = str_replace("&#13;", $break, $output);
		$output = str_replace("&#10;", $break, $output);
		$output = str_replace("\n", $break, $output);
		return $output;
	}
}