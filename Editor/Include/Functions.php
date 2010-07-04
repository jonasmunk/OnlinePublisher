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

//////////////////// XML /////////////////////

/**
 * Retrieves the text of the first node of an XPath query on a DOM document
 * @param object $doc The document to search in
 * @param string $xpath The xpath expression to evaluate
 * @return string The content of the first found node
 */
function getDomXpathText($doc,$xpath) {
	if ($node =& $doc->selectNodes($xpath, 1)) {
		return $node->getText();
	}
	else {
		return null;
	}
}

/**
 * Transforms some XML data using some XSLT data
 * @param string $xmlData The XML to transform
 * @param string $xslData The XSLT to use
 * @return string The result of the transformation
 */
function transformXml($xmlData,$xslData) {
	if (function_exists('xslt_create')) {
		$arguments = array('/_xml' => $xmlData,'/_xsl' => $xslData);
		$xp = xslt_create();
		$result = xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
		xslt_free($xp);
	}
	else {
		$xslt = new xsltProcessor;
		$xslt->importStyleSheet(DomDocument::loadXML($xslData));
		$result = $xslt->transformToXML(DomDocument::loadXML($xmlData));
	}
	return $result;
}

///////////////////// Compatibility ///////////////////////

/**
 * Checks whether the browser is Gecko based
 * @return boolean True if browser is Gecko based, false otherwise
 */
function browserIsGecko() {
	$agent=strtolower($_SERVER['HTTP_USER_AGENT']);
	$pos = strpos($agent, 'gecko');
	if ($pos === false) {
		return false;
	} else {
		return true;
	}
}

///////////////////////// Strings /////////////////////////

/**
 * Creates a summary of a text based on some keywords.
 * Keywords will be enclosed in <highlight> tags.
 * @param array $keywords Array of keywords to highlight
 * @param string $text The text to analyze
 * @return string A highlighted summary of the text
 */
function summarizeAndHighligt($keywords,$text) {
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
					$out.='... '.encodeXML(substr($text,$dist-14,14));
				}
				else {
					$out.=encodeXML(substr($text,0,$dist));
				}
			}
			else {
				$middle = substr($text,$lastPos,$dist);
				if (strlen($middle)>30) {
					$out.=
					encodeXML(substr($middle,0,14)).
					' ... '.
					encodeXML(substr($middle,strlen($middle)-14,14));
				}
				else {
					$out.=encodeXML($middle);
				}
			}
			$out.='<highlight>'.encodeXML($word).'</highlight>';
		}
		$lastPos=$pos+strlen($word);
	}
	if ((strlen($text)-$lastPos)>14) {
		$out.=encodeXML(substr($text,$lastPos,14)).' ...';
	}
	else {
		$out.=encodeXML(substr($text,$lastPos));
	}
	return $out;
}

/**
 * Escapes special HTML characters and inserts break tags as <br/>
 * @param string $input The text to escape
 * @return string Escaped HTML string with break tags
 */
function escapeHTMLwithLineBreak($input) {
	$output=$input;
	$output=str_replace('&', '&amp;', $output);
	$output=str_replace('<', '&lt;', $output);
	$output=str_replace('>', '&gt;', $output);
	$output=str_replace("\r\n", '<br/>', $output);
	return $output;
}

function insertLineBreakTags($input,$tag) {
	return str_replace(array("\r\n","\r","\n"), $tag, $input);;
}

/**
 * Escapes special HTML characters of a string
 * @param string $input The text to escape
 * @return string Escaped HTML string
 */
