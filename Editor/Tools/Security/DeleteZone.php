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

$id = Request::getInt('id');

$zone = SecurityZone::load($id);
$zone->remove();

Response::redirect('index.php');
?>