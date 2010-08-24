<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

class StringUtils {
	
	function isBlank($str) {
		return $str===null || strlen(trim($str))==0;
	}
	
	function isNotBlank($str) {
		return !StringUtils::isBlank($str);
	}
	
	function escapeSimpleXML($input) {
		$output=$input;
		$output=str_replace('&', '&amp;', $output);
		$output=str_replace('<', '&lt;', $output);
		$output=str_replace('>', '&gt;', $output);
		return $output;
	}

	/**
	 * Escapes special XML characters and inserts break tags
	 * @param string $input The text to escape
	 * @param string $tag The break tag to use
	 * @return string Escaped XML string with break tags
	 */
	function escapeSimpleXMLwithLineBreak($input,$tag) {
		$output=$input;
		$output=str_replace('&', '&amp;', $output);
		$output=str_replace('<', '&lt;', $output);
		$output=str_replace('>', '&gt;', $output);
		$output=str_replace("\r\n", $tag, $output);
		$output=str_replace("\r", $tag, $output);
		$output=str_replace("\n", $tag, $output);
		return $output;
	}
	
	function toUnicode($str) {
		return mb_convert_encoding($str, "UTF-8","ISO-8859-1");
	}
	
	function fromUnicode($str) {
		return mb_convert_encoding($str,"ISO-8859-1", "UTF-8");
	}
	
	function escapeXML($input) {
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
	
	function escapeXMLBreak($input,$break) {
		$output = StringUtils::escapeXML($input);
		$output = str_replace("&#13;&#10;", $break, $output);
		$output = str_replace("&#13;", $break, $output);
		$output = str_replace("&#10;", $break, $output);
		$output = str_replace("\n", $break, $output);
		return $output;
	}
	
	function insertLineBreakTags($input,$tag) {
		return str_replace(array("\r\n","\r","\n"), $tag, $input);;
	}
}