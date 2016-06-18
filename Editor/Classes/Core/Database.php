<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Core
 */

if (!isset($GLOBALS['basePath'])) {
  header('HTTP/1.1 403 Forbidden');
  exit;
}
class Database {

  static function testConnection() {
    if (!function_exists('mysqli_connect')) {
      return false;
    }
    return Database::getConnection()!==false;
  }

  static function testServerConnection($host,$user,$password) {
    if (!function_exists('mysqli_connect')) {
      return false;
    }

    $con = mysqli_connect($host, $user,$password);
    if (!$con) {
      return false;
    }
    if (mysqli_errno($con)>0) {
      return false;
    }
    return true;
  }

  static function testDatabaseConnection($host,$user,$password,$name) {
    $con = mysqli_connect($host, $user,$password);
    if (!$con) {
      return false;
    }
    mysqli_select_db($con,$name);
    if (mysqli_errno($con)>0) {
      return false;
    }
    return true;
  }

  static function debug($sql) {
    if (isset($_SESSION['core.debug.logDatabaseQueries']) && $_SESSION['core.debug.logDatabaseQueries']) {
      error_log($sql);
    }
  }

  static function getConnection() {
    if (!isset($GLOBALS['OP_CON'])) {
      $config = ConfigurationService::getDatabase();
      $con = mysqli_connect($config['host'], $config['user'],$config['password'],false);
      if (!$con) {
        return false;
      }
      // TODO mysqli_set_charset is expensive - and sometimes it has no effect (it is correct already)
      if (ConfigurationService::isUnicode()) {
        mysqli_set_charset($con,'utf8');
      } else {
        mysqli_set_charset($con,'latin1');
      }
      mysqli_select_db($con,$config['database']);
      if (mysqli_errno($con)>0) {
        return false;
      }
      $GLOBALS['OP_CON'] = $con;
    }
    return $GLOBALS['OP_CON'];
  }

  static function select($sql,$parameters=null) {
    $con = Database::getConnection();
    if (!$con) {
      error_log('No database connection');
      return false;
    }
    if ($parameters!==null) {
      $sql = Database::compile($sql,$parameters);
    }
    Database::debug($sql);
    $result = mysqli_query($con,$sql);
    if (mysqli_errno($con)>0) {
      error_log(mysqli_error($con).': '.$sql);
      return false;
    }
    else {
      return $result;
    }
  }

  static function selectFirst($sql,$parameters=null) {
    $output = false;
    if ($parameters!==null) {
      $sql = Database::compile($sql,$parameters);
    }
    $result = Database::select($sql);
    if ($row = Database::next($result)) {
      $output = $row;
    }
    Database::free($result);
    return $output;
  }

  static function selectAll($sql,$key=null) {
    $output = array();
    $result = Database::select($sql);
    while ($row = Database::next($result)) {
      if ($key!=null) {
        $output[$row['key']] = $row;
      } else {
        $output[] = $row;
      }
    }
    Database::free($result);
    return $output;
  }

  static function size($result) {
    return mysqli_num_rows($result);
  }

  static function isEmpty($sql,$parameters=null) {
    $output = true;
    $result = Database::select($sql,$parameters);
    if ($row = Database::next($result)) {
      $output = false;
    }
    Database::free($result);
    return $output;
  }

  static function update($sql,$parameters=null) {
    if (is_array($sql)) {
      $sql = Database::buildUpdateSql($sql);
    }
    if ($parameters!==null) {
      $sql = Database::compile($sql,$parameters);
    }
    $con = Database::getConnection();
    mysqli_query($con,$sql);
    return Database::_checkError($sql,$con);
  }

  static function delete($sql,$parameters=null) {
    if ($parameters!==null) {
      $sql = Database::compile($sql,$parameters);
    }
    Database::debug($sql);
    $con = Database::getConnection();
    mysqli_query($con,$sql);
    Database::_checkError($sql,$con);
    return mysqli_affected_rows($con);
  }

  static function insert($sql,$parameters=null) {
    if (is_array($sql)) {
      $sql = Database::buildInsertSql($sql);
    }
    if ($parameters!==null) {
      $sql = Database::compile($sql,$parameters);
    }
    Database::debug($sql);
    $con = Database::getConnection();
    mysqli_query($con,$sql);
    $id = mysqli_insert_id($con);
    if (Database::_checkError($sql,$con)) {
      return $id;
    } else {
      return false;
    }
  }

  static function _checkError($sql,&$con) {
    if (mysqli_errno($con)>0) {
      error_log(mysqli_error($con).': '.$sql);
      return false;
    }
    else {
      return true;
    }
  }

  static function next($result) {
    return mysqli_fetch_array($result);
  }

  static function free($result) {
    mysqli_free_result($result);
  }

