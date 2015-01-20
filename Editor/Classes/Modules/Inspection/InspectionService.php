<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Warnings
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class InspectionService {
	
	static function performInspection($query) {
		$inspections = array();
		
		InspectionService::checkFolders($inspections);
		InspectionService::checkPageStructure($inspections);
		InspectionService::checkFrameStructure($inspections);
		InspectionService::checkPageContent($inspections);
		InspectionService::checkEnvironment($inspections);
		InspectionService::checkLinks($inspections);
		InspectionService::checkObjects($inspections);

		$filtered = array();
		
		foreach ($inspections as $inspection) {
			if ((!isset($query['status']) || $query['status'] == 'all' || $query['status']==$inspection->getStatus()) && (!isset($query['category']) || $query['category'] == 'all' || $query['category']==$inspection->getCategory())) {
				$filtered[] = $inspection;
			}
		}
		
		return $filtered;
	}
	
	static function checkEnvironment(&$inspections) {
		{
			$ok = class_exists('XSLTProcessor');
			$inspection = new Inspection();
			$inspection->setCategory('environment');
			$inspection->setEntity(array('type'=>'api','title'=>'XSL-transformation','id'=>'XSLTProcessor','icon'=>'common/object'));
			$inspection->setStatus($ok ? 'ok' : 'error');
			$inspection->setText($ok ? 'XSLT er installeret' : 'XSLT mangler');
			$inspections[] = $inspection;
		}
		{
			$ok = function_exists('curl_init');
			$inspection = new Inspection();
			$inspection->setCategory('environment');
			$inspection->setEntity(array('type'=>'api','title'=>'Netværksklient (CURL)','id'=>'curl','icon'=>'common/object'));
			$inspection->setStatus($ok ? 'ok' : 'error');
			$inspection->setText($ok ? 'Netværksklient er installeret' : 'Netværksklient mangler');
			$inspections[] = $inspection;
		}
		{
			$ok = function_exists('gd_info');
			$inspection = new Inspection();
			$inspection->setCategory('environment');
			$inspection->setEntity(array('type'=>'api','title'=>'Billedbehandling (GD)','id'=>'curl','icon'=>'common/object'));
			$inspection->setStatus($ok ? 'ok' : 'error');
			$inspection->setText($ok ? 'Billedbehandling er installeret' : 'Billedbehandling mangler');
			$inspections[] = $inspection;
		}
		{
			$ok = function_exists('iconv_get_encoding');
			$inspection = new Inspection();
			$inspection->setCategory('environment');
			$inspection->setEntity(array('type'=>'api','title'=>'Tekstkonvertering (ICONV)','id'=>'iconv','icon'=>'common/object'));
			$inspection->setStatus($ok ? 'ok' : 'error');
			$inspection->setText($ok ? 'Tekstkonvertering er installeret' : 'Tekstkonvertering mangler');
			$inspections[] = $inspection;
		}
		{
			$ok = function_exists('openssl_encrypt');
			$inspection = new Inspection();
			$inspection->setCategory('environment');
			$inspection->setEntity(array('type'=>'api','title'=>'Krypteret netværk (OPENSSL)','id'=>'openssl','icon'=>'common/object'));
			$inspection->setStatus($ok ? 'ok' : 'error');
			$inspection->setText($ok ? 'Krypteret netværk er installeret' : 'Krypteret netværk mangler');
			$inspections[] = $inspection;
		}
		{
		  $text = shell_exec('jpegoptim -V');
      $ok = Strings::isNotBlank($text);
			$inspection = new Inspection();
			$inspection->setCategory('environment');
			$inspection->setEntity(['type'=>'api','title'=>'JPEG optimization','id'=>'jpegoptim','icon'=>'common/object']);
			$inspection->setStatus($ok ? 'ok' : 'error');
			$inspection->setText($ok ? 'JPEG optimization available' : 'JPEG optimization missing (jpegoptim from CLI)');
      $inspection->setInfo($text);
			$inspections[] = $inspection;
		}
		{
		  $text = shell_exec('optipng -v');
      $ok = Strings::isNotBlank($text);
			$inspection = new Inspection();
			$inspection->setCategory('environment');
			$inspection->setEntity(['type'=>'api','title'=>'PNG optimization','id'=>'optipng','icon'=>'common/object']);
			$inspection->setStatus($ok ? 'ok' : 'error');
			$inspection->setText($ok ? 'PNG optimization available' : 'PNG optimization missing (optipng from CLI)');
      $inspection->setInfo($text);
			$inspections[] = $inspection;
		}
		
		
	}

	static function checkLinks(&$inspections) {
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
	
	static function checkPageStructure(&$inspections) {
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
	
	static function checkFrameStructure(&$inspections) {
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
	
	static function checkObjects(&$inspections) {
		$sql = "select id,title,type from object";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$loaded = Object::load($row['id']);
			if (!$loaded) {
				$entity = array('type'=>$row['type'],'title'=>$row['title'],'id'=>$row['id'],'icon'=>'common/object');
				$inspection = new Inspection();
				$inspection->setCategory('model');
				$inspection->setEntity($entity);
				$inspection->setStatus('error');
				$inspection->setText('The object could not be loaded ('.$row['id'].' / '.$row['type'].')');
				$inspections[] = $inspection;
			}
				
		}
		Database::free($result);
	}
	
	static function checkPageContent(&$inspections) {
		$sql = "select title,id,description,path from page order by title";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$valid = true;
			$entity = array('type'=>'page','title'=>$row['title'],'id'=>$row['id'],'icon'=>'common/page');
			if (Strings::isBlank($row['description'])) {
				$inspection = new Inspection();
				$inspection->setCategory('content');
				$inspection->setEntity($entity);
				$inspection->setStatus('warning');
				$inspection->setText('Siden har ingen beskrivelse');
				$inspections[] = $inspection;
				$valid = false;
			}
			if (Strings::isBlank($row['path'])) {
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
	
	static function checkFolders(&$inspections) {
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