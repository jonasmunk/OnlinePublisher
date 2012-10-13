<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class IssueService {
	
	private static $KINDS = array(
		'unknown' => array('da'=>'Ukendt','en' => 'Unknown'),
		'improvement' => array('da' => 'Forbedring','en' => 'Improvement'),
		'task' => array('da'=>'Opgave','en' => 'Task'),
		'feedback' => array('da' => 'Tilbagemelding', 'en' => 'Feedback'),
		'error' => array('da' => 'Fejl', 'en' => 'Error')
	);
	
	function getKinds() {
		return IssueService::$KINDS;
	}
	
	function translateKind($kind,$lang=null) {
		if ($lang==null) {
			$lang = InternalSession::getLanguage();
		}
		if (isset(IssueService::$KINDS[$kind]) && isset(IssueService::$KINDS[$kind][$lang])) {
			return IssueService::$KINDS[$kind][$lang];
		}
		return $kind;
	}
	
	function getKindCounts() {
		$sql = "select count(object_id) as count,kind from issue group by kind";
		return Database::selectAll($sql);
	}
	
}