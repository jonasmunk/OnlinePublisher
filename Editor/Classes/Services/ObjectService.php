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
		ObjectService::importType($type);
		$class = ucfirst($type);
		return new $class;
	}
	
	function importType($type) {
		global $basePath;
		$class = ucfirst($type);
		if (class_exists($class,false)) {
			return true;
		}
		$path = $basePath.'Editor/Classes/Objects/'.$class.'.php';
		if (!file_exists($path)) {
			$path = $basePath.'Editor/Classes/'.$class.'.php';
			if (!file_exists($path)) {
				return false;
			}
		}
		require_once $path;
		return true;
	}
	
	/**
	 * @param query Query
	 */
	function search($query) {
		$parts = array( 
			'type' => $query->getType(), 
			'query' => $query->getText(), 
			'fields' => $query->getFields(),
			'ordering' => implode(',',$query->getOrdering()),
			'direction' => $query->getDirection()
		);
		if ($class = ObjectService::getInstance($query->getType())) {
			if (method_exists($class,'addCustomSearch')) {
				$class->addCustomSearch($query,$parts);
			}
		} else {
			Log::debug('Unable to get class for type='.$query->getType());
		}
		$x =  Object::retrieve($parts);
		$result = new SearchResult();
		$result->setList($x['result']);
		$result->setTotal($x['total']);
		$result->setWindowPage($x['windowPage']);
		$result->setWindowSize($x['windowSize']);
		
		return $result;
	}
}