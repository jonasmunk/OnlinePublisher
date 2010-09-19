<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Parts/LegacyPartController.php';
require_once 'Functions.php';

$pageId = getPageId();
$columnId = requestGetNumber('column',0);
$index = requestGetNumber('index',0);
$type = requestGetText('type');
$part = requestGetText('part');

$sql="select * from document_section where column_id=".$columnId." and `index`>=".$index;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update document_section set `index`=".($row['index']+1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

// Not a part
if ($type!='part') {
	$sql="insert into document_section (`page_id`,`column_id`,`index`,`type`) values (".$pageId.",".$columnId.",".$index.",'".$type."')";
	$sectionId=Database::insert($sql);
	require $basePath.'Editor/Template/document/'.ucfirst($type).'/Add.php';
} else {
	$partId = createNewPart($part);
	if ($partId) {
		$sql="insert into document_section (`page_id`,`column_id`,`index`,`type`,`part_id`) values (".$pageId.",".$columnId.",".$index.",'part',".$partId.")";
		$sectionId=Database::insert($sql);
	}
}


$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


setDocumentSection($sectionId);
setDocumentRow(0);
setDocumentColumn(0);
redirect('Editor.php');



/**
 * Creates a new part of the provided type and returns its id
 */
function createNewPart($unique) {
	if ($ctrl = PartService::getController($unique)) {
		$part = $ctrl->createPart();
		return $part->getId();
	} else {
		Log::debug("Unable to find controller for $unique");
		$part = LegacyPartController::getNewPart($unique);
		$part->create();
		return $part->getId();
	}
}
?>