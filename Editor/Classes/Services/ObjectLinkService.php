<?
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/ObjectLink.php');
require_once($basePath.'Editor/Classes/Log.php');

class ObjectLinkService {

	function search($query = array()) {
		$sql = "select object_link.*,page.title as page_title, object.title as file_title from object_link ".
		 		"left join page on page.id=object_link.target_value ".
				"left join object on object.id=object_link.target_value and object.type='file'";
		if ($query['objectId']) {
			$sql.=" where object_id=".$query['objectId'];
		}
		$sql.=" order by object_link.position";
		$list = array();
		foreach (Database::selectAll($sql) as $row) {
			$link = new ObjectLink();
			$link->setId(intval($row['id']));
			$link->setObjectId(intval($row['object_id']));
			$link->setText($row['title']);
			$link->setType($row['target_type']);
			$link->setValue($row['target_value']);
			$link->setPosition(intval($row['title']));
			if ($row['target_type']=='page') {
				$link->setInfo($row['page_title']);
			} else if ($row['target_type']=='file') {
				$link->setInfo($row['file_title']);
			} else {
				$link->setInfo($row['target_value']);
			}
			$list[] = $link;
		}
		return $list;
	}
	
	function updateLinks($id,$links) {
		$sql = "delete from object_link where object_id=".$id;
		Database::delete($sql);
		$position = 1;
		foreach ($links as $link) {
			$sql = "insert into object_link (object_id,title,target_type,target_value,position)".
			" values (".$id.",".Database::text($link->getText()).",".Database::text($link->getType()).",".Database::text($link->getValue()).",".$position.")";
			Database::insert($sql);
			$position++;
		}
	}
	
	function getLinkCounts($objects) {
		if (count($objects)==0) {
			return array();
		}
		$ids = array();
		foreach ($objects as $object) {
			$ids[] = $object->getId();
		}
		$counts = array();
		$sql = "select object_id as id,count(object_id) as count from object_link where object_id in (".implode($ids,",").") group by object_id";
		foreach (Database::selectAll($sql) as $row) {
			$counts[$row['id']] = $row['count'];
		}
		return $counts;
	}
}