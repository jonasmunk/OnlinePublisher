<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Security
 */
require_once '../../../Include/Private.php';

$data = Request::getObject('data');

if ($data->id > 0) {
	$zone = Securityzone::load($data->id);
} else {
	$zone = new Securityzone();
}
$zone->setTitle($data->title);
$zone->setAuthenticationPageId($data->authenticationPageId);
if (!$zone->save()) {
	Response::badRequest();
}
?>