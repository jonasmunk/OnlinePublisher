<?php
/**
 * @package OnlinePublisher
 * @subpackage Tool.System
 */
require_once '../../../Include/Private.php';

$result = OnlineObjectsService::test(Request::getString('url'));

Response::sendObject(array('success'=>$result));
?>