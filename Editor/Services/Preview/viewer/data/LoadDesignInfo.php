<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getInt('id');

$page = Page::load($id);

$designId = $page->getDesignId();

$design = Design::load($designId);

$info = DesignService::getInfo($design->getUnique());

Response::sendUnicodeObject($info);
?>