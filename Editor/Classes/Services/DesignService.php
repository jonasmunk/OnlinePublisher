<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */

class DesignService {
	
	/**
	 * Finds all available designs
	 * @return array An array of the unique names of all available designs
	 * @static
	 */
	function getAvailableDesigns() {
		global $basePath;
		$names = FileSystemUtil::listDirs($basePath."style/");
		$out = array();
		foreach ($names as $name) {
			$out[$name] = DesignService::getInfo($name);
		}
		return $out;
	}
	
	function getInfo($name) {
		global $basePath;
		$path = $basePath."style/".$name."/info/info.json";
		$info = JsonService::readFile($path);
		return $info;
	}
	
	function validate($name) {
		global $basePath;
		$valid = true;
		$info = DesignService::getInfo($name);
		if ($info!==null) {
			$valid = $valid && StringUtils::isNotBlank($info->name);
			$valid = $valid && StringUtils::isNotBlank($info->description);
			$valid = $valid && StringUtils::isNotBlank($info->owner);
		} else {
			$valid = false;
		}
		$valid = $valid && !file_exists($basePath."style/".$name."/info/info.xml");
		$valid = $valid && file_exists($basePath."style/".$name."/info/Preview128.png");
		$valid = $valid && file_exists($basePath."style/".$name."/info/Preview64.png");
		return $valid;
	}
}