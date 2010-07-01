<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/Securityzone.php';

$title = requestPostText('title');
$page = requestPostNumber('page');

$zone = new SecurityZone();
$zone->setTitle($title);
$zone->setAuthenticationPageId($page);
$zone->create();
$zone->publish();

redirect('index.php');
?>