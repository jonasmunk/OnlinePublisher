<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/InternalSession.php';
require_once 'Functions.php';

$pageId = InternalSession::getPageId();
$columnId = Request::getInt('column');
$index = Request::getInt('index');
$part = Request::getString('part');

$sql="select * from document_section where column_id=".$columnId." and `index`>=".$index;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update document_section set `index`=".($row['index']+1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

if ($ctrl = PartService::getController($part)) {
	if ($part = $ctrl->createPart()) {
		$sql="insert into document_section (`page_id`,`column_id`,`index`,`type`,`part_id`) values (".$pageId.",".$columnId.",".$index.",'part',".$part->getId().")";
		$sectionId=Database::insert($sql);
	}
}

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


setDocumentSection($sectionId);
setDocumentRow(0);
setDocumentColumn(0);
Response::redirect('Editor.php');

?>