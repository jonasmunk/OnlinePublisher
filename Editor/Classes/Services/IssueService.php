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

	function getTotalIssueCount() {
		$sql = "select count(object_id) as `count` from issue";
		$row = Database::selectFirst($sql);
		return intval($row['count']);
	}
	
	function getKindCounts() {
		$sql = "select count(object_id) as count,kind from issue group by kind";
		return Database::selectAll($sql);
	}
	
	function getStatusCounts() {
		$sql = "select count(issue.object_id) as count,object.title,object.id from issue left join object on issue.issuestatus_id=object.id group by object.id order by object.title";
		return Database::selectAll($sql);
	}
	
	function getStatusMap() {
		$map = array();
		$sql = "select id,title from object where type='issuestatus' order by title";
		$result = Database::select($sql);
		while($row = Database::next($result)) {
			$map[$row['id']] = $row['title'];
		}
		Database::free($result);
		return $map;
	}
	
}