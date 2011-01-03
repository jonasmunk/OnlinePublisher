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

$id = getImageGroup();
$images = Request::getArray('image');


for ($i=0;$i<count($images);$i++) {
	$sql="delete from imagegroup_image where image_id=".$images[$i].
	" and imagegroup_id=".$id;
	Database::delete($sql);
}

setUpdateHierarchy(true);
Response::redirect('ImageList.php');
?>