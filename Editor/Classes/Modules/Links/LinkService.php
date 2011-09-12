<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Modules.Links
 */

if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class LinkService {
	
	static $types = array(
		'url' => array('da' => 'Internet adresse', 'en' => 'Internet address'),
		'page' => array('da' => 'Side', 'en' => 'Page'),
		'file' => array('da' => 'Fil', 'en' => 'File'),
		'email' => array('da' => 'E-mail-adresse', 'en' => 'E-mail address')
	);

	function getPageLinks($pageId) {
		$list = array();
		$sql = "select link.*,page.title as page_title,object.title as object_title from link left join page on link.target_id=page.id left join object on link.target_id=object.id where page_id=".Database::int($pageId)." order by link.source_text";
		Log::debug($sql);
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$link = new LinkInfo();
			$link->setId(intval($row['id']));
			$link->setSourceType($row['source_type']);
			$link->setSourceText($row['source_text']);
			$link->setTargetType($row['target_type']);
			$link->setTargetValue($row['target_value']);
			$link->setTargetId(intval($row['target_id']));
			$link->setPartId(intval($row['part_id']));
			if ($row['target_type']=='page') {
				$link->setTargetTitle($row['page_title']);
				$link->setTargetIcon('common/page');
			} else if ($row['target_type']=='file') {
				$link->setTargetTitle($row['object_title']);
				$link->setTargetIcon('file/generic');
			} else if ($row['target_type']=='email') {
				$link->setTargetTitle($row['target_value']);
				$link->setTargetIcon('common/email');
			} else {
				$link->setTargetTitle($row['target_value']);
				$link->setTargetIcon('common/internet');
			}
			$list[] = $link;
		}
		Database::free($result);
		return $list;
	}
	
	function translateLinkType($type) {
		return LinkService::$types[$type]['da'];
	}
}