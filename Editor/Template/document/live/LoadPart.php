<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Include/Private.php';

$id = Request::getInt('id');
$type = Request::getString('type');

$part = PartService::load($type,$id);
Response::sendObject($part);
?>