<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ClassService {
	
	function getClasses() {
		global $basePath;
		$classes = array();
		$dir = $basePath.'Editor/Classes/';
		$files = FileSystemService::find(array(
			'dir' => $dir,
			'extension' => 'php'
		));
		foreach ($files as $path) {
			preg_match('/([A-Za-z]+)\.php/i', $path,$matches);
			$name = $matches[1];
			require_once($path);
			$valid = false;
			$parent = null;
			$props = null;
			if (class_exists($name)) {
				$valid = true;
				$parent = get_parent_class($name);
				$instance = new $name;
				$props = get_object_vars($instance);
			}
			$classes[] = array(
				'path' => $path,
				'relativePath' => substr($path,strlen($dir)),
				'name' => $name,
				'valid' => $valid,
				'parent' => $parent,
				'properties' => $props
			);
		}
		return $classes;
	}
}