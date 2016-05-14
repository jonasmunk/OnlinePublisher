<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$partId = Request::getInt('partId',null);
$pageId = InternalSession::getPageId();
$text = Request::getString('text');
$value = Request::getString('value');
$type = Request::getString('type');
$target = Request::getString('target');
$alternative = Request::getString('description');

if ($id) {
	$link=Link::load($id);
} else {
	$link=new Link();
}
if ($link) {
	$link->setText($text);
	$link->setAlternative($alternative);
	$link->setPageId($pageId);
	$link->setTypeAndValue($type,$value);
	$link->setPartId($partId);
	$link->save();

	PageService::markChanged($pageId);
}
?>