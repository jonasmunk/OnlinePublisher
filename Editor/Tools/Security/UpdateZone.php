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
require_once '../../Classes/Request.php';

$id = Request::getInt('id',0);
$title = Request::getString('title');
$page = Request::getInt('page');

$zone = SecurityZone::load($id);
$zone->setTitle($title);
$zone->setAuthenticationPageId($page);
$zone->update();
$zone->publish();

redirect('index.php');
?>