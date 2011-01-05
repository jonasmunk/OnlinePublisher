<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$pageId = InternalSession::getPageId();
$sectionId = Request::getInt('section',0);
$dir = Request::getInt('dir',0);


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


Response::redirect('Editor.php');
?>