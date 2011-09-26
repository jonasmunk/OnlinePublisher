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

$title = Request::getString('title');
$page = Request::getInt('page');

$zone = new SecurityZone();
$zone->setTitle($title);
$zone->setAuthenticationPageId($page);
$zone->create();
$zone->publish();

Response::redirect('index.php');
?>