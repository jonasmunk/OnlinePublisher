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
$sectionId = requestGetNumber('section');


$sql="select document_section.*,part.type as part_type from document_section left join part on part.id = document_section.part_id where document_section.id=".$sectionId;
$row = Database::selectFirst($sql);
$index=$row['index'];
$type=$row['type'];
$columnId=$row['column_id'];
$partType=$row['part_type'];
$partId=$row['part_id'];

$sql="select * from document_section where column_id=".$columnId." and `index`>".$index;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update document_section set `index`=".($row['index']-1)." where id=".$row['id'];
	Database::update($sql);
}
Database::free($result);

$sql="delete from document_section where id=".$sectionId;
Database::delete($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);

if ($type=='part') {
	if ($part = Part::load($partType,$partId)) {
		$part->remove();
	} else {
		$part = LegacyPartController::load($partType,$partId);
		$part->delete();
	}
}

setDocumentSection(0);
redirect('Editor.php');
?>