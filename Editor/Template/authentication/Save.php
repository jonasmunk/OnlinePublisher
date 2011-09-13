<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Authentication
 */
require_once '../../Include/Private.php';

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