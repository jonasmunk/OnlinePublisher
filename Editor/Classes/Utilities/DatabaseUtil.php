<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class DatabaseUtil {

	/**
	 * Checks whether the database is up to date
	 * @return boolean True if the database is up to date
	 */
	function isUpToDate() {
		global $basePath;
		$output = false;
		$hash = md5_file($basePath.'/Editor/Info/Database.php');
		$sql = "select `value` from `setting` where `domain`='system' and `subdomain`='database' and `key`='database-hash'";
		if ($row=Database::selectFirst($sql)) {
			if ($row['value']==$hash) {
				$output=true;
			}
		}
		return $output;
	}
	
	/**
	 * Checks if the database is correct (matches the schema)
	 * @return boolean True if the database is correct, false otherwise
	 */
	function isCorrect() {
		$correct = true;
		$tables = DatabaseUtil::getTables();
		foreach ($tables as $table) {
			$columns = DatabaseUtil::getTableColumns($table);
			$errors = DatabaseUtil::checkTable($table,$columns);
			if (count($errors)>0) {
				$correct=false;
			}
		}
		$missingTables=DatabaseUtil::findMissingTables($tables);
		if (count($missingTables)>0) {
			$correct=false;
		}
		return $correct;
	}
	

	function setAsUpToDate() {
		global $basePath;
		$hash = md5_file($basePath.'/Editor/Info/Database.php');
		$sql = "select `value` from `setting` where `domain`='system' and `subdomain`='database' and `key`='database-hash'";
		if ($row=Database::selectFirst($sql)) {
			$sql="update `setting` set `value`=".Database::text($hash)." where `domain`='system' and `subdomain`='database' and `key`='database-hash'";
			Database::update($sql);
		} else {
			$sql="insert into `setting` (`domain`,`subdomain`,`key`,`value`) values ('system','database','database-hash',".Database::text($hash).")";
			Database::insert($sql);
		}
	}
	
	

	/**
	 * Find all tables in the database
	 * @return array Array of table names
	 */
	function getTables() {
		$config = ConfigurationService::getDatabase();
		$out = array();
		$sql = "show tables from ".$config['database'];
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
	function getTableColumns($table) {
		$config = ConfigurationService::getDatabase();
		$sql = "SHOW FULL COLUMNS FROM ".$table." FROM ".$config['database'];
		$out = array();
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$out[] = $row;
		}
		Database::free($result);
		return $out;
	}
	

	/**
	 * Get a definition of how a database table is supposed to look
	 * @param string $table The name of the table
	 * @return array A definition of the table
	 */
	function getExpectedColumns($table) {
		global $databaseTables;
		return $databaseTables[$table];
	}

	/**
	 * Builds an array of SQL-sentences used to update a database table
	 * @param string $table The name of the table to analyze
	 * @param array $columns An array of columns describing how the table is now
	 * @return array An array of SQL-sentences
	 */
	function updateTable($table,$columns) {
		global $databaseTables;
		$errors = array();
		if (array_key_exists($table,$databaseTables)) {
			$expectedColumns = $databaseTables[$table];
		
			// Search for unknown columns
			foreach ($columns as $col) {
				if(!DatabaseUtil::findColumnInColumns($col[0],$expectedColumns)) {
					$errors[] = "alter table `".$table."` DROP `".$col[0]."`";
				}
			}
		
			foreach ($expectedColumns as $col) {
				if ($fields = DatabaseUtil::findColumnInColumns($col[0],$columns)) {
					if ($fields['Type']!=$col[1] || $fields['Null']!=$col[2] || $fields['Default']!=$col[4]) {
						$sql="ALTER TABLE `".$table."` CHANGE `".$col[0]."` `".$col[0]."` ".$col[1]." ".($col[2]=='YES' ? "NULL" : "NOT NULL")." DEFAULT ".($col[4]=='' ? "NULL" : "'".$col['4']."'");
						$errors[] = $sql;
					}
				}
				else {
					$sql="alter table `".$table."` ADD `".$col[0]."` ".$col[1];
					if ($col[2]=='YES') {
						$sql.=" NULL";
					}
					else {
						$sql.=" NOT NULL";
					}
					if ($col[4]!='') {
						$sql.=" DEFAULT '".$col[4]."'";
					}
					if ($col[5]!='') {
						$sql.=" ".$col[5];
					}
					if ($col[3]!='') {
						$sql.=" PRIMARY KEY";
					}
					$errors[] = $sql;
				}
			}
		}
		else {
			$errors[] = "DROP TABLE `".$table."`";
		}
		return $errors;
	}
	

	/**
	 * Analyzes a table and builds an array of problems with the table
	 * @param string $table The name of the table to analyze
	 * @param array $columns An array of columns describing how the table is now
	 * @return array An array of problems describing what is wrong with the table
	 */
	function checkTable($table,$columns) {
		global $databaseTables, $basePath;
		require_once($basePath.'/Editor/Info/Database.php');
		$errors = array();
		if (array_key_exists($table,$databaseTables)) {
			$expectedColumns = $databaseTables[$table];
			foreach ($expectedColumns as $col) {
				if ($fields = DatabaseUtil::findColumnInColumns($col[0],$columns)) {
					//print_r($fields);
					if ($fields['Type']!=$col[1]) {
						$errors[] = "column ".$col[0].", TYPE is '".$fields['Type']."' should be '".$col[1]."'";
					}
					if ($fields['Null']=='YES' && $col[2]!='YES') {
						$errors[] = "column ".$col[0].", NULL is '".$fields['Null']."' should be '".$col[2]."'";
					}
					if ($fields['Key']!=$col[3]) {
						$errors[] = "column ".$col[0].", KEY is '".$fields['Key']."' should be '".$col[3]."'";
					}
					if ($fields['Default']!=$col[4]) {
						$errors[] = "column ".$col[0].", DEFAULT is '".$fields['Default']."' should be '".$col[4]."'";
					}
					if ($fields['Extra']!=$col[5]) {
						$errors[] = "column ".$col[0].", EXTRA is '".$fields['Extra']."' should be '".$col[5]."'";
					}
				}
				else {
					$errors[] = "column ".$col[0]." missing in db : ".DatabaseUtil::buildColumnProps($col);
				}
			}
			// Search for unknown columns
			foreach ($columns as $col) {
				if(!DatabaseUtil::findColumnInColumns($col[0],$expectedColumns)) {
					$errors[] = "column ".$col[0]." in db but not in config : ".DatabaseUtil::buildColumnConfig($col);
				}
			}
		}
		else {
			$errors[] = 'Unknown table';
		}
		return $errors;
	}

	/**
	 * Finds all tables missing in the database
	 * @param array $tables An array of the tables that already exists
	 * @return array An array of the table names missing in the database
	 */
	function findMissingTables($tables) {
		global $databaseTables, $basePath;
		require_once($basePath.'/Editor/Info/Database.php');
		$out = array();
		$keys = array_keys($databaseTables);
		foreach ($keys as $table) {
			if (!in_array($table,$tables)) {
				$out[] = $table;	
			} 
		}
		return $out;
	}

	/**
	 * Finds and returns a column in a list of column definitions
	 * @param string $columnName The name of the column
	 * @param array $columns An array of column definitions
	 * @param array The deffinition of the collumn, False if not found
	 */
	function findColumnInColumns($columnName,$columns) {
		$found = false;
		foreach ($columns as $column) {
			if ($column[0]==$columnName) {
				$found = $column;
			}
		}
		return $found;
	}

	/**
	 * Builds the PHP definition of a column
	 * @param array $cols The column definition
	 * @return string The PHP definition
	 */
	function buildColumnConfig($cols) {
		return 'array('.
		'"'.$cols[0].'","'.$cols['Type'].'","'.$cols['Null'].'","'.$cols['Key'].'","'.$cols['Default'].'","'.$cols['Extra'].'"'.
		')';
	}

	function buildColumnProps($cols) {
		return "type=".$cols[0].",null=".$cols[1].",key=".$cols[2].",default=".$cols[3].",extra=".$cols[4];
	}
	
	function update() {
		$tables = DatabaseUtil::getTables();

		$log = array();
		$log[] = "== Starting update ==";
		$missingTables = DatabaseUtil::findMissingTables($tables);
		Log::debug($tables);
		Log::debug($missingTables);
		foreach ($missingTables as $table) {
			$action = "CREATE TABLE `".$table."` (";
			$columns = DatabaseUtil::getExpectedColumns($table);
			$keys = '';
			for ($i=0;$i<count($columns);$i++) {
				$column = $columns[$i];
				if ($i>0) {
					$action.= ",";
				}
				$action.= "`".$column[0]."` ".$column[1];
				if ($column[2]=='') {
					$action.= " NOT NULL";
				}
				if ($column[3]=='PRI') {
					$keys.= ",PRIMARY KEY (`".$column[0]."`)";
				}
				if ($column[4]!='') {
					$action.= " DEFAULT '".$column[4]."'";
				}
				if ($column[5]!='') {
					$action.= " ".$column[5];
				}
			}
			$action.= $keys;
			$action.= ")";
			$log[] = "";
			$log[] = "Command: ";
			$log[] = $action;
			$con = Database::getConnection();
			mysql_query($action,$con);
			$error = mysql_error($con);
			if (strlen($error)>0) {
				$log[] = "!!!Error: ".$error;
			}
		}

		foreach ($tables as $table) {
			$columns = DatabaseUtil::getTableColumns($table);
			$errors = DatabaseUtil::checkTable($table,$columns);
			if (count($errors)>0) {
				$sql=DatabaseUtil::updateTable($table,$columns);
				foreach ($sql as $action) {
					$log[] = "";
					$log[] = "Command: ";
					$log[] = $action;
					$con = Database::getConnection();
					mysql_query($action,$con);
					$error = mysql_error($con);
					if (strlen($error)>0) {
						$log[] = "!!!Error: ".$error;
					}
				}
			}
		}
		DatabaseUtil::setAsUpToDate();

		$log[] = "";
		$log[] = "== Update finished ==";
		return $log;
	}
}
?>