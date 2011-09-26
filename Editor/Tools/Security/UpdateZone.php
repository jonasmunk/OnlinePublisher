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

$id = Request::getInt('id',0);
$title = Request::getString('title');
$page = Request::getInt('page');

$zone = SecurityZone::load($id);
$zone->setTitle($title);
$zone->setAuthenticationPageId($page);
$zone->update();
$zone->publish();

Response::redirect('index.php');
?>