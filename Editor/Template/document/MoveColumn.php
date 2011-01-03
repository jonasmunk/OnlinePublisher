<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$columnId = Request::getInt('column',0);
$dir = Request::getInt('dir',0);


$sql="select * from document_column where id=".$columnId;

$row = Database::selectFirst($sql);

$index = $row['index'];
$newIndex = $index+$dir;
$rowId = $row['row_id'];

$sql="select * from document_column where `index`=".$newIndex." and row_id=".$rowId;
$row_next = Database::selectFirst($sql);
if ($row_next) {
	$next_id = $row_next['id'];
	Database::update("update document_column set `index`=".$newIndex." where id=".$columnId);
	Database::update("update document_column set `index`=".$index." where id=".$next_id);
}

$sql="update page set changed=now() where id=".InternalSession::getPageId();
Database::update($sql);

Response::redirect('Editor.php');
?>