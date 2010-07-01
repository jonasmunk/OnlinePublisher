<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.FrontPage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Part.php';
require_once '../../Include/XmlWebGui.php';

require_once 'Functions.php';

$id = getPageId();
$cell = requestPostNumber('cell');
$position = requestPostNumber('position');
$part = requestPostText('part');

$sql="select * from frontpage_section where cell_id=".$cell." and `position`>=".$position;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update frontpage_section set `position`=".($row['position']+1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

$partId = createNewPart($part);

if ($partId) {
	$sql="insert into frontpage_section (page_id,position,cell_id,part_id) values (".$id.",".$position.",".$cell.",".$partId.")";
	$sectionId = Database::insert($sql);
}


redirect('Editor.php?selectedSection='.$sectionId);

/**
 * Creates a new part of the provided type and returns its id
 */
function createNewPart($unique) {
	$part = Part::getNewPart($unique);
	$part->create();
	return $part->getId();
}
?>