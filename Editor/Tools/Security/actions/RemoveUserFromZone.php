<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Include/Private.php';

$zoneId = Request::getInt('zoneId');
$userId = Request::getInt('userId');

PageService::removeUserFromSecurityZone($userId,$zoneId);
?>