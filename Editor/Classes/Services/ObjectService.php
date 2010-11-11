<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/Model/SearchResult.php');

class ObjectService {
	
	function getLatestId($type) {
		$sql = "select max(id) as id from object where type=".Database::text($type);
		if ($row = Database::selectFirst($sql)) {
			return intval($row['id']);
		}
		return null;
	}
	
	function getInstance($type) {
		global $basePath;
		$class = ucfirst($type);
		$path = $basePath.'Editor/Classes/'.$class.'.php';
		if (!file_exists($path)) {
			return null;
		}
		require_once $path;
		return new $class;
	}
	
	function search($query) {
		$parts = array('type' => $query->getType(), 'query' => $query->getText());
		if ($class = ObjectService::getInstance($query->getType())) {
			$class->addCustomSearch($query,$parts);
		} else {
			Log::debug('Unable to get class for type='.$query->getType());
		}
		$x =  Object::retrieve($parts);
		$result = new SearchResult();
		$result->setList($x['rows']);
		$result->setTotal($x['total']);
		$result->setWindowPage($x['windowPage']);
		$result->setWindowSize($x['windowSize']);
		
		return $result;
	}
}