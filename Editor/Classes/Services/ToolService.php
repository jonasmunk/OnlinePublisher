<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Tool.php');

class ToolService {

	function getInstalledToolKeys() {
		$arr = array();
		$sql = "select id,`unique` from `tool`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$arr[] = $row['unique'];
		}
		Database::free($result);
		return $arr;
	}
	
}