<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';
require_once 'Functions.php';

$id = Request::getInt('id',0);
$images = Request::getArray('image');

for ($i=0;$i<count($images);$i++) {
	$sql="insert into imagegroup_image (image_id, imagegroup_id)".
	" values (".$images[$i].",".$id.")";
	Database::insert($sql);
}

setUpdateHierarchy(true);

Response::redirect('Group.php');
?>