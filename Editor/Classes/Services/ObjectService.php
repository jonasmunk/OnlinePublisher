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
	
	function getClass($type) {
		global $basePath;
		$class = ucfirst($type);
		$path = $basePath.'Editor/Classes/'.$class.'.php';
		if (!file_exists($path)) {
			return null;
		}
		require_once $path;
		return $class;
	}
	
	function search($query) {
		$parts = array('type' => $query->getType(), 'query' => $query->getText());
		if ($class = ObjectService::getClass($query->getType())) {
			$class::addCustomSearch($query,$parts);
		} else {
			Log::debug('Unable to get class for type='.$query->getType());
		}
		return Object::retrieve($parts);
	}
}