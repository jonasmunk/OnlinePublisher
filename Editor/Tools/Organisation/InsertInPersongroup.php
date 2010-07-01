<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Organisation
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'Functions.php';

$id = requestPostNumber('id',0);
$images = requestPostArray('image');

for ($i=0;$i<count($images);$i++) {
	$sql="insert into imagegroup_image (image_id, imagegroup_id)".
	" values (".$images[$i].",".$id.")";
	Database::insert($sql);
}

setUpdateHierarchy(true);

redirect('Group.php');
?>