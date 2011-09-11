<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($link = Link::load($id)) {
	Response::sendObject(array(
		'id' => $link->getId(),
		'text' => $link->getText(),
		'email' => $link->getEmail(),
		'url' => $link->getUrl(),
		'page' => $link->getPage(),
		'file' => $link->getFile(),
		'limitToPart' => $link->getPartId()>0 ? true : false,
		'partId' => $link->getPartId()
	));
}
?>