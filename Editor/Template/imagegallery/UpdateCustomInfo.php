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
$imageId = requestPostNumber('id');
$title = requestPostText('title');
$note = requestPostText('note');

$sql="delete from imagegallery_custom_info where image_id=".$imageId." and page_id=".$pageId;
Database::delete($sql);

$sql="insert into imagegallery_custom_info (page_id,image_id,title,note)".
" values (".$pageId.",".$imageId.",".Database::text($title).",".Database::text($note).")";
Database::insert($sql);

Page::markChanged($pageId);

redirect('Images.php');
?>