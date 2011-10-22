<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Warnings
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Modules/Inspection/Inspection.php');
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');

class InspectionService {
	
	function performInspection($query) {
		$inspections = array();
		
		InspectionService::checkFolders($inspections);
		InspectionService::checkPageStructure($inspections);
		InspectionService::checkFrameStructure($inspections);
		InspectionService::checkPageContent($inspections);
		InspectionService::checkLinks($inspections);

		$filtered = array();
		
		foreach ($inspections as $inspection) {
			if (($query['status'] == 'all' || $query['status']==$inspection->getStatus()) && ($query['category'] == 'all' || $query['category']==$inspection->getCategory())) {
				$filtered[] = $inspection;
			}
		}
		
		return $filtered;
	}

	function checkLinks(&$inspections) {
		$query = new LinkQuery();
		$query->withTextCheck()->withOnlyWarnings();
		$links = LinkService::search($query);

		foreach ($links as $link) {
			$entity = array('type'=>$link->getSourceType(),'title'=>$link->getSourceTitle(),'id'=>$link->getSourceId(),'icon'=>LinkService::getSourceIcon($link));
			$errors = $link->getErrors();
			foreach ($errors as $error) {
				$inspection = new Inspection();
				$inspection->setCategory('model');
				$inspection->setEntity($entity);
				$inspection->setStatus('error');
				$inspection->setText($error['message']);
				$inspections[] = $inspection;
			}
		}
	}
	
	function checkPageStructure(&$inspections) {
		$sql = "select title,id from page where design_id not in (select object_id from design)";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$entity = array('type'=>'page','title'=>$row['title'],'id'=>$row['id'],'icon'=>'common/page');
			$inspection = new Inspection();
			$inspection->setCategory('model');
			$inspection->setEntity($entity);
			$inspection->setStatus('error');
			$inspection->setText('Siden har intet design');
			$inspections[] = $inspection;
		}
		Database::free($result);
	}
	
	function checkFrameStructure(&$inspections) {
		$sql = "select name,id from frame where hierarchy_id not in (select id from hierarchy)";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$entity = array('type'=>'frame','title'=>$row['name'],'id'=>$row['id'],'icon'=>'common/page');
			$inspection = new Inspection();
			$inspection->setCategory('model');
			$inspection->setEntity($entity);
			$inspection->setStatus('error');
			$inspection->setText('Opsætningen har intet hierarki');
			$inspections[] = $inspection;
		}
		Database::free($result);
	}
	
	function checkPageContent(&$inspections) {
		$sql = "select title,id,description from page order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$valid = true;
			$entity = array('type'=>'page','title'=>$row['title'],'id'=>$row['id'],'icon'=>'common/page');
			if (StringUtils::isBlank($row['description'])) {
				$inspection = new Inspection();
				$inspection->setCategory('content');
				$inspection->setEntity($entity);
				$inspection->setStatus('warning');
				$inspection->setText('Siden har ingen beskrivelse');
				$inspections[] = $inspection;
				$valid = false;
			}
			if (StringUtils::isBlank($row['path'])) {
				$inspection = new Inspection();
				$inspection->setCategory('content');
				$inspection->setEntity($entity);
				$inspection->setStatus('error');
				$inspection->setText('Siden har ingen sti');
				$inspections[] = $inspection;
				$valid = false;
			}
			if ($valid) {
				$inspection = new Inspection();
				$inspection->setCategory('content');
				$inspection->setEntity($entity);
				$inspection->setStatus('ok');
				$inspection->setText('Siden har ingen kendte problemer');
				$inspections[] = $inspection;
			}
		}
		Database::free($result);
	}
	
	function checkFolders(&$inspections) {
		global $basePath;
		$folders = array("files","images","local/cache/images","local/cache/urls","local/cache/temp");
		foreach ($folders as $folder) {
			$inspection = new Inspection();
			$inspection->setCategory('system');
			$inspection->setEntity(array('title'=>$folder,'icon'=>'common/folder'));
			$path = $basePath.$folder;
			if (!file_exists($path)) {
				$inspection->setStatus('error');
				$inspection->setText('Mappen findes ikke');
			} else if (!is_writable($path)) {
				$inspection->setStatus('error');
				$inspection->setText('Mappen er ikke skrivbar ('.FileSystemService::getPermissionString($path).')');
			} else {
				$inspection->setStatus('ok');
				$inspection->setText('Mappen findes og er skrivbar');
			}
			$inspections[] = $inspection;
		}
	}
	
}