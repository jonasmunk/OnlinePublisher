<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Securityzone.php';

$zoneId = requestGetNumber('zone');
$userId = requestGetNumber('user');

$zone = SecurityZone::load($zoneId);

$zone->removeUser($userId);

redirect('EditZoneUsers.php?id='.$zoneId);
?>