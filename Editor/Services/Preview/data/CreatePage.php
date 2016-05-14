<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../Include/Private.php';

$pageId = Request::getInt('pageId');
$title = Request::getString('title');
$placement = Request::getString('placement');

$page = PageService::createPageContextually($pageId,$title,$placement);
if ($page==false) {
    Response::badRequest();
} else {
    Response::sendObject(['id' => $page->getId()]);
}
?>