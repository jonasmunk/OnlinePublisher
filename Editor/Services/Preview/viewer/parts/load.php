<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Preview
 */
require_once '../../../../Include/Private.php';

$id = Request::getInt('id');
$type = Request::getString('type');

$part = PartService::load($type,$id);
Response::sendObject($part);
?>