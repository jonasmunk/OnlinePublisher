<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Tool.php');

class ToolService {

	function getInstalled() {
		$arr = array();
		$sql = "select id,`unique` from `tool`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$arr[] = $row['unique'];
		}
		Database::free($result);
		return $arr;
	}
	
	function getAvailable() {
		global $basePath;
		$arr = FileSystemService::listDirs($basePath."Editor/Tools/");
		for ($i=0;$i<count($arr);$i++) {
			if (substr($arr[$i],0,3)=='CVS') {
				unset($arr[$i]);
			}
		}
		return $arr;
	}
	
	function getInfo($key) {
		global $basePath;
		$path = $basePath."Editor/Tools/".$key."/info.json";
		return JsonService::readFile($path);
	}
	
	function install($key) {
		$sql = "select id from `tool` where `unique`=".Database::text($key);
		if (Database::isEmpty($sql)) {
			$sql="insert into tool (`unique`) values (".Database::text($key).")";
			Database::insert($sql);
		}
	}
	
	function uninstall($key) {
		$sql = "delete from `tool` where `unique`=".Database::text($key);
		Database::delete($sql);
	}
}