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
	
	function newInstance($type) {
		global $basePath;
		$class = ucfirst($type).'Part';
		$path = $basePath.'Editor/Classes/Parts/'.$class.'.php';
		if (!file_exists($path)) {
			return null;
		}
		require_once $path;
		return new $class;
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
	

	
	function getAvailableParts() {
		global $basePath;
		$arr = FileSystemUtil::listDirs($basePath."Editor/Parts/");
		for ($i=0;$i<count($arr);$i++) {
			if (substr($arr[$i],0,3)=='CVS') {
				unset($arr[$i]);
			}
		}
		return $arr;
	}

	function getParts() {
		return array(
			'header' => array ( 'name' => 'Overskrift', 'description' => '', 'priority' => 0 ),
			 'text' => array ( 'name' => 'Tekst', 'description' => '', 'priority' => 1 ),
			'listing' => array ( 'name' => 'Punktopstilling', 'description' => '', 'priority' => 2 ),
			'image' => array ( 'name' => 'Billede', 'description' => '', 'priority' => 3 ),
			'horizontalrule' => array ( 'name' => 'Adskiller', 'description' => '', 'priority' => 4 ),
			'list' => array ( 'name' => 'Liste', 'description' => '', 'priority' => 4 ),
			'person' => array ( 'name' => 'Person', 'description' => '', 'priority' => 5 ),
			'news' => array ( 'name' => 'Nyheder', 'description' => '', 'priority' => 6 ),
			'file' => array ( 'name' => 'Fil', 'description' => '', 'priority' => 7 ),
			'html' => array ( 'name' => 'HTML', 'description' => '', 'priority' => 7 ),
			'richtext' => array ( 'name' => 'Rig tekst', 'description' => '', 'priority' => 8 ),
			'imagegallery' => array ( 'name' => 'Billedgalleri', 'description' => '', 'priority' => 8 ),
			'formula' => array ( 'name' => 'Formular', 'description' => '', 'priority' => 10 ),
			'mailinglist' => array ( 'name' => 'Postliste', 'description' => '', 'priority' => 10 )
		);
		
		
		
		$out = null;
		if ($out == null) {
			$out = array();	
			$parts = PartService::getAvailableParts();
			foreach ($parts as $part) {
				$info = PartService::getPartInfo($part);
				if ($info) {
					$out[$part] = $info;
				}
			}
			uasort($out,array("PartService", "compareParts"));
		}
		print_r($out);
		return $out;
	}

	/**
	 * Used to sort arrays of tools
	 */
	function compareParts($partA, $partB) {
		$a = $partA['priority'];
		$b = $partB['priority'];
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}


	function getPartInfo($unique) {
		global $basePath;
		$file = $basePath."Editor/Parts/".$unique."/info.xml";
		if (file_exists($file)) {
			$info = array();
			$doc = new DOMDocument();
			if ($doc->load($file)) {
				$info['name'] = XmlUtils::getPathText($doc->documentElement,"/part/name");
				$info['description'] = XmlUtils::getPathText($doc->documentElement,"/part/description");
				$info['priority'] = XmlUtils::getPathText($doc->documentElement,"/part/priority");
			}
			else {
				error_log('getPartInfo: '.$doc->getErrorString());
			}
			return $info;
		}
		else {
			return false;
		}
	}
}