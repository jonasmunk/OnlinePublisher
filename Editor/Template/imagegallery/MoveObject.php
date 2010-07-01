<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.ImageGallery
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Page.php';

$pageId = getPageId();
$id = requestGetNumber('id');
$dir = requestGetNumber('dir');

$sql="select * from imagegallery_object where id=".$id;
if ($row = Database::selectFirst($sql)) {
    $position=$row['position'];

    $sql="select id from imagegallery_object where position=".($position+$dir);
    $result = Database::select($sql);
    if ($row = Database::next($result)) {
    	$otherid=$row['id'];

    	$sql="update imagegallery_object set position=".($position+$dir)." where id=".$id;
    	Database::update($sql);

    	$sql="update imagegallery_object set position=".$position." where id=".$otherid;
    	Database::update($sql);
    }
    Database::free($result);
}

Page::markChanged($pageId);

redirect('Images.php');
?>