<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class Database {
	
	function Database() {
	}
	
	function testConnection() {
		return Database::getConnection()!==false;
	}
	
	function testServerConnection($host,$user,$password) {
		$con = @mysql_connect($host, $user,$password);
		if (!$con) {
			return false;
		}
		if (@mysql_errno($con)>0) {
			return false;
		}
		return true;
	}
	
	function testDatabaseConnection($host,$user,$password,$name) {
		$con = @mysql_connect($host, $user,$password);
		if (!$con) {
			return false;
		}
		@mysql_select_db($name,$con);
		if (@mysql_errno($con)>0) {
			return false;
		}
		return true;
	}
	
	function debug($sql) {
		if ($_SESSION['core.debug.logDatabaseQueries']) {
			error_log($sql);
		}
	}
	
	function getConnection() {
		global $database_host, $database_user,$database_password,$database;
		if (!isset($GLOBALS['OP_CON'])) {
			$con = @mysql_connect($database_host, $database_user,$database_password,false);
			if (!$con) {
				return false;
			}
			@mysql_select_db($database,$con);
			if (mysql_errno($con)>0) {
				return false;
			}
			$GLOBALS['OP_CON'] = $con;
		}
		return $GLOBALS['OP_CON'];
	}
	
	function select($sql) {
		$con = Database::getConnection();
		if (!$con) {
			error_log('No database connection');
			return false;
		}
		Database::debug($sql);
		$result = @mysql_query($sql,$con);
		if (mysql_errno($con)>0) {
			error_log(mysql_error($con).': '.$sql);
			return false;
		}
		else {
			return $result;
		}
	}
	
	function selectFirst($sql) {
		$output = false;
		$result = Database::select($sql);
		if ($row = Database::next($result)) {
			$output = $row;
		}
		Database::free($result);
		return $output;
	}
	
	function selectAll($sql) {
		$output = array();
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$output[] = $row;
		}
		Database::free($result);
		return $output;
	}
	
	function size($result) {
		return @mysql_num_rows($result);
	}
	
	function isEmpty($sql) {
		$output = true;
		$result = Database::select($sql);
		if ($row = Database::next($result)) {
			$output = false;
		}
		Database::free($result);
		return $output;
	}
	
	function update($sql) {
		if (is_array($sql)) {
			$sql = Database::buildUpdateSql($sql);
		}
		$con = Database::getConnection();
		@mysql_query($sql,$con);
		return Database::_checkError($sql,$con);
	}
	
	function delete($sql) {
		Database::debug($sql);
		$con = Database::getConnection();
		@mysql_query($sql,$con);
		Database::_checkError($sql,$con);
		return mysql_affected_rows($con);
	}

	function insert($sql) {
		if (is_array($sql)) {
			$sql = Database::buildInsertSql($sql);
		}
		Database::debug($sql);
		$con = Database::getConnection();
		@mysql_query($sql,$con);
		$id=mysql_insert_id();
		if (Database::_checkError($sql,$con)) {
			return $id;
		} else {
			return false;
		}
	}
	
	function _checkError($sql,&$con) {
		if (mysql_errno($con)>0) {
			error_log(mysql_error($con).': '.$sql);
			return false;
		}
		else {
			return true;
		}
	}
	
	function next($result) {
		return @mysql_fetch_array($result);
	}
	
	function free($result) {
		@mysql_free_result($result);
	}
	
	function selectArray($sql) {
		$result = Database::select($sql);
		$ids = array();
		while($row = Database::next($result)) {
			$ids[] = $row[0];
		}
		Database::free($result);
		return $ids;
	}
	
	function selectIntArray($sql) {
		$result = Database::select($sql);
		$ids = array();
		while($row = Database::next($result)) {
			$ids[] = intval($row[0]);
		}
		Database::free($result);
		return $ids;
	}
	
	function getIds($sql) {
		$result = Database::select($sql);
		$ids = array();
		while($row = Database::next($result)) {
			// TODO intval this
			$ids[] = $row['id'];
		}
		Database::free($result);
		return $ids;
	}
	
	function buildArray($sql) {
		$result = Database::select($sql);
		$ids = array();
		while($row = Database::next($result)) {
			$ids[] = $row[0];
		}
		Database::free($result);
		return $ids;
	}
	
	function getRow($sql) {
		return Database::selectFirst($sql);
	}
	
	/**
	 * Formats a string of text for use in an SQL-sentence
	 * @param string $text The text to format
	 * @return string The formattet string
	 */
	function text($text) {
		return "'".mysql_escape_string($text)."'";
	}

	/**
	 * Formats an integer for use in an SQL-sentence
	 * @param int $value The number to format
	 * @return string The formatet string
	 */
	function int($value) {
		return intval($value);
	}

	/**
	 * Formats an float for use in an SQL-sentence
	 * @param float $value The number to format
	 * @return string The formatet string
	 */
	function float($value) {
		return floatval($value);
	}

	/**
	 * Formats a boolean for use in an SQL-sentence
	 * @param boolean $bool The boolean to format
	 * @return string The formattet string
	 */
	function boolean($bool) {
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
	function datetime($stamp) {
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
	function date($stamp) {
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
	function search($text) {
		return "'%".mysql_escape_string($text)."%'";
	}
	
	function buildUpdateSql($arr) {
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
	
	function buildInsertSql($arr) {
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