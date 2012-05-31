<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Calendar
 */
require_once '../../../Include/Private.php';

$id = Request::getId();

$sql="select * from calendarviewer where page_id=".Database::int($id);
if ($row = Database::getRow($sql)) {
	$sql="select object.id,object.type from calendarviewer_object,object where `calendarviewer_object`.`object_id`=object.`id` and calendarviewer_object.page_id=".Database::int($id);
	Log::debug($sql);
	$all = Database::selectAll($sql);
	foreach ($all as $item) {
		$type = $item['type'];
		if (!isset($row[$type])) {
			$row[$type] = array();
		}
		$row[$type][] = intval($item['id']);
	}
	
	Response::sendUnicodeObject($row);
} else {
	Response::notFound();
}
?>