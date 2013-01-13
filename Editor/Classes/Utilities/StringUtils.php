<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class StringUtils {
	
	function isBlank($str) {
		return $str===null || strlen(trim($str))===0;
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
	/*
	function toUnicode($str) {
		return mb_convert_encoding($str, "UTF-8","ISO-8859-1");
	}*/
	
	function toUnicode($obj) {
		if (is_string($obj)) {
			return mb_convert_encoding($obj, "UTF-8","ISO-8859-1");
		} else if (is_object($obj)) {
			foreach ($obj as $key => $value) {
				$obj->$key = StringUtils::toUnicode($value);
			}
		} else if (is_array($obj)) {
			foreach ($obj as $key => $value) {
				unset($obj[$key]);
				$obj[StringUtils::toUnicode($key)] = StringUtils::toUnicode($value);
			}
		}
		return $obj;
	}
	
	function fromUnicode($obj) {
		if (is_string($obj)) {
			$str = str_replace("\xe2\x80\x9c",'"',$obj);
			$str = str_replace("\xe2\x80\x9d",'"',$str);
			$str = str_replace("\xe2\x80\x93",'-',$str);
			$str = str_replace("\xca\xbc",'\'',$str);
			$str = str_replace("\xca\xbb",'\'',$str);
			return mb_convert_encoding($str,"ISO-8859-1", "UTF-8");
		} else if (is_object($obj)) {
			foreach ($obj as $key => $value) {
				$obj->$key = StringUtils::fromUnicode($value);
			}
		} else if (is_array($obj)) {
			foreach ($obj as $key => $value) {
				$obj[$key] = StringUtils::fromUnicode($value);
			}
		}
		return $obj;
	}
	
	function escapeXML($str) {
		if (is_float($str)) {
			$str = (string) $str;
		}
		$str = StringUtils::stripInvalidXml($str);
		$str = StringUtils::htmlNumericEntities($str);
		$str = str_replace('&#151;', '-', $str);
		$str = str_replace('&#146;', '&#39;', $str);
		$str = str_replace('&#147;', '&#8220;', $str);
		$str = str_replace('&#148;', '&#8221;', $str);
		$str = str_replace('&#128;', '&#243;', $str);
		$str = str_replace('"', '&quot;', $str);
		return $str;
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
	
	function escapeJavaScriptXML($input) {
		$output = StringUtils::escapeXML($input);
		$output = str_replace("'", "\'", $output);
		return $output;
	}
	
	function insertLineBreakTags($input,$tag) {
		return str_replace(array("\r\n","\r","\n"), $tag, $input);
	}
	
	function toBoolean($var) {
		return $var ? 'true' : 'false';
	}
	
	function splitIntegers($str) {
		$arr = array();
		$parts = preg_split('/\\,/',$str);
		foreach ($parts as $part) {
			$part = trim($part);
			if (is_numeric($part)) {
				$arr[] = intval($part);
			}
		}
		return $arr;
	}
	
	/**
	 * Appends a word to a string using a separator if neither are empty
	 * @param string $str The text to append to
	 * @param string $word The word to append
	 * @param string $separator The separator to use (may be more than 1 char)
	 * @return string The resulting text
	 */
	function appendWordToString($str,$word,$separator) {
		if (strlen($word)==0) {
			return $str;
		}
		else if (strlen($str)>0) {
			return $str.$separator.$word;
		}
		else {
			return $word;
		}
	}
	
	function buildIndex($array) {
		$str = '';
		if (is_array($array)) {
			foreach ($array as $value) {
				$trimmed = trim($value);
				if (strlen($str)>0 && strlen($trimmed)>0) {
					$str.=' ';
				}
				$str.=$trimmed;
			}
		}
		return $str;
	}
	
	
	/**
	 * Creates a summary of a text based on some keywords.
	 * Keywords will be enclosed in <highlight> tags.
	 * @param array $keywords Array of keywords to highlight
	 * @param string $text The text to analyze
	 * @return string A highlighted summary of the text
	 */
	function summarizeAndHighlight($keywords,$text) {
		$lower=strtolower($text);
		$positions = array();
		$out = '';
		for ($i=0;$i<count($keywords);$i++) {
			$word=strtolower($keywords[$i]);
			$index=0;
			$endIsReached = false;
			while(!$endIsReached) {
				$pos = strpos($lower, $word,$index);
				if ($pos!==false) {
					$positions[$pos] = $word;
					$index=$pos+strlen($word);
				}
				else {
					$endIsReached = true;
				}
			}
		}
		ksort($positions);
		$lastPos=0;
		foreach ($positions as $pos => $word) {
			if ($pos>=$lastPos) {
				$dist = $pos-$lastPos;
				if ($lastPos==0) {
					if ($dist>17) {
						$out.='... '.StringUtils::escapeXML(substr($text,$dist-14,14));
					}
					else {
						$out.=StringUtils::escapeXML(substr($text,0,$dist));
					}
				}
				else {
					$middle = substr($text,$lastPos,$dist);
					if (strlen($middle)>30) {
						$out.=
						StringUtils::escapeXML(substr($middle,0,14)).
						' ... '.
						StringUtils::escapeXML(substr($middle,strlen($middle)-14,14));
					}
					else {
						$out.=StringUtils::escapeXML($middle);
					}
				}
				$out.='<highlight>'.StringUtils::escapeXML($word).'</highlight>';
			}
			$lastPos=$pos+strlen($word);
		}
		if ((strlen($text)-$lastPos)>14) {
			$out.=StringUtils::escapeXML(substr($text,$lastPos,14)).' ...';
		}
		else {
			$out.=StringUtils::escapeXML(substr($text,$lastPos));
		}
		return $out;
	}

	/**
	 * Converts email addresses of a text to links
	 * @param string $string The text to analyze
	 * @param string $tag The name of the tag to insert, fx: a
	 * @param string $attr The name of attribute to use, fx: href
	 * @param string $protocol The protocol prefix to use, fx: mailto: og "nothing"
	 * @return string The text with inserted email links
	 */
	function insertEmailLinks($string,$tag='a',$attr='href',$protocol='mailto:',$class='') {
		$pattern = "/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.([a-zA-Z]{2,4}))\b/i";
		$replacement = '<'.$tag.' '.$attr.'="'.$protocol.'${1}"'.($class!='' ? ' class="'.$class.'"' : '').'>${1}</'.$tag.'>';
		return preg_replace($pattern, $replacement, $string);
	}

	/**
	 * Shortens a string if it is too long
	 * Note: does not guarantee the resulting length
	 * @param string $str The string to shorten
	 * @param int $maxLength The maximum length before shortening occurs
	 * @return string The shortened string
	 */
	function shortenString($str,$maxLength) {
		if (strlen($str)>$maxLength) {
			$half = floor($maxLength/2);
			$first = substr($str,0,$half);
			$last = substr($str,strlen($str)-$half);
			return $first.' ... '.$last;
		}
		else {
			return (string) $str;
		}
	}
	
	function startsWith($str,$find) {
		return strpos($str,$find)===0;
	}
	
	function endsWith($str,$find) {
		return strrpos($str,$find)===strlen($str)-strlen($find);
	}
	
	function removeTags($string) {
		return preg_replace("/<[\/a-z]+[^>]*>/i", '', $string);
	}
	
	function convertMarkupToText($string) {
		$text = preg_replace("/<[\/a-z]+[^>]*>/i", ' ', $string);
		$text = html_entity_decode($text);
		return $text;
	}
	
	function toJSON($obj) {
		global $basePath;
		if (function_exists('json_encode')) {
			return json_encode($obj);
		}
		require_once($basePath.'Editor/Libraries/json/JSON2.php');
		$json = new Services_JSON();
		return $json->encode($obj);
	}
	
	function fromJSON($str) {
		global $basePath;
		if (function_exists('json_decode')) {
			return json_decode($str);
		}
		require_once($basePath.'Editor/Libraries/json/JSON2.php');
		$json = new Services_JSON();
		return $json->decode($str);
	}
	
	function toString($val) {
		if ($val===0) {
			//return '0';
		}
		return strval($val);
	}
	
	function stripInvalidXml($value) {
		$value = StringUtils::toString($value);
	    $ret = ""; 
	    $length = strlen($value);
	    for ($i=0; $i < $length; $i++)
	    {
	        $current = ord($value{$i});
	        if (($current == 0x9) ||
	            ($current == 0xA) ||
	            ($current == 0xD) ||
	            (($current >= 0x20) && ($current <= 0xD7FF)) ||
	            (($current >= 0xE000) && ($current <= 0xFFFD)) ||
	            (($current >= 0x10000) && ($current <= 0x10FFFF)))
	        {
	            $ret .= chr($current);
	        }
	        else
	        {
	            $ret .= " ";
	        }
	    }
	    return $ret;
	}
	
	function concatUrl($str1,$str2) {
		$str1 = trim(StringUtils::toString($str1));
		$str2 = trim(StringUtils::toString($str2));
		if ($str1==='' && $str2==='') {
			return '';
		}
		else if ($str1==='') {
			return $str2;
		}
		else if ($str2==='') {
			return $str1;
		}
		else if (StringUtils::endsWith($str1,'/') && StringUtils::startsWith($str2,'/')) {
			return $str1.substr($str2,1);
		}
		else if (StringUtils::endsWith($str1,'/') || StringUtils::startsWith($str2,'/')) {
			return $str1.$str2;
		}
		return $str1.'/'.$str2;		
	}
	
	function extract($str,$start,$stop) {
		$extracted = array();
		$pos = 0;
		while ($pos!==false) {
			$from = strpos($str,$start,$pos);
			if ($from===false) {
				$pos = false;
				continue;
			}
			$to = strpos($str,$stop,$from+strlen($start));
			if ($to!==false) {
				$to+=strlen($stop);
				//Log::debug('From '.$from.' to '.$to);
				$extracted[] = substr($str,$from,$to-$from);
				$pos = $to;
			} else {
				$pos = false;
			}
		}
		return $extracted;
	}
}