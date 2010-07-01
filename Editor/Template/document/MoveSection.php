<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$pageId = getPageId();
$sectionId = requestGetNumber('section',0);
$dir = requestGetNumber('dir',0);


$sql="select * from document_section where id=".$sectionId;
$row = Database::selectFirst($sql);

$index = $row['index'];
$column = $row['column_id'];
$newIndex = $row['index']+$dir;
	
$sql="select * from document_section where `index`=".$newIndex." and column_id=".$column;
$row_next = Database::selectFirst($sql);
	
if ($row_next) {
	$next_id = $row_next['id'];
	Database::update("update document_section set `index`=".$newIndex." where id=".$sectionId);
	Database::update("update document_section set `index`=".$index." where id=".$next_id);
		
}

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


redirect('Editor.php');
?>