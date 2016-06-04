<?php
if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}

class Request {
  
  static function getParameters() {
    $query = $_SERVER['QUERY_STRING'];
    $parameters = array();
    $parts = explode('&',$query);
    foreach ($parts as $part) {
      $pair = explode('=',$part);
      $parameters[] = array('name' => @$pair[0] , 'value' => @$pair[1]);
    }
    return $parameters;
  }

  /**
   * Checks if a variable was passed thru the get or post protocol
   * @param string $key The name of the variable
   * @return boolean True if variable was set, False otherwise
   */
  static function exists($key,$value=null) {
    if ($value!=null) {
      return isset($_REQUEST[$key]) && $_REQUEST[$key]==$value;
    }
    return isset($_REQUEST[$key]);
  }

  /**
   * Gets a text string variable passed thru the get protocol
   * @param string $key The name of the variable
   * @return string The value of the variable, '' if variable not set
   */
  static function getString($key,$default='') {
    if (!ConfigurationService::isUnicode()) {
      return Strings::fromUnicode(Request::getStringAsUnicode($key,$default));
    }
    return Request::getStringAsUnicode($key,$default);
  }

  static function getStringAsUnicode($key,$default='') {
    if (isset($_POST[$key])) {
      $output=$_POST[$key];
    } else if (isset($_GET[$key])) {
      $output=$_GET[$key];
    } else {
      return $default;
    }
    if ($default!=='' && Strings::isBlank($output)) {
      return $default;
    }
// Very old and probably no longer applies
//    $output=str_replace('\\"', '"', $output);
//    $output=str_replace('\\\'', '\'', $output);
//    $output=str_replace('\\\\', '\\', $output);
    return $output;
  }

  /**
   * Gets the value of a checkbox
   * @param string $key The name of the checkbox
   * @return boolean True if the checkbox was checked, false otherwise
   */
  static function getCheckbox($key) {
    return Request::getString($key)=='on';
  }

  /**
   * Gets a integer variable passed thru the GET or POST protocol
   * @param string $key The name of the variable
   * @param string $default Optional: The value to return if the variable is not set
   * or not a number. Defaults to 0.
   * @return int The value of the variable, $default if variable not set or not numeric
   */
  static function getInt($key,$default=0) {
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
  static function getFloat($key,$default=0) {
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
  static function getId() {
    return Request::getInt('id');
  }
  
  /**
   * Gets "1,9,3" as array(1,9,3)
   */
  static function getIntArrayComma($key) {
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
   * Gets the boolean value of a request parameter
   * @param string $key The name of the parameter
   * @return boolean True if the value equals "true", false otherwise
   */
  static function getBoolean($key) {
    if (isset($_POST[$key])) {
      return $_POST[$key] == 'true';
    }
    if (isset($_GET[$key])) {
      return $_GET[$key] == 'true';
    }
    return false;
  }

  /**
   * Gets a date variable passed thru the get protocol as an XmlWebGui date
   * @param string $key The name of the variable
   * @return string The value of the variable, '' if variable not set
   */
  static function getDate($key) {
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
  static function getDateTime($key) {
    if (isset($_POST[$key])) {
      $d=$_POST[$key];
      return mktime(substr($d,8,2),substr($d,10,2),substr($d,12,2),substr($d,4,2),substr($d,6,2),substr($d,0,4));
    }
    else {
      return '';
    }
  }
  
  static function getObject($key) {
    $obj = Strings::fromJSON(Request::getStringAsUnicode($key));
    if ($obj!==null) {
      if (!ConfigurationService::isUnicode()) {
        Strings::fromUnicode($obj);
      }
    }
    return $obj;
  }

  
  static function isPost() {
    return $_SERVER['REQUEST_METHOD']=='POST';
  }

  /**
   * Gets the array value of a variable
   * @param string $key The name of the variable
   * @return array the array value of the variable,
   *         an empty array if variable is not an array
   */
  static function getArray($key) {
    if (isset($_POST[$key]) && is_array($_POST[$key])) {
      return $_POST[$key];
    }
    else if (isset($_GET[$key]) && is_array($_GET[$key])) {
      return $_GET[$key];
    }
    else {
      return array();
    }
  }
  
  static function isLocalhost() {
    return $_SERVER['SERVER_NAME']==='localhost';
  }
  
  static function supportsGzip() {
    return isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;
  }

  static function getHeader($name) {
    foreach ($_SERVER as $k => $v) {
      if (substr($k, 0, 5) == "HTTP_") {
        $k = str_replace('_', ' ', substr($k, 5));
        $k = str_replace(' ', '-', ucwords(strtolower($k)));
        if ($name==$k) {
          return $v;
        }
      }
    }
    return null;
  }
  
  static function getHeaders() {
    $headers = array();
    foreach ($_SERVER as $k => $v) {
      if (substr($k, 0, 5) == "HTTP_") {
        $k = str_replace('_', ' ', substr($k, 5));
        $k = str_replace(' ', '-', ucwords(strtolower($k)));
        $headers[$k] = $v;
      }
    }
    return $headers;
  }
  
  /**
   * Transfers request parameters to an object if the parameter exists and a corresponding setter exists.
   * The keys can be just the parameters or parameter => type (string, int etc.) - or mixed.
   * @param object $object The object to change
   * @param array $keys The parameters to transfer
   */
  static function transfer($object,$keys) {
    foreach ($keys as $key=>$type) {
      if (is_int($key)) {
        $key = $type;
        $type = 'string';
      }
      if (Request::exists($key)) {
        $method = 'set'.ucfirst($key);
        $value = $type=='int' ? Request::getInt($key) : Request::getString($key);
        if (method_exists($object,$method)) {
          $object->$method($value);
        } else {
          Log::debug('Method: '.$method.' does not exists on...');
          Log::debug($object);
        }
      }
    }
  }
}
?>