  static function selectArray($sql) {
    $result = Database::select($sql);
    $ids = array();
    while($row = Database::next($result)) {
      $ids[] = $row[0];
    }
    Database::free($result);
    return $ids;
  }

  static function selectMap($sql,$parameters=null) {
    $result = Database::select($sql,$parameters);
    $map = array();
    while($row = Database::next($result)) {
      $map[$row[0]] = $row[1];
    }
    Database::free($result);
    return $map;
  }

  static function selectIntArray($sql,$parameters=null) {
    $result = Database::select($sql,$parameters);
    $ids = array();
    while($row = Database::next($result)) {
      $ids[] = intval($row[0]);
    }
    Database::free($result);
    return $ids;
  }

  static function getIds($sql,$parameters=null) {
    if ($parameters!==null) {
      $sql = Database::compile($sql,$parameters);
    }
    $result = Database::select($sql);
    $ids = [];
    while($row = Database::next($result)) {
      $ids[] = intval($row['id']);
    }
    Database::free($result);
    return $ids;
  }

  static function buildArray($sql) {
    $result = Database::select($sql);
    $ids = array();
    while($row = Database::next($result)) {
      $ids[] = $row[0];
    }
    Database::free($result);
    return $ids;
  }

  static function getRow($sql) {
    return Database::selectFirst($sql);
  }

  /**
   * Formats a string of text for use in an SQL-sentence
   * @param string $text The text to format
   * @return string The formattet string
   */
  static function text($text) {
    return "'".mysqli_real_escape_string(Database::getConnection(),$text)."'";
  }

  /**
   * Formats an integer for use in an SQL-sentence
   * @param int $value The number to format
   * @return string The formatet string
   */
  static function int($value) {
    return intval($value);
  }

  /**
   * Formats an float for use in an SQL-sentence
   * @param float $value The number to format
   * @return string The formatet string
   */
  static function float($value) {
    return floatval($value);
  }

  /**
   * Formats a boolean for use in an SQL-sentence
   * @param boolean $bool The boolean to format
   * @return string The formattet string
   */
  static function boolean($bool) {
    if ($bool) {
      return '1';
    } else {
      return '0';
    }
  }

  /**
   * Formats a unix timestamp for use in an SQL-sentence
   * @param int $stamp The number to format
   * @return string The formattet string
   */
  static function datetime($stamp) {
    if (is_numeric($stamp)) {
      return "'".date('Y-m-d H:i:s',intval($stamp))."'";
    }
    else {
      return "NULL";
    }
  }

  /**
   * Formats a unix timestamp for use in an SQL-sentence
   * @param int $stamp The number to format
   * @return string The formattet string
   */
  static function date($stamp) {
    if (is_numeric($stamp)) {
      return "'".date('Y-m-d',intval($stamp))."'";
    }
    else {
      return "NULL";
    }
  }

  /**
   * Formats a string for use as a search parameter in an SQL-sentence
   * @param string $text The text to format
   * @return string The formattet string
   */
  static function search($text) {
    return "'%".mysqli_real_escape_string(Database::getConnection(),$text)."%'";
  }

  static function buildUpdateSql($arr) {
    $sql = "update ".$arr['table']." set ";
    $num = 0;
    foreach ($arr['values'] as $column => $value) {
      if ($num>0) {
        $sql.=',';
      }
      $sql.="`".$column."`=".$value;
      $num++;
    }
    $sql.=" where ";
    $num = 0;
    foreach ($arr['where'] as $column => $value) {
      if ($num>0) {
        $sql.=' and ';
      }
      $sql.="`".$column."`=".$value;
    }
    return $sql;
  }

  static function buildInsertSql($arr) {
    $sql = "insert into ".$arr['table']." (";
    $num = 0;
    foreach ($arr['values'] as $column => $value) {
      if ($num>0) {
        $sql.=',';
      }
      $sql.="`".$column."`";
      $num++;
    }
    $sql.=") values (";
    $num = 0;
    foreach ($arr['values'] as $column => $value) {
      if ($num>0) {
        $sql.=',';
      }
      $sql.=$value;
      $num++;
    }
    $sql.=")";
    return $sql;
  }

  static function compile($sql,$vars) {
    $replacements = array();
    if (preg_match_all("/@[a-z]+\\([a-zA-Z]+\\)/u", $sql,$matches) > 0) {
      foreach ($matches[0] as $expression) {
        $pos = strpos($expression,'(');
        $type = substr($expression,1,$pos-1);
        $name = substr($expression,$pos+1,-1);
        if (array_key_exists($name,$vars)) {
          $value = $vars[$name];
          if ($type=='int') {
            $value = Database::int($value);
          } else if ($type=='text') {
            $value = Database::text($value);
          } else {
            continue;
          }
          $replacements[$expression] = $value;
        }
      }
    }
    $sql = str_replace(array_keys($replacements),$replacements,$sql);
    return $sql;
  }
}
?>