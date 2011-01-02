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
?>