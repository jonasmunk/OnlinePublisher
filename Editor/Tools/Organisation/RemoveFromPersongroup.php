<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = getImageGroup();
$images = requestPostArray('image');


for ($i=0;$i<count($images);$i++) {
	$sql="delete from imagegroup_image where image_id=".$images[$i].
	" and imagegroup_id=".$id;
	Database::delete($sql);
}

setUpdateHierarchy(true);
redirect('ImageList.php');
?>