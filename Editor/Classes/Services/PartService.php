<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Core/Database.php');
require_once($basePath.'Editor/Classes/Parts/PartContext.php');
require_once($basePath.'Editor/Classes/Core/Log.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/Utilities/DOMUtils.php');
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');

class PartService {
	
	function load($type,$id) {
		global $basePath;
		if (!$type) {
			Log::debug('Unable to load part with no type');
			return null;
		}
		if (!$id) {
			Log::debug('Unable to load part with no ID');
			return null;
		}
		
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
	
	function getLinkText($partId) {
		$text = '';
		$sql = "select text,document_section.part_id from part_text,document_section where document_section.part_id=part_text.part_id and document_section.part_id=".Database::int($partId)."
union select text,document_section.part_id from part_header,document_section where document_section.part_id=part_header.part_id and document_section.part_id=".Database::int($partId)."
union select text,document_section.part_id from part_listing,document_section where document_section.part_id=part_listing.part_id and document_section.part_id=".Database::int($partId)."
union select html as text,document_section.part_id from part_table,document_section where document_section.part_id=part_table.part_id and document_section.part_id=".Database::int($partId);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$text.=' '.$row['text'];
		}
		Database::free($result);
		return $text;
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
		if (!$type) {
			Log::debug('Unable to get controller for no type');
			return null;
		}
		$class = ucfirst($type).'PartController';
		$path = $basePath.'Editor/Classes/Parts/'.$class.'.php';
		if (!file_exists($path)) {
			Log::debug('Unable to find controller for: '.$type);
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
		$arr = FileSystemService::listDirs($basePath."Editor/Parts/");
		for ($i=0;$i<count($arr);$i++) {
			if (substr($arr[$i],0,3)=='CVS') {
				unset($arr[$i]);
			}
		}
		return $arr;
	}

	function getParts() {
		return array(
			'header' => array ( 'name' => array('da'=>'Overskrift','en'=>'Header'), 'description' => '', 'priority' => 0 ),
			'text' => array ( 'name' => array('da'=>'Tekst','en'=>'Text'), 'description' => '', 'priority' => 1 ),
			'listing' => array ( 'name' => array('da'=>'Punktopstilling','en'=>'Bullet list'), 'description' => '', 'priority' => 2 ),
			'image' => array ( 'name' => array('da'=>'Billede','en'=>'Image'), 'description' => '', 'priority' => 3 ),
			'imagegallery' => array ( 'name' => array('da'=>'Billedgalleri','en'=>'Image gallery'), 'description' => '', 'priority' => 8 ),
			'horizontalrule' => array ( 'name' => array('da'=>'Adskiller','en'=>'Divider'), 'description' => '', 'priority' => 4 ),
			'table' => array ( 'name' => array('da'=>'Tabel','en'=>'Table'), 'description' => '', 'priority' => 4 ),
			'richtext' => array ( 'name' => array('da'=>'Rig tekst','en'=>'Rich text'), 'description' => '', 'priority' => 8 ),
			'file' => array ( 'name' => array('da'=>'Fil','en'=>'File'), 'description' => '', 'priority' => 7 ),
			'person' => array ( 'name' => array('da'=>'Person','en'=>'Person'), 'description' => '', 'priority' => 5 ),
			'news' => array ( 'name' => array('da'=>'Nyheder','en'=>'News'), 'description' => '', 'priority' => 6 ),
			'formula' => array ( 'name' => array('da'=>'Formular','en'=>'Formula'), 'description' => '', 'priority' => 10 ),
			'list' => array ( 'name' => array('da'=>'Liste','en'=>'List'), 'description' => '', 'priority' => 4 ),
			'mailinglist' => array ( 'name' => array('da'=>'Postliste','en'=>'Mailing list'), 'description' => '', 'priority' => 10 ),
			'html' => array ( 'name' => array('da'=>'HTML','en'=>'HTML'), 'description' => '', 'priority' => 7 ),
			'poster' => array ( 'name' => array('da'=>'Plakat','en'=>'Poster'), 'description' => '', 'priority' => 7 )
		);
	}
	
	function getAllControllers() {
		$controllers = array();
		$parts = PartService::getParts();
		foreach ($parts as $key => $value) {
			$controllers[] = PartService::getController($key);
		}
		return $controllers;
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
				$info['name'] = DOMUtils::getPathText($doc->documentElement,"/part/name");
				$info['description'] = DOMUtils::getPathText($doc->documentElement,"/part/description");
				$info['priority'] = DOMUtils::getPathText($doc->documentElement,"/part/priority");
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