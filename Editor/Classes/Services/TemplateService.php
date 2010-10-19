<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Log.php');

class TemplateService {
		
	function getController($type) {
		global $basePath;
		$class = ucfirst($type).'TemplateController';
		$path = $basePath.'Editor/Classes/Templates/'.$class.'.php';
		if (!file_exists($path)) {
			return null;
		}
		require_once $path;
		return new $class;
	}
	
}