<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');

if ($link = Link::load($id)) {
	$link->remove();
} else {
	Log::debug('Link with id='.$id.' not found');
}
?>