<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/InternalSession.php';
require_once 'Functions.php';

$pageId = InternalSession::getPageId();
$columnId=requestGetNumber('column',0);


$sql="select * from document_column where id=".$columnId;
$row = Database::selectFirst($sql);
if ($row) {
	$index=$row['index'];
	$rowId=$row['row_id'];
}

$sql="select * from document_column where row_id=".$rowId." and `index`>".$index;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update document_column set `index`=".($row['index']-1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);


$sql="select document_section.*,part.type as part_type from document_section left join part on part.id=document_section.part_id where column_id=".$columnId;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$type=$row['type'];
	$sectionId=$row['id'];
	$partType=$row['part_type'];
	$partId=$row['part_id'];
	if ($type=='part') {
		if ($part = Part::load($partType,$partId)) {
			$part->remove();
		}
	}
}
Database::free($result);


$sql="delete from document_section where column_id=".$columnId;
Database::delete($sql);

$sql="delete from document_column where id=".$columnId;
Database::delete($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);

setDocumentColumn(0);
setDocumentSection(0);
redirect('Editor.php');
?>