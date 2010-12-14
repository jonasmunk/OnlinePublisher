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
require_once 'Functions.php';

$pageId = InternalSession::getPageId();
$rowId = requestGetNumber('row',0);
$index = requestGetNumber('index',0);


$sql="select * from document_column where row_id=".$rowId." and `index`>=".$index;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update document_column set `index`=".($row['index']+1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

$sql="insert into document_column (page_id,row_id,`index`) values (".$pageId.",".$rowId.",".$index.")";
$columnId=Database::insert($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);

//setDocumentColumn($columnId);
setDocumentSection(0);
redirect('Editor.php');
?>