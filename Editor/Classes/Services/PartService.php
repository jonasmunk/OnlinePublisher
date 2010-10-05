<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/PartContext.php');
require_once($basePath.'Editor/Classes/Log.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class PartService {
	
	function load($type,$id) {
		global $basePath;
		$class = ucfirst($type).'Part';
		$path = $basePath.'Editor/Classes/Parts/'.$class.'.php';
		if (!file_exists($path)) {
			return null;
		}
		require_once $path;
		$instance = new $class;
		$part = $instance->load($id);
		return $part;
	}
	
	function getController($type) {
		global $basePath;
		$class = ucfirst($type).'PartController';
		$path = $basePath.'Editor/Classes/Parts/'.$class.'.php';
		if (!file_exists($path)) {
			return null;
		}
		require_once $path;
		return new $class;
	}

	function buildPartContext($pageId) {
		$context = new PartContext();
	
		$sql = "select link.*,page.path from link left join page on page.id=link.target_id and link.target_type='page' where page_id=".$pageId." and source_type='text'";

		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$context -> addBuildLink(
				StringUtils::escapeSimpleXML($row['source_text']),
				$row['target_type'],
				$row['target_id'],
				$row['target_value'],
				$row['target'],
				$row['alternative'],
				$row['path']
			);
		}
		Database::free($result);

		return $context;
	}
}