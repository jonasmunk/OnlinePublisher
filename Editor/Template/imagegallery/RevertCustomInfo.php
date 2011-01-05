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
require_once '../../Classes/InternalSession.php';
require_once '../../Classes/Request.php';

$pageId = InternalSession::getPageId();
$imageId = Request::getInt('id');

$sql="delete from imagegallery_custom_info where image_id=".$imageId." and page_id=".$pageId;
Database::delete($sql);

Page::markChanged($pageId);

response::redirect('EditCustomInfo.php?id='.$imageId);
?>