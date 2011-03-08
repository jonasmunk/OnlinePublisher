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
	
	function deleteLink($object,$linkId) {

		// Delete item
		$sql="delete from object_link where id=".Database::int($linkId);
		Database::delete($sql);

		// Fix positions
		$sql="select id from object_link where object_id=".Database::int($object->id)." order by position";
		$result = Database::select($sql);
		$pos=1;
		while ($row = Database::next($result)) {
			$sql="update object_link set position=".Database::int($pos)." where id=".Database::int($row['id']);
			Database::update($sql);
			$pos++;
		}
		Database::free($result);
		
		$sql = "update `object` set updated=now() where id=".Database::int($object->id);
		Database::update($sql);
	}
	
	function moveLink($object,$linkId,$dir) {

		$sql="select * from object_link where id=".Database::int($linkId);
		$row = Database::selectFirst($sql);
		$pos=$row['position'];

		$sql="select id from object_link where object_id=".Database::int($object->id)." and `position`=".Database::int($pos+$dir);
		$result = Database::select($sql);
		if ($row = Database::next($result)) {
			$otherid=$row['id'];

			$sql="update object_link set `position`=".Database::int($pos+$dir)." where id=".Database::int($linkId);
			Database::update($sql);

			$sql="update object_link set `position`=".Database::int($pos)." where id=".Database::int($otherid);
			Database::update($sql);
		}
		Database::free($result);
		
		$sql = "update `object` set updated=now() where id=".Database::int($object->id);
		Database::update($sql);
	}

	function addLink($object,$title,$alternative,$target,$targetType,$targetValue) {
		$sql="select max(`position`) as `position` from object_link where object_id=".Database::int($object->id);
		if ($row = Database::selectFirst($sql)) {
			$pos=$row['position']+1;
		} else {
			$pos=1;
		}
		
		$sql="insert into object_link (object_id,title,alternative,target,position,target_type,target_value) values (".
		$this->id.
		",".Database::text($title).
		",".Database::text($alternative).
		",".Database::text($target).
		",".Database::int($pos).
		",".Database::text($targetType).
		",".Database::text($targetValue).
		")";
		Database::insert($sql);
		
		$sql = "update `object` set updated=now() where id=".Database::int($object->id);
		Database::update($sql);
	}

	function updateLink($object,$id,$title,$alternative,$target,$targetType,$targetValue) {
		
		$sql="update object_link set title=".Database::text($title).
		",alternative=".Database::text($alternative).
		",target_type=".Database::text($targetType).
		",target=".Database::text($target).
		",target_value=".Database::text($targetValue).
		" where id = ".$id;
		Database::update($sql);
		
		$sql = "update `object` set updated=now() where id=".$this->id;		
		Database::update($sql);
		
	}

}