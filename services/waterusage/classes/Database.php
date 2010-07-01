<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */

class Database {
	
	function Database() {
	}
	
	function getConnection() {
		global $database_host, $database_user,$database_password,$database;
		if (!isset($GLOBALS['OP_CON'])) {
			$con = mysql_connect($database_host, $database_user,$database_password,false);
			mysql_select_db($database,$con);
			$GLOBALS['OP_CON'] = $con;
		}
		return $GLOBALS['OP_CON'];
	}
	
	function select($sql) {
		$con = Database::getConnection();
		$result = mysql_query($sql,$con);
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
	
	function size($result) {
		return mysql_num_rows($result);
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
		$con = Database::getConnection();
		mysql_query($sql,$con);
		return Database::_checkError($sql,$con);
	}
	
	function delete($sql) {
		$con = Database::getConnection();
		mysql_query($sql,$con);
		return Database::_checkError($sql,$con);
	}

	function insert($sql) {
		$con = Database::getConnection();
		mysql_query($sql,$con);
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
		return mysql_fetch_array($result);
	}
	
	function free($result) {
		mysql_free_result($result);
	}
	
	function getIds($sql) {
		$result = Database::select($sql);
		$ids = array();
		while($row = Database::next($result)) {
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
	
}
?>