<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'Functions.php';

$id = getImageGroup();
$dragId = explode('-',requestPostText('dragId'));
$image = $dragId[1];


$sql="delete from imagegroup_image where image_id=".$image.
" and imagegroup_id=".$id;
Database::delete($sql);

setUpdateHierarchy(true);

redirect('ImageIcons.php');
?>