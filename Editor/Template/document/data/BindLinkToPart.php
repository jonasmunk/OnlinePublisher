<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$linkId = Request::getInt('linkId');
$partId = Request::getInt('partId');

if ($link = Link::load($linkId)) {
	$link->setPartId($partId);
	$link->save();
} else {
	Log::debug('Link with id='.$id.' not found');
}
?>