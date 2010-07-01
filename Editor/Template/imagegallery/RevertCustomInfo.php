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
$imageId = requestGetNumber('id');

$sql="delete from imagegallery_custom_info where image_id=".$imageId." and page_id=".$pageId;
Database::delete($sql);

Page::markChanged($pageId);

redirect('EditCustomInfo.php?id='.$imageId);
?>