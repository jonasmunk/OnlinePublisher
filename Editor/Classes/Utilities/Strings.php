<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Utilities
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class Strings {
	
	static function isBlank($str) {
		return $str===null || strlen(trim($str))===0;
	}
	
	static function isNotBlank($str) {
		return !Strings::isBlank($str);
	}
	
	static function escapeSimpleXML($input) {
		$output=$input;
		$output=str_replace('&', '&amp;', $output);
		$output=str_replace('<', '&lt;', $output);
		$output=str_replace('>', '&gt;', $output);
		$output=str_replace('"', '&quot;', $output);
		return $output;
	}

	/**
	 * Escapes special XML characters and inserts break tags
	 * @param string $input The text to escape
	 * @param string $tag The break tag to use
	 * @return string Escaped XML string with break tags
	 */
	static function escapeSimpleXMLwithLineBreak($input,$tag) {
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
	static function toUnicode($str) {
		return mb_convert_encoding($str, "UTF-8","ISO-8859-1");
	}*/
	
	static function toUnicode($obj) {
		if (is_string($obj)) {
			return mb_convert_encoding($obj, "UTF-8","ISO-8859-1");
		} else if (is_object($obj)) {
			foreach ($obj as $key => $value) {
				$obj->$key = Strings::toUnicode($value);
			}
		} else if (is_array($obj)) {
			foreach ($obj as $key => $value) {
				unset($obj[$key]);
				$obj[Strings::toUnicode($key)] = Strings::toUnicode($value);
			}
		}
		return $obj;
	}
	
	static function fromUnicode($obj) {
		if (is_string($obj)) {
			$str = str_replace("\xe2\x80\x9c",'"',$obj);
			$str = str_replace("\xe2\x80\x9d",'"',$str);
			$str = str_replace("\xe2\x80\x93",'-',$str);
			$str = str_replace("\xca\xbc",'\'',$str);
			$str = str_replace("\xca\xbb",'\'',$str);
			return mb_convert_encoding($str,"ISO-8859-1", "UTF-8");
		} else if (is_object($obj)) {
			foreach ($obj as $key => $value) {
				$obj->$key = Strings::fromUnicode($value);
			}
		} else if (is_array($obj)) {
			foreach ($obj as $key => $value) {
				$obj[$key] = Strings::fromUnicode($value);
			}
		}
		return $obj;
	}
	
	static function escapeXML($str) {
		if (is_float($str)) {
			$str = (string) $str;
		}
		$str = Strings::stripInvalidXml($str);
		$str = Strings::htmlNumericEntities($str);
		$str = str_replace('&#151;', '-', $str);
		$str = str_replace('&#146;', '&#39;', $str);
		$str = str_replace('&#147;', '&#8220;', $str);
		$str = str_replace('&#148;', '&#8221;', $str);
		$str = str_replace('&#128;', '&#243;', $str);
		$str = str_replace('"', '&quot;', $str);
		return $str;
	}
    
    static function escapeEncodedXML($str) {
        if (ConfigurationService::isUnicode()) {
            return Strings::escapeSimpleXML($str);
        }
        return Strings::escapeXML($str);
    }

	static function htmlNumericEntities(&$str){
	  return preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
	}
	
	static function escapeXMLBreak($input,$break) {
        $output = Strings::escapeEncodedXML($input);
        $output = str_replace("&#13;&#10;", $break, $output);
		$output = str_replace("&#13;", $break, $output);
		$output = str_replace("&#10;", $break, $output);
		$output = str_replace("\n", $break, $output);
		return $output;
	}
	
	static function escapeJavaScriptXML($input) {
		$output = Strings::escapeEncodedXML($input);
		$output = str_replace("'", "\'", $output);
		return $output;
	}
	
	static function insertLineBreakTags($input,$tag) {
		return str_replace(array("\r\n","\r","\n"), $tag, $input);
	}
	
	static function toBoolean($var) {
		return $var ? 'true' : 'false';
	}
	
	static function splitIntegers($str) {
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
	static function appendWordToString($str,$word,$separator) {
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
	
	static function buildIndex($array) {
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
	static function summarizeAndHighlight($keywords,$text) {
		$lower=mb_strtolower($text,'UTF-8');
		$positions = array();
		$out = '';
		for ($i=0;$i<count($keywords);$i++) {
			$word=mb_strtolower($keywords[$i],'UTF-8');
			$index=0;
			$endIsReached = false;
			while(!$endIsReached) {
				$pos = mb_strpos($lower, $word,$index,'UTF-8');
				if ($pos!==false) {
					$positions[$pos] = $word;
					$index=$pos+mb_strlen($word,'UTF-8');
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
						$out.='... '.Strings::escapeEncodedXML(mb_substr($text,$dist-14,14,'UTF-8'));
					}
					else {
						$out.=Strings::escapeEncodedXML(mb_substr($text,0,$dist,'UTF-8'));
					}
				}
				else {
					$middle = mb_substr($text,$lastPos,$dist,'UTF-8');
					if (mb_strlen($middle,'UTF-8')>30) {
						$out.=
						Strings::escapeEncodedXML(mb_substr($middle,0,14,'UTF-8')).
						' ... '.
						Strings::escapeEncodedXML(mb_substr($middle,strlen($middle)-14,14,'UTF-8'));
					}
					else {
						$out.=Strings::escapeEncodedXML($middle);
					}
				}
				$out.='<highlight>'.Strings::escapeEncodedXML($word).'</highlight>';
			}
			$lastPos=$pos+strlen($word);
		}
		if ((mb_strlen($text,'UTF-8')-$lastPos)>14) {
			$out.=Strings::escapeEncodedXML(mb_substr($text,$lastPos,14,'UTF-8')).' ...';
		}
		else {
			$out.=Strings::escapeEncodedXML(mb_substr($text,$lastPos,null,'UTF-8'));
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
	static function insertEmailLinks($string,$tag='a',$attr='href',$protocol='mailto:',$class='') {
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
	static function shortenString($str,$maxLength) {
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
	
	static function startsWith($str,$find) {
		return strpos($str,$find)===0;
	}
	
	static function endsWith($str,$find) {
		return strrpos($str,$find)===strlen($str)-strlen($find);
	}
	
	static function removeTags($string) {
		return preg_replace("/<[\/a-z]+[^>]*>/i", '', $string);
	}
	
	static function convertMarkupToText($string) {
		$text = preg_replace("/<[\/a-z]+[^>]*>/i", ' ', $string);
		$text = html_entity_decode($text);
		return $text;
	}
	
	static function toJSON($obj) {
		global $basePath;
		if (function_exists('json_encode')) {
			return json_encode($obj);
		}
		require_once($basePath.'Editor/Libraries/json/JSON2.php');
		$json = new Services_JSON();
		return $json->encode($obj);
	}
	
	static function fromJSON($str) {
		global $basePath;
		if (function_exists('json_decode')) {
			return json_decode($str);
		}
		require_once($basePath.'Editor/Libraries/json/JSON2.php');
		$json = new Services_JSON();
		return $json->decode($str);
	}
	
	static function toString($val) {
		if ($val===0) {
			//return '0';
		}
		return strval($val);
	}
	
	static function stripInvalidXml($value) {
		$value = Strings::toString($value);
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
	
	static function concatUrl($str1,$str2) {
		$str1 = trim(Strings::toString($str1));
		$str2 = trim(Strings::toString($str2));
		if ($str1==='' && $str2==='') {
			return '';
		}
		else if ($str1==='') {
			return $str2;
		}
		else if ($str2==='') {
			return $str1;
		}
		else if (Strings::endsWith($str1,'/') && Strings::startsWith($str2,'/')) {
			return $str1.substr($str2,1);
		}
		else if (Strings::endsWith($str1,'/') || Strings::startsWith($str2,'/')) {
			return $str1.$str2;
		}
		return $str1.'/'.$str2;		
	}
	
	static function extract($str,$start,$stop,$encoding='UTF-8') {
		$extracted = array();
		$pos = 0;
		while ($pos!==false) {
			$from = mb_strpos($str,$start,$pos,$encoding);
			if ($from===false) {
				$pos = false;
				continue;
			}
			$to = mb_strpos($str,$stop,$from+strlen($start),$encoding);
			if ($to!==false) {
				$to+=mb_strlen($stop,'UTF-8');
				$extracted[] = mb_substr($str,$from,$to-$from,$encoding);
				$pos = $to;
			} else {
				$pos = false;
			}
		}
		return $extracted;
	}
    
    static function generate($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
}