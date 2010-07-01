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
$rowId = requestGetNumber('row');

// Find position of the row
$sql="select * from frontpage_row where id=".$rowId;
$row = Database::selectFirst($sql);
$position=$row['position'];

// Fix positions of the other rows
$sql="select * from frontpage_row where page_id=".$pageId." and position>".$position;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update frontpage_row set position=".($row['position']-1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

// Find and delete alle sections in the cells of the row
$sql="select frontpage_section.*,part.type from frontpage_section,part,frontpage_cell where frontpage_section.cell_id=frontpage_cell.id and frontpage_section.part_id=part.id and frontpage_cell.row_id=".$rowId;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="delete from frontpage_section where id =".$row['id'];
	Database::delete($sql);
	$part = Part::load($row['type'],$row['part_id']);
	$part->delete();
}
Database::free($result);

// Delete all cells
$sql="delete from frontpage_cell where row_id=".$rowId;
Database::delete($sql);

// Delete the row
$sql="delete from frontpage_row where id=".$rowId;
Database::delete($sql);

// Mark page as changed
$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);

redirect('Editor.php');
?>