<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Page.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/InternalSession.php';

$id = InternalSession::getPageId();
$objects = Request::getArray('object');

$position = 0;
$sql="select max(position) as max from imagegallery_object where page_id = ".$id;
if ($row = Database::selectFirst($sql)) {
    $position=$row['max'];
}

for ($i=0;$i<count($objects);$i++) {
    $position++;
	$sql="insert into imagegallery_object (page_id, object_id, position)".
	" values (".$id.",".$objects[$i].",".$position.")";
	Database::insert($sql);
}

PageService::markChanged($id);

Response::redirect('Images.php');
?>