function escapeHTML($input) {
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
function escapeXMLwithLineBreak($input,$tag) {
	$output=$input;
	$output=str_replace('&', '&amp;', $output);
	$output=str_replace('<', '&lt;', $output);
	$output=str_replace('>', '&gt;', $output);
	$output=str_replace("\r\n", $tag, $output);
	$output=str_replace("\r", $tag, $output);
	$output=str_replace("\n", $tag, $output);
	return $output;
}

function escapeXML($input) {
	$output=$input;
	$output=str_replace('&', '&amp;', $output);
	$output=str_replace('<', '&lt;', $output);
	$output=str_replace('>', '&gt;', $output);
	return $output;
}

function encodeXML(&$input) {
	$output = htmlnumericentities($input);
	$output = str_replace('&#151;', '-', $output);
	$output = str_replace('&#146;', '&#39;', $output);
	$output = str_replace('&#147;', '&#8220;', $output);
	$output = str_replace('&#148;', '&#8221;', $output);
	$output = str_replace('&#128;', '&#243;', $output);
	$output = str_replace('&#128;', '&#243;', $output);
	$output=str_replace('"', '&quot;', $output);
	return $output;
}

function encodeXMLBreak($input,$break) {
	$output=encodeXML($input);
	$output=str_replace("&#13;&#10;", $break, $output);
	$output=str_replace("&#13;", $break, $output);
	$output=str_replace("&#10;", $break, $output);
	$output=str_replace("\n", $break, $output);
	return $output;
}

function htmlnumericentities(&$str){
  return preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
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
 * Converts http addresses of a text to links
 * @param string $string The text to analyze
 * @param string $tag The name of the tag to insert, fx: a
 * @param string $attr The name of the attribute to use, fx: href
 * @return string The text with inserted links
 */
function insertUrlLinks($string,$tag='a',$attr='href',$class='') {
	$pattern = "/((http:\/\/[a-z0-9\-\.]+\.[a-z0-9]{2,3})((\/[a-z0-9.\?&\/\#=_\-\)\(;]*)| |\r\n))/mi";
	$replacement = '<'.$tag.' '.$attr.'="${2}${4}"'.($class!='' ? ' class="'.$class.'"' : '').'>${1}</'.$tag.'>';
	return preg_replace($pattern, $replacement, $string);
}

/**
 * Converts markup of type: [tag]..[tag] to: <tag>...</tag>
 * @param string $orig The name of the existing tag
 * @param string $result The name of the resulting tag
 * @param string $string The text to analyze
 * @return string The text with converted tags
 */
function convertToTags($orig,$result,$string) {
	$pattern = "/\[".$orig."\]([^\[\]]+)\[".$orig."\]/i";
	$replacement = "<".$result.">\${1}</".$result.">";
	return preg_replace($pattern, $replacement, $string);
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


///////////////////////// Pages ///////////////////////////

/**
 * Get the ID of the active page
 * @return The id of active page, -1 of not yet set
 */
function getPageId() {
	if (isset($_SESSION['core.page.id'])) {
		return $_SESSION['core.page.id'];
	}
	else {
		return -1;
	}
}

/**
 * Sets the ID of the active page
 * @param int $id The pages ID
 */
function setPageId($id) {
	$_SESSION['core.page.id']=$id;
}

/**
 * Sets the design of the active page
 * @param string $unique The unique name of the design
 */
function setPageDesign($unique) {
	$_SESSION['core.page.design']=$unique;
}

/**
 * Gets the design of the active page (if set) or a special sticky design
 * @return string The design of the active page if set, false otherwise
 */
function getPageDesign() {
	if (isset($_SESSION['debug.design'])) {
		return $_SESSION['debug.design'];
	}
	elseif (isset($_SESSION['core.page.design'])) {
		return $_SESSION['core.page.design'];
	}
	else {
		return false;
	}
}

// ******************* User *******************

/**
 * Get the ID of the active internal user
 * @return int ID of the user
 */
function getUserId() {
	if (isset($_SESSION['core.user.id'])) {
		return $_SESSION['core.user.id'];
	}
	else {
		return -1;
	}
}




//////////////////////// Database /////////////////////////


/**
 * Find all tables in the database
 * @return array Array of table names
 */
function getDatabaseTables() {
	global $database;
	$out = array();
	$sql = "show tables from ".$database;
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out[] = $row[0];
	}
	Database::free($result);
	return $out;
}

/**
 * Find all columns of database table
 * @param string $table The name of the table
 * @return array An array of column info TODO: Format of array??
 */
function getDatabaseTableColumns($table) {
	global $database;
	$sql = "SHOW FULL COLUMNS FROM ".$table." FROM ".$database;
	$out = array();
	$result = Database::select($sql);
	while ($row = Database::next($result)) {
		$out[] = $row;
	}
	Database::free($result);
	return $out;
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
 * Gets the value of a checkbox passed thru the get protocol
 * @param string $key The name of the checkbox
 * @return boolean True if the checkbox was checked, false otherwise
 */
function requestGetCheckbox($key) {
	if (isset($_GET[$key]) && $_GET[$key]=='on') {
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