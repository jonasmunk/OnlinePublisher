<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Log.php');

class ObjectService {
	
	function getLatestId($type) {
		$sql = "select max(id) as id from object where type=".Database::text($type);
		if ($row = Database::selectFirst($sql)) {
			return intval($row['id']);
		}
		return null;
	}
}