<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getInt('id');
$parameters = Request::getObject('parameters');

error_log($id);

$page = Page::load($id);

$design = Design::load($page->getDesignId());

DesignService::saveParameters($design->getId(),get_object_vars($parameters));
?>