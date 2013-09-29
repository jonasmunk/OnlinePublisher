<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class Database {
		
	static function testConnection() {
		if (!function_exists('mysql_connect')) {
			return false;
		}
		return Database::getConnection()!==false;
	}
	
	static function testServerConnection($host,$user,$password) {
		if (!function_exists('mysql_connect')) {
			return false;
		}
		
		$con = mysql_connect($host, $user,$password);
		if (!$con) {
			return false;
		}
		if (mysql_errno($con)>0) {
			return false;
		}
		return true;
	}
	
	static function testDatabaseConnection($host,$user,$password,$name) {
		$con = mysql_connect($host, $user,$password);
		if (!$con) {
			return false;
		}
		mysql_select_db($name,$con);
		if (mysql_errno($con)>0) {
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
		$config = ConfigurationService::getDatabase();
		if (!isset($GLOBALS['OP_CON'])) {
			$con = mysql_connect($config['host'], $config['user'],$config['password'],false);
			if (!$con) {
				return false;
			}
      mysql_set_charset('latin1',$con);
			mysql_select_db($config['database'],$con);
			if (mysql_errno($con)>0) {
				return false;
			}
			$GLOBALS['OP_CON'] = $con;
		}
		return $GLOBALS['OP_CON'];
	}
	
	static function select($sql) {
		$con = Database::getConnection();
		if (!$con) {
			error_log('No database connection');
			return false;
		}
		Database::debug($sql);
		$result = mysql_query($sql,$con);
		if (mysql_errno($con)>0) {
			error_log(mysql_error($con).': '.$sql);
			return false;
		}
		else {
			return $result;
		}
	}
	
	static function selectFirst($sql) {
		$output = false;
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
		return mysql_num_rows($result);
	}
	
	static function isEmpty($sql) {
		$output = true;
		$result = Database::select($sql);
		if ($row = Database::next($result)) {
			$output = false;
		}
		Database::free($result);
		return $output;
	}
	
	static function update($sql) {
		if (is_array($sql)) {
			$sql = Database::buildUpdateSql($sql);
		}
		$con = Database::getConnection();
		mysql_query($sql,$con);
		return Database::_checkError($sql,$con);
	}
	
	static function delete($sql) {
		Database::debug($sql);
		$con = Database::getConnection();
		mysql_query($sql,$con);
		Database::_checkError($sql,$con);
		return mysql_affected_rows($con);
	}

	static function insert($sql) {
		if (is_array($sql)) {
			$sql = Database::buildInsertSql($sql);
		}
		Database::debug($sql);
		$con = Database::getConnection();
		mysql_query($sql,$con);
		$id=mysql_insert_id();
		if (Database::_checkError($sql,$con)) {
			return $id;
		} else {
			return false;
		}
	}
	
	static function _checkError($sql,&$con) {
		if (mysql_errno($con)>0) {
			error_log(mysql_error($con).': '.$sql);
			return false;
		}
		else {
			return true;
		}
	}
	
	static function next($result) {
		return mysql_fetch_array($result);
	}
	
	static function free($result) {
		mysql_free_result($result);
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
	
	static function selectIntArray($sql) {
		$result = Database::select($sql);
		$ids = array();
		while($row = Database::next($result)) {
			$ids[] = intval($row[0]);
		}
		Database::free($result);
		return $ids;
	}
	
	static function getIds($sql) {
		$result = Database::select($sql);
		$ids = array();
		while($row = Database::next($result)) {
			// TODO intval this
			$ids[] = $row['id'];
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
		return "'".mysql_escape_string($text)."'";
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
		return "'%".mysql_escape_string($text)."%'";
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
}
?>