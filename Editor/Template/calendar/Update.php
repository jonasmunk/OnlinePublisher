<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Html
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';

$objects = requestPostArray('object');
$title = requestPostText('title');
$newsViewStartHour = requestPostNumber('weekview_starthour');

$id = getPageId();

$sql="update calendarviewer set title=".sqlText($title).",weekview_starthour=".sqlInt($newsViewStartHour)." where page_id=".$id;
Database::update($sql);

$sql="delete from calendarviewer_object where page_id=".$id;
Database::delete($sql);

foreach ($objects as $object) {
	$sql="insert into calendarviewer_object (page_id,object_id) values (".$id.",".$object.")";
	Database::insert($sql);
}

$sql="update page set changed=now() where id=".$id;
Database::update($sql);

redirect('Editor.php');
?>