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

$id = getPageId();
$index = requestGetNumber('index',0);


$sql="select * from document_row where page_id=".$id." and `index`>=".$index;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update document_row set `index`=".($row['index']+1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

$sql="insert into document_row (page_id,`index`) values (".$id.",".$index.")";
$rowId=Database::insert($sql);
$sql="insert into document_column (page_id,row_id,`index`) values (".$id.",".$rowId.",1)";
$columnId=Database::insert($sql);
$sql="update page set changed=now() where id=".$id;
Database::update($sql);

setDocumentRow($rowId);
setDocumentColumn(0);
setDocumentSection(0);
redirect('Editor.php');
?>