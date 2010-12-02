<?php
/**
 * General purpose functions that are likely to be used alot
 *
 * @package OnlinePublisher
 * @subpackage Include
 */
require_once $basePath.'Editor/Classes/Database.php';

	
/**
 * Starts a session in the appropriate way, should be used instead
 * of calling session_start() directly
 */
function startSession() {
	session_set_cookie_params(0);
	session_start();
}

/**
 * Redirects to an URL and exits, should be used instead of setting headers directly
 * @param string $url The url to be redirected to
 */
function redirect($url) {
	session_write_close();
	header('Location: '.$url);
	exit;
}

///////////////////////// Strings /////////////////////////


function encodeXML(&$input) {
	$output = preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $input);
	$output = str_replace('&#151;', '-', $output);
	$output = str_replace('&#146;', '&#39;', $output);
	$output = str_replace('&#147;', '&#8220;', $output);
	$output = str_replace('&#148;', '&#8221;', $output);
	$output = str_replace('&#128;', '&#243;', $output);
	$output = str_replace('&#128;', '&#243;', $output);
	$output=str_replace('"', '&quot;', $output);
	return $output;
}

///////////////////// Formula parsing /////////////////////

/**
 * Gets a text string variable passed thru the post protocol
 * @param string $key The name of the variable
 * @return string The value of the variable, '' if variable not set
 */
function requestPostText($key) {
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

/**
 * Gets a date variable passed thru the post protocol as an XmlWebGui date
 * @param string $key The name of the variable
 * @return string The value of the variable, '' if variable not set
 */
function requestPostDate($key) {
	if (isset($_POST[$key])) {
		$d=$_POST[$key];
		return mktime(0,0,0,substr($d,4,2),substr($d,6,2),substr($d,0,4));
	}
	else {
		return '';
	}
}

/**
 * Gets a date+time variable passed thru the post protocol as a XmlWebGui datetime
 * @param string $key The name of the variable
 * @return string The value of the variable, '' if variable not set
 */
function requestPostDateTime($key) {
	if (isset($_POST[$key])) {
		$d=$_POST[$key];
		return mktime(substr($d,8,2),substr($d,10,2),substr($d,12,2),substr($d,4,2),substr($d,6,2),substr($d,0,4));
	}
	else {
		return '';
	}
}

/**
 * Gets a integer variable passed thru the post protocol
 * @param string $key The name of the variable
 * @param string $default Optional: The value to return if the variable is not set
 * or not a number. Defaults to 0.
 * @return int The value of the variable, $default if variable not set or not numeric
 */
function requestPostNumber($key,$default=0) {
	if (isset($_POST[$key]) && is_numeric($_POST[$key])) {
		return intval($_POST[$key]);
	}
	else {
		return $default;
	}
}

/**
 * Gets a floating point variable passed thru the post protocol
 * @param string $key The name of the variable
 * @param string $default Optional: The value to return if the variable is not set
 * or not a number. Defaults to 0.
 * @return float The value of the variable, $default if variable not set or not numeric
 */
function requestPostFloat($key,$default=0) {
	if (isset($_POST[$key]) && is_numeric($_POST[$key])) {
		return floatval($_POST[$key]);
	}
	else {
		return $default;
	}
}

/**
 * Gets the value of a checkbox passed thru the post protocol
 * @param string $key The name of the checkbox
 * @return boolean True if the checkbox was checked, false otherwise
 */
function requestPostCheckbox($key) {
	if (isset($_POST[$key]) && $_POST[$key]=='on') {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Gets the array value of a variable passed thru the post protocol
 * @param string $key The name of the variable
 * @return array the array value of the variable,
 *         an empty array if variable is not an array
 */
function requestPostArray($key) {
	if (isset($_POST[$key]) && is_array($_POST[$key])) {
		return $_POST[$key];
	}
	else {
		return array();
	}
}

/**
 * Checks if a variable was passed thru the post protocol
 * @param string $key The name of the variable
 * @return boolean True if variable was set, False otherwise
 */
function requestPostExists($key) {
	return isset($_POST[$key]);
}

/**
 * Checks whether the request was made using the post protocol
 * @return boolean True if post protocol, false otherwise
 */
function requestPost() {
	if ($_POST) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Gets the boolean value of a variable passed thru the get protocol
 * @param string $key The name of the variable
 * @return boolean True if the value equals "true", false otherwise
 */
function requestPostBoolean($key) {
	$output=false;
	if (isset($_POST[$key])) {
		if ($_POST[$key]=='true') {
			$output=true;
		}
	}
	return $output;
}

////////////////// Query string parsing ///////////////////

/**
 * Checks if a variable was passed thru the get protocol
 * @param string $key The name of the variable
 * @return boolean True if variable was set, False otherwise
 */
function requestGetExists($key) {
	return isset($_GET[$key]);
}

/**
 * Gets a text string variable passed thru the get protocol
 * @param string $key The name of the variable
 * @return string The value of the variable, '' if variable not set
 */
function requestGetText($key) {
	if (isset($_GET[$key])) {
		$output=$_GET[$key];
		$output=str_replace('\\"', '"', $output);
		$output=str_replace('\\\'', '\'', $output);
		$output=str_replace('\\\\', '\\', $output);
		return $output;
	}
	else {
		return '';
	}
}

/**
 * Gets a date variable passed thru the get protocol as an XmlWebGui date
 * @param string $key The name of the variable
 * @return string The value of the variable, '' if variable not set
 */
function requestGetDate($key) {
	if (isset($_GET[$key])) {
		$d=$_GET[$key];
		return mktime(0,0,0,substr($d,4,2),substr($d,6,2),substr($d,0,4));
	}
	else {
		return '';
	}
}

/**
 * Gets the boolean value of a variable passed thru the get protocol
 * @param string $key The name of the variable
 * @return boolean True if the value equals "true", false otherwise
 */
function requestGetBoolean($key) {
	$output=false;
	if (isset($_GET[$key])) {
		if ($_GET[$key]=='true') {
			$output=true;
		}
	}
	return $output;
}

/**
 * Gets a integer variable passed thru the get protocol
 * @param string $key The name of the variable
 * @param string $default Optional: The value to return if the variable is not set
 * or not a number. Defaults to 0.
 * @return int The value of the variable, $default if variable not set or not numeric
 */
function requestGetNumber($key,$default=0) {
	if (isset($_GET[$key]) && is_numeric($_GET[$key])) {
		return intval($_GET[$key]);
	}
	else {
		return $default;
	}
}


/**
 * Gets a text string variable passed thru the get or post protocol
 * @param string $key The name of the variable
 * @return string The value of the variable, '' if variable not set
 */
function requestText($key) {
	if (isset($_REQUEST[$key])) {
		$output=$_REQUEST[$key];
		$output=str_replace('\\"', '"', $output);
		$output=str_replace('\\\'', '\'', $output);
		$output=str_replace('\\\\', '\\', $output);
		return $output;
	}
	else {
		return '';
	}
}
?>