<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Frontpage
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once 'Functions.php';

$pageId = getPageId();
$sectionId = requestGetNumber('section',0);
$dir = requestGetNumber('dir',0);


$sql="select * from frontpage_section where id=".$sectionId;
$row = Database::selectFirst($sql);

$index = $row['position'];
$cell = $row['cell_id'];
$newIndex = $row['position']+$dir;
	
$sql="select * from frontpage_section where position=".$newIndex." and cell_id=".$cell;
$row_next = Database::selectFirst($sql);
	
if ($row_next) {
	$next_id = $row_next['id'];
	Database::update("update frontpage_section set position=".$newIndex." where id=".$sectionId);
	Database::update("update frontpage_section set position=".$index." where id=".$next_id);
}

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);

redirect('Editor.php');
?>