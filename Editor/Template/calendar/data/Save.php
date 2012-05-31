<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Calendar
 */
require_once '../../../Include/Private.php';

$data = Request::getUnicodeObject('data');
if ($data) {
	$sql = "update calendarviewer set title=".Database::text($data->title).",weekview_starthour=".Database::int($data->weekview_starthour)." where page_id=".Database::int($data->id);
	Database::update($sql);	
	
	$sql="delete from calendarviewer_object where page_id=".Database::int($data->id);
	Database::delete($sql);
	foreach ($data->calendar as $id) {
		$sql="insert into calendarviewer_object (page_id,object_id) values (".$data->id.",".$id.")";
		Database::insert($sql);
	}
	foreach ($data->calendarsource as $id) {
		$sql="insert into calendarviewer_object (page_id,object_id) values (".$data->id.",".$id.")";
		Database::insert($sql);
	}
	
}
?>