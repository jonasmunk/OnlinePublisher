<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Include/Private.php';

$zoneId = Request::getInt('zoneId');
$pageId = Request::getInt('pageId');

PageService::addPageToSecurityZone($pageId,$zoneId);
?>