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

// Get parameters
$pageId = getPageId();
$sectionId = requestGetNumber('id');

// Get info on the section
$sql="select frontpage_section.*,part.type from frontpage_section,part where frontpage_section.part_id=part.id and frontpage_section.id=".$sectionId;
$row = Database::selectFirst($sql);
$position=$row['position'];
$partType=$row['type'];
$partId=$row['part_id'];
$cellId=$row['cell_id'];

// Fix positions of other sections
$sql="select * from frontpage_section where cell_id=".$cellId." and `position`>".$position;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update frontpage_section set `position`=".($row['position']-1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

// Delete the part
$part = Part::load($partType,$partId);
$part->delete();

// Delete the section
$sql="delete from frontpage_section where id=".$sectionId;
Database::delete($sql);

// Mark page as changed
$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);

redirect('Editor.php?selectedSection=0');
?>