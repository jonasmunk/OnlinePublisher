<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$pageId = InternalSession::getPageId();
$rowId = Request::getInt('row',0);
$dir = Request::getInt('dir',0);


$sql="select * from document_row where id=".$rowId;

$row = Database::selectFirst($sql);

$index = $row['index'];
$newIndex = $index+$dir;
$sql="select * from document_row where `index`=".$newIndex." and page_id=".$pageId;

$row_next = Database::selectFirst($sql);
if ($row_next) {
	$next_id = $row_next['id'];
	Database::update("update document_row set `index`=".$newIndex." where id=".$rowId);
	Database::update("update document_row set `index`=".$index." where id=".$next_id);
}

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);

redirect('Editor.php');
?>