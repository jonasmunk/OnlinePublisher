<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';

$id = Request::getId();
$title = Request::getEncodedString('title');

if ($obj = AuthenticationTemplate::load($id)) {
	Log::debug($obj);
	$obj->setTitle($title);
	$obj->save();
} else {
	Log::debug('Unable to load : '.$id);
	Response::badRequest();
}
?>