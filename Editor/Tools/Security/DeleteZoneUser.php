<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Securityzone.php';
require_once '../../Classes/Request.php';

$zoneId = Request::getInt('zone');
$userId = Request::getInt('user');

$zone = SecurityZone::load($zoneId);

$zone->removeUser($userId);

Response::redirect('EditZoneUsers.php?id='.$zoneId);
?>