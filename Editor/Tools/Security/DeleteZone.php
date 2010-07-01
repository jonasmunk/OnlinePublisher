<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Classes/Securityzone.php';

$id = requestGetNumber('id');

$zone = SecurityZone::load($id);
$zone->remove();

redirect('index.php');
?>