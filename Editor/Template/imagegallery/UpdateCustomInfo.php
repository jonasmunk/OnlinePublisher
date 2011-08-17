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
$title = Request::getString('title');
$note = Request::getString('note');

$sql="delete from imagegallery_custom_info where image_id=".$imageId." and page_id=".$pageId;
Database::delete($sql);

$sql="insert into imagegallery_custom_info (page_id,image_id,title,note)".
" values (".$pageId.",".$imageId.",".Database::text($title).",".Database::text($note).")";
Database::insert($sql);

PageService::markChanged($pageId);

Response::redirect('Images.php');
?>