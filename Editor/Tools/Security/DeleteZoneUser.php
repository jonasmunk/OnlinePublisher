<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Core/Response.php';
require_once '../../Classes/Objects/Securityzone.php';
require_once '../../Classes/Core/Request.php';

$zoneId = Request::getInt('zone');
$userId = Request::getInt('user');

$zone = SecurityZone::load($zoneId);

$zone->removeUser($userId);

Response::redirect('EditZoneUsers.php?id='.$zoneId);
?>