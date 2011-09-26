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

$id = Request::getInt('id');

$zone = SecurityZone::load($id);
$zone->remove();

Response::redirect('index.php');
?>