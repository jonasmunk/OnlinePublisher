<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Warnings
 */
require_once($basePath.'Editor/Classes/Modules/Inspection/Inspection.php');
require_once($basePath.'Editor/Classes/Services/FileSystemService.php');

class InspectionService {
	
	function performInspection($query) {
		$inspections = array();
		
		InspectionService::checkFolders($inspections);
		InspectionService::checkPageContent($inspections);

		$filtered = array();
		
		foreach ($inspections as $inspection) {
			if (($query['status'] == 'all' || $query['status']==$inspection->getStatus()) && ($query['category'] == 'all' || $query['category']==$inspection->getCategory())) {
				$filtered[] = $inspection;
			}
		}
		
		return $filtered;
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