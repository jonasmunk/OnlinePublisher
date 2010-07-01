<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Part.php';
require_once 'Functions.php';

$pageId = getPageId();
$id = requestGetNumber('id');


$sql="select * from frontpage_cell where id=".$id;
$row = Database::selectFirst($sql);
$position=$row['position'];
$rowId=$row['row_id'];


$sql="select * from frontpage_cell where page_id=".$pageId." and position>".$position;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update frontpage_cell set position=".($row['position']-1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

$sql="delete from frontpage_cell where id=".$id;
Database::delete($sql);

$sql="select frontpage_section.*,part.type from frontpage_section,part where frontpage_section.part_id=part.id and cell_id=".$id;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="delete from frontpage_section where id =".$row['id'];
	Database::delete($sql);
	$part = Part::load($row['type'],$row['part_id']);
	$part->delete();
}
Database::free($result);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);

redirect('Editor.php?selectedCell=0');

?>