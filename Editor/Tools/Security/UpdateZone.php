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

$id = requestPostNumber('id',0);
$title = requestPostText('title');
$page = requestPostNumber('page');

$zone = SecurityZone::load($id);
$zone->setTitle($title);
$zone->setAuthenticationPageId($page);
$zone->update();
$zone->publish();

redirect('index.php');
?>