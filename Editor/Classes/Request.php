<?
class Request {

	/**
	 * Checks if a variable was passed thru the get or post protocol
	 * @param string $key The name of the variable
	 * @return boolean True if variable was set, False otherwise
	 */
	function exists($key) {
		return isset($_REQUEST[$key]);
	}

	/**
	 * Gets a text string variable passed thru the get protocol
	 * @param string $key The name of the variable
	 * @return string The value of the variable, '' if variable not set
	 */
	function getString($key) {
		if (isset($_POST[$key])) {
			$output=$_POST[$key];
		} else if (isset($_GET[$key])) {
			$output=$_GET[$key];
		} else {
			return '';
		}
		$output=str_replace('\\"', '"', $output);
		$output=str_replace('\\\'', '\'', $output);
		$output=str_replace('\\\\', '\\', $output);
		return $output;
	}

	/**
	 * Gets the value of a checkbox
	 * @param string $key The name of the checkbox
	 * @return boolean True if the checkbox was checked, false otherwise
	 */
	function getCheckbox($key) {
		return Request::getString($key)=='on';
	}

	/**
	 * Gets a string and converts it to ISO-8859-1 if it is in Unicode
	 */
	function getEncodedString($key) {
		$value = Request::getString($key);
		if (strpos($_SERVER['CONTENT_TYPE'],'UTF-8')!==false) {
			return mb_convert_encoding($value, "ISO-8859-1","UTF-8");
		}
		return $value;
	}
	
	function getUnicodeString($key) {
		$value = Request::getString($key);
		return mb_convert_encoding($value, "ISO-8859-1","UTF-8");
	}

	/**
	 * Gets a integer variable passed thru the GET or POST protocol
	 * @param string $key The name of the variable
	 * @param string $default Optional: The value to return if the variable is not set
	 * or not a number. Defaults to 0.
	 * @return int The value of the variable, $default if variable not set or not numeric
	 */
	function getInt($key,$default=0) {
		if (isset($_POST[$key]) && is_numeric($_POST[$key])) {
			return intval($_POST[$key]);
		} else if (isset($_GET[$key]) && is_numeric($_GET[$key])) {
			return intval($_GET[$key]);
		} else {
			return $default;
		}
	}

	/**
	 * Gets a float variable passed thru the GET or POST protocol
	 * @param string $key The name of the variable
	 * @param string $default Optional: The value to return if the variable is not set
	 * or not a number. Defaults to 0.
	 * @return int The value of the variable, $default if variable not set or not numeric
	 */
	function getFloat($key,$default=0) {
		if (isset($_POST[$key]) && is_numeric($_POST[$key])) {
			return floatval($_POST[$key]);
		} else if (isset($_GET[$key]) && is_numeric($_GET[$key])) {
			return floatval($_GET[$key]);
		} else {
			return $default;
		}
	}
	
	/**
	 * Gets an int with name id;
	 */
	function getId() {
		return Request::getInt('id');
	}
	
	/**
	 * Gets "1,9,3" as array(1,9,3)
	 */
	function getIntArrayComma($key) {
		$str = Request::getString($key);
		$out = array();
		$parts = explode(",",$str);
		foreach ($parts as $part) {
			if (is_numeric($part)) {
				$out[] = intval($part);
			}
		}
		return $out;
	}

	/**
	 * Gets the boolean value of a variable passed thru the get protocol
	 * @param string $key The name of the variable
	 * @return boolean True if the value equals "true", false otherwise
	 */
	function getBoolean($key) {
		if (isset($_GET[$key])) {
			if ($_GET[$key]=='true') {
				return true;
			}
		} else if (isset($_POST[$key])) {
			if ($_POST[$key]=='true') {
				return true;
			}
		}
		return false;
	}

	/**
	 * Gets a date variable passed thru the get protocol as an XmlWebGui date
	 * @param string $key The name of the variable
	 * @return string The value of the variable, '' if variable not set
	 */
	function getDate($key) {
		if (isset($_GET[$key])) {
			$d=$_GET[$key];
			return mktime(0,0,0,substr($d,4,2),substr($d,6,2),substr($d,0,4));
		}
		else if (isset($_POST[$key])) {
			$d=$_POST[$key];
			return mktime(0,0,0,substr($d,4,2),substr($d,6,2),substr($d,0,4));
		}
		else {
			return null;
		}
	}

	/**
	 * Gets a date+time variable passed thru the post protocol as a XmlWebGui datetime
	 * @param string $key The name of the variable
	 * @return string The value of the variable, '' if variable not set
	 */
	function getDateTime($key) {
		if (isset($_POST[$key])) {
			$d=$_POST[$key];
			return mktime(substr($d,8,2),substr($d,10,2),substr($d,12,2),substr($d,4,2),substr($d,6,2),substr($d,0,4));
		}
		else {
			return '';
		}
	}

	function getPostDateInFormat($key,$format) {
		$value = Request::getPostString($key);
		$parsed = strptime($value, $format);
		$stamp = mktime ( $parsed['tm_hour'] , $parsed['tm_min'] , $parsed['tm_sec'], $parsed['tm_mon']+1, $parsed['tm_mday'], $parsed['tm_year'] );
		error_log($value);
		error_log(print_r($parsed,true));
		error_log(strftime($format, $stamp));
		return $stamp;
	}


	/**
	 * Gets a integer variable passed thru the POST protocol
	 * @param string $key The name of the variable
	 * @param string $default Optional: The value to return if the variable is not set
	 * or not a number. Defaults to 0.
	 * @return int The value of the variable, $default if variable not set or not numeric
	 */
	function getPostInt($key,$default=0) {
		if (isset($_POST[$key]) && is_numeric($_POST[$key])) {
			return intval($_POST[$key]);
		}
		else {
			return $default;
		}
	}
	
	function getObject($key) {
		global $basePath;
		require_once($basePath.'Editor/Libraries/json/JSON2.php');
		$json = new Services_JSON();
		//return $json->decode(Request::getString($key));
		return json_decode(Request::getString($key));
	}
	
	function getUnicodeObject($key) {
		$obj = Request::getObject($key);
		if ($obj!==null) {
			foreach ($obj as $key => $value) {
				if (is_string($value)) {
					$obj->$key = Request::fromUnicode($value);
				}
			}
		}
		return $obj;
	}

	/**
	 * Gets a text string variable passed thru the get protocol
	 * @param string $key The name of the variable
	 * @return string The value of the variable, '' if variable not set
	 */
	function getPostString($key) {
		if (isset($_POST[$key])) {
			$output=$_POST[$key];
			$output=str_replace('\\"', '"', $output);
			$output=str_replace('\\\'', '\'', $output);
			$output=str_replace('\\\\', '\\', $output);
			return $output;
		}
		else {
			return '';
		}
	}
	
	function isPost() {
		return $_SERVER['REQUEST_METHOD']=='POST';
	}

	/**
	 * Gets the array value of a variable passed thru the post protocol
	 * @param string $key The name of the variable
	 * @return array the array value of the variable,
	 *         an empty array if variable is not an array
	 */
	function getPostArray($key) {
		if (isset($_POST[$key]) && is_array($_POST[$key])) {
			return $_POST[$key];
		}
		else {
			return array();
		}
	}
	
	function toUnicode($value) {
		return mb_convert_encoding($value, "UTF-8","ISO-8859-1");
	}
	
	function fromUnicode($value) {
		return mb_convert_encoding($value,"ISO-8859-1", "UTF-8");
	}
}
?>