<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Include/Functions.php';
require_once 'Functions.php';

$dropId = explode('-',requestPostText('dropId'));
$dragId = explode('-',requestPostText('dragId'));


$sql="select * from imagegroup_image where image_id=".$dragId[1]." and imagegroup_id=".$dropId[1];
if (!Database::selectFirst($sql)) {
	$sql="insert into imagegroup_image (image_id, imagegroup_id)".
	" values (".$dragId[1].",".$dropId[1].")";
	Database::insert($sql);
	setUpdateHierarchy(true);
}

redirect(requestGetText('return'));
?>