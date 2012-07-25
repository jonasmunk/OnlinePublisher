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
			'header' => array ( 'name' => array('da'=>'Overskrift','en'=>'Header') ),
			'text' => array ( 'name' => array('da'=>'Tekst','en'=>'Text') ),
			'listing' => array ( 'name' => array('da'=>'Punktopstilling','en'=>'Bullet list') ),
			'image' => array ( 'name' => array('da'=>'Billede','en'=>'Image') ),
			'imagegallery' => array ( 'name' => array('da'=>'Billedgalleri','en'=>'Image gallery') ),
			'horizontalrule' => array ( 'name' => array('da'=>'Adskiller','en'=>'Divider') ),
			'table' => array ( 'name' => array('da'=>'Tabel','en'=>'Table') ),
			'richtext' => array ( 'name' => array('da'=>'Rig tekst','en'=>'Rich text') ),
			'file' => array ( 'name' => array('da'=>'Fil','en'=>'File') ),
			'person' => array ( 'name' => array('da'=>'Person','en'=>'Person') ),
			'news' => array ( 'name' => array('da'=>'Nyheder','en'=>'News') ),
			'formula' => array ( 'name' => array('da'=>'Formular','en'=>'Formula') ),
			'list' => array ( 'name' => array('da'=>'Liste','en'=>'List') ),
			'mailinglist' => array ( 'name' => array('da'=>'Postliste','en'=>'Mailing list') ),
			'html' => array ( 'name' => array('da'=>'HTML','en'=>'HTML') ),
			'poster' => array ( 'name' => array('da'=>'Plakat','en'=>'Poster') ),
			'map' => array ( 'name' => array('da'=>'Kort','en'=>'Map') )
		);
	}
	
	function getPartMenu() {
		$parts = PartService::getParts();
		$menu = array(
			'header' => $parts['header'],
			'text' => $parts['text'],
			'listing' => $parts['listing'],
			'image' => $parts['image'],
			'horizontalrule' => $parts['horizontalrule'],
			'table' => $parts['table'],
			'x' => 'divider',
			'richtext' => $parts['richtext'],
			'file' => $parts['file'],
			'imagegallery' => $parts['imagegallery'],
			'y' => 'divider',
			'advanced' => array('name'=>array('da'=>'Avanceret','en'=>'Advanced'),'children'=>array(
				'person' => $parts['person'],
				'news' => $parts['news'],
				'formula' => $parts['formula'],
				'list' => $parts['list'],
				'mailinglist' => $parts['mailinglist'],
				'html' => $parts['html'],
				'poster' => $parts['poster'],
				'map' => $parts['map']				
			))
		);
		return $menu;
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
	
	function getSingleLink($part,$sourceType=null) {
	    $sql = "select part_link.*,page.path from part_link left join page on page.id=part_link.target_value and part_link.target_type='page' where part_id=".Database::int($part->getId());
	    if (!is_null($sourceType)) {
	        $sql.=" and source_type=".Database::text($sourceType);
	    }
	    if ($row = Database::selectFirst($sql)) {
	        return $row;
	    } else {
	        return false;
	    }
	}
	
	function removeLinks($partId) {
		$sql = "delete from part_link where part_id=".Database::int($partId);
		Database::delete($sql);
	}
	
	function getLinks($part) {
		$links = array();
		
		return $links;
	}
	
	function saveLink($link) { /* PartLink */
		if ($link->id) {
			$sql="update link set ".
			"part_id=".Database::int($link->partId).
			",source_type=".Database::text($link->sourceType).
			",target_type=".Database::text($link->targetType).
			",target_value=".Database::text($link->targetValue).
			" where id=".Database::int($link->id);
			Database::update($sql);
		} else {
			$sql="insert into link (part_id,source_type,target_type,target_value
				) values (".
				Database::int($link->partId).",".
				Database::text($link->sourceType).",".
				Database::text($link->targetType).",".
				Database::text($link->targetValue).
			")";
			$this->id = Database::insert($sql);
		}
	}
}