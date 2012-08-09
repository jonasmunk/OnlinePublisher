<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
require_once '../../Include/Private.php';

$id = Request::getId();
$title = Request::getString('title');

if ($obj = AuthenticationTemplate::load($id)) {
	$obj->setTitle($title);
	$obj->save();
} else {
	Response::badRequest();
}
?>